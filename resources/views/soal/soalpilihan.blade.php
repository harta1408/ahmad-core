@extends('layouts.menus')
@section('content')
<div class="long-title"><h3>Buat Soal Pilihan</h3></div>
{!! Form::open(['id' => 'frm','route' => 'soal.store','class' => 'form-horizontal']) !!}
<div class="second-group">
    <div id="form"></div>
    <input id="txtjenissoal" type="text" name="soal_jenis" value="{!!$pilihan!!}" hidden>
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
            dataField: "soal_no",
            label:{
                text:"No Soal",
            }, 
            editorOptions:{
                mask: "000",
                maskInvalidMessage: "Nomor Soal tiga Digit",
                useMaskedValue: true,
                width:50,
            },
          validationRules: [{
                    type: "stringLength",
                    min: 3,
                    message: "Nomor Soal Tiga Digit"
                }, {
                    type: "required",
                    message: "Nomor Soal harus di isi",
          }],
        },{
          dataField: "materi_id",
          label:{
            text:"Pilih Materi",
          },
          editorType: "dxSelectBox",
          editorOptions: {
              items: {!!$materi!!},
              displayExpr: "materi_nama",
              valueExpr: "id",
          },
          validationRules: [{
                type: "required",
                message: "Pilih Materi"}]
        },{
          dataField: "soal_deskripsi",
          label:{
            text:"Soal",
          },
          editorOptions:{
            height: 50,
          },
          validationRules: [{
              type: "required",
              message: "Masukan Pertanyaan untuk Soal"
          }]
        },{
          dataField: "soal_pilihan_a",
          label:{
            text:"Pilihan A",
          },
        },{
          dataField: "soal_pilihan_b",
          label:{
            text:"Pilihan B",
          },
        },{
          dataField: "soal_pilihan_c",
          label:{
            text:"Pilihan C",
          },
        },{
          dataField: "soal_pilihan_d",
          label:{
            text:"Pilihan D",
          },
        },{
          dataField: "soal_nilai_maksimum",
          label:{
            text:"Nilai Maksimum",
          },
          editorType: "dxNumberBox",
            editorOptions: { 
                dataType:"number",
                format: "#,##0",
                value:0,
            },
        },{
          dataField: "soal_nilai_minimum",
          label:{
            text:"NIlai Minimum",
          },
          editorType: "dxNumberBox",
            editorOptions: { 
                dataType:"number",
                format: "#,##0",
                value:0,
            },
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
