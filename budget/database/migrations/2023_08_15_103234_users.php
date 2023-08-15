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
		Schema::create('Users', function (Blueprint $table) {
			$table->id('id');
			$table->string('username', 50)->unique();
			$table->string('password', 200);
			$table->string('first_name', 50);
			$table->string('last_name', 100)->nullable();
			$table->string('token')->nullable();
			$table->timestamp('token_valid_until')->nullable();
			$table->timestamp('last_logged_in')->nullable();
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
