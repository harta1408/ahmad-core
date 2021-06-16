@extends('layouts.menus')
@section('content')
    <div class="long-title"><h3>Daftar Santri</h3></div>
    <div id="gridData"></div>
@endsection

@section('script')
<script type="text/javascript">
$(function(){
    $("#gridData").dxDataGrid({
        dataSource: {!! $santri !!},
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
        groupPanel: {
            visible: true
        },
        columns: [
            {
              dataField: "santri_kode",
              caption: "Kode santri",
              visible:false,
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
              dataField: "santri_tmp_lahir",
              caption: "Tempat Lahir",
              visible:false,
            },{
              dataField: "santri_tgl_lahir",
              caption: "Tanggal Lahir",
              visible:false,
            },{
              dataField: "santri_gender",
              caption: "Jenis Kelamin",
              visible:false,
            },{
              dataField: "santri_agama",
              caption: "Agama",
              visible:false,
            },{
              dataField: "santri_alamat",
              caption: "Alamat",
              visible:false,  
            },{
              dataField: "santri_provinsi",
              caption: "Provinsi",
              visible:false,
            },{
              dataField: "santri_kota",
              caption: "Kota",
              visible:false,
            },{
              dataField: "santri_kecamatan",
              caption: "Kecamatan",
              visible:false,
            },{
              dataField: "santri_kelurahan",
              caption: "Kelurahan",
              visible:false,
            },{
              dataField: "santri_kode_pos",
              caption: "Kode Pos",
              visible:false,
            },{
              dataField: "santri_status",
              caption: "Status",
            },
            
        ],
    });
});
</script>
@endsection
