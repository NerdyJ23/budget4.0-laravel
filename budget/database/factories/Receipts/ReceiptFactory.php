<?php

namespace Database\Factories\Receipts;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Receipts\Receipt;
use App\Models\Receipts\ReceiptItemCategory;
use App\Models\Users\User;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ReceiptFactory extends Factory
{
	protected $model = Receipt::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
			'Name' => fake()->company(),
			'Location' => fake()->city(),
			'ReceiptNumber' => fake()->shuffle(fake()->bothify('????#####  ## - ???')),
			'Cost' => fake()->randomFloat(3),
			'Date' => fake()->dateTime(),
			'Category' => ReceiptItemCategory::factory(),
			'User' => User::factory(),
			'CreatedUTC' => now(),
			'EditedUTC' => null
        ];
	}
}
