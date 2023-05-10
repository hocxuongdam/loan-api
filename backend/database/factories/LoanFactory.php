<?php

namespace Database\Factories;

use App\Models\Loan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Loan>
 */
class LoanFactory extends Factory
{
    protected $model = Loan::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => $this->faker->numberBetween(1, 6),
            'amount' => $this->faker->numberBetween(3, 300) * 1000,
            'term' => $this->faker->numberBetween(3, 12),
        ];
    }

    public function loansOfTestUser(): LoanFactory
    {
        $user = User::query()->where('email', 'duymai@gmail.com')->first();

        return $this->state(function () use ($user) {
            return [
                'user_id' => $user->id,
            ];
        });
    }
}
