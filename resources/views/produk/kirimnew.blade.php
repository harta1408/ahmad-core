@extends('layouts.menus')
@section('content')
<div class="long-title"><h3>Buat Pengiriman Produk</h3></div>
{!! Form::open(['id' => 'frm','route' => 'kirimproduk.store','class' => 'form-horizontal']) !!}
  <div id="form"></div>
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

  var provinsi;
  var kota;
  var kecamatan;
  $("#form").dxForm({
      colCount: 1,
      showColonAfterLabel: true,
      showValidationSummary: true,
      items:[
      {
        itemType:"group",
        colCount:1,
        items: [{
          dataField: "kirim_produk_no_seri",
          label:{
            text:"Nomor Seri Produk",
          },
          editorOptions: { 
          }
        },{
          dataField: "santri_id",
          label:{
            text:"Santri",
          },
          editorType: "dxSelectBox",
          editorOptions: {
              items: {!!$santri!!},
              displayExpr: "santri_nama",
              valueExpr: "id",
          },
          validationRules: [{
                    type: "required",
                    message: "Pilih Santri dari Daftar",
                }]
        },{
          dataField: "kirim_no_resi",
          label:{
            text:"Nomor Resi",
          },
        },{
          dataField: "kirim_tanggal_kirim",
          label:{
            text:"Tanggal Kirim",
          },
          editorType: "dxDateBox",
            editorOptions:{
                value: new Date(),
                type: "date",
            },
        },{
          dataField: "kirim_biaya",
          label:{
            text:"Biaya Kirim",
          },
          editorType: "dxNumberBox",
            editorOptions: { 
                dataType:"number",
                format: "#,##0",
                value:0,
            },
          validationRules: [{
              type: "required",
              message: "Biaya Kirim harus di isi"
          }]

       
        },]
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
