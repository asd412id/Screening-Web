<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Models\Kelas;
use App\Models\Config;
use Validator;
use Str;
use PDF;

class KelasController extends Controller
{
  public function index(Request $r)
  {
    $lists = Kelas::when($r->s,function($q,$s){
      $q->where('name','like',"%$s%");
    })
    ->paginate(10)
    ->appends($r->all());
    $data = [
      'title' => 'Data Master Kelas',
      'data' => $lists
    ];

    return view('kelas.index',$data);
  }

  public function create(Request $r)
  {
    $data = [
      'title' => 'Data Kelas Baru',
    ];
    return view('kelas.create',$data);
  }

  public function store(Request $r)
  {
    $roles = [
      'name' => 'required|unique:kelas,name',
    ];
    $msgs = [
      'name.required' => 'Nama kelas tidak boleh kosong',
      'name.unique' => 'Data kelas :input sudah ada',
    ];
    Validator::make($r->all(),$roles,$msgs)->validate();

    $insert = new Kelas;
    $insert->uuid = (string) Str::uuid();
    $insert->name = $r->name;

    if ($insert->save()) {
      return redirect()->route('kelas.index')->with('msg','Data berhasil disimpan');
    }
    return redirect()->back()->withErrors('Data gagal disimpan');
  }

  public function edit($uuid)
  {
    $data = [
      'title' => 'Ubah Data Kelas',
      'data' => Kelas::where('uuid',$uuid)->first(),
    ];
    return view('kelas.edit',$data);
  }

  public function update(Request $r,$uuid)
  {
    $roles = [
      'name' => 'required|unique:kelas,name,'.$uuid.',uuid',
    ];
    $msgs = [
      'name.required' => 'Nama kelas tidak boleh kosong',
      'name.unique' => 'Data kelas :input sudah ada',
    ];
    Validator::make($r->all(),$roles,$msgs)->validate();

    $insert = Kelas::where('uuid',$uuid)->first();
    $insert->name = $r->name;

    if ($insert->save()) {
      return redirect()->route('kelas.index')->with('msg','Data berhasil disimpan');
    }
    return redirect()->back()->withErrors('Data gagal disimpan');
  }

  public function destroy($uuid)
  {
    $delete = Kelas::where('uuid',$uuid)->first();
    if ($delete->delete()) {
      return redirect()->route('kelas.index')->with('msg','Data berhasil dihapus');
    }
    return redirect()->route('kelas.index')->with('msg','Data gagal dihapus');
  }

  public function printCard($uuid)
  {
    $siswa = Kelas::where('uuid', $uuid)->first()->siswa;

    $data = [
      'title' => 'Kartu Siswa',
      'data' => $siswa,
    ];

    $params = [
      'subject' => $data['title']
    ];

    $pdf = PDF::loadView('siswa.kartu', $data, [], $params);
    return $pdf->stream($data['title'] . '.pdf');
  }
}
