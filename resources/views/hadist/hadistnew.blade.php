@extends('layouts.menus')
@section('content')
<div class="long-title"><h3>Buat Hadist dan Doa Baru</h3></div>
{!! Form::open(['id' => 'frm','route' => 'hadist.store','class' => 'form-horizontal']) !!}
<div class="second-group">
    <div id="form"></div>
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
                dataField: "hadist_jenis",
                label:{
                    text:"Jenis",
                },
                editorType: "dxSelectBox",
                editorOptions: {
                    items: [{"hadist_jenis":"1","hadist_jenis_desc":"HADIST"}, 
                            {"hadist_jenis":"2","hadist_jenis_desc":"DOA"}],
                    displayExpr: "hadist_jenis_desc",
                    valueExpr: "hadist_jenis",
                    value:"1",
                },
                validationRules: [{
                            type: "required",
                            message: "Pilih Jenis hadist"}]
            },{
                dataField: "hadist_judul",
                label:{
                    text:"Judul Hadist",
                }, 
                editorOptions:{
                },
                validationRules: [{
                        type: "required",
                        message: "Judul hadist harus di isi",
                }],
            },{
                dataField: "hadist_isi",
                label:{
                    text:"Isi Hadist",
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
                    message: "Masukan hadist"
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
