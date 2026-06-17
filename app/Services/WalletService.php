<?php

namespace App\Services;

use App\Helpers\WalletGenerate;
use App\Models\Transaction;
use App\Models\User;
use App\Notifications\GeneralNotification;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class WalletService
{
    public function addAmount(array $data): Transaction
    {
        return DB::transaction(function () use ($data) {
            $toAccount = User::with('wallet')->where('id', $data['user_id'])->firstOrFail();
            $toAccount->wallet->increment('amount', $data['amount']);

            return $this->createAdminTransaction($toAccount, 1, $data['amount'], $data['description']);
        });
    }

    public function reduceAmount(array $data): Transaction
    {
        return DB::transaction(function () use ($data) {
            $toAccount = User::with('wallet')->where('id', $data['user_id'])->firstOrFail();

            if ($toAccount->wallet->amount < $data['amount']) {
                throw new Exception('The amount is greater than the wallet balance.');
            }

            $toAccount->wallet->decrement('amount', $data['amount']);
            $transaction = $this->createAdminTransaction($toAccount, 2, $data['amount'], $data['description'] ?? null);

            $this->sendReducedNotification($toAccount, $transaction, $data['amount']);

            return $transaction;
        });
    }

    private function createAdminTransaction(User $user, int $type, int $amount, ?string $description): Transaction
    {
        $transaction = new Transaction;
        $transaction->ref_no = WalletGenerate::refNumber();
        $transaction->trx_id = WalletGenerate::trxId();
        $transaction->user_id = $user->id;
        $transaction->type = $type;
        $transaction->amount = $amount;
        $transaction->source_id = 0;
        $transaction->description = $description;
        $transaction->save();

        return $transaction;
    }

    private function sendReducedNotification(User $user, Transaction $transaction, int $amount): void
    {
        $deepLink = [
            'target' => 'transaction_detail',
            'parameter' => [
                'trx_id' => $transaction->trx_id,
            ],
        ];

        Notification::send([$user], new GeneralNotification(
            'E-money Reduced!',
            'Your wallet reduced '.number_format($amount).' MMK by Magic Pay Super Admin.',
            $transaction->id,
            Transaction::class,
            url('/transaction/'.$transaction->trx_id),
            $deepLink
        ));
    }
}
