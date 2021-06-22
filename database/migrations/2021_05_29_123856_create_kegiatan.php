<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKegiatan extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('kegiatan', function (Blueprint $table) {
			$table->id();
			$table->uuid('uuid')->unique();
			$table->string('name');
			$table->string('tahun_pelajaran')->nullable();
			$table->tinyInteger('semester')->nullable();
			$table->float('max_temp')->nullable();
			$table->dateTime('tanggal');
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
		Schema::dropIfExists('kegiatan');
	}
}
