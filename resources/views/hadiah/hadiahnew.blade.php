@extends('layouts.menus')
@section('content')
<div class="long-title"><h3>Buat Hadiah Baru</h3></div>
{!! Form::open(['id' => 'frm','route' => 'hadiah.store','class' => 'form-horizontal']) !!}
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
                dataField: "hadiah_jenis",
                label:{
                    text:"Jenis Hadiah",
                },
                editorType: "dxSelectBox",
                editorOptions: {
                    items: [{"hadiah_jenis":"1","hadiah_jenis_desc":"NOMINAL"}, 
                            {"hadiah_jenis":"2","hadiah_jenis_desc":"PRODUK"}],
                    displayExpr: "hadiah_jenis_desc",
                    valueExpr: "hadiah_jenis",
                    value:"1",
                },
                validationRules: [{
                            type: "required",
                            message: "Pilih Jenis Berita"}]
            },{
                dataField: "hadiah_judul",
                label:{
                    text:"Nama Hadiah",
                }, 
                editorOptions:{
                },
                validationRules: [{
                        type: "required",
                        message: "Silakan isi nama hadiah",
                }],
            },{
                dataField: "hadiah_nilai",
                label:{
                    text:"Nilai Refferal (Poin)",
                },
                editorType: "dxNumberBox",
                editorOptions: { 
                    dataType:"number",
                    format: "#,##0",
                    value:0,
                },
                validationRules: [{
                    type: "required",
                    message: "Silakan isi jumlah poin yang ditukarkan"
                }]
            },{
                dataField: "hadiah_nominal",
                label:{
                    text:"Nilai Hadiah (Rp.)",
                },
                editorType: "dxNumberBox",
                editorOptions: { 
                    dataType:"number",
                    format: "#,##0",
                    value:0,
                },
                validationRules: [{
                    type: "required",
                    message: "Silakan isi Nominal Hadiah"
                }]
          
            },{
                dataField: "hadiah_no_seri",
                label:{
                    text:"No Seri (Opsional)",
                }, 
                editorOptions:{
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
