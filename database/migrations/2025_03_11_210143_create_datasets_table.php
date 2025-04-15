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
			$table->integer('thumbUpCount');
			$table->string('reviewCreatedVersion', 36);
			$table->string('at', 64);
			$table->string('replyContent', 255);
			$table->string('repliedAt', 64);
			$table->string('appVersion', 24);

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
