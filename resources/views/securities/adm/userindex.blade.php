@extends('layouts.menus')
@section('content')
    {!! Form::open(['id' => 'frm','route' => 'users.adm.main','class' => 'form-horizontal']) !!}
    <div class="long-title"><h3>Pengaturan Penggguna</h3></div>
    <div id="toolbar"></div>
    <div id="gridContainer"></div>
    <input id="txtUserID" type="text" name="userid" class="form-control" hidden>
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
            },{
                dataField: "email",
                caption: "Email",
                hidingPriority: 1,
            },
        ],
        onSelectionChanged: function (selectedItems) {
            var data = selectedItems.selectedRowsData[0]; 
            $("#txtUserID").val(data.id);
        }
    });
    $("#toolbar").dxToolbar({
    items: [{
              location: 'center',
              locateInMenu: 'never',
              template: function() {
                  return $("<div class='toolbar-label'><b>RESET PASSWORD</b></div>");
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
                    var txtUserID=document.getElementById("txtUserID").value;
                    if(txtUserID==""){
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
                    var txtUserID=document.getElementById("txtUserID").value;
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
                    if(txtUserID==""){
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
