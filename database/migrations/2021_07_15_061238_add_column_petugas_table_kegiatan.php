<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnPetugasTableKegiatan extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if (!Schema::hasColumn('kegiatan', 'petugas')) {
			Schema::table('kegiatan', function (Blueprint $table) {
				$table->json('petugas')->after('max_temp')->nullable();
			});
		}
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		if (Schema::hasColumn('kegiatan', 'petugas')) {
			Schema::table('kegiatan', function (Blueprint $table) {
				$table->dropColumn('petugas');
			});
		}
	}
}
