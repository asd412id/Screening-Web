<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Models\Kegiatan;
use App\Models\Guru;
use App\Models\Siswa;
use App\Models\Screen;
use App\Models\Kelas;
use App\Models\Config;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use PDF;

class KegiatanController extends Controller
{
  public function index(Request $r)
  {
    $lists = Kegiatan::when($r->s, function ($q, $s) {
      $q->where('name', 'like', "%$s%");
    })
      ->orderBy('id', 'desc')
      ->paginate(10)
      ->appends($r->all());
    $data = [
      'title' => 'Data Master Kegiatan',
      'data' => $lists
    ];

    return view('kegiatan.index', $data);
  }

  public function create(Request $r)
  {
    $data = [
      'title' => 'Data Kegiatan Baru',
    ];
    return view('kegiatan.create', $data);
  }

  public function store(Request $r)
  {
    $roles = [
      'name' => 'required',
      'tanggal' => 'required',
      'tahun_pelajaran' => 'required',
      'semester' => 'required',
      'max_temp' => 'required',
    ];
    $msgs = [
      'name.required' => 'Nama kegiatan tidak boleh kosong',
      'tanggal.required' => 'Tanggal kegiatan tidak boleh kosong',
      'tahun_pelajaran.required' => 'Tahun pelajaran tidak boleh kosong',
      'semester.required' => 'Semester tidak boleh kosong',
      'max_temp.required' => 'Temperatur maksimal tidak boleh kosong',
    ];
    Validator::make($r->all(), $roles, $msgs)->validate();

    $insert = new Kegiatan;
    $insert->uuid = (string) Str::uuid();
    $insert->name = $r->name;
    $insert->tanggal = $r->tanggal;
    $insert->tahun_pelajaran = $r->tahun_pelajaran;
    $insert->semester = $r->semester;
    $insert->max_temp = $r->max_temp ?? 0;

    if ($insert->save()) {
      return redirect()->route('kegiatan.peserta', ['uuid' => $insert->uuid])->with('msg', 'Data berhasil disimpan');
    }
    return redirect()->back()->withErrors('Data gagal disimpan');
  }

  public function edit($uuid)
  {
    $data = [
      'title' => 'Ubah Data Kegiatan',
      'data' => Kegiatan::where('uuid', $uuid)->first(),
    ];
    return view('kegiatan.edit', $data);
  }

  public function update(Request $r, $uuid)
  {
    $roles = [
      'name' => 'required',
      'tanggal' => 'required',
      'tahun_pelajaran' => 'required',
      'semester' => 'required',
      'max_temp' => 'required',
    ];
    $msgs = [
      'name.required' => 'Nama kegiatan tidak boleh kosong',
      'tanggal.required' => 'Tanggal kegiatan tidak boleh kosong',
      'tahun_pelajaran.required' => 'Tahun pelajaran tidak boleh kosong',
      'semester.required' => 'Semester tidak boleh kosong',
      'max_temp.required' => 'Temperatur maksimal tidak boleh kosong',
    ];
    Validator::make($r->all(), $roles, $msgs)->validate();

    $insert = Kegiatan::where('uuid', $uuid)->first();
    $insert->name = $r->name;
    $insert->tanggal = $r->tanggal;
    $insert->tahun_pelajaran = $r->tahun_pelajaran;
    $insert->semester = $r->semester;
    $insert->max_temp = $r->max_temp ?? 0;

    if ($insert->save()) {
      return redirect()->route('kegiatan.peserta', ['uuid' => $uuid])->with('msg', 'Data berhasil disimpan');
    }
    return redirect()->back()->withErrors('Data gagal disimpan');
  }

  public function destroy($uuid)
  {
    $delete = Kegiatan::where('uuid', $uuid)->first();
    if ($delete->delete()) {
      $delete->screen_all()->delete();
      return redirect()->route('kegiatan.index')->with('msg', 'Data berhasil dihapus');
    }
    return redirect()->route('kegiatan.index')->with('msg', 'Data gagal dihapus');
  }

