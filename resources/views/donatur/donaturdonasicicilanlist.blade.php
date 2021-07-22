@extends('layouts.menus')
@section('content')
    <div class="long-title"><h3>Daftar Cicilan Donasi</h3></div>
    <div id="form"></div>
    <div id="gridData"></div>
@endsection

@section('script')
<script type="text/javascript">
$(function(){
    $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    $("#form").dxForm({
      formData:{!!$donasi!!},  
      showColonAfterLabel: true,
      showValidationSummary: true,
      colCount:2,
      items:[
      {
        dataField: "donatur.donatur_nama",
          label:{
            text:"Donatur",
          },
          editorOptions: { 
              disabled: true
          }
        },{
          dataField: "donasi_no",
          label:{
            text:"No Donasi",
          },
          editorOptions: { 
              disabled: true
          }
        },{
          dataField: "donasi_total_harga",
          label:{
            text:"Jumah Donasi",
          },
          editorType: "dxNumberBox",
          editorOptions: { 
            dataType:"number",
            format: "#,##0",
            disabled: true,
          },
        },{
          dataField: "donasi_jumlah_santri",
          label:{
            text:"Jumlah Santri",
          },
          editorOptions: { 
              disabled: true
          }
        },{
          dataField: "donasi_cara_bayar",
          label:{
            text:"Cara Bayar",
          },
          editorType: "dxSelectBox",
          editorOptions: {
              items: [{"donasi_cara_bayar":"1","donasi_cara_bayar_desc":"HARIAN"},
                      {"donasi_cara_bayar":"2","donasi_cara_bayar_desc":"PEKANAN"},
                      {"donasi_cara_bayar":"3","donasi_cara_bayar_desc":"BULANAN"}, 
                      {"donasi_cara_bayar":"4","donasi_cara_bayar_desc":"TUNAI"}],
              displayExpr: "donasi_cara_bayar_desc",
              valueExpr: "donasi_cara_bayar",
              disabled: true,

          },
        },{
            dataField: "donasi_nominal",
            label:{
                text:"Nominal Cicilan",
            },
            editorType: "dxNumberBox",
            editorOptions: { 
              dataType:"number",
              format: "#,##0",
              disabled: true,
            },
      },]
  }).dxForm("instance"); 
    $("#gridData").dxDataGrid({
        dataSource: {!!$donasi->cicilan!!},
        keyExpr: "id",
        showBorders: true,
        dateSerializationFormat:"yyyy-MM-dd",
        selection: {
            mode: "single",
        },
        hoverStateEnabled: true,
        paging: {
            pageSize: 10
        },
        columns: [
            {
                dataField: "cicilan_ke",
                caption: "Cicilan",
            },{
                dataField: "cicilan_jatuh_tempo",
                caption: "Jatuh Tempo",
                dataType:"date",
                format:"dd-MM-yyyy",    
            },{
                dataField: "cicilan_nominal",
                caption: "Nominal",
                dataType:"number",
                format: "#,##0",
            },{
                dataField: "cicilan_status",
                caption: "Status",
                lookup: {
                    dataSource: [{"cicilan_status":"1","cicilan_status_desc":"Belum Bayar"},
                            {"cicilan_status":"2","cicilan_status_desc":"Sudah Bayar"}],
                    valueExpr: "cicilan_status",
                    displayExpr: "cicilan_status_desc",
                },
            },
            
        ],
        onEditingStart: function(e){
        if (e.column.dataField != "donasi_tanggal_bayar" && e.column.dataField != "donasi_status") {
             e.cancel = true;
          }
        },
    });
});
</script>
@endsection
