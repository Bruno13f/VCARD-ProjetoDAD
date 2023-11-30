<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTransactionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'vcard' =>'required','integer','digits:9','regex:/^9\d{8}$/',
            'value' => 'required|numeric|regex:/^\d{0,9}(\.\d{1,2})?$/',
            'type' => 'required|in:C,D',
            'payment_type' => 'required|in:VCARD,MBWAY,PAYPAL,IBAN,MB,VISA',
            'payment_reference' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
        ];
    }
}
