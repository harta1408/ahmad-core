@extends('layouts.menus')
@section('content')
    <div class="long-title"><h3>Daftar Santri</h3></div>
    {!! Form::open(['id' => 'frm','route' => 'santri.pembaharuan.main', 'class' => 'form-horizontal']) !!}
        <div id="toolbar"></div>
        <div class="second-group">
            <div id="gridData"></div>
        </div>
        <input id="txtSantriId" type="text" name="santri_id" class="form-control" hidden >
        <input id="txtSantriState" type="text" name="santri_state" class="form-control" hidden>
    {!! Form::close()!!}
@endsection

@section('script')
<script type="text/javascript">
$(function(){
    $("#gridData").dxDataGrid({
        dataSource: {!! $santri !!},
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
              dataField: "santri_status",
              caption: "Status",
            },
            
        ],
        onSelectionChanged: function (selectedItems) {
            var data = selectedItems.selectedRowsData[0];
            $("#txtSantriId").val(data.id);
          },
    });
    $("#toolbar").dxToolbar({
    items: [{
        location: 'center',
        locateInMenu: 'never',
        template: function() {
            return $("<div class='toolbar-label'><b>Pembaharuan Data Santri</b></div>");
        }
    },{
        location: 'after',
        widget: 'dxButton',
        locateInMenu: 'auto',
        options: {
            icon: "edit",
            hint: 'Update Data santri',
            useSubmitBehavior: true,
            onClick: function(e) {      
            var txtSantriId=document.getElementById("txtSantriId").value;
            if(txtSantriId==""){
                DevExpress.ui.notify({
                    message: "Silakan Pilih santri.",
                    position: {
                        my: "center top",
                        at: "center top"
                    }
                }, "warning", 3000);
                e.preventDefault();
                return false;
            }
            $("#txtSantriState").val("UPDATE"); //kirim perintah update ke server
            }
        }
    },{
        location: 'after',
        widget: 'dxButton',
        locateInMenu: 'auto',
        options: {
            icon: "trash",
            hint: 'Hapus Data santri',
            useSubmitBehavior: true,
            onClick: function(e) {      
            var txtSantriId=document.getElementById("txtSantriId").value;
            var txtSantriState=document.getElementById("txtSantriState").value;
            if(txtSantriId==""){
                DevExpress.ui.notify({
                    message: "Silakan Pilih santri.",
                    position: {
                        my: "center top",
                        at: "center top"
                    }
                }, "warning", 3000);
                e.preventDefault();
                return false;
            }
            if(txtSantriState!="0"){
                DevExpress.ui.notify({
                    message: "Proses Hapus santri",
                    position: {
                        my: "center top",
                        at: "center top"
                    }
                }, "error", 3000);
                e.preventDefault();
                return false;
            }
            $("#txtSantriState").val("DELETE"); //kirim perintah hapus ke server
            }
        }
        }]
    });
});
</script>
@endsection
