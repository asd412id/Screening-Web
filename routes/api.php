<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('v1')->group(function () {
	Route::post('login', 'MobileController@login');

	Route::middleware(['auth:api'])->group(function () {
		Route::get('logout', 'MobileController@logout');
		Route::get('user', 'MobileController@user');

		Route::get('peserta', 'MobileController@queryPeserta');
		Route::get('kegiatan', 'MobileController@kegiatan');
		Route::get('screen', 'MobileController@screen');
		Route::put('peserta', 'MobileController@pesertaSubmit');
		Route::delete('peserta', 'MobileController@hapusPeserta');
		Route::post('cari', 'MobileController@searchPeserta');
	});
});
