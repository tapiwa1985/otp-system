<?php

namespace Tests\Unit;

use App\Models\OneTimePinModel;
use App\Models\User;
use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use App\Repositories\OTPRepository;

class OTPUnitTest extends TestCase
{
    use DatabaseMigrations;

    public function test_save_generated_pin()
    {
        $faker = Factory::create();
        $otpRepository = new OTPRepository();
        $user = User::factory()->create();
        $oneTimePin = $faker->numerify('######');
        $expiryDate =  Carbon::now()->addSeconds(30);
        $savedPin = $otpRepository->create($user->id, $oneTimePin, $expiryDate);
        $this->assertDatabaseHas('one_time_personal_identification_numbers',[
            'user_id' => $user->id,
            'expires_at' => $expiryDate,
            'otp' => $oneTimePin
        ]);
        $this->assertInstanceOf(OneTimePinModel::class, $savedPin);
    }

    public function test_update_otp_expiry_date_time()
    {
        $otp = OneTimePinModel::factory()->create();
        $otpRepository = new OTPRepository();
        $newExpiryDateTime = Carbon::parse($otp->expires_at)->addSeconds(config('app.otp_ttl'));
        $refreshedOtp = $otpRepository->refreshOtp($otp->id, $newExpiryDateTime);
        $this->assertEquals($refreshedOtp->expires_at, $newExpiryDateTime);
    }

    public function test_get_valid_otp_for_user()
    {
        $user = User::factory()->create();
        OneTimePinModel::factory()->create(['user_id' => $user->id]);
        sleep(1);
        $latestPin = OneTimePinModel::factory()->create(['user_id' => $user->id]);
        $otpRepository = new OTPRepository();
        $validOtp = $otpRepository->getValidOtpForUser($user->id);
        $this->assertEquals($latestPin->otp, $validOtp->otp);
    }

    public function test_get_otp_generated_within_no_reset_window()
    {
        $user = User::factory()->create();
        $testOtp = OneTimePinModel::factory()->create([
            'created_at' => Carbon::now()->subSeconds(config('app.otp_no_reset_time') - 10),
            'user_id' => $user->id
        ]);
        OneTimePinModel::factory()->create([
            'created_at' => Carbon::now()->subHour()
        ]);
        $otpRepository = new OTPRepository();
        $validOtp = $otpRepository->getGeneratedOtpWithinNoResetWindow($testOtp->user_id);
        $this->assertEquals($testOtp->otp, $validOtp->otp);
    }

    public function test_is_otp_generated_in_last_twenty_four_hours()
    {
        $testOtp = OneTimePinModel::factory()->create([
            'created_at' => Carbon::now()->subHours(10),
        ]);
        $otpRepository = new OTPRepository();
        $isOtpGeneratedInTheLastDay = $otpRepository->isOtpUsedInLastDay($testOtp->user_id, $testOtp->otp);
        $this->assertTrue($isOtpGeneratedInTheLastDay);
    }
}
