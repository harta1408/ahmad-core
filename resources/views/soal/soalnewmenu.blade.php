@extends('layouts.menus')
@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">Pilih Jenis Pertanyaan</div>
            <div class="card-body">
                {!! Form::open(['id' => 'frm','route' => 'soal.new.menu','class' => 'form-horizontal']) !!}
                    <label>Pilih Jenis Pertanyaan yang akan di buat soal</label>
                    <div id="pilihan_jenis_soal"></div>
                    <div id="btnLanjut"></div>
                    <!-- <div id="selectedStores"></div> -->
                    <input id="txtpilihan" type="text" name="pilihan" hidden>
                {!! Form::close()!!}
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

    var jenis_soal = ["Essay", "Pilihan"];

    var radjenissoal=$("#pilihan_jenis_soal").dxRadioGroup({
        items: jenis_soal,
        value: jenis_soal[0],
        layout: "horizontal",
    }).dxRadioGroup("instance");
 
    $("#btnLanjut").dxButton({
        type: "success",
        text: "Lanjut",
        useSubmitBehavior: true,
        onClick: function(e) {      
             $("#txtpilihan").val(radjenissoal.option("value")); 
        }
    });
 
});
</script>
@endsection


