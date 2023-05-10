<?php

namespace App\Providers;

use App\Models\Loan;
use App\Models\Repayment;
use App\Services\Auth\AuthService;
use App\Services\Loan\LoanService;
use App\Observers\Loan\LoanObserver;
use Illuminate\Support\ServiceProvider;
use App\Services\Payment\PaymentService;
use App\Services\Auth\AuthServiceInterface;
use App\Services\Loan\LoanServiceInterface;
use App\Observers\Repayment\RepaymentObserver;
use App\Services\Payment\PaymentServiceInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(AuthServiceInterface::class, AuthService::class);
        $this->app->bind(LoanServiceInterface::class, LoanService::class);
        $this->app->bind(PaymentServiceInterface::class, PaymentService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Loan::observe(LoanObserver::class);
        Repayment::observe(RepaymentObserver::class);
    }
}
