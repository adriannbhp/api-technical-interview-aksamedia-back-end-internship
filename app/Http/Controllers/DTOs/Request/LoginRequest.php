<?php

namespace App\Http\Controllers\DTOs\Request;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function rules()
    {
        return [
            'username' => 'required|string|max:255',
            'password' => 'required|string|min:8',
        ];
    }

    public function authorize()
    {
        return true; // Allow all users to make this request
    }
}
