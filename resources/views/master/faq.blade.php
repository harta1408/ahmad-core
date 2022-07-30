@extends('layouts.menus')
@section('content')
    <div class="long-title"><h3>Frequently Ask Question</h3></div>
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
                url: "{{route('faq.create')}}"
            })
        },
        insert: function (values) {
            $.ajax({
                type: 'POST',
                url: '{{route('faq.store')}}',
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
                $("#gridData").dxDataGrid("instance").refresh();
                return false;
        },
        update: function(key, value) {
            var kunci= key.id;
            $.ajax({
                url: "{{URL::to('dashboard/faq')}}"+"/"+kunci,
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
                title: "Frequently Ask Question",
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
                        dataField: "faq_tanya",
                        label:{
                            text:"Pertanyaan",
                        },
                    },{
                        dataField: "faq_jawab",
                        editorType: "dxTextArea",
                        label:{
                            text:"Jawaban",
                        },
                        colSpan: 2,
                        editorOptions: {
                            height: 100
                        }
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
                dataField: "faq_tanya",
                caption: "Pertanyaan",
                validationRules: [{
                    type: "required",
                    message: "Masukan Pertanyaan"
                }],
            },{
                dataField: "faq_jawab",
            },
            
        ],
    });
   
});
</script>
@endsection
