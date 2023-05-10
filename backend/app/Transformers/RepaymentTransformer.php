<?php

namespace App\Transformers;

use App\Models\Repayment;

class RepaymentTransformer extends BaseTransformer
{
    public function transform(Repayment $repayment): array
    {
        return [
            'id' => data_get($repayment, 'id'),
            'amount' => data_get($repayment, 'amount'),
            'status' => data_get($repayment, 'status'),
            'due_date' => data_get($repayment, 'due_date'),
        ];
    }
}
