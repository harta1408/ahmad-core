
@extends('layouts.menus')
@section('content')
    <div class="long-title"><h3>Daftar Penambahan Santri</h3></div>
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
        dataSource: {!!$santri!!},
        keyExpr: "id",
        showBorders: true,
        "export": {
            enabled: true,
            fileName: "santrilist",
        },
        columnChooser: {
            enabled: true
        },
        searchPanel: {
            visible: true
        },
        paging: {
            pageSize: 10
        },
        columns: [
            {
              dataField: "santri_kode",
              caption: "Kode santri",
            },{
              dataField: "santri_nama",
              caption: "Nama",
            },{
              dataField: "santri_email",
              caption: "Alamat Email",
            },{
              dataField: "santri_telepon",
              caption: "Telepon",
            },{
              dataField: "santri_status", 
              caption:"Status",
            },
        ],
    });
});
</script>
@endsection
