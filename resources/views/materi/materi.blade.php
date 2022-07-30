@extends('layouts.menus')
@section('content')
    <div class="long-title"><h3>Daftar Materi</h3></div>
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
                url: "{{route('materi.create')}}"
            })
        },
        insert: function (values) {
            $.ajax({
                type: 'POST',
                url: '{{route('materi.store')}}',
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
                    $("#gridData").dxDataGrid("instance").refresh();
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
                url: "{{URL::to('dashboard/materi')}}"+"/"+kunci,
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
        remove: function (key) {
        var kunci= key.id;
        $.ajax({
            url: "{{URL::to('dashboard/materi')}}"+"/"+kunci,
            method: "DELETE",
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
                }else {
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
      }  
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
                title: "Materi",
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
                        dataField: "materi_nama",
                        label:{
                            text:"Nama Materi",
                        },
                    },{
                        dataField: "materi_deskripsi",
                        label:{
                            text:"Deskripsi Materi",
                        },
                        editorType: "dxTextArea",
                        colSpan: 2,
                        editorOptions: {
                            height: 100
                        },
                    },{
                        dataField: "materi_level",
                        label:{
                            text:"Level Materi",
                        },
                    },{
                        dataField: "materi_bobot",
                        dataType:"number",
                        format: "fixedPoint",
                        label:{
                            text:"Bobot Nilai",
                        },
                        editorType: "dxNumberBox",
                        editorOptions:{
                            mask: "000",
                            maskInvalidMessage: "Materi Level Tiga Digit",
                            useMaskedValue: true,
                            width:50,
                        },
                        editorType: "dxNumberBox",
                        editorOptions: { 
                            dataType:"number",
                            format: "#,##0",
                        },
                        validationRules: [{
                            min:0,
                            type: "required",
                            message: "Masukan Angka..."
                        }]
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
              dataField: "materi_nama",
              caption: "Nama Materi",
              validationRules: [{
                type: "required",
                message: "Nama Materi Harus di isi..."
              }],
            },{
              dataField: "materi_deskripsi",
              caption: "Deskripsi Materi",
              visible: false,
              validationRules: [{
                type: "required",
                message: "Nama Materi Harus di isi..."
              }],
            },{
              dataField: "materi_level",
              caption: "Level Materi",
              dataType:"number",
              format: "fixedPoint",
              validationRules: [{
                type: "required",
                message: "Level Materi Harus di isi..."
              }],
            },{
              dataField: "materi_bobot",
              caption: "Bobot Nilai Materi",
              dataType:"number",
              format: "fixedPoint",
              editorType: "dxNumberBox",
              editorOptions: { 
                dataType:"number",
                format: "#,##0",
              },
              validationRules: [{
                min:0,
                type: "required",
                message: "Masukan Angka..."
              }],
            },
            
        ],

    });

   
 
});
</script>
@endsection
