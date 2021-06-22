<aside class="main-sidebar sidebar-dark-info elevation-4">
  <a href="{{ route('dashboard') }}" class="d-block brand-link navbar-info">
    <img src="/assets/img/app_icon.png"
    alt="{{ getenv('APP_NAME') }} Logo"
    class="brand-image img-circle elevation-2"
    style="opacity: .8">
    <span class="brand-text font-weight-light">Halaman Admin</span>
  </a>

  <div class="sidebar">
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image">
        <img src="/assets/img/avatar.png" class="img-circle" alt="User Image">
      </div>
      <div class="info">
        <a href="{{ route('profile') }}" class="d-block">{{ $user->name }}</a>
      </div>
    </div>
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent nav-legacy" data-widget="treeview" role="menu" data-accordion="false">
        <li class="nav-item">
          <a href="{{ route('dashboard') }}" class="nav-link {{ (\Request::url() == route('dashboard')?'active':'') }}">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>
              Beranda
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ route('kegiatan.index') }}" class="nav-link {{ (strpos(\Request::url(),route('kegiatan.index'))!==false?'active':'') }}">
            <i class="fas fa-tasks nav-icon"></i>
            <p>Kegiatan</p>
          </a>
        </li>
        @php
          $cmenu = '';
          $cmenua = '';
          if (strpos(\Request::url(),route('kelas.index'))!==false
              || strpos(\Request::url(),route('siswa.index'))!==false
              || strpos(\Request::url(),route('guru.index'))!==false
              )
          {
            $cmenu = ' menu-open';
            $cmenua = ' active';
          }
        @endphp
        <li class="nav-item has-treeview{{ $cmenu }}">
          <a href="#" class="nav-link{{ $cmenua }}">
            <i class="nav-icon fas fa-box"></i>
            <p>
              Master Data
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ route('kelas.index') }}" class="nav-link {{ (strpos(\Request::url(),route('kelas.index'))!==false?'active':'') }}">
                <i class="fas fa-door-open nav-icon"></i>
                <p>Kelas</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('siswa.index') }}" class="nav-link {{ (strpos(\Request::url(),route('siswa.index'))!==false?'active':'') }}">
                <i class="fas fa-users nav-icon"></i>
                <p>Siswa</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('guru.index') }}" class="nav-link {{ (strpos(\Request::url(),route('guru.index'))!==false?'active':'') }}">
                <i class="fas fa-user-graduate nav-icon"></i>
                <p>Guru</p>
              </a>
            </li>
          </ul>
        </li>
        <li class="nav-item">
          <a href="{{ route('config') }}" class="nav-link {{ (strpos(\Request::url(),route('config'))!==false?'active':'') }}">
            <i class="fas fa-wrench nav-icon"></i>
            <p>Pengaturan</p>
          </a>
        </li>
      </ul>
    </nav>
  </div>
</aside>
