@extends('layouts.menus')
@section('content')
    <div class="long-title"><h3>Daftar Agniya</h3></div>
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
        dataSource: {!! $donatur !!},
        keyExpr: "id",
        showBorders: true,
        export: {
          enabled: true,
          fileName: "donaturlist",
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
        // groupPanel: {
        //     visible: true
        // },
        columns: [
            {
              dataField: "donatur_kode",
              caption: "Kode Agniya",
              visible:false,
            },{
              dataField: "donatur_nama",
              caption: "Nama",
            },{
              dataField: "donatur_email",
              caption: "Alamat Email",
            },{
              dataField: "donatur_telepon",
              caption: "Telepon",
            },{
              dataField: "donatur_tmp_lahir",
              caption: "Tempat Lahir",
              visible:false,
            },{
              dataField: "donatur_tgl_lahir",
              caption: "Tanggal Lahir",
              visible:false,
            },{
              dataField: "donatur_gender",
              caption: "Jenis Kelamin",
              visible:false,
            },{
              dataField: "donatur_agama",
              caption: "Agama",
              visible:false,
            },{
              dataField: "donatur_alamat",
              caption: "Alamat",
              visible:false,  
            },{
              dataField: "donatur_provinsi",
              caption: "Provinsi",
              visible:false,
            },{
              dataField: "donatur_kota",
              caption: "Kota",
              visible:false,
            },{
              dataField: "donatur_kecamatan",
              caption: "Kecamatan",
              visible:false,
            },{
              dataField: "donatur_kelurahan",
              caption: "Kelurahan",
              visible:false,
            },{
              dataField: "donatur_kode_pos",
              caption: "Kode Pos",
              visible:false,
            },{
              dataField: "donatur_status",
              caption: "Status",
            },
            
        ],
    });
});
</script>
@endsection
