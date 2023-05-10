<?php

namespace App\Services\Payment;

use App\Models\Payment;
use App\Models\Repayment;

interface PaymentServiceInterface
{
    public function pay(Payment $payment, Repayment $repayment);

    public function payMultipleRepayments(Payment $payment, $repayments);

    public function updatePaymentChangeAmount(Payment $payment, float $amount);
}
