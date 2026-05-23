<?php

namespace App\Http\Requests\Officer;

use Illuminate\Foundation\Http\FormRequest;

class SchoolClassRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'subject_id' => ['required', 'exists:subjects,id'],
            'section' => ['required', 'string', 'max:255'],
            'school_year' => ['required', 'string', 'max:255'],
            'semester' => ['required', 'string', 'max:255'],
        ];
    }
}
