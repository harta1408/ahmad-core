@extends('layouts.menus')
@section('content')
    <div class="long-title"><h3>Daftar Soal</h3></div>
    {!! Form::open(['id' => 'frm','route' => 'soal.main', 'class' => 'form-horizontal']) !!}
        <div id="toolbar"></div>
        <div class="second-group">
            <div id="gridData"></div>
        </div>
        <input id="txtSoalId" type="text" name="soal_id" class="form-control" hidden >
        <input id="txtSoalState" type="text" name="soal_state" class="form-control" hidden>
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
                url: "{{route('soal.create')}}"
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
              dataField: "materi.materi_deskripsi",
              caption: "Materi",
            },{
              dataField: "soal_no",
              caption: "No Soal",
            },{
              dataField: "materi.materi_level",
              caption: "Level Materi",
            },{
              dataField: "soal_jenis",
              caption: "Jenis Soal",
            },
            
        ],
        onSelectionChanged: function (selectedItems) {
            var data = selectedItems.selectedRowsData[0];
            $("#txtSoalId").val(data.id);
          },
    });
    $("#toolbar").dxToolbar({
    items: [{
        location: 'center',
        locateInMenu: 'never',
        template: function() {
            return $("<div class='toolbar-label'><b>Pembaharuan Data Soal</b></div>");
        }
    },{
        location: 'after',
        widget: 'dxButton',
        locateInMenu: 'auto',
        options: {
            icon: "plus",
            hint: 'Buat Soal Baru',
            useSubmitBehavior: true,
            onClick: function(e) {      
                $("#txtSoalState").val("NEW"); //kirim perintah update ke server
            }
        }
    },{
        location: 'after',
        widget: 'dxButton',
        locateInMenu: 'auto',
        options: {
            icon: "edit",
            hint: 'Perbaharui Soal',
            useSubmitBehavior: true,
            onClick: function(e) {      
                var txtSoalId=document.getElementById("txtSoalId").value;
                if(txtSoalId==""){
                    DevExpress.ui.notify({
                        message: "Silakan Pilih Soal yang akan di perbaharui",
                        position: {
                            my: "center top",
                            at: "center top"
                        }
                    }, "warning", 3000);
                    e.preventDefault();
                    return false;
                }
                $("#txtSoalState").val("UPDATE"); //kirim perintah update ke server
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
            var txtSoalId=document.getElementById("txtSoalId").value;
            var txtSoalState=document.getElementById("txtSoalState").value;
            if(txtSoalId==""){
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
            if(txtSoalState!="0"){
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
            $("#txtSoalState").val("DELETE"); //kirim perintah hapus ke server
            }
        }
        }]
    });
});
</script>
@endsection