<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdatePassword;
use App\Models\Wallet;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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
        return view('frontend.transfer');
    }
}
