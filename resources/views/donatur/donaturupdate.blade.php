@extends('layouts.menus')
@section('content')
<div class="long-title"><h3>Pembaharuan Donatur</h3></div>
{!! Form::open(['id' => 'frm','route' => ['donatur.update',$donatur->id],'method' => 'PUT', 'class' => 'form-horizontal']) !!}
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

  var provinsi='{!!$donatur->donatur_provinsi!!}';
  var kota='{!!$donatur->donatur_kota!!}';
  var kecamatan='{!!$donatur->donatur_kecamatan!!}';
  $("#form").dxForm({
      colCount: 1,
      formData: {!!$donatur!!},
      showColonAfterLabel: true,
      showValidationSummary: true,
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
          validationRules: [{
                    type: "required",
                    message: "Alamat Email Harus Di isi"
                }, {
                    type: "email",
                    message: "Alamat Email tidak valid"
                }]
        },{
          dataField: "donatur_nama",
          label:{
            text:"Nama Donatur",
          },
          validationRules: [{
              type: "required",
              message: "Nama harus di isi"
          }, {
              type: "pattern",
              pattern: "^[^0-9]+$",
              message: "Jangan Gunakan Angka"
          }]
        },{
          dataField: "donatur_nid",
          label:{
            text:"No KTP",
          },
        },{
          dataField: "donatur_telepon",
          label:{
            text:"Handphone",
          },
          validationRules: [{
              type: "required",
              message: "Nomor Handphone Harus di Isi"
          }]
        },{
          dataField: "donatur_gender",
          label:{
            text:"Jenis Kelamin",
          },
          editorType: "dxRadioGroup",
          editorOptions: {
                items: [{"donatur_gender":"PRIA","donatur_gender_desc":"PRIA"},
                        {"donatur_gender":"WANITA","donatur_gender_desc":"WANITA"},],
                // value:"PRIA",
                displayExpr: "donatur_gender_desc",
                valueExpr: "donatur_gender",
                layout: "horizontal",
              }
        },{
          dataField: "donatur_agama",
          label:{
            text:"Agama",
          },
          editorType: "dxSelectBox",
          editorOptions: {
              items: [{"donatur_religion":"ISLAM","donatur_religion_desc":"ISLAM"},
                      {"donatur_religion":"PROTESTAN","donatur_religion_desc":"PROTESTAN"},
                      {"donatur_religion":"KATOLIK","donatur_religion_desc":"KATOLIK"},
                      {"donatur_religion":"BUDHA","donatur_religion_desc":"BUDHA"},
                      {"donatur_religion":"HINDU","donatur_religion_desc":"HINDU"},
                      {"donatur_religion":"LAINNYA","donatur_religion_desc":"LAINNYA"}],
              displayExpr: "donatur_religion_desc",
              valueExpr: "donatur_religion",
              // value:"ISLAM",
          },
        },{
          dataField: "donatur_kerja",
          label:{
            text:"Pekerjaan",
          },
          editorType: "dxSelectBox",
          editorOptions: {
              items: [{"donatur_job":"PEGAWAI NEGERI","donatur_job_desc":"PEGAWAI NEGERI"},
                      {"donatur_job":"KARYAWAN SWASTA","donatur_job_desc":"KARYAWAN SWASTA"},
                      {"donatur_job":"TNI/POLRI","donatur_job_desc":"TNI/POLRI"},
                      {"donatur_job":"PENGUSAHA","donatur_job_desc":"PENGUSAHA"},
                      {"donatur_job":"GURU/DOSEN","donatur_job_desc":"GURU/DOSEN"},
                      {"donatur_job":"TENAGA KESEHATAN","donatur_job_desc":"TENAGA KESEHATAN"},
                      {"donatur_job":"BIDANG HUKUM","donatur_job_desc":"BIDANG HUKUM"},
                      {"donatur_job":"PEDAGANG","donatur_job_desc":"PEDAGANG"},
                      {"donatur_job":"BIDANG JASA","donatur_job_desc":"BIDANG JASA"},
                      {"donatur_job":"IBU RUMAH TANGGA","donatur_job_desc":"IBU RUMAH TANGGA"},
                      {"donatur_job":"LAINNYA","donatur_job_desc":"LAINNYA"}],
              displayExpr: "donatur_job_desc",
              valueExpr: "donatur_job",
              value:"LAINNYA",
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
                invalidDateMessage: "The date must have the following format: dd-MM-yyyy"
            }
          },],
      },{
        itemType:"group",
        colCount:1,
        items: [{
            dataField: "donatur_alamat",
            label:{
              text:"Alamat",
            },
            editorOptions: {
              height: 100
            },
            validationRules: [{
                    type: "required",
                    message: "Alamat Harus di isi",
            }],
        },],
      },{
        itemType:"group",
        colCount:2,
        items: [{
            dataField: "donatur_provinsi",
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
                displayExpr: "provinsi",
                valueExpr: "provinsi",
                searchEnabled: true,
                onValueChanged : function (e){
                    provinsi=e.value;
                    var form=$('#form').dxForm('instance')
                    var itemKota=form.getEditor('donatur_kota');
                    itemKota.getDataSource().load();
                }
            },
           
        },{
            dataField: "donatur_kota",
            label:{
              text:"Kota",
            },  
            editorType: "dxSelectBox",
            validationRules: [{
                    type: "required",
                    message: "Silakan Pilih Kota"
            }],
            editorOptions: {
              dataSource: new DevExpress.data.CustomStore({       
                  loadMode: "raw",   
                  cacheRawData: false,
                  load: function() {
                    return $.getJSON("{{URL::to('dashboard/kodepos/kota')}}"+"/"+encodeURIComponent(provinsi));
                  }
              }),                
              displayExpr: "kota",
              valueExpr: "kota",
              searchEnabled: true,
              onValueChanged : function (e){
                  kota=e.value;
                  var form=$('#form').dxForm('instance');
                  var itemKecamatan=form.getEditor('donatur_kecamatan');
                  itemKecamatan.getDataSource().load();
              }
            }
        },{
            dataField: "donatur_kecamatan",
            label:{
              text:"Kecamatan",
            },  
            editorType: "dxSelectBox",
            validationRules: [{
                    type: "required",
                    message: "Silakan Pilih Kecamatan"
            }],
            editorOptions: {
              dataSource: new DevExpress.data.CustomStore({  
                  loadMode: "raw",
                  cacheRawData: false,         
                  load: function() {
                    return $.getJSON("{{URL::to('dashboard/kodepos/kabupaten')}}"+ "/" + encodeURIComponent(kota));
                  }
              }),
              displayExpr: "kecamatan",
              valueExpr: "kecamatan",
              searchEnabled: true,
              onValueChanged : function (e){
                  kecamatan=e.value;
                  var form=$('#form').dxForm('instance');
                  var itemKelurahan=form.getEditor('donatur_kelurahan');
                  // itemKelurahan.getDataSource().filter(['kecamatan','=',e.value]);
                  itemKelurahan.getDataSource().load();
              }
            }
        },{
            dataField: "donatur_kelurahan",
            label:{
              text:"Kelurahan",
            },  
            editorType: "dxSelectBox",
            validationRules: [{
                    type: "required",
                    message: "Silakan pilih kelurahan"
            }],
            editorOptions: {
              dataSource: new DevExpress.data.CustomStore({  
                  loadMode: "raw",    
                  cacheRawData: false,        
                  load: function() {
                    return $.getJSON("{{URL::to('dashboard/kodepos/kelurahan')}}"+ "/" + encodeURIComponent(kecamatan));
                  }
              }),
              displayExpr: "kelurahan",
              valueExpr: "kelurahan",
              searchEnabled: true,
            },
        },{
            dataField: "donatur_kode_pos",
            label:{
              text:"Kode Pos",
            }, 
       
        },]
      },{
          itemType: "button",
          horizontalAlignment: "left",
          buttonOptions: {
              text: "Perbaharui",
              type: "success",
              useSubmitBehavior: true
          }
      },]
  }).dxForm("instance"); 

});
</script>
@endsection
