@extends('layouts.menus')
@section('content')
<div class="long-title"><h3>Buat Pengiriman Produk</h3></div>
{!! Form::open(['id' => 'frm','route' => 'kirimproduk.main','class' => 'form-horizontal']) !!}
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

  $("#produknoseri").dxTextBox({
        placeholder: "Masukan nomor seri produk..."
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
          dataField: "kirim_produk_no_seri",
          label:{
            text:"Nomor Seri Produk",
          },
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
          dataField: "kirim_kurir",
          label:{
            text:"Kurir",
          },
          editorType: "dxSelectBox",
          editorOptions: {
              items: {!!$kurir!!},
              displayExpr: "kurir_nama",
              valueExpr: "kurir_id",
          },
          validationRules: [{
                    type: "required",
                    message: "Pilih Kurir dari Daftar",
            }]
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
        },]
      },{
          itemType: "button",
          horizontalAlignment: "left",
          buttonOptions: {
              text: "Proses",
              type: "success",
              useSubmitBehavior: true
          }
      },]
  }).dxForm("instance"); 

});
</script>
@endsection
