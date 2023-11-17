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
            //'phone_number' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'photo_url' => 'nullable|file|image',
            'password' => 'nullable|file|image',
            'confirmation_code' => 'nullable|file|image',
            //todo corrigir blocked
            //'blocked' => 'nullable|in:false,true',
            //'max_debit' => 'nullable|decimal|min:0',
            //'custom_options' => 'nullable|json',
            //'custom_data' => 'nullable|json'
        ];
    }
}
