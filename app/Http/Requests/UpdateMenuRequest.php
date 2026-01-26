<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMenuRequest extends FormRequest
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
            'title' => 'required|string|max:255', // Ensure slug is unique except for the current menu
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|string',  // Assuming you just need a path
            'category_id' => 'required|exists:categories,id',  // Validate the category exists
        ];
    }
}
