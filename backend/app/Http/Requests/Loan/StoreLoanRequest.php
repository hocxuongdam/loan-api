<?php

namespace App\Http\Requests\Loan;

use Illuminate\Foundation\Http\FormRequest;

class StoreLoanRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'amount' => 'required|integer|min:100',
            'term' => 'required|integer|min:1',
        ];
    }

    /**
     * Generate suggested parameters for knuckleswtf/scribe
     * @codeCoverageIgnore
     * @return array[]
     */
    public function bodyParameters(): array
    {
        return [
            'amount' => [
                'description' => 'Amount',
                'example' => 30000,
            ],
            'term' => [
                'description' => 'Loan term',
                'example' => 3,
            ],
        ];
    }
}
