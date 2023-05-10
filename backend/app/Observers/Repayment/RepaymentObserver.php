<?php

namespace App\Observers\Repayment;

use App\Models\Repayment;
use App\Jobs\UpdateLoanStatusAfterRepaymentPaidJob;

class RepaymentObserver
{
    public function updated(Repayment $repayment)
    {
        if ($repayment->wasChanged('status')) {
            /** If repayment status is changed to PAID */
            if ($repayment->isPaid()) {
                dispatch(new UpdateLoanStatusAfterRepaymentPaidJob($repayment));
            }
        }
    }
}
