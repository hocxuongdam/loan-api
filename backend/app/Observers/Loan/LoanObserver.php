<?php

namespace App\Observers\Loan;

use App\Models\Loan;
use App\Models\User;
use App\Jobs\RejectLoanJob;
use App\Models\Repayment;
use App\Jobs\ApproveLoanJob;

class LoanObserver
{
    public function created(Loan $loan): void
    {
        $this->shouldApprove($loan);
    }

    private function shouldApprove(Loan $loan): void
    {
        /** @var User $user */
        $user = $loan->borrower;
        $overdueRepaymentCount = $user->repayments()->where('status', Repayment::STATUS_OVERDUE)->count();

        if ($overdueRepaymentCount) {
            dispatch(new RejectLoanJob($loan))->onQueue('default');
        } else {
            dispatch(new ApproveLoanJob($loan))->onQueue('default');
        }
    }
}
