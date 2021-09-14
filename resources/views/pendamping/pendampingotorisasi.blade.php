@extends('layouts.menus')
@section('content')
    <div class="long-title"><h3>Daftar Pendamping Belum Di Otorisasi</h3></div>
    <span>Untuk Melihat Detail Silakan Gunakan Menu Pendamping->Pembaharuan</span>
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
            return $.getJSON("{{URL::to('dashboard/pendamping/otorisasi/load')}}")
                    .fail(function() { throw "Data loading error" });
            },
        update: function (key, activestate) {
            var id= key.id;
            $.ajax({
                url: "{{URL::to('dashboard/pendamping/otorisasi/update')}}"+"/"+id,
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
        // "export": {
        //     enabled: true,
        //     fileName: "pendampinglist",
        // },
        selection: {
            mode: "single"
        },
        hoverStateEnabled: true,
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
              dataField: "pendamping_kode",
              caption: "Kode pendamping",
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
              dataField: "pendamping_status", 
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
        onSelectionChanged: function (selectedItems) {
            var data = selectedItems.selectedRowsData[0];
            $("#txtPendampingId").val(data.id);
        },
        onEditorPreparing: function (e) {  
            if (e.parentType == "dataRow" && e.dataField == "pendamping_status")  {
                e.editorName = "dxSwitch"; 
            }
        },
        onEditingStart: function(e){
          if (e.column.dataField != "pendamping_status" ) {
             e.cancel = true;
          }
        },
        onRowUpdated:function(e){
                DevExpress.ui.notify("pendamping Berhasil di Aktifasi"); 
        },

    });

});
</script>
@endsection
