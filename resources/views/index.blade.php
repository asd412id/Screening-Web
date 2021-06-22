@extends('layouts.master')
@section('content')
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-4 col-12">
        <div class="small-box bg-info">
          <div class="inner">
            <h3>{{ $siswa }}</h3>
            <p>Jumlah Siswa</p>
          </div>
          <div class="icon">
            <i class="fas fa-users"></i>
          </div>
        </div>
      </div>
      <div class="col-lg-4 col-12">
        <div class="small-box bg-success">
          <div class="inner">
            <h3>{{ $guru }}</h3>
            <p>Jumlah Guru</p>
          </div>
          <div class="icon">
            <i class="fas fa-user-graduate"></i>
          </div>
        </div>
      </div>
      <div class="col-lg-4 col-12">
        <div class="small-box bg-warning">
          <div class="inner">
            <h3>{{ $kegiatan }}</h3>
            <p>Jumlah Kegiatan</p>
          </div>
          <div class="icon">
            <i class="fas fa-tasks"></i>
          </div>
        </div>
      </div>
      <div class="col-lg-4 col-12">
        <div class="small-box bg-danger">
          <div class="inner">
            <h3>{{ $screen }}</h3>
            <p>Jumlah Screening Hari ini</p>
          </div>
          <div class="icon">
            <i class="fas fa-search"></i>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
