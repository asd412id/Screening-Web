<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use stdClass;

class Config extends Model
{
	use HasFactory;
	protected $table = 'configs';
	protected $fillable = ['config','value'];
	public $timestamps = false;
	public $primaryKey = 'config';

	public static function configs()
	{
		$configs = null;
		$gets = DB::table('configs')->get();
		if (count($gets)) {
			$configs = new stdClass();
		}
		foreach ($gets as $key => $v) {
			$configs->{$v->config} = $v->value;
		}

		return $configs;
	}
}
