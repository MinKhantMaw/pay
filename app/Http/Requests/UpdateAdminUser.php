<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAdminUser extends FormRequest
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
        $id = $this->route('admin_user');
        return [
            'name' => 'required',
            'email' => 'required|unique:admin_users,email,' . $id,
            'phone' => 'required|unique:admin_users,phone|min:11|max:20,' . $id,
        ];
    }
}
