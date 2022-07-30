@extends('layouts.menus')
@section('content')
    <div class="long-title"><h3>Daftar Santri Belum Di Otorisasi</h3></div>
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
    var gridDataSource = new DevExpress.data.DataSource({
        load: function() {
            return $.getJSON("{{URL::to('dashboard/santri/otorisasi/load')}}")
                    .fail(function() { throw "Data loading error" });
            },
        update: function (key, activestate) {
            var id= key.id;
            $.ajax({
                url: "{{URL::to('dashboard/santri/otorisasi/update')}}"+"/"+id,
                method: "PUT",
                data: {activestate},
                dataType: "json",
                success: function (data) {
                    if(data.code != 200) {
                        swal({
                            title: data.status,
                            icon: data.status,
                            text: data.message,
                            value: true,
                            visible: true,
                            className: "",
                            closeModal: true,
                        });
                    }else {
                        DevExpress.ui.notify("Otoriasi Berhasil"); 
                    }
                    $("#tabProd").dxDataGrid("instance").refresh();
                    return false;
                },
            });
            return false;
        }
    });

    $("#gridData").dxDataGrid({
        dataSource: gridDataSource,
        keyExpr: "id",
        showBorders: true,
        export: {
            enabled: true,
            fileName: "santrilist",
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
        editing: {
          mode: "batch",
          allowUpdating: true,
          useIcons: true,
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
              dataType:"boolean",
              caption:"Otorisasi",
              editorType: "dxSwitch", 
              editorOptions: { 
                switchedOffText:"Tidak",
                switchedOnText:"Ya",
                width:80,
              },  
            },
        ],
        onEditorPreparing: function (e) {  
            if (e.parentType == "dataRow" && e.dataField == "santri_status")  {
                e.editorName = "dxSwitch"; 
            }
        },
      onEditingStart: function(e){
          if (e.column.dataField != "santri_status" ) {
             e.cancel = true;
          }
       },
       onRowUpdated:function(e){
            DevExpress.ui.notify("Santri Berhasil di Aktifasi"); 
       },
    });
});
</script>
@endsection
