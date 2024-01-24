<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InternFormRequest extends FormRequest
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
            'title'=> 'required|max:150',
            'position'=> 'required|max:255',
            'description'=> 'required|max:5000',
            'roles'=> 'required|max:800',
            'address'=> 'required|max:500',
            'type'=> 'required',
            'last_date'=> 'required',
        ];
    }
}