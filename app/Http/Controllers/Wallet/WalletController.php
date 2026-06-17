<?php

namespace App\Http\Controllers\Wallet;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddWalletAmountRequest;
use App\Http\Requests\ReduceWalletAmountRequest;
use App\Models\User;
use App\Models\Wallet;
use App\Services\WalletService;
use Carbon\Carbon;
use Exception;
use Yajra\DataTables\Facades\DataTables;

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
                    return '<p>Name : '.$user->name.' </p> <p>Email : '.$user->email.' </p> <p>Phone : '.$user->phone.'</p>';
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

    public function addAmountStore(AddWalletAmountRequest $request, WalletService $walletService)
    {
        try {
            $walletService->addAmount($request->validated());

            return redirect()->route('wallet.index')->with('create', 'Successfully Add Admount');
        } catch (Exception $error) {
            return back()->withErrors(['fail' => 'Something was wrong.'.$error->getMessage()])->withInput();
        }
    }

    public function reduceAmount()
    {
        $users = User::orderBy('name')->get();

        return view('backend.wallets.reduce_amount', ['users' => $users]);
    }

    public function reduceAmountStore(ReduceWalletAmountRequest $request, WalletService $walletService)
    {
        try {
            $walletService->reduceAmount($request->validated());

            return redirect()->route('wallet.index')->with('create', 'Successfully reduced amount.');
        } catch (Exception $error) {
            return back()->withErrors(['fail' => 'Something wrong. '.$error->getMessage()])->withInput();
        }
    }
}
