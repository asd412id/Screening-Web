<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Guru;
use App\Models\Kegiatan;
use App\Models\Screen;
use App\Models\Config;
use Validator;

class CommonController extends BaseController
{
	use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

	public function login()
	{
		$data = [
			'title' => 'Halaman Login',
		];
		return view('login', $data);
	}

	public function loginProcess(Request $r)
	{
		$roles = [
			'password' => 'required',
			'username' => 'required',
		];

		$msgs = [
			'password.required' => ':Attribute harus diisi',
			'username.required' => ':Attribute harus diisi',
		];

		Validator::make($r->all(), $roles, $msgs)->validate();

		if (auth()->attempt(['username' => $r->username, 'password' => $r->password], $r->remember ?? false)) {
			return redirect()->back();
		}

		return redirect()->back()->withErrors('Username dan password tidak dikenal!');
	}

	public function profile()
	{
		$user = auth()->user();
		$data = [
			'title' => 'Pengaturan Profil',
			'data' => $user
		];
		return view('profile', $data);
	}

	public function profileUpdate(Request $r)
	{
		$user = auth()->user();
		$roles = [
			'username' => 'required|unique:users,username,' . $user->uuid . ',uuid',
			'name' => 'required',
		];
		$msgs = [
			'name.required' => 'Nama tidak boleh kosong',
			'username.required' => ':Attribute tidak boleh kosong',
			'username.unique' => ':Attribute :input telah digunakan',
		];

		if ($r->password) {
			$roles['password'] = 'confirmed';
			$msgs['password.confirmed'] = 'Perulangan :attribute tidak benar';
		}
		Validator::make($r->all(), $roles, $msgs)->validate();

		$user->name = $r->name;
		$user->username = $r->username;

		if ($r->password) {
			$user->password = bcrypt($r->password);
		}

		if ($user->save()) {
			return redirect()->route('profile')->with('msg', 'Data berhasil diubah');
		}
		return redirect()->back()->withErrors('Data gagal disimpan');
	}

	public function logout()
	{
		auth()->logout();
		return redirect()->route('login')->with('msg', 'Anda berhasil logout');
	}

	public function dashboard()
	{
		$data = [
			'title' => 'Beranda',
			'siswa' => Siswa::count(),
			'guru' => Guru::count(),
			'kegiatan' => Kegiatan::count(),
			'screen' => Screen::where('tanggal', '>=', now()->startOfDay())
				->where('tanggal', '<=', now()->endOfDay())
				->count(),
		];
		return view('index', $data);
	}

	public function downloadTemplate($type)
	{
		$pathToFile = public_path('files/import_' . $type . '_template.csv');
		$name = 'Import Data ' . ucfirst($type) . '.csv';
		return response()->download($pathToFile, $name);
	}

	public function config()
	{
		$configs = Config::configs();
		$data = [
			'title' => 'Pengaturan Sistem',
			'data' => $configs
		];
		return view('config', $data);
	}

	public function configUpdate(Request $r)
	{
		$data = [];
		foreach ($r->all() as $key => $v) {
			if ($key == '_token') {
				continue;
			}
			array_push($data, [
				'config' => $key,
				'value' => $v
			]);
		}

		Config::truncate();
		$insert = Config::insert($data);

		if ($insert) {
			return redirect()->route('config')->with('msg', 'Pengaturan berhasil diperbaharui');
		}
		return redirect()->back()->withErrors('Pengaturan gagal disimpan');
	}

	public function ajaxPetugas(Request $r)
	{
		$petugas = Guru::where('nip', $r->q)
			->orWhere('name', 'like', "%$r->q%")
			->orWhere('jabatan', 'like', "%$r->q%")
			->select('id', 'name as text')
			->get();

		return response()->json([
			'results' => $petugas
		]);
	}
}
