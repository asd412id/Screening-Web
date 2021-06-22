<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
	protected $table = 'guru';
	protected $fillable = [
		'uuid',
		'nip',
		'name',
		'jk',
		'jabatan',
	];

	public function screen()
	{
		return $this->hasMany(Screen::class,'peserta_id')->where('role','guru');
	}
}
