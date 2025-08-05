<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreMicrodataOptionsRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'name_title' => 'required|string|max:255',
            'description' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'The title field is required.',
            'name_title.required' => 'The name title field is required.',
            'description.required' => 'The description field is required.',
            'title.string' => 'The title must be a string.',
            'name_title.string' => 'The name title must be a string.',
            'description.string' => 'The description must be a string.',
            'title.max' => 'The title may not be greater than 255 characters.',
            'name_title.max' => 'The name title may not be greater than 255 characters.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422)
        );
    }
}
