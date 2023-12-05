<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
use App\Models\Vcard;

class UpdateUserConfirmationCodeRequest extends FormRequest
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
        $userPhoneNumber = $this->route('vcard');

        return [
            'current_confirmation_code' => ['required', 'digits:4',
            function ($attribute, $value, $fail) use ($userPhoneNumber){
                if (!$this->validateCurrentConfirmationCode($value, $userPhoneNumber)) {
                    $fail('Invalid current confirmation code.');
                }
            }],
            'confirmation_code' => 'required|integer|digits:4',
            'confirmation_code_confirm' => 'required|integer|digits:4|same:confirmation_code',
        ];
    }

    public function messages(): array
    {
        return [
            'confirmation_code_confirm.same' => 'Confirmation Code Confirmation should match the Confirmation Code',
        ];
    }
    
    protected function validateCurrentConfirmationCode($currentConfirmationCode, $userPhoneNumber): bool
    {
        $user = Vcard::find($userPhoneNumber);

        return Hash::check($currentConfirmationCode, $user[0]['confirmation_code']);
    }

}
