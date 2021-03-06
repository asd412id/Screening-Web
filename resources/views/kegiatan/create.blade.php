@extends('layouts.master')
@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-md-6 col-sm-12">
      <div class="card card-info">
        <div class="card-header">
          <h3 class="card-title">{{ $title }}</h3>
        </div>
        <form role="form" method="post">
          @csrf
          <div class="card-body pb-0">
            <div class="form-group">
              <label for="tanggal">Tanggal Kegiatan</label>
              <div class="input-group date datepicker" id="tanggal" data-target-input="nearest">
                <input type="text" class="form-control" onclick="$(this).parent().find('.input-group-append').click()"
                  name="tanggal" value="{{ old('tanggal')??date('d-m-Y') }}" autocomplete="off"
                  placeholder="Tanggal Kegiatan" />
                <div class="input-group-append" data-target="#tanggal" data-toggle="datetimepicker">
                  <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                </div>
              </div>
            </div>
            <div class="form-group">
              <label for="name">Nama Kegiatan</label>
              <input type="text" name="name" class="form-control" id="name" placeholder="Nama Kegiatan"
                value="{{ old('name') }}">
            </div>
            <div class="form-group">
              <label for="tahun_pelajaran">Tahun Pelajaran</label>
              <input type="text" name="tahun_pelajaran" class="form-control" id="tahun_pelajaran"
                placeholder="Tahun Pelajaran" value="{{ old('tahun_pelajaran') }}">
            </div>
            <div class="form-group">
              <label for="semester">Semester</label>
              <select name="semester" id="semester" class="form-control">
                <option {{ old('semester')==1?'selected':'' }} value="1">I (Ganjil)</option>
                <option {{ old('semester')==2?'selected':'' }} value="2">II (Genap)</option>
              </select>
            </div>
            <div class="form-group">
              <label for="max_temp">Temperatur Maksimal</label>
              <input type="number" step="0.01" name="max_temp" class="form-control" id="max_temp"
                placeholder="Temperatur Maksimal" value="{{ old('max_temp') }}">
            </div>
            <div class="form-group">
              <label for="petugas_name">Nama Petugas</label>
              <select name="petugas" id="petugas_name" class="form-control select2-ajax"
                data-placeholder="Pilih Petugas Screening" data-url="{{ route('ajax.petugas') }}"></select>
            </div>
          </div>
          <div class="card-footer">
            <button type="submit" class="btn btn-info">Simpan</button>
            <a href="{{ route('kegiatan.index') }}" class="btn btn-default">Batal</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection