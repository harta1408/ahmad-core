@extends('layouts.menus')
@section('content')
    <div class="long-title"><h3>Daftar Donasi Aktif</h3></div>
    {!! Form::open(['id' => 'frm','route' => 'donasi.random.main', 'class' => 'form-horizontal']) !!}
        <div id="toolbar"></div>
        <div class="dx-field">
            <div class="dx-field-label">Jumlah Santri Tersedia</div>
            <div class="dx-field-value">
                <div id="txtSantriTersedia"></div>
            </div>
        </div>    
        <div class="dx-field">
            <div class="dx-field-label">Jumlah Pendamping Tersedia</div>
            <div class="dx-field-value">
                <div id="txtPendampingTersedia"></div>
            </div>
        </div>
        <div class="dx-field">
            <div class="dx-field-label">Jumlah Santri Diperlukan</div>
            <div class="dx-field-value">
                <div id="txtSantriDibutuhkan"></div>
            </div>
        </div>

        <div class="second-group">
            <div id="form"></div>
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
    $("#txtSantriTersedia").dxTextBox({
        value: "{!!$santritersedia!!}",
        readOnly: true,
    });
    
    $("#txtPendampingTersedia").dxTextBox({
        value: "{!!$pendampingtersedia!!}",
        readOnly: true,
    });
    $("#txtSantriDibutuhkan").dxTextBox({
        value: "0",
        readOnly: true,
    }); 

    var gridDataSource = new DevExpress.data.DataSource({
        load: function (loadOptions) {
            return $.ajax({
                url: "{{route('donasi.random.load')}}"
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
              dataField: "donasi_sisa_santri",
              caption: "Jumlah Santri",
            },
            
        ],
        onSelectionChanged: function (selectedItems) {
            var data = selectedItems.selectedRowsData;
            if(data.length > 0){
                var jmlsantriterpilih=eval($.map(data, function(value) {
                    return value.donasi_jumlah_santri ;
                }).join("+"));
                $("#txtSantriDibutuhkan").dxTextBox("instance").option("value",jmlsantriterpilih);
            }else{
                $("#txtSantriDibutuhkan").dxTextBox("instance").option("value",0);
            } 




            // var data = selectedItems.selectedRowsData[0];
            // console.log(data.donasi_jumlah_santri);
            // if(data!= null){
            //     var jmlsantriterpilih=parseInt($("#txtSantriDibutuhkan").dxTextBox("instance").option("value"))+
            //     data.donasi_jumlah_santri;
            //     $("#txtSantriDibutuhkan").dxTextBox("instance").option("value",jmlsantriterpilih);
            // }else 
            //     $("#txtSantriDibutuhkan").dxTextBox("instance").option("value",0);

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
                var jmlpendamping=$("#txtPendampingTersedia").dxTextBox("instance").option("value");
                var jmlsantritersedia=$("#txtSantriTersedia").dxTextBox("instance").option("value");
                var jmlsantriterpilih=$("#txtSantriDibutuhkan").dxTextBox("instance").option("value");
                if(jmldata==0){
                    swal({
                        title: "Ada Kesalahan",
                        icon: 'error',
                        text: "Proses tidak dapat dilanjutkan, Silakan Pilih Donasi",
                        value: true,
                        visible: true,
                        className: "",
                        closeModal: true,
                    });
                    e.preventDefault();
                    return false;                    
                }
                if(jmlpendamping==0){
                    swal({
                        title: "Ada Kesalahan",
                        icon: 'error',
                        text: "Proses tidak dapat dilanjutkan, Jumlah Pendamping tidak mencukupi",
                        value: true,
                        visible: true,
                        className: "",
                        closeModal: true,
                    });
                    e.preventDefault();
                    return false;                    
                }
                if(jmlsantriterpilih>jmlsantritersedia){
                    swal({
                        title: "Ada Kesalahan",
                        icon: 'error',
                        text: "Proses tidak dapat dilanjutkan, Jumlah Santri tidak mencukupi",
                        value: true,
                        visible: true,
                        className: "",
                        closeModal: true,
                    });
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
