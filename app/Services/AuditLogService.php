<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuditLogService
{
    public function log(
        string $action,
        string $module,
        ?string $description = null,
        ?array $oldValues = null,
        ?array $newValues = null,
        ?Model $actor = null,
        ?Request $request = null
    ): AuditLog {
        $request ??= request();
        $actor ??= Auth::guard('admin_user')->user() ?: Auth::guard('web')->user();

        return AuditLog::create([
            'user_id' => $actor?->getKey(),
            'user_type' => $actor ? $actor::class : null,
            'action' => $action,
            'module' => $module,
            'description' => $description,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => $request?->ip(),
            'user_agent' => $request?->userAgent(),
            'created_at' => now(),
        ]);
    }
}
