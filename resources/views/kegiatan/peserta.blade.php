@extends('layouts.master')
@section('content')
<input type="hidden" id="kid" value="{{ $kid }}">
<div class="container-fluid">
  <div class="row">
    <div class="col-12">
      <div class="row">
        <div class="col-md-6">
          <div id="search-wrap" class="mb-2">
            <input type="search" id="search" class="form-control" placeholder="Cari Peserta" autocomplete="off"
              autofocus>
            <ul id="search-result"></ul>
          </div>
        </div>
        <div class="col-md-6">
          <form class="form-inline" action="{{ route('kegiatan.peserta.print.kelas', ['uuid'=>$kuuid]) }}"
            method="post">
            @csrf
            <div class="form-group">
              <select name="role" id="role" class="form-control">
                <option value="all">Semua Peserta</option>
                <option value="guru">Guru & Tenaga Kependidikan</option>
                <option value="siswa">Siswa</option>
              </select>
            </div>
            <div class="form-group ml-md-2">
              <select name="kelas" id="kelas" class="form-control">
                <option value="all">Semua Kelas</option>
                @foreach ($kelas as $v)
                <option value="{{ $v->uuid }}">Kelas {{ $v->name }}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group ml-2">
              <button type="submit" class="btn btn-default"><i class="fas fa-file-pdf text-red"></i> Download</button>
            </div>
          </form>
        </div>
      </div>
      <div class="card">
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-hover">
              <thead>
                <th width="10">No.</th>
                <th>Id Peserta (NIS/NIP)</th>
                <th>Nama Peserta</th>
                <th>Status</th>
                <th>Jenis Screening</th>
                <th>Prokes</th>
                <th>Suhu</th>
                <th>Kondisi</th>
                <th width="10"></th>
              </thead>
              <tbody>
                @if (count($data))
                @foreach ($data as $key => $v)
                <tr class="{{ $v->status?'bg-pulang':'bg-datang' }}">
                  <td>{{ ($key+1).'.' }}</td>
                  <td>{{ $v->{$v->role}->nis??$v->{$v->role}->nip }}</td>
                  <td>{{ $v->{$v->role}->name }}</td>
                  <td>{{ ucfirst($v->role) }}</td>
                  <td>{{ !$v->status?'Datang':'Pulang' }}</td>
                  <td class="{{ !$v->prokes?'text-red':'' }}">{{ $v->prokes?'Ya':'Tidak' }}</td>
                  <td class="{{ $v->suhu>=$max_temp?'text-red':'' }}">{{ $v->suhu && $v->suhu != 0? $v->suhu : '-' }}
                  </td>
                  <td>{{ $v->kondisi??'-' }}</td>
                  <td>
                    <div class="btn-group btn-group-sm">
                      <a href="javascript:void(0)" onclick="clickTrigger(this)" data-id="{{ $v->peserta_id }}"
                        data-pid="{{ $v->{$v->role}->nis??$v->{$v->role}->nip }}" data-name="{{ $v->{$v->role}->name }}"
                        data-role="{{ $v->role }}" data-status="{{ $v->status }}" class="btn btn-warning"
                        title="Ubah Data"><i class="fas fa-edit"></i></a>
                      <a href="{{ route('kegiatan.screen.destroy',['uuid'=>$kuuid,'suuid'=>$v->uuid]) }}"
                        class="confirm btn btn-danger" title="Hapus Data" data-text="Yakin ingin menghapus data ini?"><i
                          class="fas fa-trash-alt"></i></a>
                    </div>
                  </td>
                </tr>
                @endforeach
                @else
                <tr>
                  <td colspan="8" class="text-center">
                    {{ isset(request()->cari)?'Data tidak ditemukan':'Data tidak tersedia' }}</td>
                </tr>
                @endif
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
@section('beforescripts')
<script id="socket" src="{{ asset('assets/js/socket.io.js') }}"></script>
<script>
  var audio = new Audio('/assets/sounds/audio.mp3');
</script>
@endsection
@section('foot')
<div id="modal-peserta" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Screening Peserta</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body pb-0"></div>
    </div>
  </div>
</div>
@endsection