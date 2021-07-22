@extends('layouts.menus')
@section('content')
    <div class="long-title"><h3>Daftar Donasi Pending</h3></div>
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
                url: "{{route('donasi.pending.load')}}"
            })
        },
        update: function(key, value) {
            var kunci= key.id;
            $.ajax({
                url: "{{URL::to('dashboard/donasi/pending/update')}}"+"/"+kunci,
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
            mode: "single",
        },
        editing: {
            mode: "batch",
            allowUpdating: true,
            useIcons: true,
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
                dataField: "donasi.donasi_no",
                caption: "No Donasi",
            },{
                dataField: "donasi.donatur.donatur_nama",
                caption: "Donatur",
            },{
                dataField: "donasi.donasi_cara_bayar",
                caption: "Cara Pembayaran",
                lookup: {
                    dataSource: [{"donasi_cara_bayar":"1","donasi_cara_bayar_desc":"Harian"},
                            {"donasi_cara_bayar":"2","donasi_cara_bayar_desc":"Pekanan"},
                            {"donasi_cara_bayar":"3","donasi_cara_bayar_desc":"Bulanan"},
                            {"donasi_cara_bayar":"4","donasi_cara_bayar_desc":"Tunai"}],
                    valueExpr: "donasi_cara_bayar",
                    displayExpr: "donasi_cara_bayar_desc",
                },
            },{
                dataField: "cicilan_ke",
                caption: "Cicilan Ke",
            },{
                dataField: "cicilan_jatuh_tempo",
                caption: "Jatuh Tempo",
                dataType:"date",
                format:"dd-MM-yyyy",    
            },{
                dataField: "cicilan_status",
                caption: "Status Cicilan",
                lookup: {
                    dataSource: [{"cicilan_status":"1","cicilan_status_desc":"Belum Diterima"},
                            {"cicilan_status":"2","cicilan_status_desc":"Sudah Diterima"}],
                    valueExpr: "cicilan_status",
                    displayExpr: "cicilan_status_desc",
                },
                validationRules:[{
                        type: "required",
                        message: "Pilih dari daftar",}],
            },{
                dataField: "cicilan_tanggal_bayar",
                caption: "Tanggal Bayar",
                dataType:"date",
                format:"shortDate",     
            },
            
        ],
        onEditingStart: function(e){
        if (e.column.dataField != "cicilan_tanggal_bayar" && e.column.dataField != "cicilan_status") {
             e.cancel = true;
          }
        },
    });
});
</script>
@endsection
