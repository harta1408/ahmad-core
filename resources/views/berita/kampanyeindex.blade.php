@extends('layouts.menus')
@section('content')
    <div class="long-title"><h3>Daftar Kampanye</h3></div>
    {!! Form::open(['id' => 'frm','route' => 'berita.kampanye.main', 'class' => 'form-horizontal']) !!}
        <div id="toolbar"></div>
        <div class="second-group">
            <div id="gridData"></div>
        </div>
        <input id="txtBeritaId" type="text" name="berita_id" class="form-control" hidden >
        <input id="txtBeritaState" type="text" name="berita_state" class="form-control" hidden>
        <input id="txtBeritaJenis" type="text" name="berita_jenis" class="form-control" hidden>
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
                url: "{{route('berita.kampanye.load')}}"
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
              dataField: "berita_jenis",
              caption: "Jenis",
            },{
              dataField: "berita_index",
              caption: "No Urut",
            },{
              dataField: "berita_judul",
              caption: "Judul",
            },{
              dataField: "berita_entitas",
              caption: "Entitas",
            },
            
        ],
        onSelectionChanged: function (selectedItems) {
            var data = selectedItems.selectedRowsData[0];
            $("#txtBeritaId").val(data.id);
            $("#txtBeritaJenis").val(data.berita_jenis);
          },
    });
    $("#toolbar").dxToolbar({
    items: [{
        location: 'center',
        locateInMenu: 'never',
        template: function() {
            return $("<div class='toolbar-label'><b>Pembaharuan Berita Kampanye</b></div>");
        }
    },{
        location: 'after',
        widget: 'dxButton',
        locateInMenu: 'auto',
        options: {
            icon: "plus",
            hint: 'Tambah Berita Baru',
            useSubmitBehavior: true,
            onClick: function(e) {      
                $("#txtBeritaState").val("NEW"); //kirim perintah tambah ke server
            }
        }
    },{
        location: 'after',
        widget: 'dxButton',
        locateInMenu: 'auto',
        options: {
            icon: "edit",
            hint: 'Update Data Berita',
            useSubmitBehavior: true,
            onClick: function(e) {      
            var txtBeritaId=document.getElementById("txtBeritaId").value;
            if(txtBeritaId==""){
                DevExpress.ui.notify({
                    message: "Silakan Pilih Berita",
                    position: {
                        my: "center top",
                        at: "center top"
                    }
                }, "warning", 3000);
                e.preventDefault();
                return false;
            }
            $("#txtBeritaState").val("UPDATE"); //kirim perintah update ke server
            }
        }
    },{
        location: 'after',
        widget: 'dxButton',
        locateInMenu: 'auto',
        options: {
            icon: "video",
            hint: 'Preview Video',
            useSubmitBehavior: true,
            onClick: function(e) {      
            var txtBeritaId=document.getElementById("txtBeritaId").value;
            if(txtBeritaId==""){
                DevExpress.ui.notify({
                    message: "Silakan Pilih Berita",
                    position: {
                        my: "center top",
                        at: "center top"
                    }
                }, "warning", 3000);
                e.preventDefault();
                return false;
            }
            $("#txtBeritaState").val("PREVIEW"); //kirim perintah update ke server
            }
        }
    },{
        location: 'after',
        widget: 'dxButton',
        locateInMenu: 'auto',
        options: {
            icon: "trash",
            hint: 'Hapus Data Berita',
            useSubmitBehavior: true,
            onClick: function(e) {      
            var txtBeritaId=document.getElementById("txtBeritaId").value;
            var txtBeritaState=document.getElementById("txtBeritaState").value;
            if(txtBeritaId==""){
                DevExpress.ui.notify({
                    message: "Silakan Pilih Berita.",
                    position: {
                        my: "center top",
                        at: "center top"
                    }
                }, "warning", 3000);
                e.preventDefault();
                return false;
            }
            if(txtBeritaState!="0"){
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
            $("#txtBeritaState").val("DELETE"); //kirim perintah hapus ke server
            }
        }
        }],
    });
});
</script>
@endsection
