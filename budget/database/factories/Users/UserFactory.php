<?php

namespace Database\Factories\Users;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

use App\Models\Users\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
	protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
		return [
			// 'id' => fake()->randomNumber(),
			'username' => fake()->userName(),
			'password' => fake()->password(),
			'first_name' => fake()->firstName(),
			'token' => null,
			'token_valid_until' => null,
			'last_logged_in' => fake()->dateTime()
		];
    }
}
