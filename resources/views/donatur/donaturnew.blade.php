@extends('layouts.menus')
@section('content')
<div class="long-title"><h3>Daftarkan Donatur Baru</h3></div>
<div id="form"></div>
<div class="second-group">
    
</div>

@endsection

@section('script')
<script type="text/javascript">
$(function() {
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




  //menggunakan konsep load, karena kalau produk di kirim langsung dari promotion main 
  //terjadi double save pada saat di server
  var prodDataSource = new DevExpress.data.DataSource({
      load: function() {
        return $.getJSON("{{URL::to('office/promotion/discount/product/load')}}");
      },
      update: function (key, values) {
          var productid= key.product_id;
          return $.ajax({
              url: "{{URL::to('office/promotion/discount/product/update')}}"+"/"+productid,
              method: "PUT",
              data: {values,productid},
          })
      }
  });

 
 

  var selecteddays=[];
  $("#form").dxForm({
      colCount: 1,
      items:[
      {
        itemType:"group",
        colCount:2,
        items: [{
          dataField: "donatur_kode",
          label:{
            text:"Kode Donatur",
          },
          editorOptions: { 
              value : "Penomoran Otomatis",
              disabled: true
          }
        },{
          dataField: "donatur_email",
          label:{
            text:"Alamat Email",
          },
        },{
          dataField: "donatur_nama",
          label:{
            text:"Nama Donatur",
          },
        },{
          dataField: "donatur_nid",
          label:{
            text:"No KTP",
          },
        },{
          dataField: "donatur_tmp_lahir",
          label:{
            text:"Tempat Lahir",
          },
          editorOptions: {
          }
        },{
            dataField: "donatur_tgl_lahir",
            label:{
                text:"Tanggal Lahir",
            },
            editorType: "dxDateBox",
            editorOptions: {
                displayFormat: "dd-MM-yyyy",
                value : new Date(),
            }
        },{
            dataField: "donatur_provinsi",
            label:{
              text:"Provinsi",
            },  
            editorType: "dxSelectBox",
            editorOptions: {
                dataSource: new DevExpress.data.ArrayStore({
                    data: {!!$provinsi!!},
                    // key: "ID"
                }),
                displayExpr: "provinsi",
                valueExpr: "provinsi",
                searchEnabled: true
                // value: products[0].ID,
            }
        },{
            dataField: "donatur_kota",
            label:{
              text:"Kota",
            },  
        },{
            dataField: "donatur_kecamatan",
            label:{
              text:"Kecamatan",
            },  
        },{
            dataField: "donatur_kelurahan",
            label:{
              text:"Kelurahan",
            },  
        },{
            dataField: "donatur_kodepos",
            label:{
              text:"Kode Pos",
            }, 
        },]
      },]
  }); 

  $("#donatur_provinsi").dxSelectBox({
        dataSource: new DevExpress.data.ArrayStore({
            data: products,
            key: "ID"
        }),
        displayExpr: "Name",
        valueExpr: "ID",
        value: products[0].ID,
    });

  // save penerimaan
  $("#btnSave").dxButton({
      text: "Simpan",
      type: "success",
      width: 125,
      onClick: function(e) {
        var data = dataGrid._controllers.data._dataSource._cachedStoreData;
        console.log(data);
          var form =$('#form-container').serializeObject();
          if(form['promo_desc']==""){
            DevExpress.ui.notify({
                message: "Silakan isi Nama Promo...",
                position: {
                    my: "center top",
                    at: "center top"
                }
            }, "warning", 3000);
            return false;
          }
          if(selecteddays.length==0){
            DevExpress.ui.notify({
                message: "Silakan pilih Hari...",
                position: {
                    my: "center top",
                    at: "center top"
                }
            }, "warning", 3000);
            return false;
          }         
          // $("#btnSave").dxButton("instance").option("disabled",true);
          $.ajax({
              type: "POST",
              url: "{{route('donatur.store')}}",
              data: JSON.stringify({form:form,table:data,selecteddays:selecteddays}),
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
                          window.location = '{{route('donatur.index')}}';
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
          
          return false;
      }
  });
  
});
</script>
@endsection
