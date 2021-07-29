@extends('layouts.menus')
@section('content')
    <div class="long-title"><h3>Hasil Pelacakan</h3></div>
    <div id="form"></div>
    <div id="gridData"></div>
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
        colCount: 1,
        formData:{!!$kirimproduk!!},
        readOnly:true,
        showColonAfterLabel: true,
        showValidationSummary: true,
        items:[
        {
            itemType:"group",
            colCount:1,
            items: [{
                dataField: "kirim_produk_no_seri",
                label:{
                    text:"Nomor Seri Produk",
                },
            },{
                dataField: "santri.santri_nama",
                label:{
                    text:"Santri",
                },
            },{
                dataField: "kirim_kurir",
                label:{
                    text:"Kurir",
                },
            },{
                dataField: "kirim_no_resi",
                label:{
                    text:"No Resi",
                },
            },{
                dataField: "kirim_tanggal_kirim",
                label:{
                    text:"Tanggal Kirim",
                },  
            },]
        },]
    }).dxForm("instance"); 

    $("#gridData").dxDataGrid({
        dataSource: {!!$kirimproduk->manifest!!},
        showBorders: true,
        allowColumnResizing: true,
        selection: {
            mode: "single"
        },
        columns: [
            {
                dataField: "kirim_manifest_deskripsi",
                caption: "Deskripsi",
            },{
                dataField: "kirim_manifest_kota",
                caption: "Kota",  
                width:150,
            },{
                dataField: "kirim_manifest_waktu",
                caption: "Waktur",
                width:100,
            },{
                dataField: "kirim_manifest_tanggal",
                caption: "Tanggal",
                dataType:"date",
                format:"shortDate",  
                width:100,   
            },            
        ],

    });
 
});
</script>
@endsection
