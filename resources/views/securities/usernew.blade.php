@extends('layouts.menus')
@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">Pengguna Baru</div>
            <div class="card-body">
            <form id="form-container"  class="first-group">
                <div id="form"></div>
            </form>
            </div>
        </div>
    </div>
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
  $.fn.serializeObject = function()
  {
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
 
  var makeAsyncDataSource = function(jsonFile){
      return new DevExpress.data.CustomStore({
          loadMode: "raw",
          key: "id",
          load: function() {
              return jsonFile;
          }
      });
    };
    var weekdays = [{ id: 1, text: "Senin"},
              { id: 2, text: "Selasa"},
              { id: 3, text: "Rabu"},
              { id: 4, text: "Kamis"},
              { id: 5, text: "Jumat"},
              { id: 6, text: "Sabtu"}, 
              { id: 7, text: "Minggu"}];
  var formWidget = $("#form").dxForm({
    readOnly: false,
    showColonAfterLabel: true,
    showValidationSummary: true,
    validationGroup: "userData",
        items: [{ 
            label: {
                    text: "Alamat email",
                },
                dataField: "email",
                validationRules: [{
                    type: "required",
                    message: "Email harus di isi"
                }, {
                    type: "email",
                    message: "Email tidak valid"
                }]
            },{
            
                label: {
                    text: "Nama Pengguna",
                },
                dataField: "name",
                validationRules: [{
                    type: "required",
                    message: "Nama Pengguna harus di isi"
                },]
            },{
                label: {
                    text: "Jenis Entitas",
                },
                dataField: "tipe",
                editorType: "dxSelectBox",
                editorOptions: {
                    items: [{
                        name: "Donatur",
                        value: "1"
                    },{
                        name: "Santri",
                        value: "2"
                    },{
                        name: "Pendaping",
                        value: "3"
                    },{
                        name: "Manajer",
                        value: "4"
                    },{
                        name: "Help Desk",
                        value: "5"
                    }],
                    displayExpr: "name",
                    valueExpr: "value",
                },
                validationRules: [{
                    type: "required",
                    message: "Pilih Lokasi User",
                }]
            },{
                dataField: "password",
                editorOptions: {
                    mode: "password"
                },
                validationRules: [{
                    type: "required",
                    message: "Password harus di isi"
                }]
            },{
                label: {
                    text: "Konfirmasi Password"
                },
                editorType: "dxTextBox",
                editorOptions: {
                    mode: "password"
                },
                validationRules: [{
                    type: "required",
                    message: "Konfirmasi Password harus di isi"
                }, {
                    type: "compare",
                    message: "'Password' dan 'Konfirmasi Password' tidak sesuai",
                    comparisonTarget: function() {
                        return formWidget.option("formData").password;
                    }
                }]
            // },{
            //     dataField: "role",
            //     editorType: "dxDropDownBox",
            //     editorOptions: {
                 
            //     },
            //     validationRules: [{
            //         type: "required",
            //         message: "Pilih Akses Level"
            //     }]
            },{
            itemType: "button",
            horizontalAlignment: "right",
            buttonOptions: {
                text: "Simpan",
                type: "success",
                useSubmitBehavior: true
            }
        }]
    }).dxForm("instance");

    $("#form-container").on("submit", function(e) {
        e.preventDefault();
        var form =$('#form-container').serializeObject();
        $.ajax({
          type: "POST",
          url: "{{route('users.store')}}",
          data: JSON.stringify({ form: form }),
          contentType: "application/json; charset=utf-8",
          dataType: "json",
          success: function(response){
            if(response.code != 200) {
                swal({
                    title: response.status,
                    icon: 'error',
                    text: response.message,
                    value: true,
                    visible: true,
                    className: "",
                    closeModal: true,
                });
            }else {
                    swal({
                        title: response.status,
                        icon: response.status,
                        text: "Data berhasil diperbaharui",
                        value: true,
                        visible: true,
                        className: "",
                        closeModal: true,
                    });
                    window.location = '{{route('users.index')}}';
                }
                
            },
            error: function(jqXHR, textStatus, errorThrown) {
                swal({
                    title: "Server Error",
                    icon: "error",
                    text: statusText,
                    value: true,
                    visible: true,
                    className: "",
                    closeModal: true,
                });
                return false;
            },
        });
    });
});
</script>
@endsection


