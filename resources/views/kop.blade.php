<table style="width: 100%" id="kop">
  <tr>
    <td align="center">
      <img src="{{ url('assets/img/app_icon.png') }}" alt="" style="display: inline;max-height: 65px"></td>
  </tr>
  <tr>
    <td align="center">
      @foreach (@explode("\n",@$config->kop) as $item)
        <h3 class="font-weight-bold" style="margin: 0;padding: 0 115px;font-size: 1.2em">{{ $item }}</h3>
      @endforeach
    </td>
  </tr>
  <tr>
    <td align="center">
      <h3 class="font-weight-bold" style="margin: 0;padding: 0 115px;font-size: 1.35em">{{ strtoupper(@$config->ins_name) }}</h3>
    </td>
  </tr>
  <tr>
    <td align="center">
      <em>
        {!! nl2br(@$config->ins_addr) !!}
      </em>
    </td>
  </tr>
</table>
<div id="kop-sep"></div>