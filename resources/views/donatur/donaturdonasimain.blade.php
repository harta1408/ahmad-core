@extends('layouts.menus')
@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">Pilih Donasi</div>
            <div class="card-body">
                {!! Form::open(['id' => 'frm','route' => 'donatur.donasi.cicilan.main','class' => 'form-horizontal']) !!}
                    <label>Pilih Nomor Donasi dari Daftar</label>
                    <div id="simpleList"></div>
                    <div id="btnLanjut"></div>
                    <!-- <div id="selectedStores"></div> -->
                    <input id="txtID" type="text" name="id_donasi"
                            class="form-control" placeholder="ID" hidden>
                {!! Form::close()!!}
            </div>
        </div>
    </div>
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
    var donaturid='{!!$donaturid!!}';
    var listDataSource = new DevExpress.data.DataSource({
        loadMode: "raw",
        key: "id",
        load: function (key) {
          return $.ajax({
            url: "{{URL::to('dashboard/donasi/donatur/byid')}}"+"/"+donaturid,
          })
      },
    });


    var listWidget = $("#simpleList").dxList({
        dataSource: listDataSource, 
        itemTemplate: function(data, index) {
            return data.donasi_no;
        },
        editEnabled: true,
        height: 300,
        searchExpr: "donasi_no",
        allowItemDeleting: false,
        itemDeleteMode: "toggle",
        showSelectionControls: true,
        searchEnabled: true,
        selectionMode: "single",
    }).dxList("instance");

 
    $("#btnLanjut").dxButton({
        type: "success",
        text: "Lanjut",
        useSubmitBehavior: true,
        onClick: function(e) {      
            $("#txtID").val(listWidget.option("selectedItemKeys")); 
            var txtID=document.getElementById("txtID").value;
            if(txtID==""){
                DevExpress.ui.notify({
                    message: "Silakan Pilih Donatur...",
                    position: {
                        my: "center top",
                        at: "center top"
                    }
                }, "warning", 3000);
                e.preventDefault();
                // event.preventDefault();
                return false;
            }
      }
    });
 
});
</script>
@endsection


