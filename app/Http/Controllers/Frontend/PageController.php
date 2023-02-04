<?php

namespace App\Http\Controllers\Frontend;

use App\Helpers\WalletGenerate;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use function GuzzleHttp\Promise\all;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use App\Http\Requests\UpdatePassword;
use App\Http\Requests\TransferFormValidate;
use App\Models\Transaction;

class PageController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('frontend.home', ['user' => $user]);
    }

    public function profile()
    {
        $user = Auth::guard('web')->user();
        return view('frontend.profile', ['user' => $user]);
    }

    public function updatePassword()
    {
        return view('frontend.update_password');
    }

    public function updatePasswordStore(UpdatePassword $request)
    {
        $old_password = $request->old_password;
        $new_password = $request->new_password;

        $user = Auth::guard('web')->user();

        if (Hash::check($old_password, $user->password)) {
            $user->password = Hash::make($new_password);
            $user->update();

            return redirect()->route('profile')->with('update', 'Successfully password Updated');
        }

        return back()->withErrors(['old_password' => 'The old password was incorrect'])->withInput();
    }

    public function wallet()
    {
        $auth_user = auth()->guard('web')->user();
        // return $auth_user;
        return view('frontend.wallet', ['auth_user' => $auth_user]);
    }

    public function transfer()
    {
        $auth_user = auth()->guard('web')->user();
        return view('frontend.transfer', ['auth_user' => $auth_user]);
    }

    public function transferConfirm(TransferFormValidate $request)
    {
        if ($request->amount < 1000) {
            return back()->withErrors(['amount' => 'The amount must be greater than 1000 MMK'])->withInput();
        }

        $auth_user = auth()->guard('web')->user();
        if ($auth_user->phone == $request->to_phone) {
            return back()->withErrors(['to_phone' => 'To account is invalid..!'])->withInput();
        }

        $to_account = User::where('phone', $request->to_phone)->first();
        if (!$to_account) {
            return back()->withErrors(['to_phone' => 'This phone number is not opening account'])->withInput();
        }


        $from_account = $auth_user;
        $amount = $request->amount;
        $description = $request->description;
        return view('frontend.transfer_confirm', ['from_account' => $from_account, 'to_account' => $to_account, 'amount' => $amount, 'description' => $description]);
    }

    public function transferComplete(TransferFormValidate $request)
    {
        // return $request->all();
        $auth_user = auth()->guard('web')->user();
        if ($auth_user->phone == $request->to_phone) {
            return back()->withErrors(['to_phone' => 'To account is invalid..!'])->withInput();
        }

        $to_account = User::where('phone', $request->to_phone)->first();
        if (!$to_account) {
            return back()->withErrors(['to_phone' => 'This phone number is not opening account'])->withInput();
        }


        $from_account = $auth_user;
        $amount = $request->amount;
        $description = $request->description;

        if (!$from_account->wallet || !$to_account->wallet) {
            return back()->withErrors(['fail' => 'Something was wrong.The given data is invalid.'])->withInput();
        }

        DB::beginTransaction();
        try {
            $from_account_wallet = $from_account->wallet;
            $from_account_wallet->decrement('amount', $amount);
            $from_account_wallet->update();

            $to_account_wallet = $to_account->wallet;
            $to_account_wallet->increment('amount', $amount);
            $to_account_wallet->update();

            $ref_no = WalletGenerate::refNumber();

            $from_account_transaction = new Transaction();
            $from_account_transaction->ref_no = $ref_no;
            $from_account_transaction->trx_id = WalletGenerate::trxId();
            $from_account_transaction->user_id = $from_account->id;
            $from_account_transaction->type = 2;
            $from_account_transaction->amount = $amount;
            $from_account_transaction->source_id = $to_account->id;
            $from_account_transaction->description = $description;
            $from_account_transaction->save();

            $to_account_transaction = new Transaction();
            $to_account_transaction->ref_no = $ref_no;
            $to_account_transaction->trx_id = WalletGenerate::trxId();
            $to_account_transaction->user_id = $to_account->id;
            $to_account_transaction->type = 1;
            $to_account_transaction->amount = $amount;
            $to_account_transaction->source_id = $from_account->id;
            $to_account_transaction->description = $description;
            $to_account_transaction->save();

            DB::commit();
            return redirect('/transactions/' . $from_account_transaction->trx_id)->with('transfer_success', 'Successfully transferred');
        } catch (\Exception  $error) {
            DB::rollBack();
            return back()->withErrors(['fail' => 'Something was wrong.' . $error->getMessage()])->withInput();
        }
    }

    public function toAccountVerify(Request $request)
    {
        $authUser = auth()->guard('web')->user();
        if ($authUser->phone != $request->phone) {
            $user = User::where('phone', $request->phone)->first();
            if ($user) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'success',
                    'data' => $user,
                ]);
            }
        }
        return response()->json([
            'status' => 'fail',
            'message' => 'Invalid phone number',
        ]);
    }

    public function passwordCheck(Request $request)
    {
        if (!$request->password) {
            return response()->json([
                'status' => 'fail',
                'message' => 'Please enter a password',
            ]);
        }
        $user = auth()->user();
        if (Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => 'success',
                'message' => 'The password is correct',
            ]);
        }
        return response()->json([
            'status' => 'fail',
            'message' => 'The password is incorrect',
        ]);
    }

    public function transactions(Request $request)
    {
        $authUser = auth()->user();
        $transactions = Transaction::with(['user', 'source'])->where('user_id', $authUser->id)->orderBy('id', 'DESC');
        if ($request->type) {
            $transactions = $transactions->where('type', $request->type);
        }
        $transactions = $transactions->paginate(5);
        return view('frontend.transactions', ['transactions' => $transactions]);
    }

    public function transactionsDetails($trx_id)
    {
        $authUser = Auth::guard('web')->user();
        $transactionDetail = Transaction::with(['user', 'source'])->where('user_id', $authUser->id)->where('trx_id', $trx_id)->first();
        return view('frontend.transactionDetail', ['transactionDetail' => $transactionDetail]);
    }
}
