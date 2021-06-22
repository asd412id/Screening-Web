@extends('layouts.master')
@section('content')
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-6 col-sm-12">
        <div class="card card-info">
          <div class="card-header">
            <h3 class="card-title">{{ $title }}</h3>
          </div>
          <form role="form" method="post" action="{{ route('siswa.update',['uuid'=>$data->uuid]) }}">
            @csrf
            <div class="card-body pb-0">
              <div class="form-group">
                <label for="nis">NIS</label>
                <input type="text" name="nis" class="form-control" id="nis" placeholder="Nomor Induk Siswa" value="{{ old('nis')??$data->nis }}" autofocus required>
              </div>
              <div class="form-group">
                <label for="name">Nama Siswa</label>
                <input type="text" name="name" class="form-control" id="name" placeholder="Nama Siswa" value="{{ old('name')??$data->name }}" required>
              </div>
              <div class="form-group">
                <label for="jk">Jenis Kelamin</label>
                <select name="jk" id="jk" class="form-control" required>
                  @php($jk = ['L'=>'Laki-Laki','P'=>'Perempuan']);
                  @foreach ($jk as $k => $v)
                    <option {{ ($k==old('jk') || $k==$data->jk)?'selected':'' }} value="{{ $k }}">{{ $v }}</option>
                  @endforeach
                </select>
              </div>
              <div class="form-group">
                <label for="kelas">Kelas</label>
                <select name="kelas" id="kelas" class="form-control" required>
                  @foreach ($kelas as $v)
                    <option {{ ($v->id==old('kelas') || $v->id==$data->kelas_id)?'selected':'' }} value="{{ $v->id }}">{{ $v->name }}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="card-footer">
              <button type="submit" class="btn btn-info">Simpan</button>
              <a href="{{ route('siswa.index') }}" class="btn btn-default">Batal</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
