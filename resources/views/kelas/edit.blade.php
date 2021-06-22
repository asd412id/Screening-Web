@extends('layouts.master')
@section('content')
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-6 col-sm-12">
        <div class="card card-info">
          <div class="card-header">
            <h3 class="card-title">{{ $title }}</h3>
          </div>
          <form role="form" method="post" action="{{ route('kelas.update',['uuid'=>$data->uuid]) }}">
            @csrf
            <div class="card-body pb-0">
              <div class="form-group">
                <label for="name">Nama Kelas</label>
                <input type="text" name="name" class="form-control" id="name" placeholder="Nama Kelas" value="{{ old('name')??$data->name }}" autofocus>
              </div>
            </div>
            <div class="card-footer">
              <button type="submit" class="btn btn-info">Simpan</button>
              <a href="{{ route('kelas.index') }}" class="btn btn-default">Batal</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
