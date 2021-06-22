<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>{!! $title !!}</title>
    @include('print-style')
    <style>
    html,body{
      background: none;
    }
    .card{
      border-collapse: collapse;
      border: solid 1px #000;
      page-break-inside: avoid !important;
      font-size: 0.6em !important;
      width: 100%;
    }
    .no-border{
      border: none !important;
    }
    .detail{
      width: 100%;
    }
    .detail .info{
      text-transform: uppercase;
    }
    .detail .info td{
      vertical-align: top;
    }
    @page{
      margin: 20px;
    }
    </style>
  </head>
  <body>
    <table class="table" cellpadding="0" cellspacing="0">
      @php ($i = 1)
      @foreach ($data as $key => $p)
        @if ($i == 3)
          @php ($i = 1)
          </tr>
        @endif
        @if ($i == 1)
          <tr>
        @endif
        @php ($i++)
          <td style="padding: 5px">
            <table class="card" border="1">
              <tr>
                <td>
                  <table class="detail" style="margin-bottom: 30px;margin-top: 20px">
                    <tr>
                      <td colspan="4" class="text-center" style="font-weight: bold;font-size: 1.3em;padding-top: 5px;padding-bottom: 20px">{{ strtoupper($title) }}</td>
                    </tr>
                    <tr class="info">
                      <td style="width: 100px;padding-left: 15px;padding-top: 5px">NIS</td>
                      <td style="width: 10px;padding-top: 5px">:</td>
                      <td style="font-weight:  bold;padding-top: 5px">{{ $p->nis??'-' }}</td>
                      <td rowspan="4" style="text-align: right; padding: 15px;padding-right: 30px">
                        <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(85)->generate($p->credential)) !!}" alt="">
                      </td>
                    </tr>
                    <tr class="info">
                      <td style="width: 100px;padding-left: 15px">NAMA</td>
                      <td style="width: 10px">:</td>
                      <td style="font-weight:  bold">{{ $p->name }}</td>
                    </tr>
                    <tr class="info">
                      <td style="width: 100px;padding-left: 15px">JENIS KELAMIN</td>
                      <td style="width: 10px">:</td>
                      <td style="font-weight:  bold">{{ $p->jk=='L'?'LAKI-LAKI':'PEREMPUAN' }}</td>
                    </tr>
                    <tr class="info">
                      <td style="width: 100px;padding-left: 15px">KELAS</td>
                      <td style="width: 10px">:</td>
                      <td style="font-weight:  bold">{{ $p->kelas->name }}</td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>
          </td>
        @endforeach
    </table>
  </body>
</html>
