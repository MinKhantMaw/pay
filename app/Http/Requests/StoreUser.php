<?php

namespace App\Http\Requests;

use App\Enums\UserStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUser extends FormRequest
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
            'email' => 'required|unique:users,email',
            'phone' => 'required|unique:users,phone|min:11|max:20',
            'profile' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'status' => ['required', Rule::in([UserStatus::Active->value, UserStatus::InActive->value])],
            'password' => ['required', 'string', 'min:6', 'max:15', 'confirmed', 'regex:/^[0-9]+$/'],
        ];
    }

    public function messages()
    {
        return [
            'password.regex' => 'Password must contain numbers only.',
        ];
    }
}
