<?php

namespace App\Http\Controllers\Wallet;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddWalletAmountRequest;
use App\Http\Requests\ReduceWalletAmountRequest;
use App\Models\User;
use App\Models\Wallet;
use App\Services\WalletApprovalService;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Log;
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

    public function addAmountStore(AddWalletAmountRequest $request, WalletApprovalService $walletApprovalService)
    {
        try {
            $walletApprovalService->requestWalletAdjustment(
                $request->validated(),
                auth('admin_user')->user(),
                WalletApprovalService::ACTION_WALLET_ADD
            );

            return redirect()->route('admin.approvals.index')->with('create', 'Wallet add amount approval requested.');
        } catch (Exception $error) {
            Log::warning('Wallet add amount approval request failed.', [
                'admin_user_id' => auth('admin_user')->id(),
                'request' => $request->safe()->except(['_token']),
                'exception' => $error,
            ]);

            return back()->withErrors(['fail' => $this->friendlyWalletError($error)])->withInput();
        }
    }

    public function reduceAmount()
    {
        $users = User::orderBy('name')->get();

        return view('backend.wallets.reduce_amount', ['users' => $users]);
    }

    public function reduceAmountStore(ReduceWalletAmountRequest $request, WalletApprovalService $walletApprovalService)
    {
        try {
            $walletApprovalService->requestWalletAdjustment(
                $request->validated(),
                auth('admin_user')->user(),
                WalletApprovalService::ACTION_WALLET_REDUCE
            );

            return redirect()->route('admin.approvals.index')->with('create', 'Wallet reduce amount approval requested.');
        } catch (Exception $error) {
            Log::warning('Wallet reduce amount approval request failed.', [
                'admin_user_id' => auth('admin_user')->id(),
                'request' => $request->safe()->except(['_token']),
                'exception' => $error,
            ]);

            return back()->withErrors(['fail' => $this->friendlyWalletError($error)])->withInput();
        }
    }

    private function friendlyWalletError(Exception $error): string
    {
        $allowedMessages = [
            'Selected user does not have a wallet.',
            'The amount is greater than the wallet balance.',
        ];

        if (in_array($error->getMessage(), $allowedMessages, true)) {
            return $error->getMessage();
        }

        return 'The wallet request could not be submitted. Please check the details and try again.';
    }
}
