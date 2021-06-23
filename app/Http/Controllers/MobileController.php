<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Kegiatan;
use App\Models\Kelas;
use App\Models\Screen;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Str;

class MobileController extends Controller
{
	private $limit = 20;
	public function user()
	{
		return response()->json(auth()->user(), 200);
	}

	public function login(Request $r)
	{
		if (auth()->attempt([
			'username' => $r->header('username'),
			'password' => $r->header('password')
		])) {
			$user = auth()->user();
			if (is_null($user->api_token)) {
				$user->api_token = Str::random(100);
				$user->save();
			}
			return response()->json($user, 202);
		}
		return response()->json(['error' => 'Username dan password tidak dikenal'], 406);
	}

	public function logout()
	{
		$user = auth()->user();
		$user->api_token = null;
		$user->save();
		return response()->json(['status' => 'success'], 200);
	}

	public function kegiatan(Request $r)
	{
		$query = Kegiatan::orderBy("created_at", "desc")->paginate($this->limit);
		if (count($query)) {
			$data = [];
			foreach ($query as $key => $v) {
				array_push($data, [
					'no' => ($key + 1),
					'id' => $v->id,
					'uuid' => $v->uuid,
					'tanggal' => $v->tanggal,
					'tanggal_slash' => $v->tanggal->translatedFormat('l, j F Y')
						. "\nTahun Pelajaran\t\t: " . $v->tahun_pelajaran . ' - ' . ($v->semester == 1 ? 'Ganjil' : 'Genap')
						. "\nSuhu Maksimal\t\t: " . $v->max_temp . "°C"
						. "\nJumlah Peserta\t\t: " . $v->screen_all->groupBy('peserta_id')->count(),
					'name' => $v->name,
					'tahun_pelajaran' => $v->tahun_pelajaran,
					'semester' => $v->semester,
					'max_temp' => $v->max_temp
				]);
			}
			return response()->json(['total' => $query->total(), 'count' => count($data), 'page' => $r->page ?? 1, 'data' => $data], 200);
		}
		return response()->json(['total' => 0, 'count' => 0, 'page' => 0, 'data' => [[
			'no' => 0,
			'id' => "",
			'uuid' => "",
			'tanggal' => "",
			'tanggal_slash' => "",
			'name' => "Kegiatan tidak tersedia",
			'tahun_pelajaran' => "",
			'semester' => "",
			'max_temp' => ""
		]]], 404);
	}

	public function screen(Request $r)
	{
		$kegiatan = Kegiatan::where('uuid', $r->header('kegiatan-id'))->first();
		if (!$kegiatan) {
			return response()->json(['error' => 'Kegiatan tidak ditemukan'], 404);
		}
		$data = [];
		$screen = $kegiatan->screen_all()->paginate($this->limit);
		if (count($screen)) {
			foreach ($screen as $key => $v) {
				$pid = $v->peserta->nis ?? ($v->peserta->nip != '' ? $v->peserta->nip : 'Tenaga Sukarela');
				array_push($data, [
					'id' => $v->id,
					'uuid' => $v->uuid,
					'pid' => $pid,
					'name' => ($screen->firstItem() + $key) . '. ' . $v->peserta->name,
					'credential' => $v->peserta->credential,
					'kuuid' => $v->kegiatan->uuid,
					'role' => "ID\t\t\t\t\t\t\t\t: " . $pid
						. "\nStatus\t\t\t\t: " . ucfirst($v->role)
						. "\n" . ($v->peserta->jabatan ? "Jabatan \t\t: " . $v->peserta->jabatan : "Kelas\t\t\t\t\t: " . $v->peserta->kelas->name)
						. "\nScreening\t: " . ($v->status ? 'Pulang' : 'Datang'),
					'status' => (int) $v->status,
					'prokes' => (bool) $v->prokes,
					'kondisi' => $v->kondisi,
					'suhu' => $v->suhu,
					'keterangan' => $v->keterangan
				]);
			}
			return response()->json(['total' => $screen->total(), 'count' => count($screen), 'page' => $r->page ?? 1, 'data' => $data], 200);
		}
		return response()->json(['total' => 0, 'count' => 0, 'page' => 0, 'data' => [[
			'id' => "",
			'uuid' => "",
			'pid' => "",
			'name' => "Belum ada peserta pada kegiatan ini!\n\nSilahkan menambahkan peserta dengan melakukan \"Scan Kode QR\" pada kartu peserta atau dengan menggunakan fitur \"Cari Peserta\"",
			'credential' => "",
			'kuuid' => "",
			'role' => "",
			'status' => 0,
			'prokes' => true,
			'kondisi' => "",
			'suhu' => "",
			'keterangan' => ""
		]]], 404);
	}

