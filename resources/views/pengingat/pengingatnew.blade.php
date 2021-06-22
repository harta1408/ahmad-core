@extends('layouts.menus')
@section('content')
<div class="long-title"><h3>Buat Pengingat Baru</h3></div>
{!! Form::open(['id' => 'frm','route' => 'pengingat.store','class' => 'form-horizontal']) !!}
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
                    items: [{"pengingat_jenis":"1","pengingat_jenis_desc":"SEDEKAH SUBUH"},
                            {"pengingat_jenis":"2","pengingat_jenis_desc":"SEDEKAH JUM'AT"},
                            {"pengingat_jenis":"3","pengingat_jenis_desc":"SEDEKAH YAUMUL BIDH"}],
                    displayExpr: "pengingat_jenis_desc",
                    valueExpr: "pengingat_jenis",
                    value:"1",
                },
                validationRules: [{
                            type: "required",
                            message: "Pilih Jenis Pengingat"}]
            },{
                dataField: "pengingat_judul",
                label:{
                    text:"Judul",
                }, 
                editorOptions:{
                },
                validationRules: [{
                        type: "required",
                        message: "Judul Pengingat harus di isi",
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
                    message: "Masukan Pengingat"
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
