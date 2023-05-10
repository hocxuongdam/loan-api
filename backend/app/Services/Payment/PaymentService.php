<?php

namespace App\Services\Payment;

use App\Models\Payment;
use App\Models\Repayment;
use App\Services\AbstractService;

class PaymentService extends AbstractService implements PaymentServiceInterface
{
    public function pay(Payment $payment, Repayment $repayment)
    {
        $repayment->update([
            'payment_id' => $payment->id,
            'status' => Repayment::STATUS_PAID,
        ]);

        return $payment->amount - $repayment->amount;
    }

    public function payMultipleRepayments(Payment $payment, $repayments)
    {
        $changeAmount = $payment->amount;
        /** @var Repayment $repayment */
        foreach ($repayments as $repayment) {
            if ($changeAmount >= $repayment->amount) {
                $this->pay($payment, $repayment);
                $changeAmount -= $repayment->amount;
            } else {
                break;
            }
        }

        return $changeAmount;
    }

    public function updatePaymentChangeAmount(Payment $payment, float $amount)
    {
        $payment->update([
            'change_amount' => $amount,
        ]);
    }
}
