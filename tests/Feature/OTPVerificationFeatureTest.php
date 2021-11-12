<?php

namespace Tests\Feature;

use App\Models\OneTimePinModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OTPVerificationFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_otp_verification()
    {
        $otpObj = OneTimePinModel::factory()->create();
        $this->json('POST', '/api/verify', [
            'email' => $otpObj->user->email,
            'otp' => $otpObj->otp
        ])->assertStatus(200)
            ->assertJson([
                'message' => 'Verification successful'
            ]);
    }
}
