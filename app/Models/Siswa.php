<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
	protected $table = 'siswa';
	protected $fillable = [
		'uuid',
		'nis',
		'name',
		'jk',
		'kelas_id',
	];

	public function kelas()
	{
		return $this->belongsTo(Kelas::class);
	}

	public function screen()
	{
		return $this->hasMany(Screen::class,'peserta_id')->where('role','siswa');
	}
}
