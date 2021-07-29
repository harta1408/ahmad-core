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
  var provinsi;
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
            itemType: "group",
            caption: "Informasi Lembaga",
            items:[{
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
                  }],
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
                dataField: "lembaga_provinsi_id",
                label:{
                    text:"Provinsi",
                },  
                validationRules: [{
                        type: "required",
                        message: "Provinsi harus di isi"
                }],
                editorType: "dxSelectBox",
                editorOptions: {
                    dataSource: new DevExpress.data.CustomStore({
                        loadMode: "raw", // omit in the DataGrid, TreeList, PivotGrid, and Scheduler
                        load: function() {
                        return $.getJSON("{{URL::to('dashboard/kodepos/provinsi/all')}}")
                                .fail(function() { throw "Data loading error" });
                        }
                    }),
                    displayExpr: "province",
                    valueExpr: "province_id",
                    searchEnabled: true,
                    onValueChanged : function (e){
                        provinsi=e.value;
                        var form=$('#form').dxForm('instance')
                        var itemKota=form.getEditor('lembaga_kota_id');
                        itemKota.getDataSource().load();
                    }
                },
            },{
                dataField: "lembaga_kota_id",
                label:{
                    text:"Kota",
                },
                validationRules: [{
                        type: "required",
                        message: "Provinsi harus di isi"
                }],
                editorType: "dxSelectBox",
                editorOptions: {
                    dataSource: new DevExpress.data.CustomStore({       
                        loadMode: "raw",   
                        cacheRawData: false,
                        load: function() {
                            return $.getJSON("{{URL::to('dashboard/kodepos/kota')}}"+"/"+encodeURIComponent(provinsi));
                        }
                    }),    
                    displayExpr: "city_name",
                    valueExpr: "city_id",
                    searchEnabled: true,
                },
            }],
      },{
        itemType: "group",
        caption: "Tentang AHMaD Project",
        items:[{
              dataField: "lembaga_tentang_ahmad_judul",
              label:{
                text:"Judul",
              },
            },{
              dataField: "lembaga_tentang_ahmad_isi",
              label:{
                  text:"Isi",
              },
              editorType: "dxHtmlEditor",
              editorOptions:{
                  height: 200,
                  toolbar: {
                      items: [
                          "undo", "redo", "separator",
                          {
                              name: "size",
                              acceptedValues: ["8pt", "10pt", "12pt", "14pt", "18pt", "24pt", "36pt"] },
                          "separator", "bold", "italic", "underline", "separator",
                          "alignLeft", "alignCenter", "alignRight", "alignJustify", "separator",
                          "link", "image", "separator",
                      ],
                      multiline:true,
                  },
                  mediaResizing: {
                      enabled: true
                  }
              },
            }],
        },{
          itemType: "group",
          caption: "Program Donatur",
          items:[{
              dataField: "lembaga_landing_donatur_judul",
              label:{
                text:"Judul",
              },
            },{
              dataField: "lembaga_landing_donatur_isi",
              label:{
                  text:"Isi",
              },
              editorType: "dxHtmlEditor",
              editorOptions:{
                  height: 200,
                  toolbar: {
                      items: [
                          "undo", "redo", "separator",
                          {
                              name: "size",
                              acceptedValues: ["8pt", "10pt", "12pt", "14pt", "18pt", "24pt", "36pt"] },
                          "separator", "bold", "italic", "underline", "separator",
                          "alignLeft", "alignCenter", "alignRight", "alignJustify", "separator",
                          "link", "image", "separator",
                      ],
                      multiline:true,
                  },
                  mediaResizing: {
                      enabled: true
                  }
              },
            }],
        },{
          itemType: "group",
          caption: "Program Santri",
          items:[{
              dataField: "lembaga_landing_santri_judul",
              label:{
                text:"Judul",
              },
            },{
              dataField: "lembaga_landing_santri_isi",
              label:{
                  text:"Isi",
              },
              editorType: "dxHtmlEditor",
              editorOptions:{
                  height: 200,
                  toolbar: {
                      items: [
                          "undo", "redo", "separator",
                          {
                              name: "size",
                              acceptedValues: ["8pt", "10pt", "12pt", "14pt", "18pt", "24pt", "36pt"] },
                          "separator", "bold", "italic", "underline", "separator",
                          "alignLeft", "alignCenter", "alignRight", "alignJustify", "separator",
                          "link", "image", "separator",
                      ],
                      multiline:true,
                  },
                  mediaResizing: {
                      enabled: true
                  }
              },
            }],
        },{
          itemType: "group",
          caption: "Program Pendamping",
          items:[{
              dataField: "lembaga_landing_pendamping_judul",
              label:{
                text:"Judul",
              },
            },{
              dataField: "lembaga_landing_pendamping_isi",
              label:{
                  text:"Isi",
              },
              editorType: "dxHtmlEditor",
              editorOptions:{
                  height: 200,
                  toolbar: {
                      items: [
                          "undo", "redo", "separator",
                          {
                              name: "size",
                              acceptedValues: ["8pt", "10pt", "12pt", "14pt", "18pt", "24pt", "36pt"] },
                          "separator", "bold", "italic", "underline", "separator",
                          "alignLeft", "alignCenter", "alignRight", "alignJustify", "separator",
                          "link", "image", "separator",
                      ],
                      multiline:true,
                  },
                  mediaResizing: {
                      enabled: true
                  }
              },
            }],
        },{
          itemType: "group",
          caption: "Program Kemitraan",
          items:[{
              dataField: "lembaga_landing_mitra_judul",
              label:{
                text:"Judul",
              },
            },{
              dataField: "lembaga_landing_mitra_isi",
              label:{
                  text:"Isi",
              },
              editorType: "dxHtmlEditor",
              editorOptions:{
                  height: 200,
                  toolbar: {
                      items: [
                          "undo", "redo", "separator",
                          {
                              name: "size",
                              acceptedValues: ["8pt", "10pt", "12pt", "14pt", "18pt", "24pt", "36pt"] },
                          "separator", "bold", "italic", "underline", "separator",
                          "alignLeft", "alignCenter", "alignRight", "alignJustify", "separator",
                          "link", "image", "separator",
                      ],
                      multiline:true,
                  },
                  mediaResizing: {
                      enabled: true
                  }
              },
            }],
        },{
          itemType: "group",
          caption: "Produk Pelatihan",
          items:[{
              dataField: "lembaga_landing_produk_judul",
              label:{
                text:"Judul",
              },
            },{
              dataField: "lembaga_landing_produk_isi",
              label:{
                  text:"Isi",
              },
              editorType: "dxHtmlEditor",
              editorOptions:{
                  height: 200,
                  toolbar: {
                      items: [
                          "undo", "redo", "separator",
                          {
                              name: "size",
                              acceptedValues: ["8pt", "10pt", "12pt", "14pt", "18pt", "24pt", "36pt"] },
                          "separator", "bold", "italic", "underline", "separator",
                          "alignLeft", "alignCenter", "alignRight", "alignJustify", "separator",
                          "link", "image", "separator",
                      ],
                      multiline:true,
                  },
                  mediaResizing: {
                      enabled: true
                  }
              },
            }],
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
