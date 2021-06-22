<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-info navbar-dark">
  <!-- Left navbar links -->
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
    </li>
  </ul>

  <form class="form-inline ml-auto">
    <div class="input-group input-group-sm">
      <input class="form-control form-control-navbar" type="search" placeholder="Pencarian Data" aria-label="Search" name="s" value="{{ request()->s }}" autocomplete="off">
      <div class="input-group-append">
        <button class="btn btn-navbar" type="submit">
          <i class="fas fa-search"></i>
        </button>
      </div>
    </div>
  </form>

  <!-- Right navbar links -->
  <ul class="navbar-nav ml-auto">
    <!-- Notifications Dropdown Menu -->
    <li class="nav-item dropdown">
      <a class="nav-link" data-toggle="dropdown" href="#">
        <i class="fas fa-cogs"></i>
      </a>
      <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
        <a href="{{ route('profile') }}" class="dropdown-item">
          <i class="fas fa-user mr-2"></i> Pengaturan Profil
        </a>
        <div class="dropdown-divider"></div>
        <a href="{{ route('logout') }}" class="dropdown-item">
          <i class="fas fa-sign-out-alt mr-2"></i> Keluar
        </a>
      </div>
    </li>
  </ul>
</nav>
<!-- /.navbar -->
