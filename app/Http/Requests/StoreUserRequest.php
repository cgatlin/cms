<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use Override;

class StoreUserRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'string', 'max:255', Password::defaults()],
        ];
    }

    #[Override]
    public function messages(): array
    {
        return [
            'name.required' => 'The first name is required.',
            'name.string' => 'The first name must be a string.',
            'name.max' => 'The first name may not be greater than 255 characters.',
            'email.required' => 'Email is required.',
            'email.email' => 'Must be an email',
            'email.max' => 'The email may not be greater than 255 characters.',
        ];
    }
}
