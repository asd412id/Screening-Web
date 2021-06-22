@extends('layouts.master')
@section('content')
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-6 col-sm-12">
        <div class="card card-info">
          <div class="card-header">
            <h3 class="card-title">Pengaturan Profil</h3>
          </div>
          <form role="form" method="post">
            @csrf
            <div class="card-body pb-0">
              <div class="form-group">
                <label for="name">Nama</label>
                <input type="text" name="name" class="form-control" id="name" placeholder="Nama User" value="{{ old('name')??$data->name }}" autofocus>
              </div>
              <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" class="form-control" id="username" placeholder="Username" value="{{ $data->username }}">
              </div>
              <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" class="form-control" id="password" placeholder="Kosongkan jika tidak ingin mengubah password">
              </div>
              <div class="form-group">
                <label for="password_confirmation">Ulang Password</label>
                <input type="password" name="password_confirmation" class="form-control" id="password_confirmation" placeholder="Kosongkan jika tidak ingin mengubah password">
              </div>
            </div>
            <div class="card-footer">
              <button type="submit" class="btn btn-info">Simpan</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
