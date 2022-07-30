@extends('layouts.menus')
@section('content')
<form id="form-container" class="first-group">
    @csrf
    <div id="toolbar"></div>
    <div id="form" style="margin-top: 10px;"></div>
</form>
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
  $("#toolbar").dxToolbar({
    items: [{
        location: 'center',
        locateInMenu: 'never',
        template: function() {
            return $("<div class='long-title'><h3>Perbaharui Hadiah</h3></div>");
        }
        },{
            location: 'after',
            widget: 'dxButton',
            locateInMenu: 'auto',
            options: {
                icon: "save",
                hint: 'Simpan Penerimaan', 
                onClick: function(e) {       
                    var form =$('#form-container').serializeObject();
                    const hadiahid={!!$hadiah->id!!};
                    $.ajax({
                        type: "PUT", 
                        url: "{{URL::to('dashboard/hadiah')}}"+"/"+hadiahid,
                        data: JSON.stringify({form:form}),
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
                                    window.location = '{{route('hadiah.index')}}';
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
                }
            }
        },{
            location: 'after',
            widget: 'dxButton',
            locateInMenu: 'auto',
            options: {
                icon: "close",
                hint: 'Keluar Tanpa Simpan', 
                onClick: function(e) {      
                    window.location = '{{route('hadiah.index')}}';
                }
            }
        }]
    });
    const hadiah={!!$hadiah!!};
    $("#form").dxForm({
        formData : hadiah,
        colCount: 1,
        showColonAfterLabel: true,
        showValidationSummary: true,
        items: [{
                dataField: "hadiah_jenis",
                label:{
                    text:"Jenis Hadiah",
                },
                editorType: "dxSelectBox",
                editorOptions: {
                    items: [{"hadiah_jenis":"1","hadiah_jenis_desc":"NOMINAL"}, 
                            {"hadiah_jenis":"2","hadiah_jenis_desc":"PRODUK"}],
                    displayExpr: "hadiah_jenis_desc",
                    valueExpr: "hadiah_jenis",
                    value:"1",
                },
                validationRules: [{
                            type: "required",
                            message: "Pilih Jenis Berita"}]
            },{
                dataField: "hadiah_nama",
                label:{
                    text:"Nama Hadiah",
                }, 
                editorOptions:{
                },
                validationRules: [{
                        type: "required",
                        message: "Silakan isi nama hadiah",
                }],
            },{
                dataField: "hadiah_nilai",
                label:{
                    text:"Nilai Hadiah (Poin)",
                },
                editorType: "dxNumberBox",
                editorOptions: { 
                    dataType:"number",
                    format: "#,##0",
                    // value:0,
                },
                validationRules: [{
                    type: "required",
                    message: "Silakan isi jumlah poin yang ditukarkan"
                }]
            },{
                dataField: "hadiah_nominal",
                label:{
                    text:"Nominal Hadiah (Rp.)",
                },
                editorType: "dxNumberBox",
                editorOptions: { 
                    dataType:"number",
                    format: "#,##0",
                    // value:0,
                },
                validationRules: [{
                    type: "required",
                    message: "Silakan isi Nominal Hadiah"
                }]
          
            },{
                dataField: "hadiah_no_seri",
                label:{
                    text:"No Seri (Opsional)",
                }, 
                editorOptions:{
                },

      },]
  }).dxForm("instance"); 

});
</script>
@endsection
