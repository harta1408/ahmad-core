@extends('layouts.menus')
@section('content')
    <div class="long-title"><h3>Informasi Pengiriman Produk </h3></div>
    <div class="second-group">
        <form id="form-container" class="first-group">
            <div id="form"></div>
        </form>
        <span>Pengiriman melalui {!!$kurir!!}</span>
        <div id="gridData"></div>
        <div id="btnSave"></div>
    </div>
    <input id="txtValue" type="text" hidden>
@endsection

@section('script')
<script type="text/javascript">
$(function(){
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
    $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    var provinsi;
  var kota;
  var kecamatan;
  $("#form").dxForm({
      colCount: 1,
      formData: {!!$santri!!},
      readOnly: true,
      items:[
      {
        itemType:"group",
        colCount:2,
        items: [{
          dataField: "santri_kode",
          label:{
            text:"Kode santri",
          },
        },{
          dataField: "santri_email",
          label:{
            text:"Alamat Email",
          },
        },{
          dataField: "santri_nama",
          label:{
            text:"Nama santri",
          },
        // },{
        //   dataField: "santri_nid",
        //   label:{
        //     text:"No KTP",
        //   },         
        },{
          dataField: "santri_telepon",
          label:{
            text:"Handphone",
          },
        },],
      },{
        itemType:"group",
        colCount:1,
        items: [{
            dataField: "santri_alamat",
            label:{
              text:"Alamat",
            },
            editorOptions: {
              height: 50,
            },
        },],
      },{
        itemType:"group",
        colCount:2,
        items: [{
            dataField: "santri_provinsi",
            label:{
              text:"Provinsi",
            },         
        },{
            dataField: "santri_kota",
            label:{
              text:"Kota",
            },           
        },{
            dataField: "santri_kecamatan",
            label:{
              text:"Kecamatan",
            },     
        },]
      },]
    }).dxForm("instance"); 
    var dataGrid=$("#gridData").dxDataGrid({
        dataSource: {!!$costs!!},
        showBorders: true,
        selection: {
            mode: "single"
        },
        columns: [
            {
              dataField: "service",
              caption: "Layanan",
            },{
              dataField: "cost[0][etd]",
              caption: "Waktu Kirim (Hari)",
            },{
              dataField: "cost[0][value]",
              caption: "Biaya Kirim (Rp.)",  
              dataType:"number",
              format:"fixedPoint",     
            },            
        ],
        onSelectionChanged: function (selectedItems) {
            var value = selectedItems.selectedRowsData[0].cost[0].value;
            $("#txtValue").val(value); 
          },
    }).dxDataGrid("instance");;
    $("#btnSave").dxButton({
        text: "Simpan",
        type: "success",
        width: 120,
        onClick: function() {
            var biaya=document.getElementById("txtValue").value;
            if(biaya==''){
                swal({
                    title: "Pilih Biaya",
                    icon: 'error',
                    text: 'Silakan Pilih Biaya Pengiriman',
                    value: true,
                    visible: true,
                    className: "",
                    closeModal: true,
                });
                return false;
            }
            var kuririd='{!!$kuririd!!}';
            var kurir='{!!$kurir!!}';
            var tglkirim='{!!$tglkirim!!}';
            var noseri='{!!$noseri!!}';
            var santriid='{!!$santri->id!!}';
            var form =$('#form-container').serializeObject();
            $.ajax({
                type: 'POST',
                url: "{{route('kirimproduk.store')}}",
                data: JSON.stringify({form:form,biaya:biaya,kuririd:kuririd,kurir:kurir,
                tglkirim:tglkirim,noseri:noseri,santriid:santriid}),
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
                        window.location = '{{route('kirimproduk.index')}}';
                    });
                }
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
        }
    });
   
});
</script>
@endsection
