<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'email' => 'required|email',
            'password' => 'required',
        ];
    }

    /**
     * Generate suggested parameters for knuckleswtf/scribe"
     * @codeCoverageIgnore
     * @return array[]
     */
    public function bodyParameters(): array
    {
        return [
            'email' => [
                'description' => 'Email',
                'example' => 'duymai@gmail.com',
            ],
            'password' => [
                'description' => 'Password',
                'example' => 'password',
            ],
        ];
    }
}
