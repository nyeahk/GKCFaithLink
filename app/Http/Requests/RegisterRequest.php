<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Allow anyone to register
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'username' => [
                'required', 
                'string', 
                'min:3', 
                'max:20', 
                'unique:users', 
                'regex:/^[a-zA-Z0-9][a-zA-Z0-9._]{1,18}[a-zA-Z0-9]$/',
            ],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'position' => ['required', 'string', 'in:Pastor,Staff,Treasurer,Member'],
        ];
    }
    
    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'username.required' => 'Username is required.',
            'username.unique' => 'This username is already taken.',
            'username.regex' => 'Username must be 3-20 characters, start and end with a letter or number, and can only contain letters, numbers, underscores, and periods.',
            'username.min' => 'Username must be at least 3 characters long.',
            'username.max' => 'Username cannot exceed 20 characters.',
            'email.required' => 'Your email address is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email address is already registered.',
            'password.required' => 'A password is required.',
            'password.min' => 'Your password must be at least 8 characters.',
            'password.confirmed' => 'Your password confirmation does not match.',
            'position.required' => 'Please select your church position.',
            'position.in' => 'Please select a valid church position.',
        ];
    }
}
