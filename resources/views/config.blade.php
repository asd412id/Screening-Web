@extends('layouts.master')
@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-md-6 col-sm-12">
      <div class="card card-info">
        <div class="card-header">
          <h3 class="card-title">Pengaturan Sistem</h3>
        </div>
        <form role="form" method="post">
          @csrf
          <div class="card-body pb-0">
            <div class="form-group">
              <label for="kop">Kop Teks</label>
              <textarea name="kop" id="kop" cols="30" rows="5" class="form-control"
                placeholder="Kop Teks">{{ old('kop')??(@$data->kop) }}</textarea>
            </div>
            <div class="form-group">
              <label for="ins_name">Nama Instansi</label>
              <input type="text" name="ins_name" class="form-control" id="ins_name" placeholder="Nama Instansi"
                value="{{ old('ins_name')??(@$data->ins_name) }}">
            </div>
            <div class="form-group">
              <label for="ins_addr">Alamat Instansi</label>
              <textarea name="ins_addr" id="ins_addr" cols="30" rows="5" class="form-control"
                placeholder="Alamat Instansi">{{ old('ins_addr')??(@$data->ins_addr) }}</textarea>
            </div>
            <div class="form-group">
              <label for="tahun_pelajaran">Tahun Pelajaran</label>
              <input type="text" name="tahun_pelajaran" class="form-control" id="tahun_pelajaran"
                placeholder="Tahun Pelajaran" value="{{ old('tahun_pelajaran')??(@$data->tahun_pelajaran) }}">
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="kepsek">Kepala Sekolah</label>
                  <input type="text" name="kepsek" class="form-control" id="kepsek" placeholder="Nama Kepala Sekolah"
                    value="{{ old('kepsek')??(@$data->kepsek) }}">
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="kepsek_nip">NIP Kepala Sekolah</label>
                  <input type="text" name="kepsek_nip" class="form-control" id="kepsek_nip"
                    placeholder="NIP Kepala Sekolah" value="{{ old('kepsek_nip')??(@$data->kepsek_nip) }}">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="satgas">Ketua Satgas</label>
                  <input type="text" name="satgas" class="form-control" id="satgas" placeholder="Nama Ketua Satgas"
                    value="{{ old('satgas')??(@$data->satgas) }}">
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="satgas_nip">NIP Ketua Satgas</label>
                  <input type="text" name="satgas_nip" class="form-control" id="satgas_nip"
                    placeholder="NIP Ketua Satgas" value="{{ old('satgas_nip')??(@$data->satgas_nip) }}">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="petugas">Petugas</label>
                  <input type="text" name="petugas" class="form-control" id="petugas" placeholder="Nama Petugas"
                    value="{{ old('petugas')??(@$data->petugas) }}">
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="petugas_nip">NIP Petugas</label>
                  <input type="text" name="petugas_nip" class="form-control" id="petugas_nip" placeholder="NIP Petugas"
                    value="{{ old('petugas_nip')??(@$data->petugas_nip) }}">
                </div>
              </div>
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