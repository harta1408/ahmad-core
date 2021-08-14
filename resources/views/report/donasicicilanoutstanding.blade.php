@extends('layouts.menus')
@section('content')
    <div class="long-title"><h3>Daftar Outstanding Cicilan Harian Tanggal {{date('d-m-Y')}}</h3></div>
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
        dataSource: {!!$donasicicilan!!},
        keyExpr: "id",
        showBorders: true,
        "export": {
                enabled: true,
                fileName: "outstanding_cicilan",
                allowExportSelectedData: true
        },
        searchPanel: {
            visible: true
        },
        paging: {
            pageSize: 10
        },
        columns: [
            {
                dataField: "donasi.donasi_no",
                caption: "No Donasi",
            },{
                dataField: "donasi.donatur.donatur_nama",
                caption: "Donatur",
            },{
                dataField: "donasi.donasi_cara_bayar",
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
                dataField: "cicilan_ke",
                caption: "Cicilan Ke",
            },{
                dataField: "cicilan_jatuh_tempo",
                caption: "Jatuh Tempo",
                dataType:"date",
                format:"dd-MM-yyyy",    
            },{
                dataField: "cicilan_nominal",
                caption: "Nominal",   
                dataType:"number",
                format: "fixedPoint",
            },
        ],
        summary: {
            totalItems: [{
                column: "donasi.donasi_no",
                summaryType: "count",
                displayFormat: "Donasi {0}",
            },{
                column: "cicilan_nominal",
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
