<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>{{ $title }}</title>
  @include('print-style')
  <style>
    @page{
      margin: 25px 45px;
    }
    .font-weight-bold{
      font-weight: bold;
    }
    #kop td, #kop th{
      padding: 0;
    }
    #kop-sep{
      margin-top: 3px;
      border-top: solid 3px #000;
      border-bottom: solid 1px #000;
      padding: 1px 0;
    }
    table td, table th,table td, table th{
      padding: 3px 7px;
      font-size: 15px;
    }
    .table-head{
      vertical-align: bottom;
    }
    .table-list td{
      padding: 7px;
    }
    .table-list th{
      padding: 3px 7px;
    }
    .table-list td, .table-list th{
      border-color: #000;
    }
    .table-list td{
      vertical-align: top;
    }
    .text-danger td{
      color: red !important;
    }
  </style>
</head>
<body>
  @include('kop')
  @yield('content')
</body>
</html>