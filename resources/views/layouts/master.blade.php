@php
$user = auth()->user();
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>{{ $title.' - '.getenv('APP_NAME') }}</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="_token" content="{{ csrf_token() }}">
  <meta name="nodeport" content="{{ env('NODE_PORT', 3001) }}">
  <link rel="icon" href="/assets/img/app_icon.png" type="image/x-icon"/>
  <link rel="shortcut icon" href="/assets/img/app_icon.png" type="image/x-icon"/>
  <link rel="stylesheet" href="/assets/plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="/assets/fonts/ionicicon/css/ionicons.min.css">
  <link rel="stylesheet" href="/assets/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <link rel="stylesheet" href="/assets/plugins/select2/css/select2.min.css">
  <link rel="stylesheet" href="/assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <link rel="stylesheet" href="/assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
  <link rel="stylesheet" href="/assets/plugins/toastr/toastr.min.css">
  <link rel="stylesheet" href="/assets/css/adminlte.min.css">
  <link href="/assets/fonts/sourcesanspro/stylesheet.css" rel="stylesheet">
  <link rel="stylesheet" href="/assets/css/styles.min.css">
  @yield('head')
</head>
<body class="hold-transition sidebar-mini layout-fixed accent-info">
  <div class="wrapper">
    @include('layouts.navbar')
    @include('layouts.sidebar')
    <div class="content-wrapper">
      <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-12">
              <h1>{{ $title }}</h1>
            </div>
          </div>
      </section>

      <section class="content">
        @yield('content')
      </section>
    </div>

    <footer class="main-footer">
      <div class="float-right d-none d-sm-block">
        <b>Version</b> 1.0.0
      </div>
      <strong>Copyright &copy; {{ date('Y') }} <a href="https://www.facebook.com/aezdar">asd412id</a>.</strong> All rights reserved.
    </footer>

  </div>

  <script src="/assets/plugins/jquery/jquery.min.js"></script>
  <script src="/assets/plugins/moment/moment.min.js"></script>
  <script src="/assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="/assets/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
  <script src="/assets/plugins/select2/js/select2.full.min.js"></script>
  <script src="/assets/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
  <script src="/assets/plugins/toastr/toastr.min.js"></script>
  <script src="/assets/js/adminlte.min.js"></script>
  @yield('beforescripts')
  <script src="/assets/js/scripts.js"></script>
  @yield('foot')
  @if ($errors->any())
  <script>
    @foreach ($errors->all() as $e)
    toastr.error('{{ $e }}')
    @endforeach
  </script>
  @endif
  @if (session()->has('msg'))
  <script>
    toastr.success('{{ session()->get('msg') }}')
  </script>
  @endif
</body>
</html>
