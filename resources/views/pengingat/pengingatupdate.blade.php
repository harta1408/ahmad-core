@extends('layouts.menus')
@section('content')
<div class="long-title"><h3>Perbaharui Pengingat</h3></div>
{!! Form::open(['id' => 'frm','route' =>  ['pengingat.update',$pengingat->id],'method' => 'PUT','class' => 'form-horizontal']) !!}
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
      formData:{!!$pengingat!!},
      colCount: 1,
      showColonAfterLabel: true,
      showValidationSummary: true,
      items:[
      {
        itemType:"group",
        colCount:1,
        items: [{
                dataField: "pengingat_jenis",
                label:{
                    text:"Jenis Pengingat",
                },
                editorType: "dxSelectBox",
                editorOptions: {
                    items: [{"pengingat_jenis":"1","pengingat_jenis_desc":"SEDEKAH SUBUH"},
                            {"pengingat_jenis":"2","pengingat_jenis_desc":"SEDEKAH JUM'AT"},
                            {"pengingat_jenis":"3","pengingat_jenis_desc":"SEDEKAH YAUMUL BIDH"}],
                    displayExpr: "pengingat_jenis_desc",
                    valueExpr: "pengingat_jenis",
                },
                validationRules: [{
                            type: "required",
                            message: "Alamat Email Harus Di isi"}]
            },{
                dataField: "pengingat_judul",
                label:{
                    text:"Judul Pengingat",
                }, 
                editorOptions:{
                },
                validationRules: [{
                            type: "required",
                            message: "Nomor Soal harus di isi",
                }],
            },{
                dataField: "pengingat_isi",
                label:{
                    text:"Isi Pengingat",
                },
                editorOptions:{
                    height: 100,
                },
                validationRules: [{
                    type: "required",
                    message: "Masukan Pertanyaan untuk Soal"
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
