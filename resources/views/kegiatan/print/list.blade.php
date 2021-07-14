@extends('kegiatan.print.layouts')
@section('content')
<h4 class="text-center" style="margin-bottom: 0">DAFTAR PEMERIKSAAN</h4>
<h4 class="text-center" style="padding: 0;margin: 0">SCREENING PROKES
  {{ $role=='siswa'?'PESERTA DIDIK':($role=='guru'?'GURU DAN TENAGA KEPENDIDIKAN':'') }}</h4>
<h4 class="text-center" style="padding: 0;margin: 0">{{ strtoupper($kegiatan->name) }}</h4>

<table class="table table-head" style="margin-top: 7px">
  <tr>
    <td>
      <table>
        @if ($role=='siswa')
        <tr>
          <th>KELAS</th>
          <th>:</th>
          <th>{{ strtoupper($kelas?$kelas->name:'Semua Kelas') }}</th>
        </tr>
        @endif
        <tr>
          <th>HARI/TANGGAL</th>
          <th>:</th>
          <th>{{ strtoupper($kegiatan->tanggal->translatedFormat('l, j F Y')) }}</th>
        </tr>
      </table>
    </td>
    <td class="text-right">
      <table class="text-left">
        <tr>
          <th>TAHUN PELAJARAN</th>
          <th>:</th>
          <th>{{ strtoupper($kegiatan->tahun_pelajaran??'-') }}</th>
        </tr>
        <tr>
          <th>SEMESTER</th>
          <th>:</th>
          <th>{{ strtoupper($kegiatan->semester?($kegiatan->semester==1?'i (ganjil)':'ii (genap)'):'-') }}</th>
        </tr>
      </table>
    </td>
  </tr>
</table>
<table class="table table-bordered table-list">
  <thead>
    <tr>
      <th class="text-center" rowspan="2">NO.</th>
      <th class="text-center" rowspan="2">{{ $role=='siswa'?'NIS':($role=='guru'?'NIP':'NIP/NIS') }}</th>
      <th class="text-center" rowspan="2">NAMA</th>
      @if ($role=='siswa'&&!$kelas)
      <th class="text-center" rowspan="2">KELAS</th>
      @endif
      <th class="text-center" colspan="3">SCREENING DATANG</th>
      <th class="text-center" colspan="3">SCREENING PULANG</th>
      <th class="text-center" rowspan="2">KETERANGAN</th>
    </tr>
    <tr>
      <th class="text-center">PROKES</th>
      <th class="text-center">SUHU</th>
      <th class="text-center">KONDISI</th>
      <th class="text-center">PROKES</th>
      <th class="text-center">SUHU</th>
      <th class="text-center">KONDISI</th>
    </tr>
  </thead>
  <tbody>
    @php
    $i = 0;
    $tidak_hadir = 0;
    $tidak_memenuhi = 0;
    @endphp
    @foreach ($data as $key => $v)
    @php
    if (!@$v['datang']->suhu && !@$v['pulang']->suhu) {
    $tidak_hadir++;
    }
    if (!@$v['datang']->suhu || !@$v['pulang']->suhu ||
    @$v['datang']->suhu>=$kegiatan->max_temp||@$v['pulang']->suhu>=$kegiatan->max_temp||!@$v['datang']->prokes||!@$v['pulang']->prokes?'text-danger':'')
    {
    $tidak_memenuhi++;
    }
    @endphp
    <tr
      class="{{ @$v['datang']->suhu>=$kegiatan->max_temp||@$v['pulang']->suhu>=$kegiatan->max_temp||!@$v['datang']->prokes||!@$v['pulang']->prokes?'text-danger':'' }}">
      <td class="text-center">{{ ++$i }}.</td>
      <td>{{ @$v['pid'] }}</td>
      <td>{{ @$v['name'] }}</td>
      @if ($role=='siswa'&&!$kelas)
      <td class="text-center">{{ @$v['kelas'] }}</td>
      @endif
      <td class="text-center">
        {{ @$v['datang']->suhu?(@$v['datang']->prokes?'Ya':(!is_null(@$v['datang']->prokes)?'Tidak':'-')):'-' }}</td>
      <td class="text-center">{{ @$v['datang']->suhu && @$v['datang']->suhu != 0 ? @$v['datang']->suhu :'-' }}</td>
      <td class="text-center">{{ @$v['datang']->suhu?(@$v['datang']->kondisi??'-'):'-' }}</td>
      <td class="text-center">
        {{ @$v['pulang']->suhu?(@$v['pulang']->prokes?'Ya':(!is_null(@$v['pulang']->prokes)?'Tidak':'-')):'-' }}</td>
      <td class="text-center">{{ @$v['pulang']->suhu && @$v['pulang']->suhu != 0? @$v['pulang']->suhu : '-' }}</td>
      <td class="text-center">{{ @$v['pulang']->suhu?(@$v['pulang']->kondisi??'-'):'-' }}</td>
      <td>
        @if (@$v['datang']->keterangan)
        <strong>{{ @$v['pulang']->keterangan?'Datang: ':'' }}</strong>{{ @$v['datang']->keterangan }}
        @endif
        @if (@$v['pulang']->keterangan)
        <br>
        <strong>{{ @$v['datang']->keterangan?'Pulang: ':'' }}</strong>{{ @$v['pulang']->keterangan }}
        @endif
    </tr>
    @endforeach
  </tbody>
