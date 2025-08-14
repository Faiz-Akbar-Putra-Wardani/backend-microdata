<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StorePortofolioRequest extends FormRequest
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
            'name_project' => 'required|string|max:255',
            'image_portofolio' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'company_name' => 'required|string|max:255',
            'category_id' => 'required|exists:portofolio_categories,id',

        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'The title field is required.',
            'name_project.required' => 'The project name field is required.',

            'image_portofolio.required' => 'The portfolio image field is required.',
            'image_portofolio.image' => 'The portfolio image must be an image.',
            'image_portofolio.mimes' => 'The portfolio image must be a file of type: jpeg, png, jpg.',
            'image_portofolio.max' => 'The portfolio image size may not be greater than 2MB.',

            'company_name.required' => 'The company name field is required.',

            'category_id.required' => 'The category field is required.',
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