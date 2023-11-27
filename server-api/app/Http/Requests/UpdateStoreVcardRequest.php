<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStoreVcardRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        $rules = $this->method() == 'POST' ? 'unique:Vcards,phone_number' : 'exists:Vcards,phone_number';
        return [
            // A implementar
            'phone_number' => "required|integer|digits:9|regex:/^9\d{8}$/|$rules",
            'name' => 'required|string|min:3|max:255',
            'email' => 'required|email',
            'photo_url' => 'nullable|file|image',
            'password' => 'required|string|min:8|max:50',
            'confirmation_code' => 'required|integer|digits:3'        
        ];
    }
}
