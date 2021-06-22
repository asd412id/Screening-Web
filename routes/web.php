<?php

use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
  Route::get('/login', 'CommonController@login')->name('login');
  Route::post('/login', 'CommonController@loginProcess')->name('login.process');
});

Route::middleware(['auth'])->group(function () {
  Route::get('/', 'CommonController@dashboard')->name('dashboard');
  Route::get('/logout', 'CommonController@logout')->name('logout');
  Route::get('/profil', 'CommonController@profile')->name('profile');
  Route::post('/profil', 'CommonController@profileUpdate')->name('profile.update');
  Route::get('/template/{type}', 'CommonController@downloadTemplate')->name('template.download');
  Route::get('/pengaturan', 'CommonController@config')->name('config');
  Route::post('/pengaturan', 'CommonController@configUpdate')->name('config.update');

  Route::prefix('/kelas')->group(function () {
    Route::get('/', 'KelasController@index')->name('kelas.index');
    Route::get('/tambah', 'KelasController@create')->name('kelas.create');
    Route::post('/tambah', 'KelasController@store')->name('kelas.store');
    Route::get('/{uuid}/ubah', 'KelasController@edit')->name('kelas.edit');
    Route::post('/{uuid}/ubah', 'KelasController@update')->name('kelas.update');
    Route::get('/{uuid}/hapus', 'KelasController@destroy')->name('kelas.destroy');
    Route::get('/{uuid}/kartu', 'KelasController@printCard')->name('kelas.print.card');
  });

  Route::prefix('/siswa')->group(function () {
    Route::get('/', 'SiswaController@index')->name('siswa.index');
    Route::get('/tambah', 'SiswaController@create')->name('siswa.create');
    Route::post('/tambah', 'SiswaController@store')->name('siswa.store');
    Route::get('/{uuid}/ubah', 'SiswaController@edit')->name('siswa.edit');
    Route::post('/{uuid}/ubah', 'SiswaController@update')->name('siswa.update');
    Route::get('/{uuid}/hapus', 'SiswaController@destroy')->name('siswa.destroy');
    Route::post('/import', 'SiswaController@import')->name('siswa.import');
    Route::get('/kartu', 'SiswaController@printCard')->name('siswa.print.card');
  });

  Route::prefix('/guru')->group(function () {
    Route::get('/', 'GuruController@index')->name('guru.index');
    Route::get('/tambah', 'GuruController@create')->name('guru.create');
    Route::post('/tambah', 'GuruController@store')->name('guru.store');
    Route::get('/{uuid}/ubah', 'GuruController@edit')->name('guru.edit');
    Route::post('/{uuid}/ubah', 'GuruController@update')->name('guru.update');
    Route::get('/{uuid}/hapus', 'GuruController@destroy')->name('guru.destroy');
    Route::post('/import', 'GuruController@import')->name('guru.import');
    Route::get('/kartu', 'GuruController@printCard')->name('guru.print.card');
  });

  Route::prefix('/kegiatan')->group(function () {
    Route::get('/', 'KegiatanController@index')->name('kegiatan.index');
    Route::get('/tambah', 'KegiatanController@create')->name('kegiatan.create');
    Route::post('/tambah', 'KegiatanController@store')->name('kegiatan.store');
    Route::get('/{uuid}/ubah', 'KegiatanController@edit')->name('kegiatan.edit');
    Route::post('/{uuid}/ubah', 'KegiatanController@update')->name('kegiatan.update');
    Route::get('/{uuid}/hapus', 'KegiatanController@destroy')->name('kegiatan.destroy');
    Route::get('/{uuid}', 'KegiatanController@peserta')->name('kegiatan.peserta');
    Route::post('/{uuid}/cari', 'KegiatanController@cariPeserta')->name('kegiatan.peserta.cari');
    Route::post('/{uuid}/credential', 'KegiatanController@getByCredential')->name('kegiatan.peserta.credential');
    Route::get('/{uuid}/screen', 'KegiatanController@screenCheck')->name('kegiatan.screen');
    Route::post('/{uuid}', 'KegiatanController@pesertaSubmit')->name('kegiatan.peserta.process');
    Route::get('/{uuid}/{suuid}/hapus', 'KegiatanController@screenDestroy')->name('kegiatan.screen.destroy');
    Route::get('/{uuid}/cetak', 'KegiatanController@printPeserta')->name('kegiatan.peserta.print');
    Route::post('/{uuid}/cetak', 'KegiatanController@printPeserta')->name('kegiatan.peserta.print.kelas');
  });
});
