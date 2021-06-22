<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScreen extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('screen', function (Blueprint $table) {
			$table->id();
			$table->uuid('uuid')->unique();
			$table->bigInteger('kegiatan_id');
			$table->bigInteger('peserta_id');
			$table->string('role',30);
			$table->dateTime('tanggal');
			$table->boolean('status')->default(0)->nullable();
			$table->boolean('prokes')->nullable();
			$table->float('suhu')->nullable();
			$table->string('kondisi')->nullable();
			$table->text('keterangan')->nullable();
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
		Schema::dropIfExists('screen');
	}
}
