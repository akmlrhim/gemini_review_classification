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
		Schema::create('datasets', function (Blueprint $table) {
			$table->id();
			$table->string('reviewId', 64);
			$table->string('userName', 64);
			$table->string('userImage', 120);
			$table->longText('content');
			$table->integer('score');
			$table->integer('thumbsUpCount')->nullable();
			$table->string('reviewCreatedVersion', 36)->nullable();
			$table->string('at', 64)->nullable();
			$table->longText('replyContent')->nullable();
			$table->string('repliedAt', 64)->nullable();
			$table->string('appVersion', 24)->nullable();

			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('datasets');
	}
};
