<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Wallet;
use App\Models\Transaction;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Helpers\WalletGenerate;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\ProfileResource;
use App\Http\Resources\TransactionResource;
use App\Http\Resources\NotificationResource;
use App\Http\Resources\TransactionDetailResource;
use App\Http\Resources\NotificationDetailResource;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|min:5|max:20',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required',
            'password' => 'required|min:6|max:20',
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->ip = $request->ip();
        $user->user_agent = $request->server('HTTP_USER_AGENT');
        $user->login_at = date('Y-m-d H:i:s');
        $user->password = Hash::make($request->password);
        $user->save();

        Wallet::firstOrCreate(

            [
                'user_id' => $user->id,
            ],

            [
                'account_number' => WalletGenerate::accountNumber(),
                'amount' => 0,
            ]

        );


        $token = $user->createToken('Pay Mal')->accessToken;

        return response()->json([
            'status' => 'Register Success',
            'data' => $user,
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'phone' => 'required',
            'password' => 'required'
        ]);

        if (Auth::attempt(['phone' => $request->phone, 'password' => $request->password])) {
            $user = auth()->user();

            $user->ip = $request->ip();
            $user->user_agent = $request->server('HTTP_USER_AGENT');
            $user->login_at = date('Y-m-d H:i:s');
            $user->update();

            Wallet::firstOrCreate(

                [
                    'user_id' => $user->id,
                ],

                [
                    'account_number' => WalletGenerate::accountNumber(),
                    'amount' => 0,
                ]

            );

            $token = $user->createToken('Pay Mal')->accessToken;
            return response()->json([
                'status' => 'Login Successfully',
                'data' => $user,
                'authorisation' => [
                    'token' => $token,
                    'type' => 'bearer',
                ]
            ]);
        }

        return response()->json([
            'stataus' => 'These credentials do not match our records.',
        ]);
    }

    public function profile()
    {
        $user = auth()->user();
        $data = new ProfileResource($user);
        return ApiResponse::success('Your profile has been Fetch', $data, 200);
    }

    public function logout()
    {
        $user = auth()->user();
        $user->token()->revoke();
        return ApiResponse::success('Logout Successful', null, 200);
    }

    public function transaction(Request $request)
    {
        $user = auth()->user();
        $transaction = Transaction::with(['user', 'source'])->where('user_id', $user->id);

        if ($request->date) {
            $transaction = $transaction->whereDate('created_at', $request->date);
        }

        if ($request->type) {
            $transaction = $transaction->where('type', $request->type);
        }

        $transaction = $transaction->paginate(5);

        $transaction_resource = TransactionResource::collection($transaction)->additional(['message' => 'Fetch Transaction Successfully']);
        return $transaction_resource;
    }

    public function transactionDetail($trx_id)
    {
        $authUser = auth()->user();
        $transaction_detail = Transaction::with(['user', 'source'])->where('trx_id', $trx_id)->where('user_id', $authUser->id)->firstOrFail();
        $data = new TransactionDetailResource($transaction_detail);
        return ApiResponse::success('Fetch Transaction Detail', $data, 200);
    }

    public function notification()
    {
        $authUser = auth()->user();
        $notifications = $authUser->notifications()->paginate(5);

        return NotificationResource::collection($notifications)->additional(['message' => 'Fetch Notifications',]);
    }

    public function notificationDetail($id)
    {
        $authUser = auth()->user();
        $notification = $authUser->notifications()->where('id', $id)->firstOrFail();
        $notification->markAsRead();

        $data = new NotificationDetailResource($notification);

        return ApiResponse::success('Fetch Notification', $data, 200);
    }
}
