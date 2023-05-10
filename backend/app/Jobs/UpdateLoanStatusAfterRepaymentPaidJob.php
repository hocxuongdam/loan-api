<?php

namespace App\Jobs;

use App\Models\Loan;
use App\Models\Repayment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateLoanStatusAfterRepaymentPaidJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(protected Repayment $repayment)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        /** @var Loan $loan */
        $loan = $this->repayment->loan;
        if ($loan->unpaidRepayments()->count() == 0) {
            $loan->paid();
        }
    }
}
