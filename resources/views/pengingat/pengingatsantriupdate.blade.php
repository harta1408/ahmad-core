@extends('layouts.menus')
@section('content')
<div class="long-title"><h3>Perbaharui Pengingat</h3></div>
{!! Form::open(['id' => 'frm','route' =>  ['pengingat.update',$pengingat->id],'method' => 'PUT','class' => 'form-horizontal']) !!}
<div class="second-group">
    <div id="form"></div>
    <input id="txtentitas" type="text" name="pengingat_entitas" value="2" hidden>
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
                    text:"Jenis",
                },
                editorType: "dxSelectBox",
                editorOptions: {
                    items: [{"pengingat_jenis":"4","pengingat_jenis_desc":"SUNNAH SENIN"},
                            {"pengingat_jenis":"5","pengingat_jenis_desc":"SUNNAH KAMIS"},
                            {"pengingat_jenis":"6","pengingat_jenis_desc":"SUNNAH JUM'AT"}],
                    displayExpr: "pengingat_jenis_desc",
                    valueExpr: "pengingat_jenis",
                },
                validationRules: [{
                            type: "required",
                            message: "Alamat Email Harus Di isi"}]
            },{
                dataField: "pengingat_judul",
                label:{
                    text:"Judul",
                }, 
                editorOptions:{
                },
                validationRules: [{
                            type: "required",
                            message: "Nomor Soal harus di isi",
                }],
            },{
                dataField: "pengingat_lokasi_video",
                label:{
                    text:"Lokasi Link Video",
                }, 
                editorOptions:{
                },
            },{
                dataField: "pengingat_isi_singkat",
                label:{
                    text:"Isi (versi singkat)",
                },
                editorType: "dxTextArea",
                editorOptions:{
                    height: 150,
                },
                validationRules: [{
                    type: "required",
                    message: "Masukan Pengingat",
                }],
            },{
                dataField: "pengingat_isi",
                label:{
                    text:"Isi",
                },
                editorType: "dxHtmlEditor",
                editorOptions:{
                    height: 200,
                    toolbar: {
                        items: [
                            "undo", "redo", "separator",
                            {
                                name: "size",
                                acceptedValues: ["8pt", "10pt", "12pt", "14pt", "18pt", "24pt", "36pt"] },
                            "separator", "bold", "italic", "underline", "separator",
                            "alignLeft", "alignCenter", "alignRight", "alignJustify", "separator",
                            "link", "image", "separator",
                        ],
                        multiline:true,
                    },
                    mediaResizing: {
                        enabled: true
                    }
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
