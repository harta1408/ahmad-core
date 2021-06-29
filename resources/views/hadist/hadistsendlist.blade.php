@extends('layouts.menus')
@section('content')
    <div class="long-title"><h3>Hadist Berhasil Terkirim</h3></div>
    {!! Form::open(['id' => 'frm','route' => 'hadist.main', 'class' => 'form-horizontal']) !!}
        <div class="second-group">
            <div id="gridData"></div>
        </div>
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

    $("#gridData").dxDataGrid({
        dataSource: {!!$arrentitas!!},
        keyExpr: "entitas_id",
        showBorders: true,
        searchPanel: {
            visible: false
        },
        scrolling: {
            mode: "virtual"
        },
        columns: [
            {
              dataField: "entitas_jenis",
              caption: "Jenis",
            },{
              dataField: "entitas_kode",
              caption: "Kode",
            },{
              dataField: "entitas_nama",
              caption: "Nama",
            },
            
        ],
        onSelectionChanged: function (selectedItems) {
            var data = selectedItems.selectedRowsData[0];
            $("#txtHadistId").val(data.id);
          },
    });
   
});
</script>
@endsection
