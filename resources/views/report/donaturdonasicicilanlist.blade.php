@extends('layouts.menus')
@section('content')
    <div class="long-title"><h3>Daftar Cicilan Donasi</h3></div>
    {!! Form::open(['id' => 'frm','route' => 'report.donasi.cicilan.cetak', 'class' => 'form-horizontal']) !!}
      <div id="toolbar"></div>
      <div id="form"></div>
      <div id="gridData"></div>
      <input id="txtDonasiId" type="text" name="donasi_id" value={!!$donasi->id!!} class="form-control" hidden >
      <input id="txtDonasiCicilanId" type="text" name="donasi_cicilan_id" class="form-control" hidden >
      <input id="txtDonasiCicilanState" type="text" name="donasi_cicilan_state" class="form-control" hidden>
  {!! Form::close()!!}
@endsection

@section('script')
<script type="text/javascript">
$(function(){
    $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    $("#form").dxForm({
      formData:{!!$donasi!!},  
      showColonAfterLabel: true,
      showValidationSummary: true,
      colCount:2,
      items:[
      {
        dataField: "donatur.donatur_nama",
          label:{
            text:"Donatur",
          },
          editorOptions: { 
              disabled: true
          }
        },{
          dataField: "donasi_no",
          label:{
            text:"No Donasi",
          },
          editorOptions: { 
              disabled: true
          }
        },{
          dataField: "donasi_total_harga",
          label:{
            text:"Jumah Donasi",
          },
          editorType: "dxNumberBox",
          editorOptions: { 
            dataType:"number",
            format: "#,##0",
            disabled: true,
          },
        },{
          dataField: "donasi_jumlah_santri",
          label:{
            text:"Jumlah Santri",
          },
          editorOptions: { 
              disabled: true
          }
        },{
          dataField: "donasi_cara_bayar",
          label:{
            text:"Cara Bayar",
          },
          editorType: "dxSelectBox",
          editorOptions: {
              items: [{"donasi_cara_bayar":"1","donasi_cara_bayar_desc":"HARIAN"},
                      {"donasi_cara_bayar":"2","donasi_cara_bayar_desc":"PEKANAN"},
                      {"donasi_cara_bayar":"3","donasi_cara_bayar_desc":"BULANAN"}, 
                      {"donasi_cara_bayar":"4","donasi_cara_bayar_desc":"TUNAI"}],
              displayExpr: "donasi_cara_bayar_desc",
              valueExpr: "donasi_cara_bayar",
              disabled: true,

          },
        },{
            dataField: "donasi_nominal",
            label:{
                text:"Nominal Cicilan",
            },
            editorType: "dxNumberBox",
            editorOptions: { 
              dataType:"number",
              format: "#,##0",
              disabled: true,
            },
      },]
  }).dxForm("instance"); 
    $("#gridData").dxDataGrid({
        dataSource: {!!$donasi->cicilan!!},
        keyExpr: "id",
        showBorders: true,
        dateSerializationFormat:"yyyy-MM-dd",
        selection: {
            mode: "single",
        },
        hoverStateEnabled: true,
        paging: {
            pageSize: 10
        },
        columns: [
            {
                dataField: "cicilan_ke",
                caption: "Cicilan",
            },{
                dataField: "cicilan_jatuh_tempo",
                caption: "Jatuh Tempo",
                dataType:"date",
                format:"dd-MM-yyyy",    
            },{
                dataField: "cicilan_nominal",
                caption: "Nominal",
                dataType:"number",
                format: "#,##0",
            },{
                dataField: "cicilan_status",
                caption: "Status",
                lookup: {
                    dataSource: [{"cicilan_status":"1","cicilan_status_desc":"Belum Bayar"},
                            {"cicilan_status":"2","cicilan_status_desc":"Sudah Bayar"}],
                    valueExpr: "cicilan_status",
                    displayExpr: "cicilan_status_desc",
                },
            },
            
        ],
        onSelectionChanged: function (selectedItems) {
            var data = selectedItems.selectedRowsData[0];
            $("#txtDonasiCicilanId").val(data.id);
            $("#txtDonasiCicilanState").val(data.cicilan_status);
        },
    });
    $("#toolbar").dxToolbar({
    items: [{
        location: 'center',
        locateInMenu: 'never',
        template: function() {
            return $("<div class='toolbar-label'><b>Cetak Cicilan & Invoice</b></div>");
        }
    },{
        location: 'after',
        widget: 'dxButton',
        locateInMenu: 'auto',
        options: {
            icon: "exportselected",
            hint: 'Cetak Daftar Cicilan',
            useSubmitBehavior: true,
            onClick: function(e) {      
                $("#txtDonasiCicilanState").val("CICILAN"); //kirim perintah tambah ke server
            }
        }
    },{
        location: 'after',
        widget: 'dxButton',
        locateInMenu: 'auto',
        options: {
            icon: "money",
            hint: 'Cetak Invoice',
            useSubmitBehavior: true,
            onClick: function(e) {      
            var txtDonasiCicilanId=document.getElementById("txtDonasiCicilanId").value;
            var txtDonasiCicilanState=document.getElementById("txtDonasiCicilanState").value;
            if(txtDonasiCicilanId==""){
                swal({
                    title: "Pilih Cicilan",
                    icon: 'error',
                    text: 'Silakan Pilih Cicilan yang akan Cetak Invoice',
                    value: true,
                    visible: true,
                    className: "",
                    closeModal: true,
                });
                e.preventDefault();
                return false;
            }
            if(txtDonasiCicilanState=="1"){
                swal({
                    title: "Belum Bayar",
                    icon: 'error',
                    text: 'Pembayaran belum diterima, Tidak Dapat Cetak Invoice',
                    value: true,
                    visible: true,
                    className: "",
                    closeModal: true,
                });
                e.preventDefault();
                return false;
            }
            $("#txtDonasiCicilanState").val("INVOICE"); //kirim perintah update ke server
            }
        }
        }]
    });
});
</script>
@endsection
