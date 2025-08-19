<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreServiceRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'icon' => 'required|image|mimes:jpeg,png,jpg|max:5120',
            'description' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'The name field is required.',
            'icon.required' => 'The icon field is required.',
            'icon.image' => 'The icon must be an image.',
            'icon.mimes' => 'The icon must be a file of type: jpeg, png, jpg.',
            'icon.max' => 'The icon size may not be greater than 5MB.',
            'description.required' => 'The description field is required.',
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
