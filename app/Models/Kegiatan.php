<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Kegiatan extends Model
{
	protected $table = 'kegiatan';
	protected $fillable = [
		'uuid',
		'tanggal',
		'name',
		'tahun_pelajaran',
		'semester',
		'max_temp',
	];

	protected $appends = ['tanggal_slash'];

	protected $dates = ['tanggal'];

	public function screen_all()
	{
		return $this->hasMany(Screen::class)->orderBy('role', 'asc')
			->orderBy('peserta_id', 'asc')
			->orderBy('status', 'asc');
	}
	public function screen_siswa()
	{
		return $this->hasMany(Screen::class)->where('role', 'siswa');
	}
	public function screen_guru()
	{
		return $this->hasMany(Screen::class)->where('role', 'guru');
	}
	public function setTanggalAttribute($value)
	{
		$this->attributes['tanggal'] = Carbon::createFromFormat('d-m-Y', $value)->toDateTimeString();
	}
	public function getTanggalSlashAttribute()
	{
		return $this->tanggal->format('d/m/Y');
	}
	public function getMaxTempAttribute($value)
	{
		return round($value, 1);
	}
}
