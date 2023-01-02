<?php

namespace App\Http\Controllers\Frontend;

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UpdatePassword;
use App\Http\Requests\TransferFormValidate;

class PageController extends Controller
{
    public function index()
    {
        $user = Auth::guard('web')->user();
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

        $check_to = User::where('phone', $request->to_phone)->first();
        if (!$check_to) {
            return back()->withErrors(['to_phone' => 'This phone number is not opening account'])->withInput();
        }

        $auth_user = auth()->guard('web')->user();
        $to_phone = $request->to_phone;
        $amount = $request->amount;
        $description = $request->description;
        return view('frontend.transfer_confirm', ['auth_user' => $auth_user, 'to_phone' => $to_phone, 'amount' => $amount, 'description' => $description]);
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
}
