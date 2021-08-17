@extends('layouts.menus')
@section('content')
    <div class="long-title"><h3>Daftar Mutasi Bank</h3></div>
    {!! Form::open(['id' => 'frm','route' => 'donasi.mutasi.bank.detail', 'class' => 'form-horizontal']) !!}
    <div id="toolbar"></div>
    <div class="second-group">
        <div id="gridData"></div>
    </div> 
    <input id="txtBankId" type="text" name="bank_id" class="form-control" hidden >
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
    var daftarbank={!!json_encode($daftarbank)!!}

 
    $("#gridData").dxDataGrid({
        dataSource: daftarbank,
        keyExpr: "bank_id",
        showBorders: true,
        dateSerializationFormat:"yyyy-MM-dd",
        selection: {
            mode: "single",
        },
        hoverStateEnabled: true,
        searchPanel: {
            visible: true
        },
        paging: {
            pageSize: 10
        },
        columns: [
            {
                dataField: "label",
                caption: "Nama Bank",
            },{
                dataField: "account_number",
                caption: "No Rekening",

            },{
                dataField: "atas_nama",
                caption: "Atas Nama",
            },{
                dataField: "balance",
                caption: "Saldo",
                dataType:"number",
                format: "fixedPoint",  
            },
            
        ],
        onSelectionChanged: function (selectedItems) {
            var data = selectedItems.selectedRowsData;
            $("#txtBankId").val(data[0]['bank_id']);
        },
    });
    $("#toolbar").dxToolbar({
    items: [{
        location: 'center',
        locateInMenu: 'never',
        template: function() {
            return $("<div class='toolbar-label'><b>Melihat Detail Mutasi Bank</b></div>");
        }
    },{
        location: 'after',
        widget: 'dxButton',
        locateInMenu: 'auto',
        options: {
            icon: "columnfield",
            hint: 'Mutasi Hari Ini',
            useSubmitBehavior: true,
            onClick: function(e) {      
                var dataGrid = $("#gridData").dxDataGrid("instance");
                var selectedRowsData = dataGrid.getSelectedRowsData();
                var jmldata=selectedRowsData.length; 
                var txtBankId=document.getElementById("txtBankId").value;
                if(jmldata==0){
                    swal({
                        title: "Ada Kesalahan",
                        icon: 'error',
                        text: "Proses tidak dapat dilanjutkan, Silakan Pilih Rekening",
                        value: true,
                        visible: true,
                        className: "",
                        closeModal: true,
                    });
                    e.preventDefault();
                    return false;                    
                }
                if(txtBankId==""){
                    swal({
                        title: "Ada Kesalahan",
                        icon: 'error',
                        text: "Tidak Ada Rekening yang dipilih",
                        value: true,
                        visible: true,
                        className: "",
                        closeModal: true,
                    });
                    e.preventDefault();
                    return false;                    
                }
         
            }
        }
    }]
    });
});
</script>
@endsection
