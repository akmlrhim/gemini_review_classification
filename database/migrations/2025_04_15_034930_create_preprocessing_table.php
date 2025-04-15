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
		Schema::create('preprocessing', function (Blueprint $table) {
			$table->id();
			$table->string('clean_data');
			$table->string('folding_case');
			$table->string('stopword');
			$table->string('stemming');
			$table->string('tokenize');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('preprocessing');
	}
};
