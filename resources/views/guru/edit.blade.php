@extends('layouts.master')
@section('content')
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-6 col-sm-12">
        <div class="card card-info">
          <div class="card-header">
            <h3 class="card-title">{{ $title }}</h3>
          </div>
          <form role="form" method="post" action="{{ route('guru.update', ['uuid'=>$data->uuid]) }}">
            @csrf
            <div class="card-body pb-0">
              <div class="form-group">
                <label for="nip">NIP</label>
                <input type="text" name="nip" class="form-control" id="nip" placeholder="Nomor Induk Pegawai" value="{{ old('nip')??$data->nip }}" autofocus>
              </div>
              <div class="form-group">
                <label for="name">Nama Guru</label>
                <input type="text" name="name" class="form-control" id="name" placeholder="Nama Guru" value="{{ old('name')??$data->name }}" required>
              </div>
              <div class="form-group">
                <label for="jk">Jenis Kelamin</label>
                <select name="jk" id="jk" class="form-control" required>
                  @php($jk = ['L'=>'Laki-Laki','P'=>'Perempuan']);
                  @foreach ($jk as $k => $v)
                    <option {{ ($k==old('jk')||$k==$data->jk)?'selected':'' }} value="{{ $k }}">{{ $v }}</option>
                  @endforeach
                </select>
              </div>
              <div class="form-group">
                <label for="jabatan">Jabatan</label>
                <input type="text" name="jabatan" class="form-control" id="jabatan" placeholder="Nama Jabatan" value="{{ old('jabatan')??$data->jabatan }}" required>
              </div>
            </div>
            <div class="card-footer">
              <button type="submit" class="btn btn-info">Simpan</button>
              <a href="{{ route('guru.index') }}" class="btn btn-default">Batal</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
