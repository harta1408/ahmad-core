@extends('layouts.menus')
@section('content')
    <div class="long-title"><h3>Daftar Referral</h3></div>
    {!! Form::open(['id' => 'frm','route' => 'referral.new.menu.index', 'class' => 'form-horizontal']) !!}
        <div id="toolbar"></div>
        <div class="second-group">
            <div id="gridData"></div>
        </div>
        <input id="txtBeritaId" type="text" name="berita_id" class="form-control" hidden >
        <input id="txtBeritaState" type="text" name="berita_state" class="form-control" hidden>
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
                url: "{{route('referral.create')}}"
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
              dataField: "referral_entitas_kode",
              caption: "Pengirim",
              width:100,
            },{
              dataField: "referral_telepon",
              caption: "No Tujuan",
            },{
              dataField: "referral_web_link",
              caption: "Web Link",
            },
            
        ],
        onSelectionChanged: function (selectedItems) {
            var data = selectedItems.selectedRowsData[0];
            $("#txtBeritaId").val(data.id);
          },
    });
    $("#toolbar").dxToolbar({
    items: [{
        location: 'center',
        locateInMenu: 'never',
        template: function() {
            return $("<div class='toolbar-label'><b>Pembaharuan Referral</b></div>");
        }
    },{
        location: 'after',
        widget: 'dxButton',
        locateInMenu: 'auto',
        options: {
            icon: "plus",
            hint: 'Tambah Referral Baru',
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
            icon: "trash",
            hint: 'Hapus Data Referral',
            useSubmitBehavior: true,
            onClick: function(e) {      
            var txtBeritaId=document.getElementById("txtBeritaId").value;
            var txtBeritaState=document.getElementById("txtBeritaState").value;
            if(txtBeritaId==""){
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
        }]
    });
});
</script>
@endsection
