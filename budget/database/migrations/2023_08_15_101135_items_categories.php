<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	const TABLE = 'ItemsCategories';
    /**
     * Run the migrations.
     */
    public function up(): void
    {
		if (!Schema::hasTable(self::TABLE)) {
			Schema::create(self::TABLE, function (Blueprint $table) {
				$table->id('ID');
				$table->unsignedBigInteger('User_ID');
				$table->string('Name', 255);
				$table->boolean('Archived')->default(false);
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
