<?php

namespace App\Http\Requests\Loan;

use Illuminate\Foundation\Http\FormRequest;

class PayLoanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->loan->user_id == $this->user()->id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'amount' => 'required|numeric|min:1',
            'pay_future_repayments' => 'bool',
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
                'example' => 100000,
            ],
            'pay_future_repayments' => [
                'description' => 'Should whether continue paying for future repayments or not',
                'example' => false,
            ]
        ];
    }
}
