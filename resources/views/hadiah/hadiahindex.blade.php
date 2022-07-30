@extends('layouts.menus')
@section('content')
    <div class="long-title"><h3>Daftar Hadiah</h3></div>
    {!! Form::open(['id' => 'frm','route' => 'hadiah.main', 'class' => 'form-horizontal']) !!}
        <div id="gridData"></div>
        <input id="txtHadiahId" type="text" name="hadiah_id" class="form-control" hidden >
        <input id="txtHadiahState" type="text" name="hadiah_state" class="form-control" hidden>
        <input id="txtHadiahJenis" type="text" name="hadiah_jenis" class="form-control" hidden>
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
                url: "{{route('hadiah.create')}}"
            })
        },
    });
    $("#gridData").dxDataGrid({
        dataSource: gridDataSource,
        keyExpr: "id",
        showBorders: true,
        selection: {
            mode: "single"
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
              dataField: "hadiah_jenis",
              caption: "Jenis",
            },{
              dataField: "hadiah_nama",
              caption: "Nama",
            },{
              dataField: "hadiah_nilai",
              caption: "Referral",
            },{
              dataField: "hadiah_nominal",
              caption: "Nominal",
            },{
              dataField: "hadiah_status",
              caption: "Status",
            },
            
        ],
        toolbar: {
            items: [
                'searchPanel',
                {
                    location: 'after',
                    widget: 'dxButton',
                    options: {
                        icon: "plus",
                        hint: 'Buat Hadiah Baru',
                        useSubmitBehavior: true,
                        onClick: function(e) {      
                            $("#txtHadiahState").val("NEW"); //kirim perintah tambah ke server
                        }
                    },
                },{
                    location: 'after',
                    widget: 'dxButton',
                    options: {
                        icon: 'edit',
                        hint: 'Update Hadiah',
                        useSubmitBehavior: true,
                        onClick() {
                            var txtHadiahId=document.getElementById("txtHadiahId").value;
                            if(txtHadiahId==""){
                                DevExpress.ui.notify({
                                    message: "Silakan Pilih hadiah",
                                    position: {
                                        my: "center top",
                                        at: "center top"
                                    }
                                }, "warning", 3000);
                                e.preventDefault();
                                return false;
                            }
                            $("#txtHadiahState").val("UPDATE"); //kirim perintah update ke server
                    },},
                },{
                    location: 'after',
                    widget: 'dxButton',
                    locateInMenu: 'auto',
                    options: {
                        icon: "close",
                        hint: 'Keluar Tanpa Simpan', 
                        useSubmitBehavior: true,
                        onClick: function(e) {      
                            $("#txtHadiahState").val("CLOSE"); 
                        }
                    }
                // },{
                //     location: 'after',
                //     widget: 'dxButton',
                //     locateInMenu: 'auto',
                //     options: {
                //         icon: "message",
                //         hint: 'Kirim hadiah',
                //         useSubmitBehavior: true,
                //         onClick: function(e) {      
                //         var txtHadiahId=document.getElementById("txtHadiahId").value;
                //         var txtHadiahJenis=document.getElementById("txtHadiahJenis").value;
                //         if(txtHadiahId==""){
                //             DevExpress.ui.notify({
                //                 message: "Silakan Pilih hadiah yang akan dikirim",
                //                 position: {
                //                     my: "center top",
                //                     at: "center top"
                //                 }
                //             }, "warning", 3000);
                //             e.preventDefault();
                //             return false;
                //         }
                //         if(txtHadiahJenis!="hadiah"){
                //             DevExpress.ui.notify({
                //                 message: "Hanya untuk Jenis hadiah",
                //                 position: {
                //                     my: "center top",
                //                     at: "center top"
                //                 }
                //             }, "warning", 3000);
                //             e.preventDefault();
                //             return false;
                //         }
                //         $("#txtHadiahState").val("SEND"); //kirim perintah update ke server
                //         }
                //     }
                // },{
                //     location: 'after',
                //     widget: 'dxButton',
                //     locateInMenu: 'auto',
                //     options: {
                //         icon: "trash",
                //         hint: 'Hapus Data hadiah',
                //         useSubmitBehavior: true,
                //         onClick: function(e) {      
                //         var txtHadiahId=document.getElementById("txtHadiahId").value;
                //         var txtHadiahState=document.getElementById("txtHadiahState").value;
                //         if(txtHadiahId==""){
                //             DevExpress.ui.notify({
                //                 message: "Silakan Pilih hadiah.",
                //                 position: {
                //                     my: "center top",
                //                     at: "center top"
                //                 }
                //             }, "warning", 3000);
                //             e.preventDefault();
                //             return false;
                //         }
                //         if(txtHadiahState!="0"){
                //             DevExpress.ui.notify({
                //                 message: "Proses Hapus pendamping",
                //                 position: {
                //                     my: "center top",
                //                     at: "center top"
                //                 }
                //             }, "error", 3000);
                //             e.preventDefault();
                //             return false;
                //         }
                //         $("#txtHadiahState").val("DELETE"); //kirim perintah hapus ke server
                //         }
                //     }
                },
            ],
        },
        onSelectionChanged: function (selectedItems) {
            var data = selectedItems.selectedRowsData[0];
            $("#txtHadiahId").val(data.id);
            $("#txtHadiahJenis").val(data.hadiah_jenis);
          },
    });
  
});
</script>
@endsection