	public function queryPeserta(Request $r)
	{
		$credential = $r->header('credential');
		$kuuid = $r->header('kegiatan-id');
		$status = $r->header('status');
		$type = $r->header('type') ?? 'get';

		$kegiatan = Kegiatan::where('uuid', $kuuid)->first();
		if (!$kegiatan) {
			return response()->json(['error' => 'Kegiatan tidak ditemukan'], 404);
		}

		$data = Guru::where('credential', $credential)->first();
		if (!$data) {
			$data = Siswa::where('credential', $credential)->first();
		}

		if (!$data) {
			return response()->json(['error' => 'Peserta tidak ditemukan'], 404);
		}

		$screen = $data->screen()->where('kegiatan_id', $kegiatan->id);

		if ($type == 'get') {
			$screen = $screen->where('status', $status);
		}

		$screen = $screen->orderBy('id', 'asc')->get();

		$defScreen = [
			'kegiatan_id' => '',
			'peserta_id' => '',
			'role' => '',
			'tanggal' => '',
			'status' => ($type == 'get' && count($screen)) ? $screen[0]->status : (count($screen) > 1 ? 0 : (count($screen) == 1 && $type == 'query' ? ($screen[0]->status ? 0 : 1) : 0)),
			'prokes' => 1,
			'suhu' => '',
			'kondisi' => 'Sehat',
			'keterangan' => ''
		];

		return response()->json(['data' => [
			'id' => $data->id,
			'pid' => $data->nis ?? ($data->nip != '' ? $data->nip : 'Tenaga Sukarela'),
			'name' => $data->name,
			'kuuid' => $kegiatan->uuid,
			'kname' => $kegiatan->name,
			'role' => $data->nis ? 'siswa' : 'guru',
			'post' => $data->jabatan ?? $data->kelas->name,
			'status' => ($type == 'get' && count($screen)) ? $screen[0]->status : (count($screen) > 1 ? 0 : (count($screen) == 1 && $type == 'query' ? ($screen[0]->status ? 0 : 1) : 0)),
			'screen' => ($type == 'get' && count($screen)) ? $screen[0] : (count($screen) > 1 ? $screen[0] : $defScreen)
		]], 200);
	}

