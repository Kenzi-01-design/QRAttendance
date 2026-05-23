<?php

namespace App\Http\Requests\Officer;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SubjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('subject')?->id;

        return [
            'code' => ['required', 'string', 'max:50', Rule::unique('subjects', 'code')->ignore($id)],
            'title' => ['required', 'string', 'max:255'],
        ];
    }
}
