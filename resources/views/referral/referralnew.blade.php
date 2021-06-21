@extends('layouts.menus')
@section('content')
<div class="long-title"><h3>Buat Referral</h3></div>
{!! Form::open(['id' => 'frm','route' => 'referral.store','class' => 'form-horizontal']) !!}
<div class="second-group">
    <div id="form"></div>
    <input id="txtID" type="text" name="referral_entitas_kode" value="{!!$referral->referral_entitas_kode!!}"
        class="form-control" placeholder="Entitas Kode" hidden>
    <input id="txtBeritaId" type="text" name="berita_id" value="{!!$referral->berita_id!!}"
        class="form-control" placeholder="Jenis Entitas" hidden>
</div>
{!! Form::close()!!}
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
    console.log(referral);
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
      },{
          itemType: "button",
          horizontalAlignment: "left",
          buttonOptions: {
              text: "Kirim Pesan",
              type: "success",
              useSubmitBehavior: true
          }
      },]
  }).dxForm("instance"); 

});
</script>
@endsection
