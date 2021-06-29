@extends('layouts.menus')
@section('content')
    <div class="long-title"><h3>Daftar Hadist</h3></div>
    {!! Form::open(['id' => 'frm','route' => 'hadist.main', 'class' => 'form-horizontal']) !!}
        <div id="toolbar"></div>
        <div class="second-group">
            <div id="gridData"></div>
        </div>
        <input id="txtHadistId" type="text" name="hadist_id" class="form-control" hidden >
        <input id="txtHadistState" type="text" name="hadist_state" class="form-control" hidden>
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
                url: "{{route('hadist.create')}}"
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
              dataField: "hadist_jenis",
              caption: "Jenis",
              width:100,
            },{
              dataField: "hadist_isi",
              caption: "Isi",
            },
            
        ],
        onSelectionChanged: function (selectedItems) {
            var data = selectedItems.selectedRowsData[0];
            $("#txtHadistId").val(data.id);
          },
    });
    $("#toolbar").dxToolbar({
    items: [{
        location: 'center',
        locateInMenu: 'never',
        template: function() {
            return $("<div class='toolbar-label'><b>Pembaharuan Hadist dan Do'a</b></div>");
        }
    },{
        location: 'after',
        widget: 'dxButton',
        locateInMenu: 'auto',
        options: {
            icon: "plus",
            hint: 'Tambah Hadist atau Doa',
            useSubmitBehavior: true,
            onClick: function(e) {      
                $("#txtHadistState").val("NEW"); //kirim perintah tambah ke server
            }
        }
    },{
        location: 'after',
        widget: 'dxButton',
        locateInMenu: 'auto',
        options: {
            icon: "edit",
            hint: 'Update Hadist atau Doa',
            useSubmitBehavior: true,
            onClick: function(e) {      
            var txtHadistId=document.getElementById("txtHadistId").value;
            if(txtHadistId==""){
                DevExpress.ui.notify({
                    message: "Silakan Pilih Hadist atau Doa",
                    position: {
                        my: "center top",
                        at: "center top"
                    }
                }, "warning", 3000);
                e.preventDefault();
                return false;
            }
            $("#txtHadistState").val("UPDATE"); //kirim perintah update ke server
            }
        }
    },{
        location: 'after',
        widget: 'dxButton',
        locateInMenu: 'auto',
        options: {
            icon: "message",
            hint: 'Kirim Hadist atau Doa',
            useSubmitBehavior: true,
            onClick: function(e) {      
            var txtHadistId=document.getElementById("txtHadistId").value;
            if(txtHadistId==""){
                DevExpress.ui.notify({
                    message: "Silakan Pilih Hadist atau Doa",
                    position: {
                        my: "center top",
                        at: "center top"
                    }
                }, "warning", 3000);
                e.preventDefault();
                return false;
            }
            $("#txtHadistState").val("SEND"); //kirim perintah update ke server
            }
        }
    },{
        location: 'after',
        widget: 'dxButton',
        locateInMenu: 'auto',
        options: {
            icon: "trash",
            hint: 'Hapus Hadist atau Doa',
            useSubmitBehavior: true,
            onClick: function(e) {      
            var txtHadistId=document.getElementById("txtHadistId").value;
            var txtHadistState=document.getElementById("txtHadistState").value;
            if(txtHadistId==""){
                DevExpress.ui.notify({
                    message: "Silakan Pilih Hadist atau Doa.",
                    position: {
                        my: "center top",
                        at: "center top"
                    }
                }, "warning", 3000);
                e.preventDefault();
                return false;
            }
            if(txtHadistState!="0"){
                DevExpress.ui.notify({
                    message: "Proses Hapus Hadist atau Doa",
                    position: {
                        my: "center top",
                        at: "center top"
                    }
                }, "error", 3000);
                e.preventDefault();
                return false;
            }
            $("#txtHadistState").val("DELETE"); //kirim perintah hapus ke server
            }
        }
        }]
    });
});
</script>
@endsection
