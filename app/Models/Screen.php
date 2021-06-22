<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Screen extends Model
{
	protected $table = 'screen';
	protected $fillable = [
		'uuid',
		'kegiatan_id',
		'peserta_id',
		'role',
		'tanggal',
		'status',
		'prokes',
		'suhu',
		'kondisi',
		'keterangan',
	];

	protected $dates = ['tanggal', 'created_at', 'updated_at'];

	public function kegiatan()
	{
		return $this->belongsTo(Kegiatan::class);
	}
	public function peserta()
	{
		if ($this->role == 'siswa') {
			return $this->belongsTo(Siswa::class, 'peserta_id');
		}
		return $this->belongsTo(Guru::class, 'peserta_id');
	}
	public function siswa()
	{
		return $this->belongsTo(Siswa::class, 'peserta_id');
	}
	public function guru()
	{
		return $this->belongsTo(Guru::class, 'peserta_id');
	}
	public function getSuhuAttribute($value)
	{
		return round($value, 1);
	}
}
