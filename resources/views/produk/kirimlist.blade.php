@extends('layouts.menus')
@section('content')
    <div class="long-title"><h3>Daftar Pengiriman Produk</h3></div>
    <div class="second-group">
        <div id="gridData"></div>
    </div>

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
                url: "{{route('kirimproduk.load')}}"
            })
        },
        update: function(key, value) {
            var kunci= key.id;
            $.ajax({
                url: "{{URL::to('dashboard/kirimproduk')}}"+"/"+kunci,
                method: "PUT",
                data: value,
                dataType: "json",
                success: function (data) {
                    if(data.code != 200) {
                        swal({
                            title: data.status,
                            icon: data.status,
                            text: "Ada Kesalahan pada saat Update",
                            value: true,
                            visible: true,
                            className: "",
                            closeModal: true,
                        });
                    }
                    else {
                        swal({
                            title: data.status,
                            icon: data.status,
                            text: data.message,
                            value: true,
                            visible: true,
                            className: "",
                            closeModal: true,
                        });
                    }
                $("#gridData").dxDataGrid("instance").refresh();
                return false;
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    swal({
                        title: "Error",
                        icon: "error",
                        text: jqXHR.responseText,
                        value: true,
                        visible: true,
                        className: "",
                        closeModal: true,
                    });
                return false;
                }
            });
            return false;
        },
    });
    $("#gridData").dxDataGrid({
        dataSource: gridDataSource,
        keyExpr: "id",
        showBorders: true,
        dateSerializationFormat:"yyyy-MM-dd",
        selection: {
            mode: "single"
        },
        editing: {
            mode: "batch",
            allowUpdating: true,
            useIcons: true,
        },
        searchPanel: {
            visible: true
        },
        paging: {
            pageSize: 10
        },
        columns: [
            {
              dataField: "kirim_penerima_nama",
              caption: "Tujuan",
            },{
              dataField: "kirim_no_resi",
              caption: "No Resi",
            },{
              dataField: "kirim_tanggal_kirim",
              caption: "Tanggal Kirim",  
            },{
                dataField: "kirim_status",
                caption: "Status",
                lookup: {
                    dataSource: [{"kirim_status":"1","kirim_status_desc":"Dalam Pengiriman"},
                            {"kirim_status":"2","kirim_status_desc":"Sudah Diterima"}],
                    valueExpr: "kirim_status",
                    displayExpr: "kirim_status_desc",
                },
                validationRules:[{
                        type: "required",
                        message: "Pilih dari daftar",}],
            },{
              dataField: "kirim_tanggal_terima",
              caption: "Tanggal Sampai",
              dataType:"date",
              format:"shortDate",     
            },            
        ],
        onEditingStart: function(e){
        if (e.column.dataField != "kirim_no_resi" && e.column.dataField != "kirim_tanggal_terima" && e.column.dataField != "kirim_status") {
             e.cancel = true;
          }
        },
    });
   
});
</script>
@endsection
