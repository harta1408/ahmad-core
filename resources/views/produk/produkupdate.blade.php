@extends('layouts.menus')
@section('content')
<div class="long-title"><h3>Perbaharui Produk</h3></div>
{!! Form::open(['id' => 'frm','route' => ['produk.update',$produk->id],'method' => 'PUT','class' => 'form-horizontal']) !!}
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
      formData:{!!$produk!!},
      colCount: 1,
      showColonAfterLabel: true,
      showValidationSummary: true,
      items:[
      {
        itemType:"group",
        colCount:1,
        items: [{
          dataField: "produk_nama",
          label:{
            text:"Nama Produk",
          },
          editorOptions: { 
          },
          validationRules: [{
                    type: "required",
                    message: "Nama Produk harus di isi"
                }]
        },{
          dataField: "produk_deskripsi",
          label:{
            text:"Deskripsi",
          },
          editorType: "dxTextArea",
          editorOptions: {
            height:100,
          },
          validationRules: [{
                    type: "required",
                    message: "Deskripsi Produk harus di isi"
                }]
        },{
          dataField: "produk_harga",
          label:{
            text:"Harga Produk",
          },
          editorType: "dxNumberBox",
          editorOptions: { 
            dataType:"number",
            format: "#,##0",
          },
          validationRules: [{
              type: "required",
              message: "Harga Produk harus di isi"
          }]
        },{
          dataField: "produk_discount",
          label:{
            text:"Potongan Harga (Rp.)",
          },
          editorType: "dxNumberBox",
          editorOptions: {
            dataType:"number",
            format: "#,##0",
          },
        },{
          dataField: "produk_stok",
          label:{
            text:"Jumlah Stok",
          },
          editorOptions: {
          }
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
