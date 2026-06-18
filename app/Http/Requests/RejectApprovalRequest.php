<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RejectApprovalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth('admin_user')->check();
    }

    public function rules(): array
    {
        return [
            'reject_reason' => ['required', 'string', 'max:1000'],
        ];
    }
}
