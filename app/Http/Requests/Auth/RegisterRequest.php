<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation Rules
     */
    public function rules(): array
    {
        return [

            'nik' => [
                'required',
                'string',
                'max:30',
                'unique:applicants,nik'
            ],

            'name' => [
                'required',
                'string',
                'max:255'
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email'
            ],

            'phone' => [
                'required',
                'string',
                'max:20'
            ],

            'address' => [
                'required',
                'string'
            ],

            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed'
            ],
        ];
    }

    /**
     * Custom Validation Messages
     */
    public function messages(): array
    {
        return [

            'nik.required'
                => 'NIK is required',

            'nik.unique'
                => 'NIK already registered',

            'name.required'
                => 'Name is required',

            'email.required'
                => 'Email is required',

            'email.email'
                => 'Invalid email format',

            'email.unique'
                => 'Email already registered',

            'phone.required'
                => 'Phone number is required',

            'address.required'
                => 'Address is required',

            'password.required'
                => 'Password is required',

            'password.min'
                => 'Password minimum 8 characters',

            'password.confirmed'
                => 'Password confirmation does not match',
        ];
    }
}