</table>
<table class="table" style="page-break-inside: avoid">
  <tr>
    <td>
      <table class="table">
        <tr>
          <td width="25%">
            <table>
              <tr>
                <th><em>Keterangan</em></th>
                <th colspan="2"><em>:</em></th>
              </tr>
              <tr>
                <td>Hadir</td>
                <td>:</td>
                <td>{{ count($data) - $tidak_hadir }}</td>
              </tr>
              <tr>
                <td>Tidak Hadir</td>
                <td>:</td>
                <td>{{ $tidak_hadir }}</td>
              </tr>
            </table>
          </td>
          <td>
            <table>
              <tr>
                <th colspan="3">&nbsp;</th>
              </tr>
              <tr>
                <td>Memenuhi</td>
                <td>:</td>
                <td>{{ (count($data)-$tidak_hadir)-($tidak_memenuhi-$tidak_hadir) }}</td>
              </tr>
              <tr>
                <td>Tidak Memenuhi</td>
                <td>:</td>
                <td>{{ $tidak_memenuhi-$tidak_hadir }}</td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
      <table class="table">
        <tr>
          <td style="padding-left: 14px">
            <table>
              <tr>
                <td style="padding-left: 0">Mengetahui,</td>
              </tr>
              <tr>
                <td style="padding-left: 0">Kepala Sekolah</td>
              </tr>
              <tr>
                <td style="padding: 0;padding-top: 65px;font-weight: bold;border-bottom: solid 1px #000">
                  {{ @$config->kepsek }}</td>
              </tr>
              <tr>
                <td style="padding: 0;">NIP. {{ @$config->kepsek_nip }}</td>
              </tr>
            </table>
          </td>
          <td>
            <table>
              <tr>
                <td style="padding-left: 0">Verifikasi,</td>
              </tr>
              <tr>
                <td style="padding-left: 0">Ketua Satgas Sekolah</td>
              </tr>
              <tr>
                <td style="padding: 0;padding-top: 65px;font-weight: bold;border-bottom: solid 1px #000">
                  {{ @$config->satgas }}</td>
              </tr>
              <tr>
                <td style="padding: 0;">NIP. {{ @$config->satgas_nip }}</td>
              </tr>
            </table>
          </td>
          <td>
            <table>
              <tr>
                <td style="padding-left: 0">&nbsp;</td>
              </tr>
              <tr>
                <td style="padding-left: 0">Petugas,</td>
              </tr>
              <tr>
                <td style="padding: 0;padding-top: 65px;font-weight: bold;border-bottom: solid 1px #000">
                  {{ @$kegiatan->petugas->name??@$config->petugas }}</td>
              </tr>
              <tr>
                <td style="padding: 0;">NIP. {{ @$kegiatan->petugas->nip??@$config->petugas_nip }}</td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
@endsection