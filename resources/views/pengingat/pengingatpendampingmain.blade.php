@extends('layouts.menus')
@section('content')
    <div class="long-title"><h3>Daftar Pengingat Santri Dari Pendamping</h3></div>
    {!! Form::open(['id' => 'frm','route' => 'pengingat.santri.main', 'class' => 'form-horizontal']) !!}
        <div class="second-group">
            <div id="gridData"></div>
        </div>
        <input id="txtPengingatId" type="text" name="pengingat_id" class="form-control" hidden >
        <input id="txtPengingatState" type="text" name="pengingat_state" class="form-control" hidden>
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
    var pengingat={!!$pengingat!!};
    console.log(pengingat);

    $("#gridData").dxDataGrid({
        dataSource: pengingat,
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
              dataField: "pengingat_jenis",
              caption: "Jenis",
            },{
              dataField: "pendamping.0.pendamping_nama",
              caption: "Pengirim",  
            },{
              dataField: "santri.0.santri_nama",
              caption: "Santri",  
            },{
              dataField: "pengingat_judul",
              caption: "Judul",
            },
            
        ],
        onSelectionChanged: function (selectedItems) {
            var data = selectedItems.selectedRowsData[0];
            $("#txtPengingatId").val(data.id);
          },
    });
    $("#toolbar").dxToolbar({
    items: [{
        location: 'center',
        locateInMenu: 'never',
        template: function() {
            return $("<div class='toolbar-label'><b>Pembaharuan Data Pengingat</b></div>");
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
                $("#txtPengingatState").val("NEW"); //kirim perintah tambah ke server
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
            var txtPengingatId=document.getElementById("txtPengingatId").value;
            if(txtPengingatId==""){
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
            $("#txtPengingatState").val("UPDATE"); //kirim perintah update ke server
            }
        }
        }]
    });
});
</script>
@endsection
