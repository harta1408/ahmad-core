@extends('layouts.menus')
@section('content')
    <div class="long-title"><h3>Konfirmasi Donasi</h3></div>
    <div class="second-group">
        <div id="gridData"></div>
        <div id="btnSave"></div>
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
    $.fn.serializeObject = function(){
      var o = {};
      var a = this.serializeArray();
      $.each(a, function() {
          if (o[this.name] !== undefined) {
              if (!o[this.name].push) {
                  o[this.name] = [o[this.name]];
              }
              o[this.name].push(this.value || '');
          } else {
              o[this.name] = this.value || '';
          }
      });
      return o;
  };

    var dataDonasi={!!$donasi!!};

    console.log(dataDonasi);

    $("#gridData").dxDataGrid({
        dataSource: dataDonasi,
        // keyExpr: "id",
        showBorders: true,
        editing: {
            mode: "batch",
            allowUpdating: true,
            useIcons: true,
        },
        searchPanel: {
            visible: true
        },
        paging: {
            pageSize: 10
        },
        columns: [
            {
              dataField: "donasi_no",
              caption: "No Donasi",
            },{
              dataField: "donasi_donatur_nama",
              caption: "Donatur",
            },{
              dataField: "donasi_pendamping_nama",
              caption: "Pendamping",
            },{
                dataField: "donasi_santri_id",
                caption: "Santri",  
                editorType: "dxSelectBox",
                visible:true,
                lookup: {
                    dataSource: {!!$santri!!},
                    displayExpr: "santri_nama",
                    valueExpr: "id",
                },  
            },
            
        ],
        onEditingStart: function(e){
          if (e.column.dataField != "donasi_santri_id") {
             e.cancel = true;
          }
      },
    });
    $("#btnSave").dxButton({
        type: "success",
        icon: "save",
        text: "Proses Pendistibusion Produk",
        onClick: function(e) {
        // $("#btnSave").dxButton("instance").option("disabled",true);
        // var form =$('#form-container').serializeObject();
        
        var table=$('#gridData').dxDataGrid("instance");
        var arrDonasi=table.getDataSource().items();

        $.ajax({
                
              type: "POST",
              url: "{{route('donasi.store')}}",
              data: JSON.stringify({dataDonasi:arrDonasi}),
              contentType: "application/json; charset=utf-8",
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
                }else{
                    swal({
                        title: "OK",
                        icon: data.status,
                        text: data.message,
                        value: true,
                        visible: true,
                        className: "",
                        closeModal: true,
                    })
                    .then((value) => {
                        window.location = '{{route('home')}}';
                    });
                }
                return false;
              },    
              error: function(jqXHR, textStatus, errorThrown) {
                swal({
                    title: "Validation Error",
                    icon: "error",
                    text: "Error",
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
 
});
</script>
@endsection
