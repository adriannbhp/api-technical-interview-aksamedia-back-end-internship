<?php

namespace App\Http\Controllers\DTOs\Request;

use Illuminate\Foundation\Http\FormRequest;

class DivisionRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'nullable|string|max:255',
        ];
    }

    public function authorize()
    {
        return true;
    }
}
