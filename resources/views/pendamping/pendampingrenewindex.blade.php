@extends('layouts.menus')
@section('content')
    <div class="long-title"><h3>Daftar Pendamping</h3></div>
    {!! Form::open(['id' => 'frm','route' => 'pendamping.pembaharuan.main', 'class' => 'form-horizontal']) !!}
        <div id="toolbar"></div>
        <div class="second-group">
            <div id="gridData"></div>
        </div>
        <input id="txtPendampingId" type="text" name="pendamping_id" class="form-control" hidden >
        <input id="txtPendampingState" type="text" name="pendamping_state" class="form-control" hidden>
    {!! Form::close()!!}
@endsection

@section('script')
<script type="text/javascript">
$(function(){
    $("#gridData").dxDataGrid({
        dataSource: {!! $pendamping !!},
        keyExpr: "id",
        showBorders: true,
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
        columns: [
            {
              dataField: "pendamping_kode",
              caption: "Kode Pendamping",
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
              dataField: "pendamping_status",
              caption: "Status",
            },
            
        ],
        onSelectionChanged: function (selectedItems) {
            var data = selectedItems.selectedRowsData[0];
            $("#txtPendampingId").val(data.id);
          },
    });
    $("#toolbar").dxToolbar({
    items: [{
        location: 'center',
        locateInMenu: 'never',
        template: function() {
            return $("<div class='toolbar-label'><b>Pembaharuan Data pendamping</b></div>");
        }
    },{
        location: 'after',
        widget: 'dxButton',
        locateInMenu: 'auto',
        options: {
            icon: "edit",
            hint: 'Update Data pendamping',
            useSubmitBehavior: true,
            onClick: function(e) {      
            var txtPendampingId=document.getElementById("txtPendampingId").value;
            if(txtPendampingId==""){
                DevExpress.ui.notify({
                    message: "Silakan Pilih pendamping.",
                    position: {
                        my: "center top",
                        at: "center top"
                    }
                }, "warning", 3000);
                e.preventDefault();
                return false;
            }
            $("#txtPendampingState").val("UPDATE"); //kirim perintah update ke server
            }
        }
    },{
        location: 'after',
        widget: 'dxButton',
        locateInMenu: 'auto',
        options: {
            icon: "trash",
            hint: 'Hapus Data pendamping',
            useSubmitBehavior: true,
            onClick: function(e) {      
            var txtPendampingId=document.getElementById("txtPendampingId").value;
            var txtPendampingState=document.getElementById("txtPendampingState").value;
            if(txtPendampingId==""){
                DevExpress.ui.notify({
                    message: "Silakan Pilih pendamping.",
                    position: {
                        my: "center top",
                        at: "center top"
                    }
                }, "warning", 3000);
                e.preventDefault();
                return false;
            }
            if(txtPendampingState!="0"){
                DevExpress.ui.notify({
                    message: "Proses Hapus pendamping",
                    position: {
                        my: "center top",
                        at: "center top"
                    }
                }, "error", 3000);
                e.preventDefault();
                return false;
            }
            $("#txtPendampingState").val("DELETE"); //kirim perintah hapus ke server
            }
        }
        }]
    });
});
</script>
@endsection