	public function searchPeserta(Request $r)
	{
		$query = $r->header('query');
		$data = [];
		$kegiatan = Kegiatan::where('uuid', $r->header('kegiatan-id'))->first();
		$no = 0;
		$limit = $this->limit;

		$q1 = Siswa::with('screen')
			->where('nis', 'like', "%$query%")
			->orWhere('name', 'like', "%$query%")
			->orWhereHas('kelas', function ($q) use ($query) {
				$q->where('name', 'like', "%$query%");
			});


		$q2 = Guru::with('screen')
			->where('nip', 'like', "%$query%")
			->orWhere('name', 'like', "%$query%")
			->orWhere('jabatan', 'like', "%$query%")
			->union($q1)
			->paginate($limit);

		if (count($q2) && $limit > 0) {
			foreach ($q2 as $key => $v) {
				$screen = Screen::where('peserta_id', $v->id)
					->where('kegiatan_id', $kegiatan->id)
					->orderBy('id', 'desc')
					->first();
				$pid = $v->nis ?? ($v->nip != '' ? $v->nip : 'Tenaga Sukarela');
				$kelas = Kelas::find($v->jabatan);
				array_push($data, [
					'id' => $v->id,
					'uuid' => $v->uuid,
					'pid' => $pid,
					'name' => ($q2->firstItem() + $key) . '. ' . $v->name,
					'credential' => $v->credential,
					'kuuid' => $kegiatan->uuid,
					'role' => "ID\t\t\t\t\t\t\t\t: " . $pid
						. "\nStatus\t\t\t\t: " . ($kelas ? 'Siswa' : 'Guru')
						. "\n" . (!$kelas ? "Jabatan \t\t: " . $v->jabatan : "Kelas\t\t\t\t\t: " . $kelas->name),
					'status' => !$screen ? 0 : (int) !@$screen->status,
					'prokes' => true,
					'kondisi' => null,
					'suhu' => null,
					'keterangan' => null
				]);
			}
		}

		if (count($data)) {
			return response()->json(['total' => $q2->total(), 'count' => count($data), 'page' => $r->page ?? 1, 'data' => $data], 200);
		}
		return response()->json(['total' => 0, 'count' => 0, 'page' => 0, 'data' => [[
			'id' => "",
			'uuid' => "",
			'pid' => "",
			'name' => "Pencarian \"$query\" tidak ditemukan",
			'credential' => "",
			'kuuid' => "",
			'role' => "",
			'status' => 0,
			'prokes' => true,
			'kondisi' => "",
			'suhu' => "",
			'keterangan' => ""
		]]], 404);
	}

	public function pesertaSubmit(Request $r)
	{
		$kegiatan = Kegiatan::where('uuid', $r->header('kegiatan-id'))->first();
		if (!$kegiatan) {
			return response()->json(['error' => 'Kegiatan tidak ditemukan'], 404);
		}
		try {
			$data_submit = json_decode($r->header('Data-Submit'));
			if ($data_submit->role != 'siswa' && $data_submit->role != 'guru') {
				return response()->json(['error' => 'Status peserta tidak dikenal'], 406);
			}
			if (!Guru::find($data_submit->peserta_id) && !Siswa::find($data_submit->peserta_id)) {
				return response()->json(['error' => 'Peserta tidak ditemukan'], 404);
			}
			$peserta = Screen::where('kegiatan_id', $kegiatan->id)
				->where('role', $data_submit->role)
				->where('peserta_id', $data_submit->peserta_id)
				->where('status', $data_submit->jenis_screening)
				->first();

			if (!$peserta) {
				$peserta = new Screen;
				$peserta->uuid = (string) Str::uuid();
			}

			$peserta->kegiatan_id = $kegiatan->id;
			$peserta->tanggal = $kegiatan->tanggal;
			$peserta->peserta_id = $data_submit->peserta_id;
			$peserta->role = $data_submit->role;
			$peserta->status = $data_submit->jenis_screening && $data_submit->jenis_screening != '' ? $data_submit->jenis_screening : 0;
			$peserta->prokes = $data_submit->prokes && $data_submit->prokes != '' ? $data_submit->prokes : 1;
			$peserta->suhu = $data_submit->suhu && $data_submit->suhu != ''  ? $data_submit->suhu : null;
			$peserta->kondisi = $data_submit->kondisi;
			$peserta->keterangan = $data_submit->keterangan && $data_submit->keterangan != '' ? $data_submit->keterangan : null;

			if ($peserta->save()) {
				return response()->json(['status' => 'Data berhasil disimpan', 'data' => $peserta], 202);
			}
			return response()->json(['error' => 'Tidak dapat menyimpan data'], 500);
		} catch (\Throwable $th) {
			return response()->json(['error' => 'Format data tidak sesuai'], 406);
		}
	}

	public function hapusPeserta(Request $r)
	{
		$suuid = $r->header('Screen-Id');
		$query = Screen::where('uuid', $suuid)->first();
		if (!$query) {
			return response()->json(['error' => 'Data tidak ditemukan'], 404);
		}
		if ($query->delete()) {
			return response()->json(['status' => 'Data berhasil dihapus'], 201);
		}
		return response()->json(['error' => 'Data gagal dihapus'], 503);
	}
}
