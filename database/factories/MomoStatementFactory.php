<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\StatementFile;
use \App\Models\User;
use \App\Models\Account;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MomoStatement>
 */
class MomoStatementFactory extends Factory
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
            'transaction_date' => $this->faker->date(),
            'from_acct' => $this->faker->randomNumber(),
            'from_name' => $this->faker->firstName() . '' . $this->faker->lastName(),
            'from_no' => $this->faker->phoneNumber(),
            'transaction_type' => 'Transfer',
            'amount' => $this->faker->randomFloat(2),
            'fees' => $this->faker->randomFloat(2),
            'e-levy' => $this->faker->randomFloat(2),
            'bal_before' => $this->faker->randomFloat(2),
            'bal_after' => $this->faker->randomFloat(2),
            'to_no' => $this->faker->phoneNumber(),
            'to_name' => $this->faker->firstName() . '' . $this->faker->lastName(),
            'to_acct' => $this->faker->phoneNumber(),
            'f_id' => $this->faker->randomDigit(),
            'ref' => 'Food',
            'ova' => 'Internal',
            'user_id' => User::factory(),
            'account_id' => Account::factory(),
            'statement_file_id' => StatementFile::factory()

        ];
    }
}
