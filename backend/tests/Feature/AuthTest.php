<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_success(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->withHeaders([
                'Accept' => 'application/json',
            ])
            ->post('/api/v1/login', [
                'email' => $user->email,
                'password' => 'password',
            ]);

        $this->assertAuthenticated();

        $response
            ->assertStatus(200)
            ->assertJson(fn(AssertableJson $json) => $json
                ->has('data.token')
                ->etc()
            );
    }

    public function test_login_unauthenticated(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->withHeaders([
                'Accept' => 'application/json',
            ])
            ->post('/api/v1/login', [
                'email' => $user->email,
                'password' => 'invalid-password',
            ]);

        $this->assertGuest();

        $response
            ->assertStatus(401)
            ->assertJson(fn(AssertableJson $json) => $json
                ->where('success', false)
                ->where('message', 'Unauthorized')
            );
    }
}
