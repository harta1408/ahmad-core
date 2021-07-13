@extends('layouts.menus')

@section('content')
<div class="long-title"><h3>{!!$tanggal!!}</h3></div>
<div class="row">
    <div class="col-lg-3 col-6">
      <!-- small box -->
      <div class="small-box bg-info">
        <div class="inner">
          <div class="long-title"><h4>Rp. {!!number_format($dashboard->dash_donasi_nilai)!!}</h4></div>
            <p>Donasi Harian</p>
          </div>
          <div class="icon">
            <i class="ion ion-pie-graph"></i>
          </div>
        <a href="{{route('donasi.index')}}" class="small-box-footer">Selengkapnya <i class="fas fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-6">
      <!-- small box -->
      <div class="small-box bg-danger">
        <div class="inner">
          <div class="long-title"><h4>Rp. {!!number_format($dashboard->dash_donasi_nilai)!!}</h4></div>
            <p>Outstanding Cicilan</p>
          </div>
          <div class="icon">
            <i class="ion ion-stats-bars"></i>
          </div>
        <a href="#" class="small-box-footer">Selengkapnya <i class="fas fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-6">
      <!-- small box -->
      <div class="small-box bg-success">
        <div class="inner">
          <div class="long-title"><h4><sup style="font-size: 20px"> {!!number_format($dashboard->dash_bimbingan_jumlah)!!}</sup></h4></div>
            <p>Bimbingan</p>
          </div>
          <div class="icon">
            <i class="fas fa-chalkboard-teacher"></i>
          </div>
        <a href="#" class="small-box-footer">Selengkapnya <i class="fas fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-6">
      <!-- small box -->
      <div class="small-box bg-warning">
        <div class="inner">
          <div class="long-title"><h4>{!!number_format($dashboard->dash_santri_otorisasi)!!}</h4></div>
            <p>Pengajuan Santri</p>
          </div>
          <div class="icon">
            <i class="ion ion-person-add"></i>
          </div>
        <a href="{{route('santri.otorisasi.index')}}" class="small-box-footer">Selengkapnya <i class="fas fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <!-- ./col -->
  </div>
  <!-- /.row -->
<!-- Custom tabs (Charts with tabs)-->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">
        <i class="fas fa-chart-bar mr-1"></i>
        Total Donasi Bulanan 
        </h3>
        <div class="card-tools">
        {{-- <ul class="nav nav-pills ml-auto"> --}}
            {{-- <li class="nav-item"> --}}
              {{-- <a class="nav-link active" href="#chartmonthlysales_bar" data-toggle="tab">Bar</a> --}}
            {{-- </li> --}}
            {{-- <li class="nav-item">
              <a class="nav-link" href="#chartmonthlysales_area" data-toggle="tab">Area</a>
            </li> --}}
        {{-- </ul> --}}
        </div>
    </div><!-- /.card-header -->
    <div class="card-body">
        <div class="tab-content p-0">
        <!-- Morris chart - Sales -->
        <div class="chart tab-pane active" id="chartmonthlysales_bar" style="position: relative; height: 300px;">
            {{-- <div id="chartmonthlysales_bar"  height="300" style="height: 300px;" ></div>                       --}}
        </div>
        {{-- <div class="chart tab-pane" id="chartmonthlysales_area" style="position: relative; height: 300px;"> --}}
          {{-- <div id="chartmonthlysales_area"  height="300" style="height: 300px;" ></div>                           --}}
        {{-- </div>   --}}
        </div>
    </div> 
    <div class="card-footer">
      <div class="row">
        <div class="col-sm-3 col-6">
          <div class="description-block border-right">
            {{-- @if($dashboard->dash_revenue_last===$dashboard->dash_revenue_month)
              <span class="description-percentage text-warning"><i class="fas fa-caret-left"></i> </span>
            @elseif($dashboard->dash_revenue_last<$dashboard->dash_revenue_month)
              <span class="description-percentage text-success"><i class="fas fa-caret-up"></i> </span>
            @else
              <span class="description-percentage text-danger"><i class="fas fa-caret-down"></i> </span> 
            @endif --}}
            <h5 class="description-header">Rp. </h5>  
            <span class="description-text">TOTAL DONASI</span>
          </div>
          <!-- /.description-block -->
        </div>
        <!-- /.col -->
        <div class="col-sm-3 col-6">
          <div class="description-block border-right">
            {{-- @if($dashboard->dash_cost_last===$dashboard->dash_cost_month)
              <span class="description-percentage text-warning"><i class="fas fa-caret-left"></i> </span>
            @elseif($dashboard->dash_cost_last<$dashboard->dash_cost_month)
              <span class="description-percentage text-success"><i class="fas fa-caret-up"></i> </span>
            @else
              <span class="description-percentage text-danger"><i class="fas fa-caret-down"></i> </span> 
            @endif             --}}
            <h5 class="description-header"> </h5>
            <span class="description-text">TOTAL PRODUK</span>
          </div>
          <!-- /.description-block -->
        </div>
        <!-- /.col -->
        <div class="col-sm-3 col-6">
          <div class="description-block border-right">
            {{-- @if($dashboard->dash_profit_last===$dashboard->dash_profit_month)
              <span class="description-percentage text-warning"><i class="fas fa-caret-left"></i> </span>
            @elseif($dashboard->dash_profit_last<$dashboard->dash_profit_month)
              <span class="description-percentage text-success"><i class="fas fa-caret-up"></i> </span>
            @else
              <span class="description-percentage text-danger"><i class="fas fa-caret-down"></i> </span> 
            @endif             --}}
            <h5 class="description-header"> </h5>
            <span class="description-text">TOTAL BIMBINGAN</span>
          </div>
          <!-- /.description-block -->
        </div>
        <!-- /.col -->
        <div class="col-sm-3 col-6">
          <div class="description-block">
            {{-- @if($dashboard->dash_cust_last===$dashboard->dash_cust_month)
              <span class="description-percentage text-warning"><i class="fas fa-caret-left"></i> {!!number_format($dashboard->dash_cust_last-$dashboard->dash_cust_month)!!}</span>
            @elseif($dashboard->dash_cust_last<$dashboard->dash_cust_month)
              <span class="description-percentage text-success"><i class="fas fa-caret-up"></i> {!!number_format($dashboard->dash_cust_month-$dashboard->dash_cust_last)!!}</span>
            @else
              <span class="description-percentage text-danger"><i class="fas fa-caret-down"></i> {!!number_format($dashboard->dash_cust_last-$dashboard->dash_cust_month)!!}</span> 
            @endif            --}}
             <h5 class="description-header"></h5>
            <span class="description-text">TOTAL SANTRI</span>
          </div>
          <!-- /.description-block -->
        </div>
      </div>
      <!-- /.row -->
    </div>
    <!-- /.card-footer -->
</div>
<!-- /.card -->
 
@endsection

@section('script')


@endsection