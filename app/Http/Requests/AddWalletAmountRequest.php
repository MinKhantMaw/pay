<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddWalletAmountRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'user_id' => ['required', 'exists:users,id'],
            'amount' => ['required', 'integer', 'min:1000'],
            'description' => ['required', 'string', 'max:1000'],
        ];
    }

    public function messages()
    {
        return [
            'user_id.required' => 'Please choose a wallet user.',
            'user_id.exists' => 'The selected user could not be found.',
            'amount.required' => 'Please enter the amount.',
            'amount.integer' => 'The amount must be a whole number.',
            'amount.min' => 'The amount must be at least 1,000 MMK.',
            'description.required' => 'Please enter a description for this adjustment.',
            'description.max' => 'The description must not be longer than 1,000 characters.',
        ];
    }
}
