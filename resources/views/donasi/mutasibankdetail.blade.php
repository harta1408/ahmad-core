@extends('layouts.menus')
@section('content')
    <div class="long-title"><h3>Daftar Mutasi Detail {!!date('d-m-Y')!!}</h3></div>
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
    var mutasi={!!json_encode($mutasi)!!}

 
    $("#gridData").dxDataGrid({
        dataSource: mutasi,
        keyExpr: "mutation_id",
        showBorders: true,
        allowColumnResizing: true,
        selection: {
            mode: "single",
        },
        hoverStateEnabled: true,
        searchPanel: {
            visible: true
        },
        paging: {
            pageSize: 10
        },
        columns: [
            {
                dataField: "bank.label",
                caption: "Nama Bank",
                visible:false,
            },{
                dataField: "bank.account_number",
                caption: "No Rekening",
            },{
                dataField: "bank.atas_nama",
                caption: "Atas Nama",
                visible:false,
            },{
                dataField: "amount",
                caption: "Mutasi",
                dataType:"number",
                format: "fixedPoint",  
            },{
                dataField: "description",
                caption: "Keterangan",
            },
            
        ],
        onSelectionChanged: function (selectedItems) {
            var data = selectedItems.selectedRowsData;
            console.log(data[0]['bank_id']);
        },
    });

});
</script>
@endsection
