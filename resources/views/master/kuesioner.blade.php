@extends('layouts.menus')
@section('content')
    <div class="long-title"><h3>Daftar Kuesioner</h3></div>

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
                url: "{{route('kuesioner.create')}}"
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
                        dataField: "kuesioner_entitas",
                        label:{
                            text:"Tujuan Kuesioner",
                        },
                    },{
                        dataField: "kuesioner_tanya",
                        label:{
                            text:"Pertanyaan",
                        },
                        editorType: "dxTextArea",
                        colSpan: 2,
                        editorOptions: {
                            height: 100
                        },
                    },{
                        dataField: "kuesioner_bobot_yes",

                        label:{
                            text:"Bobot Jawab YA",
                        },
                        
                    },{
                        dataField: "kuesioner_bobot_no",
                        dataType:"number",
                        format: "fixedPoint",
                        label:{
                            text:"Bobot Jawab TIDAK",
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
              dataField: "kuesioner_entitas",
              caption: "Tujuan Kuesioner",
            //   editorType: "dxRadioGroup",
              lookup: {
                    dataSource: [{"kuesioner_entitas":"2","kuesioner_entitas_desc":"SANTRI"},
                            {"kuesioner_entitas":"3","kuesioner_entitas_desc":"PENDAMPING"}],
                    valueExpr: "kuesioner_entitas",
                    displayExpr: "kuesioner_entitas_desc",
                },

            //   editorOptions: {
            //         items: [{"kuesioner_entitas_pegawai":"TETAP","kuesioner_entitas_pegawai_desc":"TETAP"},
            //                 {"kuesioner_entitas_pegawai":"KONTRAK","kuesioner_entitas_pegawai_desc":"KONTRAK"},],
            //         value:"KONTRAK",
            //         displayExpr: "kuesioner_entitas_pegawai_desc",
            //         valueExpr: "kuesioner_entitas_pegawai",
            //         layout: "horizontal",
            //   }
            },{
              dataField: "kuesioner_tanya",
              caption: "Pertanyaan",
            },{
              dataField: "kuesioner_bobot_yes",
              caption: "Bobot Jawab YA",
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
              }]
            },{
              dataField: "kuesioner_bobot_no",
              caption: "Bobot Jawab TIDAK",
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
              }]
            // },{
            //   dataField: "pendamping_status",
            //   caption: "Status",
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
