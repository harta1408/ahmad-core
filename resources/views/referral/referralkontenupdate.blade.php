@extends('layouts.menus')
@section('content')
<div class="long-title"><h3>Perbaharui Konten Referral</h3></div>
{!! Form::open(['id' => 'frm','route' =>  ['referral.konten.update',$berita->id],'method' => 'PUT','class' => 'form-horizontal']) !!}
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
      formData:{!!$berita!!},
      colCount: 1,
      showColonAfterLabel: true,
      showValidationSummary: true,
      items:[
      {
        itemType:"group",
        colCount:1,
        items: [{
            dataField: "berita_jenis",
                label:{
                    text:"Jenis Berita",
                },
                editorType: "dxSelectBox",
                editorOptions: {
                    items: [{"berita_jenis":"1","berita_jenis_desc":"BERITA"},
                            {"berita_jenis":"2","berita_jenis_desc":"KAMPANYE"},
                            {"berita_jenis":"3","berita_jenis_desc":"BROADCAST"}],
                    displayExpr: "berita_jenis_desc",
                    valueExpr: "berita_jenis",
                    disabled:true,
                },
            },{
                dataField: "berita_entitas",
                label:{
                    text:"Tujuan Berita",
                },
                editorType: "dxSelectBox",
                editorOptions: {
                    items: [{"berita_entitas":"0","berita_entitas_desc":"SEMUA"},
                            {"berita_entitas":"1","berita_entitas_desc":"DONATUR"},
                            {"berita_entitas":"2","berita_entitas_desc":"SANTRI"},
                            {"berita_entitas":"3","berita_entitas_desc":"PENDAMPING"}],
                    displayExpr: "berita_entitas_desc",
                    valueExpr: "berita_entitas",
                    disabled:true,
                },
            },{
                dataField: "berita_judul",
                label:{
                    text:"Judul berita",
                }, 
                editorOptions:{
                },
                validationRules: [{
                            type: "required",
                            message: "Nomor Soal harus di isi",
                }],
            },{
                dataField: "berita_isi",
                label:{
                    text:"Isi berita",
                },
                editorType: "dxTextArea",
                editorOptions:{
                    height: 100,
                },
                validationRules: [{
                    type: "required",
                    message: "Silakan masukan konten referral"
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
