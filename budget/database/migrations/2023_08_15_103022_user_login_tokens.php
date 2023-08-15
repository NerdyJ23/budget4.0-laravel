<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
		Schema::create('UserLoginTokens', function (Blueprint $table) {
			$table->id('id');
			$table->foreignId('user_id')->constrained();
			$table->string('token', 500);
			$table->timestamp('token_valid_until');
			$table->timestamp('token_created_at');
		});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