  public function peserta($uuid)
  {
    $kegiatan = Kegiatan::where('uuid', $uuid)->first();

    $kelas = Kelas::whereHas('siswa.screen', function ($q) use ($kegiatan) {
      $q->where('kegiatan_id', $kegiatan->id);
    })
      ->orderBy('id', 'asc')
      ->get();

    $data = [
      'title' => 'Peserta ' . $kegiatan->name . ' (' . $kegiatan->tanggal->translatedFormat('j F Y') . ')',
      'data' => $kegiatan->screen_all,
      'kelas' => $kelas,
      'kuuid' => $kegiatan->uuid,
      'kid' => $kegiatan->id,
      'max_temp' => $kegiatan->max_temp,
    ];
    return view('kegiatan.peserta', $data);
  }

  public function cariPeserta($kuuid, Request $r)
  {
    $data = [];
    $kegiatan = Kegiatan::where('uuid', $kuuid)->first();

    $guru = Guru::where('nip', 'like', "%$r->req%")
      ->orWhere('name', 'like', "%$r->req%")
      ->orWhere('jabatan', 'like', "%$r->req%")
      ->get();


    if (count($guru)) {
      foreach ($guru as $key => $v) {
        $screen = $v->screen()->where('kegiatan_id', $kegiatan->id)->orderBy('id', 'desc')->first();
        array_push($data, [
          'id' => $v->id,
          'pid' => $v->nip,
          'name' => $v->name,
          'role' => 'Guru',
          'pos' => $v->jabatan,
          'kid' => $kegiatan->id,
          'status' => !$screen ? null : !@$screen->status,
        ]);
      }
    }

    $siswa = Siswa::where('nis', 'like', "%$r->req%")
      ->orWhere('name', 'like', "%$r->req%")
      ->orWhereHas('kelas', function ($q) use ($r) {
        $q->where('name', 'like', "%$r->req%");
      })
      ->get();

    if (count($siswa)) {
      foreach ($siswa as $key => $v) {
        $screen = $v->screen()->where('kegiatan_id', $kegiatan->id)->orderBy('id', 'desc')->first();
        array_push($data, [
          'id' => $v->id,
          'pid' => $v->nis,
          'name' => $v->name,
          'role' => 'Siswa',
          'pos' => $v->kelas->name,
          'kid' => $kegiatan->id,
          'status' => !$screen ? null : !@$screen->status,
        ]);
      }
    }

    return response()->json($data, 200);
  }

  public function getByCredential($kuuid, Request $r)
  {
    $kegiatan = Kegiatan::where('uuid', $kuuid)->first();

    $data = Guru::where('credential', $r->req)->first();

    if (!$data) {
      $data = Siswa::where('credential', $r->req)->first();
    }

    if ($data) {
      $screen = $data->screen()->where('kegiatan_id', $kegiatan->id)->orderBy('id', 'desc')->first();

      return response()->json([
        'code' => 200,
        'data' => [
          'id' => $data->id,
          'pid' => $data->nis ?? $data->nip,
          'name' => $data->name,
          'role' => $data->nis ? 'Siswa' : 'Guru',
          'pos' => $data->jabatan ?? $data->kelas->name,
          'kid' => $kegiatan->id,
          'status' => !$screen ? null : !@$screen->status,
        ]
      ], 200);
    }
    return response()->json(['code' => 404, 'data' => ['status' => 'Data tidak ditemukan']], 404);
  }

  public function screenCheck(Request $r)
  {
    $check = Screen::where('peserta_id', $r->id)
      ->where('kegiatan_id', $r->kid)
      ->where('role', $r->role)
      ->where('status', $r->status)
      ->first();

    if (!$check) {
      $check = [
        'kegiatan_id' => '',
        'peserta_id' => '',
        'role' => '',
        'tanggal' => '',
        'status' => 0,
        'prokes' => 1,
        'suhu' => '',
        'kondisi' => 'Baik',
        'keterangan' => ''
      ];
    }

    return response()->json($check, 200);
  }

