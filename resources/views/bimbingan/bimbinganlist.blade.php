@extends('layouts.menus')
@section('content')
    <div class="long-title"><h3>Daftar Santri Dalam Bimbingan</h3></div>
    <div id="gridData"></div>
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
                url: "{{route('bimbingan.create')}}"
            })
        },
        insert: function (values) {
            $.ajax({
                type: 'POST',
                url: '{{route('kuesioner.store')}}',
                data: values,
                dataType: "json",
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
                    }else {
                        swal({
                            title: "OK",
                            icon: data.status,
                            text: data.message,
                            value: true,
                            visible: true,
                            className: "",
                            closeModal: true,
                        });
                    }
                    $("#gridContainer").dxDataGrid("instance").refresh();
                    return false;
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    swal({
                        title: "Validation Error",
                        icon: data.status,
                        text: data.message,
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
        update: function(key, value) {
            var kunci= key.id;
            $.ajax({
                url: "{{URL::to('dashboard/kuesioner')}}"+"/"+kunci,
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
                            text: "Data berhasil diperbaharui",
                            value: true,
                            visible: true,
                            className: "",
                            closeModal: true,
                        });
                    }
                $("#gridContainer").dxDataGrid("instance").refresh();
                return false;
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    swal({
                        title: "Error",
                        icon: "error",
                        text: qXHR.responseText,
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
        searchPanel: {
            visible: true
        },
        paging: {
            pageSize: 10
        },
        columns: [
            {
              dataField: "produk.produk_nama",
              caption: "Produk",
            },{
              dataField: "pendamping.pendamping_nama",
              caption: "Alamat Email", 
            },{
              dataField: "santri.santri_nama",
              caption: "Nama Santri",
            },{
              dataField: "bimbingan_status",
              caption: "Status",
            },
            
        ],
    });
});
</script>
@endsection
