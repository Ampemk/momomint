<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use \App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Account>
 */
class AccountFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'account_name' => $this->faker->name(),
            'account_provider' => 'MTN',
            'account_number' => $this->faker->phoneNumber(),
            'user_id' => User::factory()
        ];
    }
}
