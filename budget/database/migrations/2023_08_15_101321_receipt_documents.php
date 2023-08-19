<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	const TABLE = 'ReceiptDocuments';
    /**
     * Run the migrations.
     */
    public function up(): void
    {
		if (!Schema::hasTable(self::TABLE)) {
			Schema::create(self::TABLE, function (Blueprint $table) {
				$table->id('ID');
				$table->unsignedBigInteger('User_ID');
				$table->unsignedBigInteger('Receipt_ID');
				$table->string('Name', 50);
				$table->string('UUID', 50);
			});
		}

		if (Schema::hasTable('Receipts')) {
			Schema::table(self::TABLE, function (Blueprint $table) {
				$table->foreign('Receipt_ID')->references('ID')->on('Receipts');
			});
		}

		if (Schema::hasTable('Users')) {
			Schema::table(self::TABLE, function (Blueprint $table) {
				$table->foreign('User_ID')->references('id')->on('Users');
			});
		}
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