  public function pesertaSubmit($uuid, Request $r)
  {
    $kegiatan = Kegiatan::where('uuid', $uuid)->first();
    $peserta = Screen::where('kegiatan_id', $kegiatan->id)
      ->where('role', $r->role)
      ->where('peserta_id', $r->peserta_id)
      ->where('status', $r->jenis_screening)
      ->first();

    if (!$peserta) {
      $peserta = new Screen;
      $peserta->uuid = (string) Str::uuid();
    }

    $peserta->kegiatan_id = $kegiatan->id;
    $peserta->tanggal = $kegiatan->tanggal;
    $peserta->peserta_id = $r->peserta_id;
    $peserta->role = $r->role;
    $peserta->status = $r->jenis_screening;
    $peserta->prokes = $r->prokes;
    $peserta->suhu = $r->suhu;
    $peserta->kondisi = $r->kondisi;
    $peserta->keterangan = $r->keterangan;

    if ($peserta->save()) {
      return redirect()->route('kegiatan.peserta', ['uuid' => $uuid])->with('msg', 'Data berhasil disimpan');
    }
    return redirect()->back()->withErrors('Data gagal disimpan');
  }

  public function screenDestroy($kuuid, $suuid)
  {
    $delete = Screen::where('uuid', $suuid)->first();
    if ($delete->delete()) {
      return redirect()->route('kegiatan.peserta', ['uuid' => $kuuid])->with('msg', 'Data berhasil dihapus');
    }
    return redirect()->route('kegiatan.peserta', ['uuid' => $kuuid])->with('msg', 'Data gagal dihapus');
  }

  public function printPeserta($kuuid, Request $r)
  {
    $configs = Config::configs();
    $kegiatan = Kegiatan::where('uuid', $kuuid)->first();
    $peserta = $kegiatan->screen_all()
      ->when($r->role != 'all', function ($q) use ($r) {
        $q->where('role', $r->role);
      })
      ->when(($r->kelas && $r->kelas != 'all'), function ($q) use ($r) {
        $q->whereHas('siswa.kelas', function ($q) use ($r) {
          $q->where('uuid', $r->kelas)
            ->orderBy('id', 'asc');
        });
      })
      ->orderBy('status', 'asc')
      ->orderBy('id', 'desc')
      ->get();

    $list = [];
    foreach ($peserta as $key => $v) {
      $list[$v->peserta_id . $v->role]['pid'] = $v->{$v->role}->nis ?? $v->{$v->role}->nip ?? '-';
      $list[$v->peserta_id . $v->role]['name'] = $v->{$v->role}->name;
      $list[$v->peserta_id . $v->role]['role'] = $v->role;
      $list[$v->peserta_id . $v->role]['kelas'] = @$v->{$v->role}->kelas->name;
      if (!$v->status) {
        $list[$v->peserta_id . $v->role]['datang'] = $v;
      } else {
        $list[$v->peserta_id . $v->role]['pulang'] = $v;
      }
    }

    if (!count($peserta)) {
      return redirect()->back()->withErrors('Tidak ada ' . $r->role . ' dalam kegiatan ini');
    }

    $data = [
      'title' => '(' . $kegiatan->tanggal->translatedFormat('l, j F Y') . ') Hasil Screening - ' . $kegiatan->name,
      'kegiatan' => $peserta[0]->kegiatan,
      'role' => $r->role,
      'kelas' => Kelas::where('uuid', $r->kelas)->first(),
      'data' => $list,
      'config' => $configs,
    ];

    $params = [
      'orientation' => 'landscape',
      'subject' => $data['title']
    ];

    $pdf = PDF::loadView('kegiatan.print.list', $data, [], $params);
    return $pdf->stream($data['title'] . '.pdf');
  }
}
