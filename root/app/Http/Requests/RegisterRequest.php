<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class RegisterRequest extends FormRequest
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
        Validator::extend('strong_password', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,}$/', $value);
        });
        Validator::extend('first_last_name', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^[a-zA-Z]+ [a-zA-Z]+$/', $value);
        });

        
        return [
            'name' => 'required|string|first_last_name|max:40',
            'email' => 'required|string|email:rfc,dns|max:40|unique:users,email',
            'password' => 'required|string|min:8|confirmed|strong_password',
            'agreement' => 'required|boolean'
        ];
    }
}
