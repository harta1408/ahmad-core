@extends('layouts.menus')
@section('content')
    {!! Form::open(['id' => 'frm','route' => 'users.main','class' => 'form-horizontal']) !!}
    <div class="long-title"><h3>Pengaturan Penggguna</h3></div>
    <div id="toolbar"></div>
    <div id="gridContainer"></div>
    <input id="txtUserName" type="text" name="username" class="form-control" hidden >
    <input id="txtUserState" type="text" name="userstate" class="form-control" hidden>
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

  var user = {!! $user !!};
  $("#gridContainer").dxDataGrid({
        dataSource: user,
        keyExpr: "id",
        selection: {
            mode: "single"
        },
        hoverStateEnabled: true,
        // columnHidingEnabled: true,
        showBorders: true,
        searchPanel: {
            visible: true
        },
        paging: {
            pageSize: 10
        },
        columns: [
            {
                dataField: "name",
                caption: "Nama",
                hidingPriority: 0,
            // },{
            //     dataField: "email",
            //     caption: "Email",
            //     hidingPriority: 1,
            },{
                dataField: "email",
                caption: "Email",
                hidingPriority: 2,
            },
        ],
        onSelectionChanged: function (selectedItems) {
            var data = selectedItems.selectedRowsData[0]; 
            $("#txtUserName").val(data.username);
        }
    });
    $("#toolbar").dxToolbar({
    items: [{
              location: 'center',
              locateInMenu: 'never',
              template: function() {
                  return $("<div class='toolbar-label'><b>SUPERADMIN</b></div>");
              }
            },{
              location: 'after',
              widget: 'dxButton',
              locateInMenu: 'auto',
              options: {
                  icon: "plus",
                  hint: 'Tambahkan Pengguna Baru',
                  useSubmitBehavior: true,
                  onClick: function(e) {      
                    var txtUserName=document.getElementById("txtUserName").value;
                    $("#txtUserState").val("NEW"); //kirim perintah buat baru ke server
                    if(txtUserName!=""){
                        $("#txtUserName").val(""); //supaya ke server jadi null
                    }
                 }
              }
            },{
              location: 'after',
              widget: 'dxButton',
              locateInMenu: 'auto',
              options: {
                  icon: "edit",
                  hint: 'Perbaharui ',
                  useSubmitBehavior: true,
                  onClick: function(e) {      
                    var txtUserName=document.getElementById("txtUserName").value;
                    if(txtUserName==""){
                        DevExpress.ui.notify({
                            message: "Silakan User Name (Nomor Ponsel User)",
                            position: {
                                my: "center top",
                                at: "center top"
                            }
                        }, "warning", 3000);
                        e.preventDefault();
                        return false;
                    }
                    $("#txtUserState").val("UPDATES"); //kirim perintah update ke server
                  }
                }
            },{
              location: 'after',
              widget: 'dxButton',
              locateInMenu: 'auto',
              options: {
                  icon: "pulldown",
                  hint: 'Reset Password ',
                  useSubmitBehavior: true,
                  onClick: function(e) {      
                    var txtUserName=document.getElementById("txtUserName").value;
                    if(txtUserName==""){
                        DevExpress.ui.notify({
                            message: "Silakan Pilih Pengguna",
                            position: {
                                my: "center top",
                                at: "center top"
                            }
                        }, "warning", 3000);
                        e.preventDefault();
                        return false;
                    }
                    $("#txtUserState").val("RESET"); //kirim perintah update ke server
                  }
                }
            },{
              location: 'after',
              widget: 'dxButton',
              locateInMenu: 'auto',
              options: {
                  icon: "trash",
                  hint: 'Non Aktifkan Pengguna',
                  useSubmitBehavior: true,
                  onClick: function(e) {      
                    var txtUserName=document.getElementById("txtUserName").value;
                    var txtUserState=document.getElementById("txtUserState").value;
                    swal({
                        title: "Non Aktifkan Pengguna",
                        icon: "error",
                        text: "Pilihan ini Belum Tersedia",
                        value: true,
                        visible: true,
                    });
                    e.preventDefault();
                    return false;
                    if(txtUserName==""){
                        DevExpress.ui.notify({
                            message: "Silakan Pilih Pengguna",
                            position: {
                                my: "center top",
                                at: "center top"
                            }
                        }, "warning", 3000);
                        e.preventDefault();
                        return false;
                    }

                    $("#txtUserState").val("DELETE"); //kirim perintah hapus ke server
                  }
                }
        }]
    });
});

</script>
@endsection
