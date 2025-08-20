<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreServiceLandingPageRequest extends FormRequest
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
            'name_service' => 'required|string|max:255',
            'description' => 'required|string',
        ];
    }

    public function messages()
    {
        return [
            'name_service.string' => 'The name_service must be a string.',
            'name_service.max' => 'The name_service may not be greater than 255 characters.',
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
