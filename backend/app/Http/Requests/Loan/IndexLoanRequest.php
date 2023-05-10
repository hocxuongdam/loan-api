<?php

namespace App\Http\Requests\Loan;

use App\Models\Loan;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class IndexLoanRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'status' => Rule::in(Loan::STATUSES),
        ];
    }

    /**
     * Generate suggested parameters for knuckleswtf/scribe
     * @codeCoverageIgnore
     * @return array[]
     */
    public function queryParameters(): array
    {
        return [
            'status' => [
                'description' => 'Status',
                'example' => Loan::STATUS_APPROVED,
            ],
        ];
    }
}
