<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Guru;
use PDF;
use Validator;
use Str;

class SiswaController extends Controller
{
  public function index(Request $r)
  {
    $lists = Siswa::when($r->s, function ($q, $s) {
      $q->where('nis', 'like', "%$s%")
        ->orWhere('name', 'like', "%$s")
        ->orWhereHas('kelas', function ($q) use ($s) {
          $q->where('name', 'like', "%$s%");
        });
    })
      ->paginate(10)
      ->appends($r->all());
    $data = [
      'title' => 'Data Master Siswa',
      'data' => $lists
    ];

    return view('siswa.index', $data);
  }

  public function create(Request $r)
  {
    $data = [
      'title' => 'Data Siswa Baru',
      'kelas' => Kelas::all()
    ];
    return view('siswa.create', $data);
  }

  public function store(Request $r)
  {
    $roles = [
      'nis' => 'required|unique:siswa,nis',
      'name' => 'required',
      'jk' => 'required',
      'kelas' => 'required',
    ];
    $msgs = [
      'nis.required' => 'NIS tidak boleh kosong',
      'nis.unique' => 'NIS :input sudah ada',
      'name.required' => 'Nama siswa tidak boleh kosong',
      'jk.required' => 'Jenis kelamin siswa tidak boleh kosong',
      'kelas.required' => 'Kelas tidak boleh kosong',
    ];
    Validator::make($r->all(), $roles, $msgs)->validate();

    $insert = new Siswa;
    $insert->uuid = (string) Str::uuid();
    $insert->nis = $r->nis ?? null;
    $insert->name = $r->name;
    $insert->jk = $r->jk;
    $insert->kelas_id = $r->kelas;
    do {
      $credential = (string) Str::random(35);
      $cekGuru = Guru::where('credential', $credential)->first();
      $cekSiswa = Siswa::where('credential', $credential)->first();
    } while ($cekGuru && $cekSiswa);
    $insert->credential = $credential;

    if ($insert->save()) {
      return redirect()->route('siswa.index')->with('msg', 'Data berhasil disimpan');
    }
    return redirect()->back()->withErrors('Data gagal disimpan');
  }

  public function edit($uuid)
  {
    $data = [
      'title' => 'Ubah Data Siswa',
      'data' => Siswa::where('uuid', $uuid)->first(),
      'kelas' => Kelas::all()
    ];
    return view('siswa.edit', $data);
  }

  public function update(Request $r, $uuid)
  {
    $roles = [
      'nis' => 'required|unique:siswa,nis,' . $uuid . ',uuid',
      'name' => 'required',
      'jk' => 'required',
      'kelas' => 'required',
    ];
    $msgs = [
      'nis.required' => 'NIS tidak boleh kosong',
      'nis.unique' => 'NIS :input sudah ada',
      'name.required' => 'Nama siswa tidak boleh kosong',
      'jk.required' => 'Jenis kelamin siswa tidak boleh kosong',
      'kelas.required' => 'Kelas tidak boleh kosong',
    ];
    Validator::make($r->all(), $roles, $msgs)->validate();

    $insert = Siswa::where('uuid', $uuid)->first();
    $insert->nis = $r->nis ?? null;
    $insert->name = $r->name;
    $insert->jk = $r->jk;
    $insert->kelas_id = $r->kelas;

    if ($insert->save()) {
      return redirect()->route('siswa.index')->with('msg', 'Data berhasil disimpan');
    }
    return redirect()->back()->withErrors('Data gagal disimpan');
  }

  public function destroy($uuid)
  {
    $delete = Siswa::where('uuid', $uuid)->first();
    if ($delete->delete()) {
      $delete->screen()->delete();
      return redirect()->route('siswa.index')->with('msg', 'Data berhasil dihapus');
    }
    return redirect()->route('siswa.index')->withErrors('Data gagal dihapus');
  }

  public function import(Request $r)
  {
    if ($r->hasFile('excel') && $r->file('excel')->isValid() && $r->excel->extension() == 'txt') {
      $handle = fopen($r->excel->path(), 'r');
      $h = fgetcsv($handle, 1000, ',');

      if (!in_array('nis', $h) || !in_array('nama', $h) || !in_array('jenis_kelamin', $h) || !in_array('kelas', $h)) {
        return redirect()->route('siswa.index')->withErrors('Format tidak valid');
      }

      $errors = 0;
      $new = 0;
      while (!feof($handle)) {
        $data = fgetcsv($handle, 1000, ',');
        if ($data) {
          if ($data[array_search('kelas', $h)] == '') {
            $errors++;
            continue;
          }
          try {
            $kelas = Kelas::where('name', $data[array_search('kelas', $h)])->first();

            if (!$kelas && $data[array_search('kelas', $h)] != '') {
              $kelas = new Kelas;
              $kelas->uuid = (string) Str::uuid();
              $kelas->name = $data[array_search('kelas', $h)];
              $kelas->save();
            }

            if ($data[array_search('nis', $h)]) {
              $insert = Siswa::where('nis', $data[array_search('nis', $h)])->first();

              if (!$insert) {
                $insert = new Siswa;
                $insert->uuid = (string) Str::uuid();
                do {
                  $credential = (string) Str::random(35);
                  $cekGuru = Guru::where('credential', $credential)->first();
                  $cekSiswa = Siswa::where('credential', $credential)->first();
                } while ($cekGuru && $cekSiswa);
                $insert->credential = $credential;
                $new++;
              }
            } else {
              $insert = new Siswa;
              $insert->uuid = (string) Str::uuid();
              do {
                $credential = (string) Str::random(35);
                $cekGuru = Guru::where('credential', $credential)->first();
                $cekSiswa = Siswa::where('credential', $credential)->first();
              } while ($cekGuru && $cekSiswa);
              $insert->credential = $credential;
              $new++;
            }
            $insert->nis = $data[array_search('nis', $h)] ?? null;
            $insert->name = $data[array_search('nama', $h)];
            $insert->jk = $data[array_search('jenis_kelamin', $h)];
            $insert->kelas_id = $kelas->id;
            if (!$insert->save()) {
              $errors++;
            }
          } catch (\Throwable $th) {
            $errors++;
          }
        }
      }
      fclose($handle);
      if (!$errors) {
        return redirect()->route('siswa.index')->with('msg', 'Data berhasil diimpor: ' . $new);
      }
      return redirect()->route('siswa.index')->withErrors('Data gagal diimpor: ' . $errors);
    }
    return redirect()->route('siswa.index')->withErrors('Data tidak valid');
  }

  public function printCard()
  {
    $data = Siswa::all();

    $data = [
      'title' => 'Kartu Siswa',
      'data' => $data,
    ];

    $params = [
      'subject' => $data['title']
    ];

    $pdf = PDF::loadView('siswa.kartu-nice', $data, [], $params);
    return $pdf->stream($data['title'] . '.pdf');
  }
}
