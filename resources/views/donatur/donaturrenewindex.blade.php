@extends('layouts.menus')
@section('content')
    <div class="long-title"><h3>Daftar Agniya</h3></div>
    {!! Form::open(['id' => 'frm','route' => 'donatur.pembaharuan.main', 'class' => 'form-horizontal']) !!}
        <div id="toolbar"></div>
        <div class="second-group">
            <div id="gridProduk"></div>
        </div>
        <input id="txtDonaturId" type="text" name="donatur_id" class="form-control" hidden >
        <input id="txtDonaturState" type="text" name="donatur_state" class="form-control" hidden>
    {!! Form::close()!!}
@endsection

@section('script')
<script type="text/javascript">
$(function(){
    $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    $("#gridProduk").dxDataGrid({
        dataSource: {!! $donatur !!},
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
              dataField: "donatur_kode",
              caption: "Kode Donatur",
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
              dataField: "donatur_status",
              caption: "Status",
            },
            
        ],
        onSelectionChanged: function (selectedItems) {
            var data = selectedItems.selectedRowsData[0];
            $("#txtDonaturId").val(data.id);
          },
    });
    $("#toolbar").dxToolbar({
    items: [{
        location: 'center',
        locateInMenu: 'never',
        template: function() {
            return $("<div class='toolbar-label'><b>Pembaharuan Data Agniya</b></div>");
        }
    },{
        location: 'after',
        widget: 'dxButton',
        locateInMenu: 'auto',
        options: {
            icon: "edit",
            hint: 'Update Data Agniya',
            useSubmitBehavior: true,
            onClick: function(e) {      
            var txtDonaturId=document.getElementById("txtDonaturId").value;
            if(txtDonaturId==""){
                DevExpress.ui.notify({
                    message: "Silakan Pilih Donatur.",
                    position: {
                        my: "center top",
                        at: "center top"
                    }
                }, "warning", 3000);
                e.preventDefault();
                return false;
            }
            $("#txtDonaturState").val("UPDATE"); //kirim perintah update ke server
            }
        }
    },{
        location: 'after',
        widget: 'dxButton',
        locateInMenu: 'auto',
        options: {
            icon: "trash",
            hint: 'Hapus Data Donatur',
            useSubmitBehavior: true,
            onClick: function(e) {      
            var txtDonaturId=document.getElementById("txtDonaturId").value;
            var txtDonaturState=document.getElementById("txtDonaturState").value;
            if(txtDonaturId==""){
                DevExpress.ui.notify({
                    message: "Silakan Pilih Donatur.",
                    position: {
                        my: "center top",
                        at: "center top"
                    }
                }, "warning", 3000);
                e.preventDefault();
                return false;
            }
            if(txtDonaturState!="0"){
                DevExpress.ui.notify({
                    message: "Proses Hapus Donatur",
                    position: {
                        my: "center top",
                        at: "center top"
                    }
                }, "error", 3000);
                e.preventDefault();
                return false;
            }
            $("#txtDonaturState").val("DELETE"); //kirim perintah hapus ke server
            }
        }
        }]
    });
});
</script>
@endsection
