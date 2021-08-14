@extends('layouts.menus')

@section('content')
<div class="long-title"><h3>{!!$tanggal!!}</h3></div>
<div class="row">
    <div class="col-lg-3 col-6">
      <!-- small box -->
      <div class="small-box bg-warning">
        <div class="inner">
          <div class="long-title"><h4>Rp. {!!number_format($dashboard->dash_donasi_harian)!!}</h4></div>
            <p>Donasi Harian</p>
          </div>
          <div class="icon">
            <i class="ion ion-pie-graph"></i>
          </div>
        <a href="{{route('report.donasi.harian')}}" class="small-box-footer">Selengkapnya <i class="fas fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-6">
      <!-- small box -->
      <div class="small-box bg-warning">
        <div class="inner">
          <div class="long-title"><h4>Rp. {!!number_format($dashboard->dash_donasi_tagihan)!!}</h4></div>
            <p>Outstanding Cicilan</p>
          </div>
          <div class="icon">
            <i class="ion ion-stats-bars"></i>
          </div>
        <a href="{{route('report.donasi.cicilan.outstanding')}}"  class="small-box-footer">Selengkapnya <i class="fas fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-6">
      <!-- small box -->
      <div class="small-box bg-warning">
        <div class="inner">
          <div class="long-title"><h4>{!!number_format($dashboard->dash_bimbingan_jumlah)!!}</h4></div>
            <p>Bimbingan</p>
          </div>
          <div class="icon">
            <i class="fas fa-chalkboard-teacher"></i>
          </div>
        <a href="{{route('report.santri.bimbingan')}}"  class="small-box-footer">Selengkapnya <i class="fas fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-6">
      <!-- small box -->
      <div class="small-box bg-warning">
        <div class="inner">
          <div class="long-title"><h4>{!!number_format($dashboard->dash_donasi_santri_nambah)!!}</h4></div>
            <p>Penambahan Santri</p>
          </div>
          <div class="icon">
            <i class="ion ion-person-add"></i>
          </div>
        <a href="{{route('report.santri.baru')}}" class="small-box-footer">Selengkapnya <i class="fas fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <!-- ./col -->
  </div>
  <!-- /.row -->
<!-- Custom tabs (Charts with tabs)-->
<div class="card">
  <div class="card-header">
      <h3 class="card-title">
      <i class="fas fa-chart-line mr-1"></i>
      Grafik
      </h3>
      <div class="card-tools">
        <button type="button" class="btn btn-tool" data-card-widget="collapse">
          <i class="fas fa-minus"></i>
        </button>
        <div class="btn-group">
          <button type="button" class="btn btn-tool dropdown-toggle" data-toggle="dropdown">
            <i class="fas fa-wrench"></i>
          </button>
          <div class="dropdown-menu dropdown-menu-right" role="menu">
            <a href="#" class="dropdown-item">Action</a>
            <a href="#" class="dropdown-item">Another action</a>
            <a href="#" class="dropdown-item">Something else here</a>
            <a class="dropdown-divider"></a>
            <a href="#" class="dropdown-item">Separated link</a>
          </div>
        </div>
        <button type="button" class="btn btn-tool" data-card-widget="remove">
          <i class="fas fa-times"></i>
        </button>
      </div>
  </div><!-- /.card-header -->
  <div class="card-body">
    <div class="row">
    <div class="col-md-6">
      <div id="chartdonasistatus"></div>
    </div>
    <div class="col-md-6">
      <div id="chartsantristatus"></div>
    </div>
  </div> 
  </div>

  <!-- /.card-footer -->
</div>
<!-- /.card -->
 
@endsection

@section('script')
<script type="text/javascript">
  $(function(){
    var donasistatus={!!$dashboard->dash_chart_donasi_status!!}; 
    var santristatus={!!$dashboard->dash_chart_santri_status!!}; 
    $("#chartdonasistatus").dxPieChart({
        palette: "bright",
        dataSource: donasistatus,
        title: {
              text: "Kondisi Donasi",
        },
        series: [{
            argumentField: "donasi_status",
            valueField: "jumlah",
            label: {
                visible: true,
                font: {
                    size: 16
                },
                connector: {
                    visible: true,
                    width: 0.5
                },
                position: "columns",
                customizeText: function(arg) {
                    return arg.valueText + " (" + arg.percentText + ")";
                }
            }
        }],
        "export": {
            enabled: true
        },
      });
      $("#chartsantristatus").dxPieChart({
        palette: "material",
        dataSource: santristatus,
        title: {
              text: "Komposisi Santri",
        },
        series: [{
            argumentField: "santri_status",
            valueField: "jumlah",
            label: {
                visible: true,
                font: {
                    size: 16
                },
                connector: {
                    visible: true,
                    width: 0.5
                },
                position: "columns",
                customizeText: function(arg) {
                    return arg.valueText + " (" + arg.percentText + ")";
                }
            }
        }],
        "export": {
            enabled: true
        },
      });
  });

  </script>
@endsection