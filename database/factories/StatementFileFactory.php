<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use \App\Models\User;
use \App\Models\Account;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\StatementFile>
 */
class StatementFileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            //
            'original_path' => $this->faker->url(),
            'cleaned_path' => $this->faker->url(),
            'cleaned' => $this->faker->boolean(),
            'account_id' => Account::factory(),
            'user_id' => User::factory()
        ];
    }
}
