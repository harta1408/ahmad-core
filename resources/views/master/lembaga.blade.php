@extends('layouts.menus')
@section('content')
<div class="long-title"><h3>Pembaharuan Lembaga</h3></div>
{!! Form::open(['id' => 'frm','route' => ['lembaga.update','ahmad'],'method' => 'PUT', 'class' => 'form-horizontal']) !!}
  <div id="form"></div>
{!! Form::close()!!}

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

  var formDataSource = new DevExpress.data.DataSource({
        load: function (loadOptions) {
            return $.ajax({
                url: "{{route('lembaga.create')}}"
            })
        }, 
  });

  $("#form").dxForm({
      formData:{!!$lembaga!!},
      colCount: 1,
      showColonAfterLabel: true,
      showValidationSummary: true,
      scrollingEnabled:true,
      items:[
      {
        itemType:"group",
        colCount:1,
        items: [{
            dataField: "lembaga_nama",
            label:{
                text:"Nama Lembaga",
            },
            editorOptions: { 
            },
            validationRules: [{
              type: "required",
              message: "Nomor Handphone harus di isi"
            }]
        },{
            dataField: "lembaga_email",
            label:{
                text:"Alamat Email",
            },
            validationRules: [{
                    type: "required",
                    message: "Alamat Email Harus Di isi"
                }, {
                    type: "email",
                    message: "Alamat Email tidak valid"
                }]
        },{
          dataField: "lembaga_telepon",
          label:{
            text:"Nomor Telepon",
          },
          validationRules: [{
              type: "required",
              message: "Nomor Handphone harus di isi"
          }]
        },{
          dataField: "lembaga_alamat",
          label:{
            text:"Alamat",
          },
        },{
          dataField: "lembaga_tentang_ahmad_judul",
          label:{
            text:"Judul Tentang Ahmad",
          },
        },{
            dataField: "lembaga_tentang_ahmad_isi",
            label:{
                text:"Isi Tetang Ahmad",
            },
            editorType: "dxTextArea",
            editorOptions: {
                height: 100,
            },
        },{
          dataField: "lembaga_landing_donatur_judul",
          label:{
            text:"Judul Donatur ",
          },
        },{
            dataField: "lembaga_landing_donatur_isi",
            label:{
                text:"Isi Donatur",
            },
            editorType: "dxTextArea",
            editorOptions: {
                height: 100,
            },      
        },{
          dataField: "lembaga_landing_santri_judul",
          label:{
            text:"Judul Santri",
          },
        },{
            dataField: "lembaga_landing_santri_isi",
            label:{
                text:"Isi Santri",
            },
            editorType: "dxTextArea",
            editorOptions: {
                height: 100,
            },       
        },{
          dataField: "lembaga_landing_pendamping_judul",
          label:{
            text:"Judul Pendamping",
          },
        },{
            dataField: "lembaga_landing_pendamping_isi",
            label:{
                text:"Isi Pendamping",
            },
            editorType: "dxTextArea",
            editorOptions: {
                height: 100,
            }, 
        },{
          dataField: "lembaga_landing_mitra_judul",
          label:{
            text:"Judul Mitra",
          },
        },{
            dataField: "lembaga_landing_mitra_isi",
            label:{
                text:"Isi Mitra",
            },
            editorType: "dxTextArea",
            editorOptions: {
                height: 100,
            }, 
        },{
          dataField: "lembaga_landing_produk_judul",
          label:{
            text:"Judul Produk",
          },
        },{
            dataField: "lembaga_landing_produk_isi",
            label:{
                text:"Isi Produk",
            },
            editorType: "dxTextArea",
            editorOptions: {
                height: 100,
            }, 
        },]
      },{
          itemType: "button",
          horizontalAlignment: "left",
          buttonOptions: {
              text: "Simpan",
              type: "success",
              useSubmitBehavior: true
          }
      },]
  }).dxForm("instance"); 

});
</script>
@endsection
