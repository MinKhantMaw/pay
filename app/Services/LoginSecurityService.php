<?php

namespace App\Services;

use App\Enums\UserStatus;
use App\Models\User;

class LoginSecurityService
{
    public const INACTIVE_MESSAGE = 'Your account is inactive. Please contact admin.';

    public const LOCKED_MESSAGE = 'Too many failed attempts. Please try again after 30 seconds.';

    public const DISABLED_MESSAGE = 'Your account has been disabled due to too many failed login attempts. Please contact admin.';

    public function blockMessage(User $user): ?string
    {
        if ($user->status === UserStatus::InActive) {
            return self::INACTIVE_MESSAGE;
        }

        if ($user->locked_until && $user->locked_until->isFuture()) {
            return self::LOCKED_MESSAGE;
        }

        return null;
    }

    public function recordFailedAttempt(User $user): string
    {
        $attempts = (int) $user->failed_login_attempts + 1;

        if ($attempts >= 8) {
            $user->forceFill([
                'failed_login_attempts' => $attempts,
                'locked_until' => null,
                'status' => UserStatus::InActive,
            ])->save();

            return self::DISABLED_MESSAGE;
        }

        $updates = [
            'failed_login_attempts' => $attempts,
        ];

        if ($attempts === 5) {
            $updates['locked_until'] = now()->addSeconds(30);
        }

        $user->forceFill($updates)->save();

        if ($attempts === 5) {
            return self::LOCKED_MESSAGE;
        }

        return trans('auth.failed');
    }

    public function clearFailedAttempts(User $user): void
    {
        if ($user->failed_login_attempts || $user->locked_until) {
            $user->forceFill([
                'failed_login_attempts' => 0,
                'locked_until' => null,
            ])->save();
        }
    }
}
