<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\WalletGenerate;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use App\Models\Wallet;
use App\Providers\RouteServiceProvider;
use App\Services\AuditLogService;
use App\Services\LoginSecurityService;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
     */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function guard()
    {
        return Auth::guard();
    }

    public function username()
    {
        return 'phone';
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request, LoginSecurityService $loginSecurityService)
    {
        $user = User::where($this->username(), $request->input($this->username()))->first();

        if (! $user) {
            app(AuditLogService::class)->log('login_failed', 'Auth', 'User login failed: unknown phone.', null, [
                'phone' => $request->input($this->username()),
            ], null, $request);

            throw ValidationException::withMessages([
                $this->username() => [trans('auth.failed')],
            ]);
        }

        if ($blockMessage = $loginSecurityService->blockMessage($user)) {
            throw ValidationException::withMessages([
                $this->username() => [$blockMessage],
            ]);
        }

        if (! Hash::check($request->password, $user->password)) {
            $message = $loginSecurityService->recordFailedAttempt($user);
            app(AuditLogService::class)->log('login_failed', 'Auth', 'User login failed.', null, [
                'user_id' => $user->id,
                'message' => $message,
            ], $user, $request);

            throw ValidationException::withMessages([
                $this->username() => [$message],
            ]);
        }

        $loginSecurityService->clearFailedAttempts($user);

        $this->guard()->login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        return $this->authenticated($request, $user) ?: redirect()->intended($this->redirectPath());
    }

    protected function authenticated(Request $request, $user)
    {
        $user->ip = $request->ip();
        $user->user_agent = $request->server('HTTP_USER_AGENT');
        $user->login_at = date('Y-m-d H:i:s');
        $user->update();
        app(AuditLogService::class)->log('login_success', 'Auth', 'User login success.', null, [
            'user_id' => $user->id,
        ], $user, $request);
        Wallet::firstOrCreate(

            [
                'user_id' => $user->id,
            ],

            [
                'account_number' => WalletGenerate::accountNumber(),
                'amount' => 0,
            ]

        );

        return redirect($this->redirectTo);
    }

    // public function logout(Request $request)
    // {
    //     $this->guard()->logout();

    //     $request->session()->invalidate();

    //     $request->session()->regenerateToken();

    //     if($response = $this->logout)
    // }
}
