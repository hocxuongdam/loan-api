<?php

namespace App\Services\Loan;

use App\Models\User;
use App\Models\Loan;
use App\Models\Payment;
use App\Jobs\PayLoanJob;
use App\Services\AbstractService;
use App\Exceptions\LoanNotPayableException;
use Illuminate\Database\Eloquent\Collection;
use App\Exceptions\RepaymentAmountNotEnoughException;

class LoanService extends AbstractService implements LoanServiceInterface
{
    public function list(User $user, array $filters): Collection
    {
        $loans = $user->loans();

        if (isset($filters['status'])) {
            $loans->where('status', $filters['status']);
        }

        return $loans->get();
    }

    public function store(User $user, array $data)
    {
        return $user->loans()->create($data);
    }

    /**
     * @param Loan $loan
     * @param array $data
     * @return Payment
     * @throws LoanNotPayableException
     * @throws RepaymentAmountNotEnoughException
     */
    public function pay(Loan $loan, array $data): Payment
    {
        $repayment = $loan->unpaidRepayments()->first();
        if (!$loan->isApproved() || empty($repayment)) {
            throw new LoanNotPayableException('This loan is not payable');
        }

        if ($data['amount'] < $repayment->amount) {
            throw new RepaymentAmountNotEnoughException("Repayment amount must be at least $repayment->amount");
        }

        /** @var Payment $payment */
        $payment = $loan->payments()->create([
            'user_id' => $loan->user_id,
            'amount' => $data['amount'],
            'pay_future_repayments' => data_get($data, 'pay_future_repayments', false),
        ]);

        PayLoanJob::dispatchSync($payment->fresh());

        return $payment;
    }
}
