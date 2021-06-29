@extends('layouts.menus')
@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">Pilih Entitas</div>
            <div class="card-body">
                {!! Form::open(['id' => 'frm','route' => 'berita.send.main','class' => 'form-horizontal']) !!}
                    <label>Pilih Jenis Entitas yang dituju</label>
                    <div id="pilihan_pilih_entitas"></div>
                    <div id="btnLanjut"></div>
                    <input id="txtpilihan" type="text" name="pilihan" hidden>
                    <input id="txtberitaid" type="text" name="beritaid" value={!!$beritaid!!} hidden>
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

    var pilih_entitas = ["Semua","Donatur", "Santri","Pendamping"];

    var radjenissoal=$("#pilihan_pilih_entitas").dxRadioGroup({
        items: pilih_entitas,
        value: pilih_entitas[0],
        layout: "vertical",
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


