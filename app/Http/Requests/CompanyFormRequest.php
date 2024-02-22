<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompanyFormRequest extends FormRequest
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
            'address' => ['required', 'string', 'max:250'],
            'phone' => ['required', 'string', 'max:40'],
            'slogan' => ['required', 'string', 'max:250'],
            'description' => ['required', 'string', 'max:8000'],
            'logo' => ['file', 'max:1024', 'required'],
            'banner' => ['file', 'max:2048', 'required'],
        ];
    }
}
