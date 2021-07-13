@extends('layouts.menus')
@section('content')
<div class="long-title"><h3>Buat Referral</h3></div>
<div class="second-group">
    <form id="form-container" class="first-group">
        <div id="form"></div>
        <div id="btnSave"></div>
    </form>
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
  var referral={!!$referral!!}
  $("#form").dxForm({
      colCount: 1,
      formData:referral,
      showColonAfterLabel: true,
      showValidationSummary: true,
      items:[
      {
        itemType:"group",
        colCount:1,
        items: [{
                dataField: "referral_entitas_kode",
                label:{
                    text:"Kode Pengirim",
                },
                editorOptions: {
                    disabled: true
                },
            },{
                dataField: "referral_nama",
                label:{
                    text:"Nama Pengirim",
                },
                editorOptions: {
                    disabled: true
                },
            },{
                dataField: "referral_entitas_tujuan",
                label:{
                    text:"Entitas Tujuan Referral",
                },
                editorType: "dxSelectBox",
                editorOptions: {
                    items: [{"referral_entitas_tujuan":"1","referral_entitas_tujuan_desc":"DONATUR"},
                            {"referral_entitas_tujuan":"2","referral_entitas_tujuan_desc":"SANTRI"},
                            {"referral_entitas_tujuan":"3","referral_entitas_tujuan_desc":"PENDAMPING"}],
                    displayExpr: "referral_entitas_tujuan_desc",
                    valueExpr: "referral_entitas_tujuan",
                    value:"1",
                },
                validationRules: [{
                            type: "required",
                            message: "Pilih Jenis Berita"}]
            },{
                dataField: "referral_telepon",
                label:{
                    text:"Nomor Penerima",
                }, 
                validationRules: [{
                        type: "required",
                        message: "Nomor Tellepon Penerima Harus di Isi",
                }],
                // editorType: "dxTextBox",
                // editorOptions:{
                //     width:200,
                //     mask: "+62X00000000000",
                //     maskRules: {"X": /[02-9A-F]/}
                // },           
            },],
    //   },{
    //       itemType: "button",
    //       horizontalAlignment: "left",
    //       buttonOptions: {
    //           text: "Kirim Pesan",
    //           type: "success",
    //           useSubmitBehavior: true
    //       }
      },]
  }).dxForm("instance"); 
// save penerimaan
$("#btnSave").dxButton({
      text: "Kirim Referral",
      type: "success",
      width: 125,
      onClick: function(e) {
        $("#btnSave").dxButton("instance").option("disabled",true);
        var form =$('#form-container').serializeObject();
        $.ajax({
              type: "POST",
              url: "{{route('referral.store')}}",
              data: JSON.stringify({referral:referral}),
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
                        window.location = '{{route('referral.index')}}';
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
