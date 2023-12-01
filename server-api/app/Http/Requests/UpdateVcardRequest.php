<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateVcardRequest extends FormRequest
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
        
        return [
            // A implementar
            'phone_number' => "required|integer|digits:9|regex:/^9\d{8}$/|exists:Vcards,phone_number",
            'name' => 'required|string|min:3|max:255',
            'email' => 'required|email',
            'photo_url' => 'nullable|file|image',
            'password' => 'required|string|min:3|max:50',
            'confirmation_code' => 'required|integer|digits:3'        
        ];
    }
}
