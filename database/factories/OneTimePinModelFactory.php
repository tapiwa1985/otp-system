<?php

namespace Database\Factories;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class OneTimePinModelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory()->create()->id,
            'otp' => $this->faker->numerify('######'),
            'expires_at' => Carbon::now()->addSeconds(30),
            'is_used' => false
        ];
    }
}
