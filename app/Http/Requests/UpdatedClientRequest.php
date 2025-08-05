<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdatedClientRequest extends FormRequest
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
            'description_client' => 'sometimes|string',
            'name_client' => 'sometimes|string|max:255',
            'position_client' => 'sometimes|string|max:255',
        ];
        
    }

    public function messages(): array
    {
        return [
            'description_client.sometimes' => 'The description of the client is optional but must be a string if provided.',
            'name_client.sometimes' => 'The name of the client is optional but must be a string if provided.',
            'name_client.max' => 'The name of the client may not be greater than 255 characters.',
            'position_client.sometimes' => 'The position of the client is optional but must be a string if provided.',
            'position_client.max' => 'The position of the client may not be greater than 255 characters.',
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
