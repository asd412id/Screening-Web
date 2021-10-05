@php($configs = App\Models\Config::configs());
<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <meta charset="utf-8">
  <title>{!! $title !!}</title>
  @include('print-style')
  <style>
    html,
    body {
      background: none;
    }

    .no-border {
      border: none !important;
    }

    .detail {
      width: 100%;
    }

    .detail .info {
      text-transform: uppercase;
    }

    .detail .info td {
      vertical-align: top;
    }

    table td,
    table tr {
      background: transparent;
    }

    @page {
      margin: 10px;
    }
  </style>
</head>

<body>
  <table class="table" cellpadding="0" cellspacing="0">
    @php ($i = 1)
    @foreach ($data as $key => $p)
    @if ($i == 5)
    @php ($i = 1)
    </tr>
    @endif
    @if ($i == 1)
    <tr>
      @endif
      @php ($i++)
      <td style="padding: 1px">
        <table>
          <tr>
            <td valign="top"
              style="height: 394px;width: 305px;background-image-resize: 6;background: url({{asset('assets/img/kartu_petugas.jpg')}});position: relative">
              <table style="width: 100%;margin-top: 58px">
                <tr>
                  <td
                    style="text-align: center;color: #fff;text-shadow: 1px 1px 3px #000;line-height: 1.05em;padding-bottom: 30px">
                    <span
                      style="font-size: 10pt;color: #f3be09;font-family: 'Bookman Old Style'">{{ @$configs->kop_kartu_petugas??'KARTU PETUGAS' }}</span><br>
                    <span
                      style="font-size: 10.7pt;font-family: 'Bookman Old Style'">{{ @$configs->ins_name??'Nama Sekolah' }}</span><br>
                    <em
                      style="font-size: 8pt;color: yellow;text-shadow: 1px 1px 3px #000;text-align: center;font-family: 'Times New Roman', Times, serif;text-transform: uppercase">Tahun
                      Pelajaran {{ @$configs->tahun_pelajaran??'2021/2022' }}</em>
                  </td>
                </tr>
                <tr>
                  <td style="text-align: center;padding-left: 16px">
                    <img
                      src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(85)->generate($p->credential)) !!}"
                      alt="" style="transform: rotate(45deg)">
                  </td>
                </tr>
                <tr>
                  <td style="text-align: center;padding-top: 30px">
                    <h4 style="font-weight: bold;{{ strlen($p->name)>28?'font-size: 1.05em':'' }}">{{ $p->name }}</h4>
                    @if ($p->nip)
                    <em>NIP. {{ $p->nip }}</em>
                    @else
                    <em>&nbsp;</em>
                    @endif
                  </td>
                </tr>
                <tr>
                  <td style="padding-top: {{ strlen($p->name)>28?'29px':'25px' }}">
                    <table style="width: 100%">
                      <tr>
                        <td style="text-align: center">
                          <h5><em>&nbsp;</em></h5>
                          <h4 style="{{ strlen($p->jabatan)>30?'font-size: 1em':'' }}">{{ $p->jabatan }}</h4>
                        </td>
                      </tr>
                    </table>
                  </td>
                </tr>
                <tr>
                <tr>
                  <td
                    style="text-align: center;color: #fff;text-shadow: 1px 1px 1px #000;padding-top: {{ strlen($p->jabatan)>30?'14px':'26px' }}">
                    <em style="font-size: 8pt">&copy; {{ date('Y').' '.@$configs->ins_name }}</em>
                  </td>
                </tr>
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