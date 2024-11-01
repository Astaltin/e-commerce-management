<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
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
        /// TODO: Gotta revise this later on if I have the time...
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0|max:9999.99',
            // 'category_id' => 'required|integer|exists:categories,id', # Updated from 'category' to 'category_id'
            'category' => 'required|string|max:64',
            'stock' => 'required|integer|min:0',
        ];
    }
}
