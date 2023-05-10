<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\User;
use App\Models\Loan;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RepaymentTest extends TestCase
{
    use RefreshDatabase;

    public function test_command_check_overdue_repayments(): void
    {
        $user = User::factory()->create();
        /** @var Loan $loan */
        $loan = Loan::factory()->create([
            'user_id' => $user->id,
            'amount' => 3000,
            'term' => 3,
        ]);
        $yesterdayDate = Carbon::now()->subDay()->toDateString();
        $loan->repayments()->update([
            'due_date' => $yesterdayDate,
        ]);

        $this->artisan('repayments:check-overdue')
            ->expectsOutputToContain(3)
            ->assertSuccessful()
        ;
    }
}
