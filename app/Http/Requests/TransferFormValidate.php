<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransferFormValidate extends FormRequest
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
            'to_phone' => ['required', 'string'],
            'amount' => ['required', 'integer', 'min:1000'],
            'description' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages()
    {
        return [
            'to_phone.required' => 'Please enter the receiver phone number.',
            'amount.required' => 'Please enter the amount.',
            'amount.integer' => 'The amount must be a whole number.',
            'amount.min' => 'The amount must be at least 1,000 MMK.',
            'description.max' => 'The description must not be longer than 1,000 characters.',
        ];
    }
}
