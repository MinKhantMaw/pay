<?php

namespace App\Http\Controllers\Frontend;

use App\Models\User;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Helpers\WalletGenerate;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UpdatePassword;
use App\Http\Requests\TransferFormValidate;
use App\Notifications\GeneralNotification;
use Illuminate\Support\Facades\Notification;

class PageController extends Controller
{
    public function index()
    {

        $user = Auth::user();

        // $title = 'hello';
        // $message = 'lorem';
        // $sourceable_id = 1;
        // $sourceable_type = User::class;
        // $web_link = url('profile');

        // Notification::send($user, new GeneralNotification($title, $message, $sourceable_id, $sourceable_type, $web_link));
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

            $title = 'password changed';
            $message = 'Your password has been changed successfully';
            $sourceable_id = $user->id;
            $sourceable_type = User::class;
            $web_link = url('profile');
            $deep_link = [
                'target' => 'profile',
                'parameter' => null,
            ];

            Notification::send($user, new GeneralNotification($title, $message, $sourceable_id, $sourceable_type, $web_link, $deep_link));

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

        $authUser = auth()->guard('web')->user();
        $from_account = $authUser;
        $to_phone = $request->to_phone;
        $amount = $request->amount;
        $description = $request->description;
        $hash_value = $request->hash_value;

        // $str = $to_phone . $amount . $description;
        // $hash_value2 = hash_hmac('sha256', $str, 'magicpay123!@#');
        // if ($hash_value !== $hash_value2) {
        //     return back()->withErrors(['amount' => 'The given data is invalid.'])->withInput();
        // }

        if ($amount < 1000) {
            return back()->withErrors(['amount' => 'The amount must be at least 1000 MMK.'])->withInput();
        }

        if ($from_account->phone == $to_phone) {
            return back()->withErrors(['to_phone' => 'To account is invalid.'])->withInput();
        }

        $to_account = User::where('phone', $request->to_phone)->first();
        if (!$to_account) {
            return back()->withErrors(['to_phone' => 'To account is invalid.'])->withInput();
        }

        if (!$from_account->wallet || !$to_account->wallet) {
            return back()->withErrors(['fail' => 'The given data is invalid.'])->withInput();
        }

        if ($from_account->wallet->amount < $amount) {
            return back()->withErrors(['amount' => 'The amount is not enough.'])->withInput();
        }


        return view('frontend.transfer_confirm', ['from_account' => $from_account, 'to_account' => $to_account, 'amount' => $amount, 'description' => $description, 'hash_value' => $hash_value]);
    }

