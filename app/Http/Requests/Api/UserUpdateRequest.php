<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->id === $this->route('id');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => ['sometimes', 'string', 'min:2', 'max:255'],
            'last_name' => ['sometimes', 'string', 'min:2', 'max:255'],
            'profile_image' => ['sometimes', 'string', 'max:255'],
            'profile_info' => ['sometimes', 'string', 'min:2', 'max:255'],
            'status' => ['sometimes', 'string', 'in:active,inactive,banned'],
            'user_type' => ['sometimes', 'string', 'in:normal,writer'],
        ];
    }
}
