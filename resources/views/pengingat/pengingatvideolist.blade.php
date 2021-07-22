@extends('layouts.menus')
@section('content')
    <div class="long-title"><h3>Daftar Video Pengingat</h3></div>
        <div class="second-group">
            <div id="gridData"></div>
            <span>Pastikan pada link Video Youtube ada embed</span>
        </div>
    <!--Modal: Name-->
    <div class="modal fade" id="modal1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
  
          <!--Content-->
          <div class="modal-content">
  
            <!--Body-->
            <div class="modal-body mb-0 p-0">
  
              <div class="embed-responsive embed-responsive-16by9 z-depth-1-half">
                <iframe class="embed-responsive-item" src="" allowfullscreen></iframe>
              </div>
  
            </div>
  
            <!--Footer-->
            <div class="modal-footer justify-content-center">
              <button type="button" class="btn btn-outline-primary btn-rounded btn-md ml-4" data-dismiss="modal">Close</button>
            </div>
  
          </div>
          <!--/.Content-->
  
        </div>
      </div>
      <!--Modal: Name-->
@endsection

@section('script')
<script type="text/javascript">
$(function(){
    $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    var gridDataSource = new DevExpress.data.DataSource({
        load: function (loadOptions) {
            return $.ajax({
                url: "{{route('pengingat.video.load')}}"
            })
        },
    });
    $("#gridData").dxDataGrid({
        dataSource: gridDataSource,
        keyExpr: "id",
        showBorders: true,
        selection: {
            mode: "single"
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
              dataField: "pengingat_jenis",
              caption: "Jenis",
            },{
              dataField: "pengingat_judul",
              caption: "Judul",
            },{
              dataField: "pengingat_lokasi_video",
              caption: "Lokasi Video",
            },
            
        ],
        onSelectionChanged: function (selectedItems) {
            var data = selectedItems.selectedRowsData[0];
            $('#modal1 iframe').attr("src",data.pengingat_lokasi_video);
            $('#modal1').modal('show');
          },
    });
});
</script>
@endsection
