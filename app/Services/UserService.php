<?php

namespace App\Services;

use App\Enums\UserStatus;
use App\Helpers\WalletGenerate;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserService
{
    public function __construct(private NotificationService $notificationService) {}

    public function create(array $data): User
    {
        return DB::transaction(function () use ($data) {
            $user = new User;
            $user->name = $data['name'];
            $user->email = $data['email'];
            $user->phone = $data['phone'];
            $user->profile = $this->storeProfileImage($data['profile'] ?? null);
            $user->status = $data['status'] ?? UserStatus::Active->value;
            $user->password = Hash::make($data['password']);
            $user->save();

            $this->ensureWallet($user);
            $this->notificationService->notifyUserCreated($user);

            return $user;
        });
    }

    public function update(User $user, array $data): User
    {
        return DB::transaction(function () use ($user, $data) {
            $user->name = $data['name'];
            $user->email = $data['email'];
            $user->phone = $data['phone'];
            $user->status = $data['status'];

            if ($data['status'] === UserStatus::Active->value) {
                $user->failed_login_attempts = 0;
                $user->locked_until = null;
            }

            if (($data['profile'] ?? null) instanceof UploadedFile) {
                $this->deleteProfileImage($user);
                $user->profile = $this->storeProfileImage($data['profile']);
            }

            $user->update();

            $this->ensureWallet($user);

            return $user;
        });
    }

    private function ensureWallet(User $user): Wallet
    {
        return Wallet::firstOrCreate(
            ['user_id' => $user->id],
            [
                'account_number' => WalletGenerate::accountNumber(),
                'amount' => 0,
            ]
        );
    }

    private function storeProfileImage(?UploadedFile $profile): ?string
    {
        if (! $profile) {
            return null;
        }

        return $profile->store('users/profiles', 'public');
    }

    private function deleteProfileImage(User $user): void
    {
        if ($user->profile && Storage::disk('public')->exists($user->profile)) {
            Storage::disk('public')->delete($user->profile);
        }
    }
}
