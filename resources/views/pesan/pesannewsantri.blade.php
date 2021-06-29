@extends('layouts.menus')
@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">Pilih Santri</div>
            <div class="card-body">
                {!! Form::open(['id' => 'frm','route' => 'pesan.new','class' => 'form-horizontal']) !!}
                    <label>Pilih Santri dari Daftar</label>
                    <div id="simpleList"></div>
                    <div id="btnLanjut"></div>
                    <!-- <div id="selectedStores"></div> -->
                    <input id="txtID" type="text" name="id_entitas"
                            class="form-control" placeholder="ID" hidden>
                    <input id="txtTipe" type="text" name="jenis_entitas" value="SANTRI"
                            class="form-control" placeholder="Jenis Entitas" hidden>
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

    var listDataSource = new DevExpress.data.DataSource({
        loadMode: "raw",
        key: "id",
        load: function (key) {
          return $.ajax({
              url: "{{route('santri.simple.list')}}"
          })
      },
    });


    var listWidget = $("#simpleList").dxList({
        dataSource: listDataSource, 
        itemTemplate: function(data, index) {
            return data.santri_nama;
        },
        editEnabled: true,
        height: 300,
        searchExpr: "santri_nama",
        allowItemDeleting: false,
        itemDeleteMode: "toggle",
        showSelectionControls: true,
        searchEnabled: true,
        selectionMode: "all",
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
                    message: "Silakan Pilih Santri...",
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


