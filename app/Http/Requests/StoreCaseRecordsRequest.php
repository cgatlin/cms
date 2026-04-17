<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Override;

class StoreCaseRecordsRequest extends FormRequest
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
     * @return array<string, Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'category_id' => [
                'required',
                Rule::exists('categories', 'id'),
            ],
            'client_id' => [
                'required',
                Rule::exists('clients', 'id'),
            ],
            'assigned_to' => [
                'nullable',
                Rule::exists('users', 'id')->where(function ($query) {
                    $query->where('role', 'case_worker');
                }),
            ],
        ];
    }

    #[Override]
    public function messages(): array
    {
        return [
            'title.required' => 'Title is required.',
            'title.string' => 'Title must be a string.',
            'title.max' => 'Title may not be greater than 255 characters.',
            'description.required' => 'Description is required.',
            'description.string' => 'Description must be a string.',
            'description.max' => 'Description may not be greater than 255 characters.',

            'category_id.required' => 'Category is required.',
            'category_id.exists' => 'Error finding Category',

            'client_id.required' => 'Client is required.',
            'client_id.exists' => 'Error finding Client',

            'assigned_to.exists' => 'Error finding Case Worker',
        ];
    }
}
