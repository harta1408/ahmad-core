@extends('layouts.menus')
@section('content')
    <div class="long-title"><h3>Daftar Donasi Harian Tanggal {{date('d-m-Y')}}</h3></div>
    <div class="second-group">
        <div id="gridData"></div>
    </div> 
@endsection

@section('script')
<script type="text/javascript">
$(function(){
    $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    
 
    $("#gridData").dxDataGrid({
        dataSource: {!!$donasi!!},
        keyExpr: "id",
        showBorders: true,
        searchPanel: {
            visible: true
        },
        "export": {
                enabled: true,
                fileName: "donasi_harian",
                allowExportSelectedData: true
        },
        paging: {
            pageSize: 10
        },
        columns: [
            {
                dataField: "donasi_no",
                caption: "No Donasi",
            },{
                dataField: "donatur.donatur_nama",
                caption: "Donatur",
            },{
                dataField: "donasi_cara_bayar",
                caption: "Cara Bayar",
                lookup: {
                    dataSource: [{"donasi_cara_bayar":"1","donasi_cara_bayar_desc":"Harian"},
                            {"donasi_cara_bayar":"2","donasi_cara_bayar_desc":"Pekanan"},
                            {"donasi_cara_bayar":"3","donasi_cara_bayar_desc":"Bulanan"},
                            {"donasi_cara_bayar":"4","donasi_cara_bayar_desc":"Tunai"}],
                    valueExpr: "donasi_cara_bayar",
                    displayExpr: "donasi_cara_bayar_desc",
                },
            },{
                dataField: "donasi_nominal",
                caption: "Nominal Cicilan",
                dataType:"number",
                format: "fixedPoint",    
            },{
                dataField: "donasi_durasi",
                caption: "Durasi Waktu",  
            },{
                dataField: "donasi_total_harga",
                caption: "Total Harga",
                dataType:"number",
                format: "fixedPoint",
            },
        ],
        summary: {
            totalItems: [{
                column: "donasi_no",
                summaryType: "count",
                displayFormat: "Donasi {0}",
            },{
                column: "donasi_nominal",
                summaryType: "sum",
                dataType: "number",
                valueFormat: "fixedPoint",
                displayFormat: "Nominal {0}",
            },{
                column: "donasi_total_harga",
                summaryType: "sum",
                dataType: "number",
                valueFormat: "fixedPoint",
                displayFormat: "Total {0}",    
            }]
        },
        onEditingStart: function(e){
        if (e.column.dataField != "cicilan_tanggal_bayar" && e.column.dataField != "cicilan_status") {
             e.cancel = true;
          }
        },
    });
});
</script>
@endsection