    public function transferComplete(TransferFormValidate $request)
    {
        // return $request->all();
        $auth_user = auth()->guard('web')->user();
        $from_account = $auth_user;
        $to_phone = $request->to_phone;
        $amount = $request->amount;
        $description = $request->description;
        $hash_value = $request->hash_value;
        // $str = $request->to_phone . $request->amount . $request->description;
        // $hash_value2 = hash_hmac('sha256', $str, 'magicpay@123');
        // if ($request->hash_value !== $hash_value2) {
        //     return back()->withErrors(['amount' => 'The given data is invalid.'])->withInput();
        // }

        if ($amount < 1000) {
            return back()->withErrors(['amount' => 'The amount must be greater than 1000 MMK'])->withInput();
        }


        if ($from_account == $to_phone) {
            return back()->withErrors(['to_phone' => 'To account is invalid..!'])->withInput();
        }

        $to_account = User::where('phone', $request->to_phone)->first();
        if (!$to_account) {
            return back()->withErrors(['to_phone' => 'This phone number is not opening account'])->withInput();
        }

        if (!$from_account->wallet || !$to_account->wallet) {
            return back()->withErrors(['fail' => 'Something was wrong.The given data is invalid.'])->withInput();
        }

        if ($from_account->wallet->amount < $amount) {
            return back()->withErrors(['amount' => 'The amount is not enought...!'])->withInput();
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

            // From Notification
            $title = 'Transfer To';
            $message = 'Your transfer ' . number_format($amount) . ' MMK to ' . $to_account->name . ' (' . $to_account->phone . ')';
            $sourceable_id = $from_account_transaction->id;
            $sourceable_type = Transaction::class;
            $web_link = url('/transactions/' . $from_account_transaction->trx_id);
            $deep_link = [
                'target' => 'transaction_detail',
                'parameter' => [
                    'tax_id' => $from_account_transaction->trx_id,
                ],
            ];
            Notification::send([$from_account], new GeneralNotification($title, $message, $sourceable_id, $sourceable_type, $web_link, $deep_link));

            // To Notification
            $title = 'Recieve From';
            $message = 'Your Recieve ' . number_format($amount) . ' MMK from ' .  $from_account->name  . ' (' . $from_account->phone . ')';
            $sourceable_id = $to_account_transaction->id;
            $sourceable_type = Transaction::class;
            $web_link =  url('/transactions/' . $to_account_transaction->trx_id);
            $deep_link = [
                'target' => 'transaction_detail',
                'parameter' => [
                    'tax_id' => $to_account_transaction->trx_id,
                ],
            ];
            Notification::send([$to_account], new GeneralNotification($title, $message, $sourceable_id, $sourceable_type, $web_link, $deep_link));

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

        if ($request->date) {
            $transactions = $transactions->whereDate('created_at', $request->date);
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

    public function transferHash(Request $request)
    {
        $str = $request->to_phone . $request->amount . $request->description;
        $hash_value = hash_hmac('sha256', $str, 'magicpay@123 ');
        return response()->json([
            'status' => 'success',
            'data' => $hash_value,
        ]);
    }

    public function receiveQR()
    {
        $authUser = auth()->guard('web')->user();
        return view('frontend.receive_qr', ['authUser' => $authUser]);
    }

    public function scanAndPay()
    {
        return view('frontend.scan_and_pay');
    }

    public function scanAndPayForm(Request $request)
    {
        $from_account = auth()->guard('web')->user();
        $to_account = User::where('phone', $request->to_phone)->first();
        if (!$to_account) {
            return back()->withErrors(['fail' => 'QR is invalid...!']);
        }

        return view('frontend.scan_and_pay_form', ['to_account' => $to_account, 'from_account' => $from_account]);
    }

    public function scanAndPayConfirm(TransferFormValidate $request)
    {
        $authUser = auth()->guard('web')->user();
        $from_account = $authUser;
        $to_phone = $request->to_phone;
        $amount = $request->amount;
        $description = $request->description;
        $hash_value = $request->hash_value;

        // $str = $to_phone . $amount . $description;
        // $hash_value2 = hash_hmac('sha256', $str, 'magicpay123!@#');
        // if ($hash_value !== $hash_value2) {
        //     return back()->withErrors(['amount' => 'The given data is invalid.'])->withInput();
        // }

        if ($amount < 1000) {
            return back()->withErrors(['amount' => 'The amount must be at least 1000 MMK.'])->withInput();
        }

        if ($from_account->phone == $to_phone) {
            return back()->withErrors(['to_phone' => 'To account is invalid.'])->withInput();
        }

        $to_account = User::where('phone', $request->to_phone)->first();
        if (!$to_account) {
            return back()->withErrors(['to_phone' => 'To account is invalid.'])->withInput();
        }

        if (!$from_account->wallet || !$to_account->wallet) {
            return back()->withErrors(['fail' => 'The given data is invalid.'])->withInput();
        }

        if ($from_account->wallet->amount < $amount) {
            return back()->withErrors(['amount' => 'The amount is not enough.'])->withInput();
        }

        return view('frontend.scan_and_pay_confirm', compact('from_account', 'to_account', 'amount', 'description', 'hash_value'));
    }

    public function scanAndPayComplete(TransferFormValidate $request)
    {
        // return $request->all();
        $auth_user = auth()->guard('web')->user();
        $from_account = $auth_user;
        $to_phone = $request->to_phone;
        $amount = $request->amount;
        $description = $request->description;
        $hash_value = $request->hash_value;
        // $str = $request->to_phone . $request->amount . $request->description;
        // $hash_value2 = hash_hmac('sha256', $str, 'magicpay@123');
        // if ($request->hash_value !== $hash_value2) {
        //     return back()->withErrors(['amount' => 'The given data is invalid.'])->withInput();
        // }

        if ($amount < 1000) {
            return back()->withErrors(['amount' => 'The amount must be greater than 1000 MMK'])->withInput();
        }


        if ($from_account == $to_phone) {
            return back()->withErrors(['to_phone' => 'To account is invalid..!'])->withInput();
        }

        $to_account = User::where('phone', $request->to_phone)->first();
        if (!$to_account) {
            return back()->withErrors(['to_phone' => 'This phone number is not opening account'])->withInput();
        }

        if (!$from_account->wallet || !$to_account->wallet) {
            return back()->withErrors(['fail' => 'Something was wrong.The given data is invalid.'])->withInput();
        }

        if ($from_account->wallet->amount < $amount) {
            return back()->withErrors(['amount' => 'The amount is not enought...!'])->withInput();
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
}
