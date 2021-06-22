<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGuru extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('guru', function (Blueprint $table) {
			$table->id();
			$table->uuid('uuid')->unique();
			$table->string('nip')->nullable();
			$table->string('name');
			$table->string('jk', 1)->default('L');
			$table->string('jabatan')->nullable();
			$table->string('credential', 35)->unique()->nullable();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('guru');
	}
}
