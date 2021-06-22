@extends('layouts.master')
@section('content')
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div>
          <a href="{{ route('kegiatan.create') }}" class="btn btn-sm btn-info col-sm-12 col-md-2 mb-2"><i class="fas fa-plus"></i> Data Baru</a>
          <div class="clearfix"></div>
        </div>
        <div class="card">
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-hover">
                <thead>
                  <th width="10">No.</th>
                  <th>Tanggal Kegiatan</th>
                  <th>Nama Kegiatan</th>
                  <th>TP/Semester</th>
                  <th>Jumlah Peserta</th>
                  <th width="10"></th>
                </thead>
                <tbody>
                  @if (count($data))
                    @foreach ($data as $key => $v)
                      <tr>
                        <td>{{ $data->firstItem()+$key.'.' }}</td>
                        <td>{{ $v->tanggal->translatedFormat('l, j F Y')??'-' }}</td>
                        <td>{{ $v->name }}</td>
                        <td>{{ $v->tahun_pelajaran.' - '.$v->semester }}</td>
                        <td>
                          <a href="{{ route('kegiatan.peserta.print', ['uuid'=>$v->uuid,'role'=>'siswa']) }}" class="text-info"><i class="text-red fas fa-file-pdf"></i> {{ $v->screen_siswa()->distinct()->count('peserta_id').' Siswa' }}</a> |
                          <a href="{{ route('kegiatan.peserta.print', ['uuid'=>$v->uuid,'role'=>'guru']) }}" class="text-info"><i class="text-red fas fa-file-pdf"></i> {{ $v->screen_guru()->distinct()->count('peserta_id').' Guru' }}</a>
                        </td>
                        <td>
                          <div class="btn-group btn-group-sm">
                            <a href="{{ route('kegiatan.peserta',['uuid'=>$v->uuid]) }}" class="btn btn-info" title="Daftar Peserta"><i class="fas fa-users"></i></a>
                            <a href="{{ route('kegiatan.edit',['uuid'=>$v->uuid]) }}" class="btn btn-warning" title="Ubah Data"><i class="fas fa-edit"></i></a>
                            <a href="{{ route('kegiatan.destroy',['uuid'=>$v->uuid]) }}" class="confirm btn btn-danger" title="Hapus Data" data-text="Yakin ingin menghapus data ini?"><i class="fas fa-trash-alt"></i></a>
                          </div>
                        </td>
                      </tr>
                    @endforeach
                  @else
                    <tr>
                      <td colspan="6" class="text-center">{{ isset(request()->cari)?'Data tidak ditemukan':'Data tidak tersedia' }}</td>
                    </tr>
                  @endif
                </tbody>
              </table>
            </div>
            <div class="px-3 pt-3 text-center">
              <div class="text-xs d-inline-block">
                {!! $data->links() !!}
              </div>
              <div class="clearfix"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
