@extends('layouts.menus')
@section('content')
<div class="long-title"><h3>Buat Pesan Baru</h3></div>
{!! Form::open(['id' => 'frm','route' => 'pesan.store','class' => 'form-horizontal']) !!}
<div class="second-group">
    <div id="form"></div>
    <input id="txtID" type="text" name="selectedentitas" value={!!$selectedentitas!!}
    class="form-control" placeholder="ID" hidden>
</div>
{!! Form::close()!!}
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
  var jenisentitas='{!!$jenisentitas!!}';
  $("#form").dxForm({
      colCount: 1,
      showColonAfterLabel: true,
      showValidationSummary: true,
      items:[
      {
        itemType:"group",
        colCount:1,
        items: [{
                dataField: "pesan_tujuan",
                label:{
                    text:"Tujuan",
                }, 
                editorOptions:{
                    value:jenisentitas,
                },
                validationRules: [{
                        type: "required",
                        message: "User ID Tujuan Pesan Harus Di Isi",
                }],
            },{
                dataField: "pesan_isi",
                label:{
                    text:"Isi Pesan",
                },
                editorType: "dxTextArea",
                editorOptions:{
                    height: 100,
                },
                validationRules: [{
                    type: "required",
                    message: "Masukan isi Pesan"
                }],
            },{
                dataField: "pesan_waktu_kirim",
                label:{
                    text:"Waktu Kirim",
                },
                editorType: "dxDateBox",
                editorOptions:{
                    value: new Date(),
                    type: "datetime",
                },
                validationRules: [{
                    type: "required",
                    message: "Masukan isi Pesan"
                }]
            },],
      },{
          itemType: "button",
          horizontalAlignment: "left",
          buttonOptions: {
              text: "Simpan",
              type: "success",
              useSubmitBehavior: true
          }
      },]
  }).dxForm("instance"); 

});
</script>
@endsection
