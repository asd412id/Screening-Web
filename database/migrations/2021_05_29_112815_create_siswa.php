<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSiswa extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('siswa', function (Blueprint $table) {
			$table->id();
			$table->uuid('uuid')->unique();
			$table->string('nis')->nullable();
			$table->string('name');
			$table->string('jk', 1)->default('L');
			$table->bigInteger('kelas_id')->nullable();
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
		Schema::dropIfExists('siswa');
	}
}
