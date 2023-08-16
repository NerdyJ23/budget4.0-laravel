<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	const TABLE = 'UserLoginTokens';
    /**
     * Run the migrations.
     */
    public function up(): void
    {
		if (!Schema::hasTable(self::TABLE)) {
			Schema::create(self::TABLE, function (Blueprint $table) {
				$table->id('id');
				$table->unsignedBigInteger('user_id');
				$table->string('token', 500);
				$table->timestamp('token_valid_until');
				$table->timestamp('token_created_at');
			});
		}

		if (Schema::hasTable('Users')) {
			Schema::table(self::TABLE, function (Blueprint $table) {
				$table->foreign('user_id')->references('id')->on('Users');
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
