<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Override;

class StoreTaskRequest extends FormRequest
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
            'description' => ['nullable', 'string', 'max:255'],
            // 'assigned_to' => ['nullable', exists:users,id'],
            'due_date' => ['nullable', 'date'],
        ];
    }

    #[Override]
    public function messages(): array
    {
        return [
            'title.required' => 'The first title is required.',
            'title.string' => 'The first title must be a string.',
            'title.max' => 'The first title may not be greater than 255 characters.',
            'description.max' => 'The description may not be greater than 255 characters.',
        ];
    }
}
