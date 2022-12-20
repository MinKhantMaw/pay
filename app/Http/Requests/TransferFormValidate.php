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
            'to_phone' => 'required',
            'amount' => 'required',
        ];
    }

    public function message()
    {
        return [
            'to_phone.required' => 'The phone number is required.',
            'amount' => 'The amount is required.',
        ];
    }
}
