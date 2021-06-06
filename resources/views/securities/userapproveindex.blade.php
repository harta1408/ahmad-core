@extends('layouts.menus')
@section('content')
    {!! Form::open(['id' => 'frm','route' => 'users.main','class' => 'form-horizontal']) !!}
    <div class="long-title"><h3>Otorisasi Penggguna</h3></div>
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

  var gridDataSource = new DevExpress.data.DataSource({
       load: function (key) {
        return $.ajax({
            url: "{{route('users.approve.load')}}"
          })
      },
      update: function (key, approve) {
          var userid= key.id;
          $.ajax({
              url: "{{URL::to('dashboard/users/approve/update')}}"+"/"+userid,
              method: "PUT",
              data: approve,
              dataType: "json",
              success: function (data) {
                if(data.code != 200) {
                    swal({
                        title: data.status,
                        icon: data.status,
                        text: data.message,
                        value: true,
                        visible: true,
                        className: "",
                        closeModal: true,
                    });
                }else {
                    DevExpress.ui.notify("Produk Berhasil di Perbaharui"); 
                }
                $("#tabProd").dxDataGrid("instance").refresh();
                return false;
            },
          });
          return false;
      }
  });
  $("#gridContainer").dxDataGrid({
        dataSource: gridDataSource,
        keyExpr: "id",
        selection: {
            mode: "single"
        },
        hoverStateEnabled: true,
        showBorders: true,
        searchPanel: {
            visible: true
        },
        editing: {
          mode: "batch",
          allowUpdating: true,
          useIcons: true, 
        },
        paging: {
            pageSize: 10
        },
        columns: [
            {
                dataField: "name",
                caption: "Nama",
            },{
                dataField: "email",
                caption: "Email",
            },{
              dataField:"approve",
              dataType:"boolean",
              caption:"Setujui",
              editorType: "dxSwitch", 
              editorOptions: { 
                switchedOnText:"Ya",
                switchedOffText:"Tidak",
                width:80,
              },  
            },
        ],
        onEditorPreparing: function (e) {  
            if (e.parentType == "dataRow" && e.dataField == "approve")  {
                e.editorName = "dxSwitch"; 
            }
        },
        onSelectionChanged: function (selectedItems) {
            var data = selectedItems.selectedRowsData[0]; 
            $("#txtUserName").val(data.username);
        }
    });
    
});

</script>
@endsection
