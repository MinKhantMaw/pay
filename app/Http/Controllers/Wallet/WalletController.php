<?php

namespace App\Http\Controllers\Wallet;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Helpers\WalletGenerate;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use App\Notifications\GeneralNotification;
use Illuminate\Support\Facades\Notification;

class WalletController extends Controller
{
    public function index()
    {
        return view('backend.wallets.index');
    }

    public function ssd()
    {
        $wallets = Wallet::with('user');

        return DataTables::of($wallets)
            ->addColumn('account_person', function ($e) {
                $user = $e->user;
                if ($user) {
                    return '<p>Name : ' . $user->name . ' </p> <p>Email : ' . $user->email . ' </p> <p>Phone : ' . $user->phone . '</p>';
                }
                return '-';
            })
            ->editColumn('amount', function ($e) {
                return number_format($e->amount, 2);
            })
            ->editColumn('created_at', function ($e) {
                return Carbon::parse($e->created_at)->format('Y-m-d H:i:s');
            })
            ->editColumn('updated_at', function ($e) {
                return Carbon::parse($e->updated_at)->format('Y-m-d H:i:s');
            })
            ->rawColumns(['account_person'])
            ->make(true);
    }

    public function addAmount()
    {
        $users = User::orderBy('name', 'desc')->get();
        return view('backend.wallets.add_amount', ['users' => $users]);
    }

    public function addAmountStore(Request $request)
    {
        $request->validate(
            [
                'user_id' => 'required',
                'amount' => 'required|integer',
                'description' => 'required',
            ],
            [
                'user_id.required' => 'You need to fill in the user name and phone number',
                'amount.required' => 'You need to fill in the amount',
                'description.required' => 'You need to fill in the description',
            ]
        );

        if ($request->amount < 1000) {
            return back()->withErrors(['amount' => 'The amount must be as less than 1000']);
        }



        DB::beginTransaction();
        try {
            $to_account = User::with('wallet')->where('id', $request->user_id)->firstOrFail();
            $to_account_wallet = $to_account->wallet;
            $to_account_wallet->increment('amount', $request->amount);
            $to_account_wallet->update();

            $ref_no = WalletGenerate::refNumber();

            $to_account_transaction = new Transaction();
            $to_account_transaction->ref_no = $ref_no;
            $to_account_transaction->trx_id = WalletGenerate::trxId();
            $to_account_transaction->user_id = $to_account->id;
            $to_account_transaction->type = 1;
            $to_account_transaction->amount = $request->amount;
            $to_account_transaction->source_id = 0;
            $to_account_transaction->description = $request->description;
            $to_account_transaction->save();

            DB::commit();
            return redirect()->route('wallet.index')->with('create', 'Successfully Add Admount');
        } catch (\Exception $error) {
            DB::rollBack();
            return back()->withErrors(['fail' => 'Something was wrong.' . $error->getMessage()])->withInput();
        }
    }

    public function reduceAmount()
    {
        $users = User::orderBy("name")->get();
        return view('backend.wallets.reduce_amount', ['users' => $users]);
    }

    public function reduceAmountStore(Request $request)
    {
        $request->validate(
            [
                'user_id' => 'required',
                'amount' => 'required|integer',
            ],
            [
                'user_id.required' => 'The user field is required.',
            ]
        );

        if ($request->amount < 1) {
            return back()->withErrors(['amount' => 'The amount must be at least 1 MMK.'])->withInput();
        }

        DB::beginTransaction();
        try {
            $to_account = User::with('wallet')->where('id', $request->user_id)->firstOrFail();
            $to_account_wallet = $to_account->wallet;

            if ($to_account_wallet->amount < $request->amount) {
                throw new Exception("The amount is greater than the wallet balance.");
            }

            $to_account_wallet->decrement('amount', $request->amount);
            $to_account_wallet->update();

            $ref_no = WalletGenerate::refNumber();
            $to_account_transaction = new Transaction();
            $to_account_transaction->ref_no = $ref_no;
            $to_account_transaction->trx_id = WalletGenerate::trxId();
            $to_account_transaction->user_id = $to_account->id;
            $to_account_transaction->type = 2;
            $to_account_transaction->amount = $request->amount;
            $to_account_transaction->source_id = 0;
            $to_account_transaction->description = $request->description;
            $to_account_transaction->save();

            // To Noti
            $title = 'E-money Reduced!';
            $message = 'Your wallet reduced ' . number_format($request->amount) . ' MMK by Magic Pay Super Admin.';
            $sourceable_id = $to_account_transaction->id;
            $sourceable_type = Transaction::class;
            $web_link = url('/transaction/' . $to_account_transaction->trx_id);
            $deep_link = [
                'target' => 'transaction_detail',
                'parameter' => [
                    'trx_id' => $to_account_transaction->trx_id,
                ],
            ];
            Notification::send([$to_account], new GeneralNotification($title, $message, $sourceable_id, $sourceable_type, $web_link, $deep_link));

            DB::commit();
            return redirect()->route('wallet.index')->with('create', 'Successfully reduced amount.');
        } catch (\Exception $error) {
            DB::rollback();
            return back()->withErrors(['fail' => 'Something wrong. ' . $error->getMessage()])->withInput();
        }
    }
}
