@extends('layouts.menus')
@section('content')
    <div class="long-title"><h3>Daftar Donasi Aktif</h3></div>
    {!! Form::open(['id' => 'frm','route' => 'donasi.main', 'class' => 'form-horizontal']) !!}
        <div id="toolbar"></div>
        <div class="second-group">
            <div id="gridData"></div>
        </div>
        <input id="txtDonasiSelected" type="text" name="donasi_selected" class="form-control" hidden >
        <input id="txtDonasiState" type="text" name="donasi_state" class="form-control" hidden>
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
                url: "{{route('donasi.create')}}"
            })
        },
    });
    $("#gridData").dxDataGrid({
        dataSource: gridDataSource,
        keyExpr: "id",
        showBorders: true,
        selection: {
            mode: "multiple",
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
              dataField: "donasi_no",
              caption: "No Donasi",
            },{
              dataField: "donatur.donatur_nama",
              caption: "Donatur",
            },{
              dataField: "donasi_jumlah_santri",
              caption: "Jumlah Santri",
            },
            
        ],
        onSelectionChanged: function (selectedItems) {
            // var data = selectedItems.selectedRowsData[0];
            // var data = selectedItems.selectedRowsData;
            // console.log(data.length);
            // $("#txtDonasiSelected").val(data);
          },
    });
    $("#toolbar").dxToolbar({
    items: [{
        location: 'center',
        locateInMenu: 'never',
        template: function() {
            return $("<div class='toolbar-label'><b>Donasi Belum Di Alokasikan Kepada Santri</b></div>");
        }
    },{
        location: 'after',
        widget: 'dxButton',
        locateInMenu: 'auto',
        options: {
            icon: "fieldchooser",
            hint: 'Pilih Santri Otomatis',
            useSubmitBehavior: true,
            onClick: function(e) {      
                var dataGrid = $("#gridData").dxDataGrid("instance");
                var selectedRowsData = dataGrid.getSelectedRowsData();
                var jmldata=selectedRowsData.length;
                if(jmldata==0){
                    DevExpress.ui.notify({
                        message: "Silakan Pilih Donasi",
                        position: {
                            my: "center top",
                            at: "center top"
                        }
                    }, "warning", 3000);
                    e.preventDefault();
                    return false;                    
                }
                var selecteddonasi="";
                for (let i = 0; i < selectedRowsData.length; i++) {
                    selecteddonasi += selectedRowsData[i]["id"] + ",";
                }
                $("#txtDonasiSelected").val(selecteddonasi);
                $("#txtDonasiState").val("NEW"); //kirim perintah tambah ke server
            }
        }
    }]
    });
});
</script>
@endsection
