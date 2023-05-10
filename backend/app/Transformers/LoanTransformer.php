<?php

namespace App\Transformers;

use App\Models\Loan;
use League\Fractal\Resource\Collection;

class LoanTransformer extends BaseTransformer
{
    protected array $availableIncludes = [
        'repayments',
    ];

    /**
     * @param Loan $loan
     * @return array
     */
    public function transform(Loan $loan): array
    {
        return [
            'id' => data_get($loan, 'id'),
            'amount' => data_get($loan, 'amount'),
            'term' => data_get($loan, 'term'),
            'term_unit' => ucfirst(data_get($loan, 'term_unit')),
            'status' => ucfirst(data_get($loan, 'status')),
        ];
    }

    /**
     * @param Loan $loan
     * @return Collection
     */
    public function includeRepayments(Loan $loan): Collection
    {
        $repayments = $loan->repayments;

        return $this->collection($repayments, new RepaymentTransformer());
    }
}
