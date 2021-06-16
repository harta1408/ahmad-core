@extends('layouts.menus')
@section('content')
<div class="long-title"><h3>Daftarkan Santri Baru</h3></div>
{!! Form::open(['id' => 'frm','route' => 'santri.store','class' => 'form-horizontal']) !!}
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

  var provinsi;
  var kota;
  var kecamatan;
  $("#form").dxForm({
      colCount: 1,
      showColonAfterLabel: true,
      showValidationSummary: true,
      items:[
      {
        itemType:"group",
        colCount:2,
        items: [{
          dataField: "santri_kode",
          label:{
            text:"Kode Santri",
          },
          editorOptions: { 
              value : "Penomoran Otomatis",
              disabled: true
          }
        },{
          dataField: "santri_email",
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
          dataField: "santri_nama",
          label:{
            text:"Nama santri",
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
          dataField: "santri_nid",
          label:{
            text:"No KTP",
          },
        },{
          dataField: "santri_telepon",
          label:{
            text:"Handphone",
          },
          validationRules: [{
              type: "required",
              message: "Nomor Handphone Harus di Isi"
          }]
        },{
          dataField: "santri_gender",
          label:{
            text:"Jenis Kelamin",
          },
          editorType: "dxRadioGroup",
          editorOptions: {
                items: [{"santri_gender":"PRIA","santri_gender_desc":"PRIA"},
                        {"santri_gender":"WANITA","santri_gender_desc":"WANITA"},],
                value:"PRIA",
                displayExpr: "santri_gender_desc",
                valueExpr: "santri_gender",
                layout: "horizontal",
              }
        },{
          dataField: "santri_agama",
          label:{
            text:"Agama",
          },
          editorType: "dxSelectBox",
          editorOptions: {
              items: [{"santri_religion":"ISLAM","santri_religion_desc":"ISLAM"},
                      {"santri_religion":"PROTESTAN","santri_religion_desc":"PROTESTAN"},
                      {"santri_religion":"KATOLIK","santri_religion_desc":"KATOLIK"},
                      {"santri_religion":"BUDHA","santri_religion_desc":"BUDHA"},
                      {"santri_religion":"HINDU","santri_religion_desc":"HINDU"},
                      {"santri_religion":"LAINNYA","santri_religion_desc":"LAINNYA"}],
              displayExpr: "santri_religion_desc",
              valueExpr: "santri_religion",
              value:"ISLAM",
          },
        },{
          dataField: "santri_kerja",
          label:{
            text:"Pekerjaan",
          },
          editorType: "dxSelectBox",
          editorOptions: {
              items: [{"santri_job":"PEGAWAI NEGERI","santri_job_desc":"PEGAWAI NEGERI"},
                      {"santri_job":"KARYAWAN SWASTA","santri_job_desc":"KARYAWAN SWASTA"},
                      {"santri_job":"TNI/POLRI","santri_job_desc":"TNI/POLRI"},
                      {"santri_job":"PENGUSAHA","santri_job_desc":"PENGUSAHA"},
                      {"santri_job":"GURU/DOSEN","santri_job_desc":"GURU/DOSEN"},
                      {"santri_job":"TENAGA KESEHATAN","santri_job_desc":"TENAGA KESEHATAN"},
                      {"santri_job":"BIDANG HUKUM","santri_job_desc":"BIDANG HUKUM"},
                      {"santri_job":"PEDAGANG","santri_job_desc":"PEDAGANG"},
                      {"santri_job":"BIDANG JASA","santri_job_desc":"BIDANG JASA"},
                      {"santri_job":"IBU RUMAH TANGGA","santri_job_desc":"IBU RUMAH TANGGA"},
                      {"santri_job":"LAINNYA","santri_job_desc":"LAINNYA"}],
              displayExpr: "santri_job_desc",
              valueExpr: "santri_job",
              value:"LAINNYA",
          },
        },{
          dataField: "santri_tmp_lahir",
          label:{
            text:"Tempat Lahir",
          },
          editorOptions: {
          }
        },{
            dataField: "santri_tgl_lahir",
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
            dataField: "santri_alamat",
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
            dataField: "santri_provinsi",
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
                    var itemKota=form.getEditor('santri_kota');
                    itemKota.getDataSource().load();
                }
            },
           
        },{
            dataField: "santri_kota",
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
                  var itemKecamatan=form.getEditor('santri_kecamatan');
                  itemKecamatan.getDataSource().load();
              }
            }
        },{
            dataField: "santri_kecamatan",
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
                  var itemKelurahan=form.getEditor('santri_kelurahan');
                  // itemKelurahan.getDataSource().filter(['kecamatan','=',e.value]);
                  itemKelurahan.getDataSource().load();
              }
            }
        },{
            dataField: "santri_kelurahan",
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
                    load: function() {
                      return $.getJSON("{{URL::to('dashboard/kodepos/kelurahan')}}"+ "/" + encodeURIComponent(kecamatan));
                    }
              }),
              displayExpr: "kelurahan",
              valueExpr: "kelurahan",
              searchEnabled: true,
            },
        },{
            dataField: "santri_kode_pos",
            label:{
              text:"Kode Pos",
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