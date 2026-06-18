<?php

namespace App\Services;

use App\Enums\ApprovalStatus;
use App\Helpers\WalletGenerate;
use App\Models\AdminUser;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletApproval;
use Exception;
use Illuminate\Support\Facades\DB;

class WalletApprovalService
{
    public const ACTION_WALLET_ADD = 'wallet_balance_add';
    public const ACTION_WALLET_REDUCE = 'wallet_balance_reduce';
    public const ACTION_CASHIN = 'cashin';
    public const ACTION_CASHOUT = 'cashout';
    public const ACTION_REFUND = 'transaction_refund';
    public const ACTION_REVERSE = 'transaction_reverse';

    public function __construct(private AuditLogService $auditLogService)
    {
    }

    public function requestWalletAdjustment(array $data, AdminUser $requester, string $action): WalletApproval
    {
        $user = User::with('wallet')->findOrFail($data['user_id']);

        if (! $user->wallet) {
            throw new Exception('Selected user does not have a wallet.');
        }

        if ($action === self::ACTION_WALLET_REDUCE && $user->wallet->amount < $data['amount']) {
            throw new Exception('The amount is greater than the wallet balance.');
        }

        $approval = WalletApproval::create([
            'action' => $action,
            'module' => 'Wallet',
            'status' => ApprovalStatus::Pending,
            'wallet_id' => $user->wallet->id,
            'user_id' => $user->id,
            'amount' => $data['amount'],
            'description' => $data['description'] ?? null,
            'requested_by' => $requester->id,
            'payload' => [
                'user_id' => $user->id,
                'amount' => $data['amount'],
                'description' => $data['description'] ?? null,
            ],
        ]);

        $this->auditLogService->log(
            'approval_requested',
            'Wallet',
            'Wallet adjustment approval requested.',
            null,
            $approval->toArray(),
            $requester
        );

        return $approval;
    }

    public function approve(WalletApproval $approval, AdminUser $approver): WalletApproval
    {
        $this->ensureCanDecide($approval, $approver);

        return DB::transaction(function () use ($approval, $approver) {
            $approval = WalletApproval::whereKey($approval->id)->lockForUpdate()->firstOrFail();

            if (! $approval->isPending()) {
                throw new Exception('This approval has already been processed.');
            }

            $oldApproval = $approval->toArray();

            match ($approval->action) {
                self::ACTION_WALLET_ADD => $this->applyWalletAdd($approval),
                self::ACTION_WALLET_REDUCE => $this->applyWalletReduce($approval),
                default => null,
            };

            $approval->forceFill([
                'status' => ApprovalStatus::Approved,
                'approved_by' => $approver->id,
                'approved_at' => now(),
            ])->save();

            $this->auditLogService->log(
                $this->approvedAuditAction($approval->action),
                $approval->module,
                'Approval approved.',
                $oldApproval,
                $approval->fresh()->toArray(),
                $approver
            );

            return $approval->fresh(['user', 'wallet', 'requester', 'approver']);
        });
    }

    public function reject(WalletApproval $approval, AdminUser $rejecter, string $reason): WalletApproval
    {
        $this->ensureCanDecide($approval, $rejecter);

        return DB::transaction(function () use ($approval, $rejecter, $reason) {
            $approval = WalletApproval::whereKey($approval->id)->lockForUpdate()->firstOrFail();

            if (! $approval->isPending()) {
                throw new Exception('This approval has already been processed.');
            }

            $oldApproval = $approval->toArray();

            $approval->forceFill([
                'status' => ApprovalStatus::Rejected,
                'rejected_by' => $rejecter->id,
                'rejected_at' => now(),
                'reject_reason' => $reason,
            ])->save();

            $this->auditLogService->log(
                $this->rejectedAuditAction($approval->action),
                $approval->module,
                'Approval rejected.',
                $oldApproval,
                $approval->fresh()->toArray(),
                $rejecter
            );

            return $approval->fresh(['user', 'wallet', 'requester', 'rejecter']);
        });
    }

    private function applyWalletAdd(WalletApproval $approval): void
    {
        $wallet = Wallet::whereKey($approval->wallet_id)->lockForUpdate()->firstOrFail();
        $oldBalance = $wallet->amount;

        $wallet->increment('amount', $approval->amount);

        $transaction = $this->createTransaction($approval, 1);
        $approval->transaction_id = $transaction->id;
        $approval->save();

        $this->auditLogService->log('wallet_balance_added', 'Wallet', 'Wallet balance added after approval.', [
            'balance' => $oldBalance,
        ], [
            'balance' => $wallet->fresh()->amount,
            'approval_id' => $approval->id,
            'transaction_id' => $transaction->id,
        ]);
    }

    private function applyWalletReduce(WalletApproval $approval): void
    {
        $wallet = Wallet::whereKey($approval->wallet_id)->lockForUpdate()->firstOrFail();
        $oldBalance = $wallet->amount;

        if ($wallet->amount < $approval->amount) {
            throw new Exception('The amount is greater than the wallet balance.');
        }

        $wallet->decrement('amount', $approval->amount);

        $transaction = $this->createTransaction($approval, 2);
        $approval->transaction_id = $transaction->id;
        $approval->save();

        $this->auditLogService->log('wallet_balance_reduced', 'Wallet', 'Wallet balance reduced after approval.', [
            'balance' => $oldBalance,
        ], [
            'balance' => $wallet->fresh()->amount,
            'approval_id' => $approval->id,
            'transaction_id' => $transaction->id,
        ]);
    }

    private function createTransaction(WalletApproval $approval, int $type): Transaction
    {
        return Transaction::create([
            'ref_no' => WalletGenerate::refNumber(),
            'trx_id' => WalletGenerate::trxId(),
            'user_id' => $approval->user_id,
            'type' => $type,
            'amount' => $approval->amount,
            'source_id' => 0,
            'description' => $approval->description,
        ]);
    }

    private function ensureCanDecide(WalletApproval $approval, AdminUser $admin): void
    {
        if ((int) $approval->requested_by === (int) $admin->id && ! $admin->hasRole('Super Admin')) {
            throw new Exception('Self approval is not allowed.');
        }

        if ($admin->hasRole('Super Admin')) {
            return;
        }

        $allowed = match ($approval->action) {
            self::ACTION_WALLET_ADD, self::ACTION_WALLET_REDUCE => $admin->hasRole('Admin'),
            self::ACTION_CASHIN, self::ACTION_CASHOUT => $admin->hasRole('Finance'),
            self::ACTION_REFUND, self::ACTION_REVERSE => $admin->hasPermissionTo('transaction.refund')
                || $admin->hasPermissionTo('transaction.reverse'),
            default => false,
        };

        if (! $allowed) {
            throw new Exception('You are not authorized to approve this request.');
        }
    }

    private function approvedAuditAction(string $action): string
    {
        return match ($action) {
            self::ACTION_CASHIN => 'cashin_approved',
            self::ACTION_CASHOUT => 'cashout_approved',
            self::ACTION_REFUND => 'transaction_refunded',
            self::ACTION_REVERSE => 'transaction_reversed',
            default => 'approval_approved',
        };
    }

    private function rejectedAuditAction(string $action): string
    {
        return match ($action) {
            self::ACTION_CASHIN => 'cashin_rejected',
            self::ACTION_CASHOUT => 'cashout_rejected',
            default => 'approval_rejected',
        };
    }
}
