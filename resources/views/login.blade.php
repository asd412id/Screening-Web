<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>{{ $title.' - '.getenv('APP_NAME') }}</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" href="/assets/img/app_icon.png" type="image/x-icon"/>
  <link rel="shortcut icon" href="/assets/img/app_icon.png" type="image/x-icon"/>
  <link rel="stylesheet" href="/assets/plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="/assets/fonts/ionicicon/css/ionicons.min.css">
  <link rel="stylesheet" href="/assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <link rel="stylesheet" href="/assets/plugins/toastr/toastr.min.css">
  <link rel="stylesheet" href="/assets/css/adminlte.min.css">
  <link rel="stylesheet" href="/assets/css/styles.min.css">
  <link href="/assets/fonts/sourcesanspro/stylesheet.css" rel="stylesheet">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <b class="text-blue">Selamat</b> <span class="text-red">Datang</span>
  </div>
  <div class="card">
    <div class="card-body login-card-body">
      <em class="d-block text-center">{{ \Carbon\Carbon::now()->translatedFormat('l, j F Y') }}</em>
      <p class="login-box-msg mb-1 pb-1">Masuk untuk Mengelola Sistem</p>
      <form {{ route('login.process') }} method="post">
        @csrf
        <div class="input-group mb-3">
          <input type="username" class="form-control" placeholder="Username" name="username" autofocus required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control" placeholder="Password" name="password" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-8">
            <div class="icheck-primary">
              <input type="checkbox" id="remember" name="remember" value="true">
              <label for="remember">
                Ingat Saya
              </label>
            </div>
          </div>
          <div class="col-4">
            <button type="submit" class="btn btn-primary btn-block">Masuk</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="/assets/plugins/jquery/jquery.min.js"></script>
<script src="/assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="/assets/plugins/toastr/toastr.min.js"></script>
<script src="/assets/js/adminlte.min.js"></script>
<script src="/assets/js/scripts.js"></script>
@if ($errors->any())
<script>
  toastr.options.positionClass = 'toast-top-center mt-5';
  @foreach ($errors->all() as $e)
  toastr.error('{{ $e }}');
  @endforeach
</script>
@endif
@if (session()->has('msg'))
  <script>
    toastr.options.positionClass = 'toast-top-center mt-5';
    toastr.success('{{ session()->get('msg') }}');
  </script>
@endif
</body>
</html>
