@extends('layouts.menus')
@section('content')
<div class="long-title"><h3>Pembaharuan Pendamping</h3></div>
{!! Form::open(['id' => 'frm','route' => ['pendamping.update',$pendamping->id],'method' => 'PUT', 'class' => 'form-horizontal']) !!}
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
      formData: {!!$pendamping!!},
      showColonAfterLabel: true,
      showValidationSummary: true,
      items:[
      {
        itemType:"group",
        colCount:2,
        items: [{
          dataField: "pendamping_kode",
          label:{
            text:"Kode Pendamping",
          },
          editorOptions: { 
              value : "Penomoran Otomatis",
              disabled: true
          }
        },{
          dataField: "pendamping_email",
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
          dataField: "pendamping_nama",
          label:{
            text:"Nama Pendamping",
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
          dataField: "pendamping_nid",
          label:{
            text:"No KTP",
          },
        },{
          dataField: "pendamping_telepon",
          label:{
            text:"Handphone",
          },
          validationRules: [{
              type: "required",
              message: "Nomor Handphone Harus di Isi"
          }]
        },{
          dataField: "pendamping_gender",
          label:{
            text:"Jenis Kelamin",
          },
          editorType: "dxRadioGroup",
          editorOptions: {
                items: [{"pendamping_gender":"PRIA","pendamping_gender_desc":"PRIA"},
                        {"pendamping_gender":"WANITA","pendamping_gender_desc":"WANITA"},],
                // value:"PRIA",
                displayExpr: "pendamping_gender_desc",
                valueExpr: "pendamping_gender",
                layout: "horizontal",
              }
        },{
          dataField: "pendamping_agama",
          label:{
            text:"Agama",
          },
          editorType: "dxSelectBox",
          editorOptions: {
              items: [{"pendamping_religion":"ISLAM","pendamping_religion_desc":"ISLAM"},
                      {"pendamping_religion":"PROTESTAN","pendamping_religion_desc":"PROTESTAN"},
                      {"pendamping_religion":"KATOLIK","pendamping_religion_desc":"KATOLIK"},
                      {"pendamping_religion":"BUDHA","pendamping_religion_desc":"BUDHA"},
                      {"pendamping_religion":"HINDU","pendamping_religion_desc":"HINDU"},
                      {"pendamping_religion":"LAINNYA","pendamping_religion_desc":"LAINNYA"}],
              displayExpr: "pendamping_religion_desc",
              valueExpr: "pendamping_religion",
            //   value:"ISLAM",
          },
        },{
          dataField: "pendamping_kerja",
          label:{
            text:"Pekerjaan",
          },
          editorType: "dxSelectBox",
          editorOptions: {
              items: [{"pendamping_job":"PEGAWAI NEGERI","pendamping_job_desc":"PEGAWAI NEGERI"},
                      {"pendamping_job":"KARYAWAN SWASTA","pendamping_job_desc":"KARYAWAN SWASTA"},
                      {"pendamping_job":"TNI/POLRI","pendamping_job_desc":"TNI/POLRI"},
                      {"pendamping_job":"PENGUSAHA","pendamping_job_desc":"PENGUSAHA"},
                      {"pendamping_job":"GURU/DOSEN","pendamping_job_desc":"GURU/DOSEN"},
                      {"pendamping_job":"TENAGA KESEHATAN","pendamping_job_desc":"TENAGA KESEHATAN"},
                      {"pendamping_job":"BIDANG HUKUM","pendamping_job_desc":"BIDANG HUKUM"},
                      {"pendamping_job":"PEDAGANG","pendamping_job_desc":"PEDAGANG"},
                      {"pendamping_job":"BIDANG JASA","pendamping_job_desc":"BIDANG JASA"},
                      {"pendamping_job":"IBU RUMAH TANGGA","pendamping_job_desc":"IBU RUMAH TANGGA"},
                      {"pendamping_job":"LAINNYA","pendamping_job_desc":"LAINNYA"}],
              displayExpr: "pendamping_job_desc",
              valueExpr: "pendamping_job",
              value:"LAINNYA",
          },
        },{
          dataField: "pendamping_tmp_lahir",
          label:{
            text:"Tempat Lahir",
          },
          editorOptions: {
          }
        },{
            dataField: "pendamping_tgl_lahir",
            label:{
                text:"Tanggal Lahir",
            },
            editorType: "dxDateBox",
            editorOptions: {
                displayFormat: "dd-MM-yyyy",
                value : new Date(),
                invalidDateMessage: "The date must have the following format: dd-MM-yyyy"
            }
          },{
            dataField: "pendamping_status_pegawai",
            label:{
              text:"Status Pegawai",
            },
            editorType: "dxRadioGroup",
            editorOptions: {
                items: [{"pendamping_status_pegawai":"TETAP","pendamping_status_pegawai_desc":"TETAP"},
                        {"pendamping_status_pegawai":"KONTRAK","pendamping_status_pegawai_desc":"KONTRAK"},],
                // value:"KONTRAK",
                displayExpr: "pendamping_status_pegawai_desc",
                valueExpr: "pendamping_status_pegawai",
                layout: "horizontal",
              }
          },{
            dataField: "pendamping_honor",
            label:{
              text:"Honor",
            },
            editorType: "dxNumberBox",
            editorOptions: { 
                dataType:"number",
                format: "#,##0",
            },
          },],
      },{
        itemType:"group",
        colCount:1,
        items: [{
            dataField: "pendamping_alamat",
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
            dataField: "pendamping_provinsi_id",
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
                    var itemKota=form.getEditor('pendamping_kota_id');
                    itemKota.getDataSource().load();
                }
            },
           
        },{
            dataField: "pendamping_kota_id",
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
              displayExpr: "city_name",
              valueExpr: "city_id",
              searchEnabled: true,
              onValueChanged : function (e){
                  kota=e.value;
                  var form=$('#form').dxForm('instance');
                  var itemKecamatan=form.getEditor('pendamping_kecamatan_id');
                  itemKecamatan.getDataSource().load();
              }
            }
        },{
            dataField: "pendamping_kecamatan_id",
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
              displayExpr: "subdistrict_name",
              valueExpr: "subdistrict_id",
              searchEnabled: true,
            }     
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
