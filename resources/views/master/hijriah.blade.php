@extends('layouts.menus')
@section('content')
<div class="long-title"><h3>Penyesuaian Tanggal Hijriah</h3></div>
{!! Form::open(['id' => 'frm','route' => ['lembaga.update','ahmad'],'method' => 'PUT', 'class' => 'form-horizontal']) !!}
<div class="dx-field">
    <div class="dx-fieldset">    
        <div class="dx-field">
            <div class="dx-field-label">Tanggal Hijriah Aktif</div>
            <div class="dx-field-value">
                <div id="currenthijr"></div>
            </div>
        </div> 
        <div class="dx-field">
            <div class="dx-field-label">Pengurangan/ Penambahan</div>
            <div class="dx-field-value">
                <div id="minAndMax"></div>
            </div>
        </div> 
        <div class="dx-field">
            <div class="dx-field-label">Tanggal Hijriah Baru</div>
            <div class="dx-field-value">
                <div id="newhijr"></div>
            </div>
        </div> 
        <div class="dx-field">
            <div id="btnSave"></div>
        </div>
    </div>
    
</div>
{!! Form::close()!!}

<div class="second-group">
    
</div>

@endsection

@section('script')
<script type="text/javascript">
$(function() {
  $.fn.serializeObject = function(){
      var o = {};
      var a = this.serializeArray();
      $.each(a, function() {
          if (o[this.name] !== undefined) {
              if (!o[this.name].push) {
                  o[this.name] = [o[this.name]];
              }
              o[this.name].push(this.value || '');
          } else {
              o[this.name] = this.value || '';
          }
      });
      return o;
  };

  $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
  });
  var today='{!!$hariini!!}';
  var todaydefault='{!!$haridefault!!}';
  $("#currenthijr").dxTextBox({
        value: today,
    });
    var newhijr=$("#newhijr").dxTextBox({
        value:todaydefault,
    }).dxTextBox("instance");
  $("#minAndMax").dxNumberBox({
        value: 0,
        min: -3,
        max: 3,
        showSpinButtons: true,
        onValueChanged: function(data) {  
            $.ajax({
                url: "{{URL::to('dashboard/lembaga/hijriah/update')}}"+"/"+data.value,
                method: "GET",
                dataType: "json",
                complete: function (data) {
                    newhijr.option("value", data.responseText);
                }
            });
        }
    })
    $("#btnSave").dxButton({
        type: "success",
        text: "Simpan",
        onClick: function(e) {      
            var minAndMax=$("#minAndMax").dxNumberBox("instance").option("value");
            console.log(minAndMax);
            $.ajax({
                url: "{{route('lembaga.hijriah.save')}}",
                method: "POST",
                data: JSON.stringify({adjust:minAndMax}),
                contentType: "application/json; charset=utf-8",
                success: function (data) {
                    if(data.code != 200) {
                        swal({
                            title: "Validation Error",
                            icon: data.status,
                            text: data.message,
                            value: true,
                            visible: true,
                            className: "",
                            closeModal: true,
                        });
                    }else{
                        swal({
                            title: "OK",
                            icon: data.status,
                            text: data.message,
                            value: true,
                            visible: true,
                            className: "",
                            closeModal: true,
                        })
                        .then((value) => {
                            window.location = "{{route('home')}}";
                        });
                    }
                    return false;
                }, 
                error: function(jqXHR, textStatus, errorThrown) {
                    swal({
                        title: "Error",
                        icon: 'error',
                        text: textStatus,
                        value: true,
                        visible: true,
                        className: "",
                        closeModal: true,
                    });
                    return false;
                }
            });
      }
    });


});
</script>
@endsection
