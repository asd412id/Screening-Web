<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Models\Guru;
use App\Models\Siswa;
use Validator;
use Str;
use PDF;

class GuruController extends Controller
{
  public function index(Request $r)
  {
    $lists = Guru::when($r->s, function ($q, $s) {
      $q->where('nip', 'like', "%$s%")
        ->orWhere('name', 'like', "%$s")
        ->orWhere('jabatan', 'like', "%$s");
    })
      ->paginate(10)
      ->appends($r->all());
    $data = [
      'title' => 'Data Master Guru',
      'data' => $lists
    ];

    return view('guru.index', $data);
  }

  public function create(Request $r)
  {
    $data = [
      'title' => 'Data Guru Baru',
    ];
    return view('guru.create', $data);
  }

  public function store(Request $r)
  {
    $roles = [
      'name' => 'required',
      'jk' => 'required',
    ];
    $msgs = [
      'name.required' => 'Nama guru tidak boleh kosong',
      'jk.required' => 'Jenip kelamin guru tidak boleh kosong',
    ];

    if ($r->nip) {
      $roles['nip'] = 'required|unique:guru,nip';
      $msgs['nip.required'] = 'NIP tidak boleh kosong';
      $msgs['nip.unique'] = 'NIP :input sudah ada';
    }

    Validator::make($r->all(), $roles, $msgs)->validate();

    $insert = new Guru;
    $insert->uuid = (string) Str::uuid();
    $insert->nip = $r->nip ?? null;
    $insert->name = $r->name;
    $insert->jk = $r->jk;
    $insert->jabatan = $r->jabatan;
    do {
      $credential = (string) Str::random(35);
      $cekGuru = Guru::where('credential', $credential)->first();
      $cekSiswa = Siswa::where('credential', $credential)->first();
    } while ($cekGuru && $cekSiswa);
    $insert->credential = $credential;

    if ($insert->save()) {
      return redirect()->route('guru.index')->with('msg', 'Data berhasil disimpan');
    }
    return redirect()->back()->withErrors('Data gagal disimpan');
  }

  public function edit($uuid)
  {
    $data = [
      'title' => 'Ubah Data Guru',
      'data' => Guru::where('uuid', $uuid)->first(),
    ];
    return view('guru.edit', $data);
  }

  public function update(Request $r, $uuid)
  {
    $roles = [
      'name' => 'required',
      'jk' => 'required',
    ];
    $msgs = [
      'name.required' => 'Nama guru tidak boleh kosong',
      'jk.required' => 'Jenip kelamin guru tidak boleh kosong',
    ];

    if ($r->nip) {
      $roles['nip'] = 'required|unique:guru,nip,' . $uuid . ',uuid';
      $msgs['nip.required'] = 'NIP tidak boleh kosong';
      $msgs['nip.unique'] = 'NIP :input sudah ada';
    }

    Validator::make($r->all(), $roles, $msgs)->validate();

    $insert = Guru::where('uuid', $uuid)->first();
    $insert->nip = $r->nip ?? null;
    $insert->name = $r->name;
    $insert->jk = $r->jk;
    $insert->jabatan = $r->jabatan;

    if ($insert->save()) {
      return redirect()->route('guru.index')->with('msg', 'Data berhasil disimpan');
    }
    return redirect()->back()->withErrors('Data gagal disimpan');
  }

  public function destroy($uuid)
  {
    $delete = Guru::where('uuid', $uuid)->first();
    if ($delete->delete()) {
      $delete->screen()->delete();
      return redirect()->route('guru.index')->with('msg', 'Data berhasil dihapus');
    }
    return redirect()->route('guru.index')->with('msg', 'Data gagal dihapus');
  }

  public function import(Request $r)
  {
    if ($r->hasFile('excel') && $r->file('excel')->isValid() && $r->excel->extension() == 'txt') {
      $handle = fopen($r->excel->path(), 'r');
      $h = fgetcsv($handle, 1000, ',');

      if (!in_array('nip', $h) || !in_array('nama', $h) || !in_array('jenis_kelamin', $h) || !in_array('jabatan', $h)) {
        return redirect()->route('guru.index')->withErrors('Format tidak valid');
      }

      $errors = 0;
      $new = 0;
      while (!feof($handle)) {
        $data = fgetcsv($handle, 1000, ',');
        if ($data) {
          try {
            $insert = null;

            if ($data[array_search('nip', $h)]) {
              $insert = Guru::where('nip', $data[array_search('nip', $h)])->first();
              if (!$insert) {
                $insert = new Guru;
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
              $insert = new Guru;
              $insert->uuid = (string) Str::uuid();
              do {
                $credential = (string) Str::random(35);
                $cekGuru = Guru::where('credential', $credential)->first();
                $cekSiswa = Siswa::where('credential', $credential)->first();
              } while ($cekGuru && $cekSiswa);
              $insert->credential = $credential;
              $new++;
            }

            $insert->nip = $data[array_search('nip', $h)] ?? null;
            $insert->name = $data[array_search('nama', $h)];
            $insert->jk = $data[array_search('jenis_kelamin', $h)];
            $insert->jabatan = $data[array_search('jabatan', $h)];
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
        return redirect()->route('guru.index')->with('msg', 'Data berhasil diimpor: ' . $new);
      }
      return redirect()->route('guru.index')->withErrors('Data gagal diimpor: ' . $errors);
    }
    return redirect()->route('guru.index')->withErrors('Data tidak valid');
  }

  public function printCard()
  {
    $data = Guru::all();

    $data = [
      'title' => 'Kartu Guru & Tenaga Pendidik',
      'data' => $data,
    ];

    $params = [
      'subject' => $data['title']
    ];

    $pdf = PDF::loadView('guru.kartu', $data, [], $params);
    return $pdf->stream($data['title'] . '.pdf');
  }
}
