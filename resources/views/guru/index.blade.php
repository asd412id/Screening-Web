@extends('layouts.master')
@section('content')
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div>
          <a href="{{ route('guru.create') }}" class="btn btn-sm btn-info col-sm-12 col-md-2 mb-2"><i class="fas fa-plus"></i> Data Baru</a>
          <a target="_blank" href="{{ route('guru.print.card') }}" class="btn btn-sm btn-default col-sm-12 col-md-2 mb-2"><i class="fas fa-print text-success"></i> Cetak Kartu</a>
          <a href="{{ route('template.download',['type'=>'guru']) }}" class="btn btn-sm btn-success col-sm-12 col-md-2 mb-2"><i class="fas fa-download"></i> Download Template</a>
          <form action="{{ route('guru.import') }}" method="post" class="d-inline" enctype="multipart/form-data">
            @csrf
            <input type="file" name="excel" id="excel" onchange="$(this).parent().submit()" class="d-none" accept=".csv">
            <button type="button" class="btn btn-sm btn-default mb-2" onclick="$(this).parent().find('input[type=file]').click()"><i class="fas fa-file-excel text-green"></i> Impor Data</button>
          </form>
          <div class="clearfix"></div>
        </div>
        <div class="card">
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-hover">
                <thead>
                  <th width="10">No.</th>
                  <th>NIP</th>
                  <th>Nama</th>
                  <th>Jenis Kelamin</th>
                  <th>Jabatan</th>
                  <th width="10"></th>
                </thead>
                <tbody>
                  @if (count($data))
                    @foreach ($data as $key => $v)
                      <tr>
                        <td>{{ $data->firstItem()+$key.'.' }}</td>
                        <td>{{ $v->nip??'-' }}</td>
                        <td>{{ $v->name??'-' }}</td>
                        <td>{{ $v->jk }}</td>
                        <td>{{ $v->jabatan??'-' }}</td>
                        <td>
                          <div class="btn-group btn-group-sm">
                            <a href="{{ route('guru.edit',['uuid'=>$v->uuid]) }}" class="btn btn-warning" title="Ubah Data"><i class="fas fa-edit"></i></a>
                            <a href="{{ route('guru.destroy',['uuid'=>$v->uuid]) }}" class="confirm btn btn-danger" title="Hapus Data" data-text="Yakin ingin menghapus data ini?"><i class="fas fa-trash-alt"></i></a>
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
