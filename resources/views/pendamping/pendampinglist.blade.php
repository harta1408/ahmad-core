@extends('layouts.menus')
@section('content')
    <div class="long-title"><h3>Daftar Pendamping</h3></div>
    <div id="gridData"></div>
@endsection

@section('script')
<script type="text/javascript">
$(function(){
    $("#gridData").dxDataGrid({
        dataSource: {!! $pendamping !!},
        keyExpr: "id",
        showBorders: true,
        export: {
          enabled: true,
          fileName: "pendampinglist",
          allowExportSelectedData: true,
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
        groupPanel: {
            visible: true
        },
        columns: [
            {
              dataField: "pendamping_kode",
              caption: "Kode pendamping",
              visible:false,
            },{
              dataField: "pendamping_nama",
              caption: "Nama",
            },{
              dataField: "pendamping_email",
              caption: "Alamat Email",
            },{
              dataField: "pendamping_telepon",
              caption: "Telepon",
            },{
              dataField: "pendamping_tmp_lahir",
              caption: "Tempat Lahir",
              visible:false,
            },{
              dataField: "pendamping_tgl_lahir",
              caption: "Tanggal Lahir",
              visible:false,
            },{
              dataField: "pendamping_gender",
              caption: "Jenis Kelamin",
              visible:false,
            },{
              dataField: "pendamping_agama",
              caption: "Agama",
              visible:false,
            },{
              dataField: "pendamping_alamat",
              caption: "Alamat",
              visible:false,  
            },{
              dataField: "pendamping_provinsi",
              caption: "Provinsi",
              visible:false,
            },{
              dataField: "pendamping_kota",
              caption: "Kota",
              visible:false,
            },{
              dataField: "pendamping_kecamatan",
              caption: "Kecamatan",
              visible:false,
            },{
              dataField: "pendamping_kelurahan",
              caption: "Kelurahan",
              visible:false,
            },{
              dataField: "pendamping_kode_pos",
              caption: "Kode Pos",
              visible:false,
            },{
              dataField: "pendamping_status",
              caption: "Status",
            },
            
        ],
    });
});
</script>
@endsection
