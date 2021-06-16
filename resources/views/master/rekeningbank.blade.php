@extends('layouts.menus')
@section('content')
    <div class="long-title"><h3>Daftar Rekening</h3></div>
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
                url: "{{route('rekeningbank.create')}}"
            })
        },
        insert: function (values) {
            $.ajax({
                type: 'POST',
                url: '{{route('rekeningbank.store')}}',
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
                    console.log(textStatus);
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
                url: "{{URL::to('dashboard/rekeningbank')}}"+"/"+kunci,
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
        showBorders: true,
        editing: {
            mode: "popup",
            useIcons: true,
            allowUpdating: true,
            allowDeleting:true,
            allowAdding:true,
            popup: {
                title: "Kuesioner",
                showTitle: true,
                position: {
                    my: "top",
                    at: "top",
                    of: window
                }
            },
            form: {
                items: [{
                    itemType: "group",
                    colCount: 1,
                    colSpan: 2,
                    items: [{
                        dataField: "rekening_nama",
                        label:{
                            text:"Nama Pada Rekening",
                        },
                    },{
                        dataField: "rekening_no",
                        label:{
                            text:"No Rekening",
                        },
                    },{
                        dataField: "rekening_nama_bank",
                        label:{
                            text:"Nama Bank",
                        },
                    }],
                }],
            },
        },
        searchPanel: {
            visible: true
        },
        paging: {
            pageSize: 10
        },
        columns: [
            {
                dataField: "rekening_nama",
                caption: "Nama Pada Rekening",
                validationRules: [{
                    type: "required",
                    message: "Masukan Nama Pada Rekening"
                }],
            },{
                dataField: "rekening_no",
                caption: "Nomor Rekening",
                editorType: "dxNumberBox",
                editorOptions: { 
                    dataType:"number",
                    format: "###0",
                },
                validationRules: [{
                    type: "required",
                    message: "Masukan Nomor Rekening"
                }],
            },{
                dataField: "rekening_nama_bank",
                caption: "Nama Bank",
                validationRules: [{
                    type: "required",
                    message: "Masukan Nama Pada Rekening"
                }],
            },
            
        ],
        onSelectionChanged: function (selectedItems) {
            var data = selectedItems.selectedRowsData[0];
            $("#txtKuesionerId").val(data.id);
          },
    });
   
});
</script>
@endsection
