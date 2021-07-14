<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnOptTableScreen extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if (!Schema::hasColumn('screen', 'opt')) {
			Schema::table('screen', function (Blueprint $table) {
				$table->json('opt')->after('keterangan')->nullable();
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
		if (Schema::hasColumn('screen', 'opt')) {
			Schema::table('screen', function (Blueprint $table) {
				$table->dropColumn('opt');
			});
		}
	}
}
