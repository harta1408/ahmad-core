@extends('layouts.menus')
@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">Reset Pasword</div>
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

  var user= {!! $user!!};
  var formWidget = $("#form").dxForm({
    formData:user,
    readOnly: false,
    showColonAfterLabel: true,
    showValidationSummary: true,
    validationGroup: "userData",
        items: [{ 
            label: {
                    text: "Nama Pengguna",
                },
                dataField: "name", 
                editorOptions:{
                    readOnly:true,
                }
            },{       
                label: {
                    text: "Emai ",
                },
                dataField: "email", 
                editorOptions:{
                    readOnly:true,
                },
            },{
                label: {
                    text: "Password Baru"
                },
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
                    message: "'Password Baru' dan 'Konfirmasi Password' tidak sesuai",
                    comparisonTarget: function() {
                        return formWidget.option("formData").password;
                    }
                }]
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
        var userid='{!! $user->id!!}';
        $.ajax({
          url: "{{URL::to('dashboard/users/adm/update')}}"+"/"+userid,
          method: "PUT",
          data: JSON.stringify({ form: form }),
          contentType: "application/json; charset=utf-8",
          dataType: "json",
          success: function(response){
            if(response.code != 200) {
                swal({
                    title: response.status,
                    icon: response.status,
                    text: response.message,
                    value: true,
                    visible: true,
                    className: "",
                    closeModal: true,
                });
                }
                else {
                    swal({
                        title: response.status,
                        icon: response.status,
                        text: "Password berhasil diperbaharui",
                        value: true,
                        visible: true,
                        className: "",
                        closeModal: true,
                    });
                    window.location = '{{route('users.adm.index')}}';
                }
                
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
            },
        });
    });
});
</script>
@endsection

