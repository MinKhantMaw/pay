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
            'user_id' => 'required',
            'amount' => 'required|integer|min:1000',
            'description' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'user_id.required' => 'You need to fill in the user name and phone number',
            'amount.required' => 'You need to fill in the amount',
            'amount.min' => 'The amount must be as less than 1000',
            'description.required' => 'You need to fill in the description',
        ];
    }
}
