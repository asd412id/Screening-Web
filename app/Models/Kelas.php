<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
	protected $table = 'kelas';
	protected $fillable = [
		'uuid',
		'name'
	];

	public function siswa()
	{
		return $this->hasMany(Siswa::class);
	}
}
