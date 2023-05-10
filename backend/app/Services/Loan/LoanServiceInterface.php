<?php

namespace App\Services\Loan;

use App\Models\User;
use App\Models\Loan;
use App\Exceptions\LoanNotPayableException;
use App\Exceptions\RepaymentAmountNotEnoughException;

interface LoanServiceInterface
{
    public function list(User $user, array $filters);

    public function store(User $user, array $data);

    /**
     * @throws LoanNotPayableException|RepaymentAmountNotEnoughException
     */
    public function pay(Loan $loan, array $data);
}
