<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Loan;
use App\Models\Repayment;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoanTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        $this->user = User::factory()->create();
    }

    /**
     * Customer request a new loan and get approved
     * @return void
     */
    public function test_create_loan_success(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->withHeaders([
                'Accept' => 'application/json',
            ])
            ->post('/api/v1/loans', [
                'amount' => 40000,
                'term' => 3,
            ]);

        $response
            ->assertStatus(201)
            ->assertJson([
                'data' => [
                    'amount' => 40000.00,
                    'term' => 3,
                    'term_unit' => 'Week',
                    'status' => 'Approved',
                ],
            ]);
    }

    /**
     * Customer has at least 1 overdue repayment, new loan will be rejected
     * @return void
     */
    public function test_create_loan_but_rejected(): void
    {
        /** @var Loan $loan */
        $loan = Loan::factory()->create([
            'user_id' => $this->user->id,
        ]);

        /** @var Repayment $repayment */
        $repayment = $loan->repayments()->first();
        $repayment->overdue();

        $response = $this
            ->actingAs($this->user)
            ->withHeaders([
                'Accept' => 'application/json',
            ])
            ->post('/api/v1/loans', [
                'amount' => 40000,
                'term' => 3,
            ]);

        $response
            ->assertStatus(201)
            ->assertJson([
                'data' => [
                    'amount' => 40000.00,
                    'term' => 3,
                    'term_unit' => 'Week',
                    'status' => 'Rejected',
                ],
            ]);
    }

    public function test_create_loan_invalid_parameter(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->withHeaders([
                'Accept' => 'application/json',
            ])
            ->post('/api/v1/loans', [
                'amount' => 0,
                'term' => 0,
            ]);

        $response
            ->assertStatus(422)
            ->assertJson(fn(AssertableJson $json) => $json
                ->hasAll(['errors.amount', 'errors.term'])
                ->etc()
            );
    }

    public function test_list_loan_success(): void
    {
        Loan::factory()->count(5)->create([
            'user_id' => $this->user->id
        ]);

        $response = $this
            ->actingAs($this->user)
            ->withHeaders([
                'Accept' => 'application/json',
            ])
            ->get('/api/v1/loans?status=approved');

        $response
            ->assertStatus(200)
            ->assertJson(fn(AssertableJson $json) => $json
                ->has('data', 5)
                ->where('success', true)
                ->etc()
            );
    }

    public function test_show_loan_success(): void
    {
        $loan = Loan::factory()->create([
            'user_id' => $this->user->id
        ]);
        $loan->refresh();

        $response = $this
            ->actingAs($this->user)
            ->withHeaders([
                'Accept' => 'application/json',
            ])
            ->get('/api/v1/loans/'.$loan->id);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                   'id',
                   'amount',
                   'term',
                   'term_unit',
                   'status',
                    'repayments' => [
                        'data' => [
                            '*' => [
                                'id',
                                'amount',
                                'status',
                                'due_date',
                            ]
                        ],
                    ],
                ],
                'success',
                'message',
            ]);
    }

}
