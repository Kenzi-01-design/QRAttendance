<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class ClaimRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'full_name' => ['required', 'string', 'max:255'],
            'section' => ['required', 'string', 'max:255'],
            'student_no' => ['required', 'string', 'max:255', 'unique:students,student_no', 'unique:users,username'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ];
    }
}
