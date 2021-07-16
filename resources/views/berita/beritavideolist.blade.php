@extends('layouts.menus')
@section('content')
    <div class="long-title"><h3>Daftar Video Berita</h3></div>
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
                url: "{{route('berita.video.load')}}"
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
              dataField: "berita_jenis",
              caption: "Jenis",
            },{
              dataField: "berita_judul",
              caption: "Judul",
            },{
              dataField: "berita_lokasi_video",
              caption: "Lokasi Video",
            },
            
        ],
        onSelectionChanged: function (selectedItems) {
            var data = selectedItems.selectedRowsData[0];
            $("#txtBeritaId").val(data.id);
            $("#txtBeritaJenis").val(data.berita_lokasi_video);
            
            $('#modal1 iframe').attr("src",data.berita_lokasi_video);
            $('#modal1').modal('show');
          },
    });
    $("#toolbar").dxToolbar({
    items: [{
        location: 'center',
        locateInMenu: 'never',
        template: function() {
            return $("<div class='toolbar-label'><b>Pembaharuan Berita Kampanye</b></div>");
        }
    },{
        location: 'after',
        widget: 'dxButton',
        locateInMenu: 'auto',
        options: {
            icon: "plus",
            hint: 'Tambah Berita Baru',
            useSubmitBehavior: true,
            onClick: function(e) {      
                $("#txtBeritaState").val("NEW"); //kirim perintah tambah ke server
            }
        }
    },{
        location: 'after',
        widget: 'dxButton',
        locateInMenu: 'auto',
        options: {
            icon: "edit",
            hint: 'Update Data Berita',
            useSubmitBehavior: true,
            onClick: function(e) {      
            var txtBeritaId=document.getElementById("txtBeritaId").value;
            if(txtBeritaId==""){
                DevExpress.ui.notify({
                    message: "Silakan Pilih Berita",
                    position: {
                        my: "center top",
                        at: "center top"
                    }
                }, "warning", 3000);
                e.preventDefault();
                return false;
            }
            $("#txtBeritaState").val("UPDATE"); //kirim perintah update ke server
            }
        }
    },{
        location: 'after',
        widget: 'dxButton',
        locateInMenu: 'auto',
        options: {
            icon: "video",
            hint: 'Preview Video',
            useSubmitBehavior: true,
            onClick: function(e) {      
            var txtBeritaId=document.getElementById("txtBeritaId").value;
            if(txtBeritaId==""){
                DevExpress.ui.notify({
                    message: "Silakan Pilih Berita",
                    position: {
                        my: "center top",
                        at: "center top"
                    }
                }, "warning", 3000);
                e.preventDefault();
                return false;
            }
            $("#txtBeritaState").val("PREVIEW"); //kirim perintah update ke server
            }
        }
    },{
        location: 'after',
        widget: 'dxButton',
        locateInMenu: 'auto',
        options: {
            icon: "trash",
            hint: 'Hapus Data Berita',
            useSubmitBehavior: true,
            onClick: function(e) {      
            var txtBeritaId=document.getElementById("txtBeritaId").value;
            var txtBeritaState=document.getElementById("txtBeritaState").value;
            if(txtBeritaId==""){
                DevExpress.ui.notify({
                    message: "Silakan Pilih Berita.",
                    position: {
                        my: "center top",
                        at: "center top"
                    }
                }, "warning", 3000);
                e.preventDefault();
                return false;
            }
            if(txtBeritaState!="0"){
                DevExpress.ui.notify({
                    message: "Proses Hapus pendamping",
                    position: {
                        my: "center top",
                        at: "center top"
                    }
                }, "error", 3000);
                e.preventDefault();
                return false;
            }
            $("#txtBeritaState").val("DELETE"); //kirim perintah hapus ke server
            }
        }
        }],
    });
});
</script>
@endsection
