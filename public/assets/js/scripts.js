toastr.options = {
  "debug": false,
  "positionClass": "toast-top-right mt-5",
  "onclick": null,
  "fadeIn": 300,
  "fadeOut": 1000,
  "timeOut": 5000,
  "extendedTimeOut": 1000
}
if ($(".currency").length) {
  $(".currency").autoNumeric({
    aSign: 'Rp ',
    aSep: '.',
    aDec: ',',
    mDec: 0
  });
}
if ($(".daterangepicker").length > 0) {
  setTimeout(() => {
    $('.datepicker').daterangepicker({
      "locale": {
        "format": "DD/MM/YYYY",
        "separator": " - ",
        "applyLabel": "Simpan",
        "cancelLabel": "Batal",
        "fromLabel": "Dari",
        "toLabel": "Sampai",
        "customRangeLabel": "Kustom",
        "daysOfWeek": [
          "Ahd",
          "Sen",
          "Sel",
          "Rab",
          "Kam",
          "Jum",
          "Sab"
        ],
        "monthNames": [
          "Januari",
          "Februari",
          "Maret",
          "April",
          "Mei",
          "Juni",
          "Juli",
          "Agustus",
          "September",
          "Oktober",
          "Nopember",
          "Desember"
        ],
        "firstDay": 0
      }
    })
  }, 100)
}
if ($(".datepicker").length) {
  $('.datepicker').datetimepicker({
    format: 'DD-MM-YYYY'
  });
}
if ($(".select2").length > 0) {
  $(".select2").select2({
    allowClear: true
  });
}
if ($(".select2-ajax").length > 0) {
  $(".select2-ajax").each(function () {
    var _this = $(this);
    _this.off().select2({
      allowClear: true,
      minimumInputLength: 1,
      placeholder: _this.data('placeholder') ?? 'Pilih',
      ajax: {
        url: _this.data('url')
      }
    });
  });
}

var _tm;
if ($("#search").length) {
  var _sr = $("#search-result");
  $("#search").on("keyup", function () {
    var _val = $(this).val();
    _sr.empty();
    clearTimeout(_tm);
    if (_val != "") {
      _sr.html('<li>Mencari data ...</li>');
      _tm = setTimeout(() => {
        $.post(location.href + '/cari', { _token: $("meta[name=_token]").attr('content'), req: _val }, function (res) {
          if (res.length) {
            var _dr = '';
            res.forEach((v, i) => {
              _dr += `<li
                        data-id="`+ v.id + `"
                        data-pid="`+ v.pid + `"
                        data-name="`+ v.name + `"
                        data-status="`+ v.status + `"
                        data-role="`+ (v.role).toLowerCase() + `"
                        onclick="clickTrigger(this)"
                      >`+ (v.pid ? v.pid + ' - ' : '') + v.name + ` (` + v.role + `) (` + v.pos + `)</li>`;
            });
            _sr.html(_dr);
          } else {
            _sr.html('<li>Data tidak ditemukan.</li>');
          }
        }, 'json');
      }, 300);
    }
  });
}

