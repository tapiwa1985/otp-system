<?php

namespace Tests\Feature;

use App\Mail\OTPMailer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class OTPRequestFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_request_one_time_pin()
    {
        Mail::fake();
        $user = User::factory()->create();
        $this->json('POST', '/api/otp', [
            'email' => $user->email
        ])
            ->assertStatus(201)
            ->assertJson([
                'message' => 'OTP sent to email'
            ]);
        Mail::assertSent(OTPMailer::class);
    }
}
