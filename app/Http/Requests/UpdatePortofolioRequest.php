<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdatePortofolioRequest extends FormRequest
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
            'name_project' => 'sometimes|string|max:255',
            'image_portofolio' => 'sometimes|image|mimes:jpeg,png,jpg|max:2048',
            'company_name' => 'sometimes|string|max:255',
            'category_id' => 'sometimes|exists:portofolio_categories,id',
        ];
    }

    public function messages(): array
    {
        return [
            'title.string' => 'The title must be a string.',
            'name_project.string' => 'The project name must be a string.',

            'image_portofolio.image' => 'The portfolio image must be an image.',
            'image_portofolio.mimes' => 'The portfolio image must be a file of type: jpeg, png, jpg.',
            'image_portofolio.max' => 'The portfolio image size may not be greater than 2MB.',

            'company_name.string' => 'The company name must be a string.',

            'category_id.exists' => 'The selected category is invalid.',
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
