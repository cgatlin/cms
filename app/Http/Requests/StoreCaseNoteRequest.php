<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Override;

class StoreCaseNoteRequest extends FormRequest
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
            'note' => ['required', 'string', 'min:3', 'max:255'],
        ];
    }

    #[Override]
    public function messages(): array
    {
        return [
            'note.required' => 'The first name is required.',
            'note.string' => 'The first name must be a string.',
        ];
    }
}
