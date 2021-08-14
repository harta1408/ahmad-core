@extends('layouts.menus')
@section('content')
    <div class="long-title"><h3>Lacak Pengiriman Produk</h3></div>
    <div id="toolbar"></div>
    <div class="second-group">
        <div id="gridData"></div>
    </div>
    <input id="txtKirimId" type="text" class="form-control" hidden >
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
                url: "{{route('kirimproduk.lacak.load')}}"
            })
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
        onSelectionChanged: function (selectedItems) {
            var data = selectedItems.selectedRowsData[0];
            $("#txtKirimId").val(data.id);
        },
    });
    $("#toolbar").dxToolbar({
    items: [{
        location: 'center',
        locateInMenu: 'never',
        // template: function() {
        //     return $("<div class='toolbar-label'><b>Pembaharuan Data pendamping</b></div>");
        // }
    },{
        location: 'after',
        widget: 'dxButton',
        locateInMenu: 'auto',
        options: {
            icon: "directions",
            hint: 'lacak pengiriman',
            onClick: function(e) {   
                var txtKirimId=document.getElementById("txtKirimId").value;
                if(txtKirimId==""){
                    swal({
                        title: "Pilih Produk",
                        icon: 'error',
                        text:  "Silakan Pilih Produk yang akan di lacak",
                        value: true,
                        visible: true,
                        closeModal: true,
                    });
                    return false;
                }   
                $.ajax({
                    type: 'POST',
                    url: "{{route('kirimproduk.lacak.main')}}",
                    data: JSON.stringify({kirim_id:txtKirimId}),
                    contentType: "application/json; charset=utf-8",
                    success: function (data) {
                    if(data.code != 200) {
                        swal({
                            title: "Validation Error",
                            icon: data.status,
                            text: data.message,
                            value: true,
                            visible: true,
                            className: "",
                            closeModal: true,
                        });
                    }else{
                        swal({
                            title: "OK",
                            icon: data.status,
                            text: data.message,
                            value: true,
                            visible: true,
                            className: "",
                            closeModal: true,
                        })
                        .then((value) => {
                            window.location = "{{URL::to('dashboard/kirimprodtuk/lacak/hasil')}}"+"/"+txtKirimId;
                        });
                    }
                    return false;
                    }, 
                    error: function(jqXHR, textStatus, errorThrown) {
                        swal({
                            title: "Error",
                            icon: 'error',
                            text: textStatus,
                            value: true,
                            visible: true,
                            className: "",
                            closeModal: true,
                        });
                        return false;
                    }
                });
            }
        }
    }]
    });   
});
</script>
@endsection
