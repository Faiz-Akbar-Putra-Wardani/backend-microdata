<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdatedAboutUsRequest extends FormRequest
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
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'vision' => 'sometimes|string',
            'mission' => 'sometimes|string',
        ];
    }

    public function messages(): array
    {
        return [
            'title.sometimes' => 'The title field is optional but must be a string if provided.',
            'description.sometimes' => 'The description field is optional but must be a string if provided.',
            'vision.sometimes' => 'The vision field is optional but must be a string if provided.',
            'mission.sometimes' => 'The mission field is optional but must be a string if provided.',
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
