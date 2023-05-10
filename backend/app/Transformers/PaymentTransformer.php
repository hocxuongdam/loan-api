<?php

namespace App\Transformers;

use App\Models\Payment;

class PaymentTransformer extends BaseTransformer
{
    public function transform(Payment $payment): array
    {
        return [
            'amount' => data_get($payment, 'amount'),
            'change_amount' => data_get($payment, 'change_amount'),
        ];
    }
}
