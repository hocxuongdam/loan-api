<?php

namespace App\Jobs;

use App\Models\Loan;
use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Services\Payment\PaymentServiceInterface;

class PayLoanJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(protected Payment $payment)
    {
    }

    /**
     * Execute the job.
     */
    public function handle(PaymentServiceInterface $paymentService): void
    {
        /** @var Loan $loan */
        $loan = $this->payment->loan;
        $repayments = $loan->unpaidRepayments;
        if ($this->payment->shouldPayFutureRepayments()) {
            DB::transaction(function () use ($paymentService, $repayments) {
                $changeAmount = $paymentService->payMultipleRepayments($this->payment, $repayments);
                $paymentService->updatePaymentChangeAmount($this->payment, $changeAmount);
            });
        } else {
            $repayment = $repayments->first();
            DB::transaction(function () use ($paymentService, $repayment) {
                $changeAmount = $paymentService->pay($this->payment, $repayment);
                $paymentService->updatePaymentChangeAmount($this->payment, $changeAmount);
            });
        }
    }
}
