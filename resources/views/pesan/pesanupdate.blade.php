@extends('layouts.menus')
@section('content')
<div class="long-title"><h3>Perbaharui Berita</h3></div>
{!! Form::open(['id' => 'frm','route' =>  ['pesan.update',$pesan->id],'method' => 'PUT','class' => 'form-horizontal']) !!}
<div class="second-group">
    <div id="form"></div>
    {{-- <input id="txtjenissoal" type="text" name="soal_jenis" value="{!!$pilihan!!}" hidden> --}}
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

  $("#form").dxForm({
      formData:{!!$pesan!!},
      colCount: 1,
      showColonAfterLabel: true,
      showValidationSummary: true,
      items:[
      {
        itemType:"group",
        colCount:1,
        items: [{
                dataField: "pesan_entitas",
                label:{
                    text:"Tujuan Pesan",
                },
                editorType: "dxSelectBox",
                editorOptions: {
                    items: [{"pesan_entitas":"0","pesan_entitas_desc":"SEMUA"},
                            {"pesan_entitas":"1","pesan_entitas_desc":"DONATUR"},
                            {"pesan_entitas":"2","pesan_entitas_desc":"SANTRI"},
                            {"pesan_entitas":"3","pesan_entitas_desc":"PENDAMPING"}],
                    displayExpr: "pesan_entitas_desc",
                    valueExpr: "pesan_entitas",
                    value:"0",
                },
                validationRules: [{
                            type: "required",
                            message: "Pilih Entitas Tujuan Pesan"}]
            },{
                dataField: "pesan_judul",
                label:{
                    text:"Judul Pesan",
                }, 
                editorOptions:{
                },
                validationRules: [{
                        type: "required",
                        message: "Judul Pesan harus di isi",
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
              text: "Perbaharui",
              type: "success",
              useSubmitBehavior: true
          }
      },]
  }).dxForm("instance"); 

});
</script>
@endsection
