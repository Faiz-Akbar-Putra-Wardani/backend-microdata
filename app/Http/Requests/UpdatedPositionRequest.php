<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdatedPositionRequest extends FormRequest
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
            'title' => 'sometimes|string',
            'position' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
        ];
    }

    public function messages()
    {
        return [
            'title.string' => 'The title must be a string.',
            'position.string' => 'The position must be a string.',
            'position.max' => 'The position may not be greater than 255 characters.',
            'description.string' => 'The description must be a string.',
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
