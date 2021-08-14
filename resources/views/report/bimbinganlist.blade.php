@extends('layouts.menus')
@section('content')
    <div class="long-title"><h3>Daftar Santri Dalam Bimbingan</h3></div>
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
 
    $("#gridData").dxDataGrid({
        dataSource: {!!$bimbingan!!},
        keyExpr: "id",
        showBorders: true,
        searchPanel: {
            visible: true
        },
        paging: {
            pageSize: 10
        },
        columns: [
            {
              dataField: "produk.produk_nama",
              caption: "Produk",
            },{
              dataField: "pendamping.pendamping_nama",
              caption: "Pendamping", 
            },{
              dataField: "santri.santri_nama",
              caption: "Santri",
            },{
              dataField: "bimbingan_status",
              caption: "Status",
            },
            
        ],
    });
});
</script>
@endsection
