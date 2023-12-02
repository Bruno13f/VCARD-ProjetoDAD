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
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {

        switch($this->payment_type){
            case 'VCARD':
                $rulesRef = 'regex:/^9\d{8}$/|exists:Vcards,phone_number';
                break;
            case 'MBWAY':
                $rulesRef = 'regex:/^9\d{8}$/';
                break;
            case 'PAYPAL':
                $rulesRef = 'email';
                break;
            case 'IBAN':
                $rulesRef = '^[A-Za-z]{2}\d{23}$';
                break;
            case 'MB':
                $rulesRef = '^\d{5}-\d{9}$';
                break;
            case 'VISA':
                $rulesRef = '^4\d{15}$';
                break;
        }

        return [
            'vcard' =>'required','integer','digits:9','regex:/^9\d{8}$/',
            'value' => 'required|numeric|regex:/^\d{0,9}(\.\d{1,2})?$/',
            'type' => 'required|in:C,D',
            'payment_type' => 'required|in:VCARD,MBWAY,PAYPAL,IBAN,MB,VISA',
            'payment_reference' => "required|$rules",
            'description' => 'nullable|string|max:255',
        ];
    }
}
