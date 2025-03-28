<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaveUserRequest extends FormRequest
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
            'name' => ['required', 'string', 'unique:users', 'max:255', 'regex:/^[\w.,:;?!-]+/'],
            'password' => ['required', 'string', 'confirmed:passwordConfirmation', 'unique:users', 'min:10'],
            'passwordConfirmation' => ['required', 'string'],
            'remember' => ['required', 'boolean'],
        ];
    }
}
