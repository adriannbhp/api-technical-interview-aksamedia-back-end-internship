<?php

namespace App\Http\Controllers\DTOs\Request;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'nullable|string|max:255',
            'division_id' => 'nullable|uuid',
        ];
    }

    public function authorize()
    {
        return true;
    }
}
