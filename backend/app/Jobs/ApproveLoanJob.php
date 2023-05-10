<?php

namespace App\Jobs;

use Carbon\Carbon;
use App\Models\Loan;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ApproveLoanJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(protected Loan $loan)
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->generateRepayments($this->loan);
        $this->loan->approve();
    }

    private function generateRepayments(Loan $loan)
    {
        $currentTime = Carbon::now();
        $repaymentAmount = round($loan->amount / $loan->term, 2);

        for ($termIndex = 1; $termIndex <= $loan->term; $termIndex++) {
            if ($termIndex == $loan->term) {
                $amount = $loan->amount - $repaymentAmount * ($termIndex - 1);
            } else {
                $amount = $repaymentAmount;
            }
            $loan->repayments()->create([
                'amount' => $amount,
                'user_id' => $loan->user_id,
                'term_index' => $termIndex,
                'due_date' => $currentTime->add('days', 7)->toDateString(),
            ]);
        }
    }
}
