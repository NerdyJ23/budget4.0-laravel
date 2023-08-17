<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Receipts\Receipt;
use App\Models\Receipts\ReceiptItem;
use App\Models\Receipts\ReceiptItemCategory;

use App\Models\Users\User;

class ReceiptsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
		// $user = User::factory()->create();

		$receipt = Receipt::factory()->create();
		$user = User::factory()->create();

		ReceiptItem::factory()->count(5)
		->recycle($user)
		->for($receipt)
		->create();

    }
}
