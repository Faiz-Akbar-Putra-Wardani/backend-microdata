<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdatedServiceRequest extends FormRequest
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
            'name' => 'sometimes|string|max:255',
            'icon' => 'sometimes|image|mimes:jpeg,png,jpg',
            'description' => 'sometimes|string',
        ];
    }

    public function messages(): array
    {
        return [
            'name.sometimes' => 'The name must be a string.',
            'icon.sometimes' => 'The icon must be an image if provided.',
            'icon.image' => 'The icon must be an image.',
            'icon.mimes' => 'The icon must be a file of type: jpeg, png, jpg.',
            'description.sometimes' => 'The description must be a string.',
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
