<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Loan;
use App\Models\Repayment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PayTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        $this->user = User::factory()->create();
        $this->loan = Loan::factory()->create([
            'user_id' => $this->user->id,
        ]);
    }

    public function test_pay_one_repayment_exact_amount_success(): void
    {
        $repayment = $this->loan->repayments()->first();

        $response = $this
            ->actingAs($this->user)
            ->withHeaders([
                'Accept' => 'application/json',
            ])
            ->post("/api/v1/loans/".$this->loan->id."/pay", [
                'amount' => $repayment->amount,
                'pay_future_repayments' => false,
            ]);

        $response
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Success',
                'data' => [
                    "amount" => $repayment->amount,
                    "change_amount" => "0.00",
                ]
            ]);

        $repayment = $repayment->fresh();
        $this->assertEquals(Repayment::STATUS_PAID, $repayment->status);
        $this->assertNotNull($repayment->payment_id);
    }

    public function test_pay_one_repayment_with_greater_amount_success(): void
    {
        $repayment = $this->loan->repayments()->first();

        $response = $this
            ->actingAs($this->user)
            ->withHeaders([
                'Accept' => 'application/json',
            ])
            ->post("/api/v1/loans/".$this->loan->id."/pay", [
                'amount' => $repayment->amount + 1,
                'pay_future_repayments' => true,
            ]);

        $response
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Success',
                'data' => [
                    "amount" => $repayment->amount + 1,
                    "change_amount" => "1.00",
                ]
            ]);

        $repayment = $repayment->fresh();
        $this->assertEquals(Repayment::STATUS_PAID, $repayment->status);
        $this->assertNotNull($repayment->payment_id);
    }

    public function test_pay_all_repayments_of_a_loan_success(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->withHeaders([
                'Accept' => 'application/json',
            ])
            ->post("/api/v1/loans/".$this->loan->id."/pay", [
                'amount' => $this->loan->amount + 2000,
                'pay_future_repayments' => true,
            ]);

        $response
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Success',
                'data' => [
                    "amount" => $this->loan->amount + 2000,
                    "change_amount" => "2000.00",
                ]
            ]);

        $loan = $this->loan->fresh();
        $this->assertEquals(Loan::STATUS_PAID, $loan->status);
        $this->assertEmpty($loan->unpaidRepayments);
    }

    public function test_pay_with_insufficient_amount(): void
    {
        $repayment = $this->loan->repayments()->first();
        $amount = $repayment->amount - 1;

        $response = $this
            ->actingAs($this->user)
            ->withHeaders([
                'Accept' => 'application/json',
            ])
            ->post("/api/v1/loans/".$this->loan->id."/pay", [
                'amount' => $amount,
                'pay_future_repayments' => false,
            ]);

        $response
            ->assertStatus(400)
            ->assertJson([
                'success' => false,
                'message' => "Repayment amount must be at least $repayment->amount",
            ]);
    }

    public function test_pay_failed_with_not_approved_loan(): void
    {
        $this->loan->update([
            'status' => Repayment::STATUS_PAID,
        ]);

        $response = $this
            ->actingAs($this->user)
            ->withHeaders([
                'Accept' => 'application/json',
            ])
            ->post("/api/v1/loans/".$this->loan->id."/pay", [
                'amount' => 900000,
                'pay_future_repayments' => false,
            ]);

        $response
            ->assertStatus(400)
            ->assertJson([
                'success' => false,
                'message' => "This loan is not payable",
            ]);
    }
}
