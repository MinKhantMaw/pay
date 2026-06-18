<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAdminUser extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'email' => 'required|unique:admin_users,email',
            'phone' => 'required|unique:admin_users,phone|min:11|max:20',
            'password' => 'required|min:6|max:15',
            'roles' => 'nullable|array',
            'roles.*' => Rule::exists('roles', 'name')->where('guard_name', 'admin_user'),
        ];
    }
}
