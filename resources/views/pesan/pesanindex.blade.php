@extends('layouts.menus')
@section('content')
    <div class="long-title"><h3>Daftar Pesan</h3></div>
    {!! Form::open(['id' => 'frm','route' => 'pesan.main', 'class' => 'form-horizontal']) !!}
        <div id="toolbar"></div>
        <div class="second-group">
            <div id="gridData"></div>
        </div>
        <input id="txtPesanId" type="text" name="pesan_id" class="form-control" hidden >
        <input id="txtPesanState" type="text" name="pesan_state" class="form-control" hidden>
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
    var gridDataSource = new DevExpress.data.DataSource({
        load: function (loadOptions) {
            return $.ajax({
                url: "{{route('pesan.create')}}"
            })
        },
    });
    $("#gridData").dxDataGrid({
        dataSource: gridDataSource,
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
              dataField: "pesan_entitas",
              caption: "Entitas Tujuan",
            },{
              dataField: "pesan_judul",
              caption: "Judul",
            },{
              dataField: "pesan_waktu_kirim",
              caption: "Waktu Kirim",
            },
            
        ],
        onSelectionChanged: function (selectedItems) {
            var data = selectedItems.selectedRowsData[0];
            $("#txtPesanId").val(data.id);
          },
    });
    $("#toolbar").dxToolbar({
    items: [{
        location: 'center',
        locateInMenu: 'never',
        template: function() {
            return $("<div class='toolbar-label'><b>Pembaharuan Data Berita</b></div>");
        }
    },{
        location: 'after',
        widget: 'dxButton',
        locateInMenu: 'auto',
        options: {
            icon: "plus",
            hint: 'Tambah Pengingat Baru',
            useSubmitBehavior: true,
            onClick: function(e) {      
                $("#txtPesanState").val("NEW"); //kirim perintah tambah ke server
            }
        }
    },{
        location: 'after',
        widget: 'dxButton',
        locateInMenu: 'auto',
        options: {
            icon: "edit",
            hint: 'Update Data Pengingat',
            useSubmitBehavior: true,
            onClick: function(e) {      
            var txtPesanId=document.getElementById("txtPesanId").value;
            if(txtPesanId==""){
                DevExpress.ui.notify({
                    message: "Silakan Pilih Pengingat",
                    position: {
                        my: "center top",
                        at: "center top"
                    }
                }, "warning", 3000);
                e.preventDefault();
                return false;
            }
            $("#txtPesanState").val("UPDATE"); //kirim perintah update ke server
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
            var txtPesanId=document.getElementById("txtPesanId").value;
            var txtPesanState=document.getElementById("txtPesanState").value;
            if(txtPesanId==""){
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
            if(txtPesanState!="0"){
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
            $("#txtPesanState").val("DELETE"); //kirim perintah hapus ke server
            }
        }
        }]
    });
});
</script>
@endsection
