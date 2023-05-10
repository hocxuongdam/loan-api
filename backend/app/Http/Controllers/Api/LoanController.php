<?php

namespace App\Http\Controllers\Api;

use App\Models\Loan;
use Illuminate\Http\JsonResponse;
use App\Transformers\LoanTransformer;
use App\Transformers\PaymentTransformer;
use App\Http\Requests\Loan\PayLoanRequest;
use App\Services\Loan\LoanServiceInterface;
use App\Http\Requests\Loan\ShowLoanRequest;
use App\Exceptions\LoanNotPayableException;
use App\Http\Requests\Loan\IndexLoanRequest;
use App\Http\Requests\Loan\StoreLoanRequest;
use App\Exceptions\RepaymentAmountNotEnoughException;

class LoanController extends ApiController
{
    public function __construct(protected LoanServiceInterface $service)
    {
    }

    /**
     * List
     *
     * List all current loans of the authenticated user
     *
     * @group Loan
     * @response 422 {"message": "The selected status is invalid.","errors": {"status": ["The selected status is invalid."]}}
     *
     * @param IndexLoanRequest $request
     * @return JsonResponse
     */
    public function index(IndexLoanRequest $request): JsonResponse
    {
        $loans = $this->service->list($request->user(), $request->input());

        return $this->respondSuccess(
            fractal($loans, new LoanTransformer())->toArray(),
        );
    }

    /**
     * Show
     *
     * @group Loan
     *
     * @param Loan $loan
     * @param ShowLoanRequest $request
     * @return JsonResponse
     */
    public function show(Loan $loan, ShowLoanRequest $request): JsonResponse
    {
        $loan->load('repayments');
        return $this->respondSuccess(
            fractal($loan, new LoanTransformer())->parseIncludes('repayments')->toArray(),
        );
    }

    /**
     * Store
     *
     * Create a new loan for authenticated user.
     *
     * @group Loan
     *
     * @param StoreLoanRequest $request
     * @return JsonResponse
     */
    public function store(StoreLoanRequest $request): JsonResponse
    {
        $loan = $this->service->store($request->user(), $request->input());

        return $this->respondCreated(
            fractal($loan->fresh(), new LoanTransformer())->toArray(),
        );
    }

    /**
     * Pay
     *
     * Make a payment for an approved loan
     *
     * @group Loan
     *
     * @param Loan $loan
     * @param PayLoanRequest $request
     * @return JsonResponse
     * @throws LoanNotPayableException
     * @throws RepaymentAmountNotEnoughException
     */
    public function pay(Loan $loan, PayLoanRequest $request): JsonResponse
    {
        $payment = $this->service->pay($loan, $request->input());

        return $this->respondSuccess(
            fractal($payment->fresh(), new PaymentTransformer())->toArray(),
        );
    }
}