function clickTrigger(data) {
  var _this = $(data);
  var _modal = $("#modal-peserta");
  var _loading = '<p class="text-center"><i class="fas fa-spinner fa-pulse"></i> Memuat data ...</p>';

  _modal.find(".modal-body").html(_loading);
  _modal.modal('show');

  $.get(location.href + '/screen', { id: _this.data('id'), role: _this.data('role'), kid: $("#kid").val(), status: _this.data('status') }, function (res) {
    var _form = `<form action="" method="post">
            <input type="hidden" name="_token" value="`+ $("meta[name=_token]").attr('content') + `">
            <input type="hidden" name="peserta_id" class="form-control" value="`+ _this.data('id') + `">
            <input type="hidden" name="role" class="form-control" value="`+ _this.data('role') + `">
            <div class="form-group">
              <label for="pid">`+ (_this.data('role') == 'siswa' ? 'NIS' : 'NIP') + `</label>
              <input type="text" id="pid" class="form-control" value="`+ _this.data('pid') + `" disabled>
            </div>
            <div class="form-group">
              <label for="name">Nama</label>
              <input type="text" id="name" class="form-control" value="`+ _this.data('name') + `" disabled>
            </div>
            <div class="form-group">
              <label for="jenis_screening">Jenis Screening</label>
              <select name="jenis_screening" id="jenis_screening" class="form-control">
                <option `+ ((_this.data('status') == 0 || _this.data('status') == null) ? 'selected' : '') + ` value="0">Datang</option>
                <option `+ (_this.data('status') == 1 ? 'selected' : '') + ` value="1">Pulang</option>
              </select>
            </div>
            <div class="form-group">
              <label for="prokes">Prokes</label>
              <select name="prokes" id="prokes" class="form-control">
                <option value="1">Ya</option>
                <option value="0">Tidak</option>
              </select>
            </div>
            <div class="form-group">
              <label for="kondisi">Kondisi</label>
              <input type="text" id="kondisi" name="kondisi" class="form-control" value="Baik">
            </div>
            <div class="form-group">
              <label for="suhu">Suhu</label>
              <input type="number" id="suhu" name="suhu" class="form-control" step="0.01">
            </div>
            <div class="form-group">
              <label for="keterangan">Keterangan</label>
              <textarea name="keterangan" id="keterangan" rows="4" class="form-control"></textarea>
            </div>
            <div class="form-group text-center">
              <button type="submit" class="btn btn-info">Simpan</button>
              <a href="javascript:void(0)" data-dismiss="modal" class="btn btn-secondary">Batal</a>
            </div>
          </form>`;
    _modal.find(".modal-body").html(_form);
    getScreening(_this);
    $("#jenis_screening").trigger('change');
  }, 'json');

}

function getScreening(_this) {
  $("#jenis_screening").unbind().change(function () {
    var _t = $(this);
    $("#prokes").prop('disabled', true);
    $("#kondisi").prop('disabled', true);
    $("#suhu").prop('disabled', true);
    $("#keterangan").prop('disabled', true);
    $.get(location.href + '/screen', { id: _this.data('id'), role: _this.data('role'), kid: $("#kid").val(), status: _t.val() }, function (res) {
      $("#prokes option").each(function (i, v) {
        if ($(v).attr('value') == res.prokes) {
          $(v).prop('selected', true);
        } else {
          $(v).prop('selected', false);
        }
      });
      $("#kondisi").val(res.kondisi != undefined ? res.kondisi : '');
      $("#suhu").val(res.suhu != undefined ? res.suhu : '');
      $("#keterangan").val(res.keterangan != undefined ? res.suhu : '');

      $("#prokes").prop('disabled', false);
      $("#kondisi").prop('disabled', false);
      $("#suhu").prop('disabled', false);
      $("#keterangan").prop('disabled', false);
      $("#suhu").select().focus();
    });
  });
}

if ($("#socket").length) {
  var socket = io('https://node.scr.smpn39sinjai.sch.id');
  socket.on('credential', (data) => {
    getPesertaByCredential(data);
  });
}

function getPesertaByCredential(credential) {
  _sr.empty();
  clearTimeout(_tm);
  if (credential != "") {
    _tm = setTimeout(() => {
      $.post(location.href + '/credential', { _token: $("meta[name=_token]").attr('content'), req: credential }, function (res) {
        socket.emit('response', res);
        audio.play();
        if (res.data.id != undefined && !$("#modal-peserta").is(":visible")) {
          var dta = `<li
                      data-id="`+ res.data.id + `"
                      data-pid="`+ res.data.pid + `"
                      data-name="`+ res.data.name + `"
                      data-status="`+ res.data.status + `"
                      data-role="`+ (res.data.role).toLowerCase() + `"
                    >`+ (res.data.pid ? res.data.pid + ' - ' : '') + res.data.name + ` (` + res.data.role + `) (` + res.data.pos + `)</li>`;
          clickTrigger(dta);
        }
      }, 'json').fail((res) => {
        socket.emit('response', res);
      });
    }, 1000);
  }
}

function initDefaultScript() {
  $(".confirm").unbind().click(function () {
    var _text = $(this).data('text');
    if (!confirm(_text)) {
      return false;
    }
  });
}
initDefaultScript();
