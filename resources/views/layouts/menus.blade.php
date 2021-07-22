<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, shrink-to-fit=no" />
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>AHMaD Project Dashboard</title>
    <!-- <title>{{ config('app.name', ' 00 Shop') }}</title> -->


    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="{{asset('/css/dx.common.css')}}"  type="text/css">
    <link rel="stylesheet" href="{{asset('/css/dx.greenmist.css')}}"  type="text/css">
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('images/logo_ahmad.png')}}">
    {{-- <link rel="stylesheet" href="{{asset('/css/jquery-ui.css')}}" type="text/css"> --}}
    <link rel="stylesheet" href="{{asset('/css/bootstrap.min.css')}}" type="text/css">
    <link rel="stylesheet" href="{{asset('/css/datepicker3.css')}}" type="text/css">
    <link rel="stylesheet" href="{{asset('/css/jquery.dataTables.min.css')}}" type="text/css">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{{asset('plugins/fontawesome/css/all.min.css')}}">

    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{asset('/css/adminlte.min.css')}}">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{asset('plugins/overlayScrollbars/css/OverlayScrollbars.min.css')}}">
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>


    <script src="{{ asset('plugins/jquery/jquery.min.js')}}" type="text/javascript"></script>
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}" type="text/javascript"></script>
    <script src="{{ asset('plugins/chart.js/dist/Chart.min.js')}}" type="text/javascript"></script>
    <script src="{{ asset('js/adminlte.js')}}" type="text/javascript"></script>
    <script src="{{ asset('plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js')}}" type="text/javascript"></script>

    <script src="{{ asset('js/sweetalert.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js')}}" type="text/javascript"></script>
    <script src="{{ asset('js/datepicker/bootstrap-datepicker.js')}}" type="text/javascript"></script>
    <script src="{{ asset('js/jquery.dataTables.js')}}" type="text/javascript" charset="utf8"></script>
    <script src="{{ asset('js/jszip/dist/jszip.min.js')}}" type="text/javascript"></script>
    <script src="https://unpkg.com/devextreme-quill/dist/dx-quill.min.js"></script>
    <script src="https://cdn3.devexpress.com/jslib/21.1.3/js/dx.all.js"></script>
 

    <script>window.jQuery || document.write(decodeURIComponent('%3Cscript src="js/jquery.min.js"%3E%3C/script%3E'))</script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    

    <style>
      .long-title h3 {
        font-family: 'Segoe UI Light', 'Helvetica Neue Light', 'Segoe UI', 'Helvetica Neue', 'Trebuchet MS', Verdana;
        font-weight: 250;
        font-size: 28px;
        text-align: center;
        margin-bottom: 10px;
      }
      .short-title h4 {
        font-family: 'Segoe UI Light', 'Helvetica Neue Light', 'Segoe UI', 'Helvetica Neue', 'Trebuchet MS', Verdana;
        font-weight: 150;
        font-size: 20px;
        text-align: center;
        margin-bottom: 10px;
      }
      .content {
          /* max-width: 500px; */
          margin: auto;
          /* padding: 2px; */
          margin-left:5px;
          margin-right:5px;
      }
      .container {
          /* max-width: 1200px;  */
          margin: auto;
          padding: 5px;
          margin-left:100px;
          margin-right:5px;
      }
      .blinking{
        animation:blinkingText 1.2s infinite;
      }
      @keyframes blinkingText{
          0%{     color: #000;    }
          49%{    color: #000; }
          60%{    color: transparent; }
          99%{    color:transparent;  }
          100%{   color: #000;    }
      }

      .contentwithleftmenu{
          margin: auto;
          padding: 5px;
          margin-left:10px;
          margin-right:5px;
          /* max-width: 700px; */
      }
      /* #chart {
          height: 450px;
      } */
      img {
          height: 100px;
          width: 100px;
          display: block;
      }
      /* On small screens, set height to 'auto' for sidenav and grid */
      @media screen and (max-width: 767px) {
        .sidenav {
          height: auto;
          padding: 15px;
        }
        .row.content {height:auto;}
      }
    </style>
    <!-- </head> -->
    <link rel="shortcut icon" href="">  <!-- hadle faveico error -->
</head>
<body class="hold-transition sidebar-mini layout-fixed">
  <div class="wrapper">
    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-dark navbar-light">
      <!-- Left navbar links -->
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{ route('login') }}" data-toggle="tooltip" data-placement="bottom" title="Home">
            <i class="fas fa-home"></i>
                  {{-- <img src="{{asset('images/ic_home_white_24dp.png')}}"
                       class="media-object" style="width:20px;height:20px"></i> --}}
                  <span class="sr-only">(current)</span>
          </a>
        </li>
        {{-- <div class="short-titlediv">Tanggal</div> --}}
        
        {{-- @role(array('storeleader','cashier'));
        <li>
          <a class="nav-link" href="{{route('sales.create')}}"
                    data-toggle="tooltip" data-placement="bottom" title="Poin of Sales">
                    <img src="{{asset('images/ic_shopping_cart_white_24dp.png')}}"
                         class="media-object" style="width:20px;height:20px"></i>
          </a>
        </li>
        @endrole --}}
        {{-- @role('superadmin') --}}
        {{-- <li class="nav-item">
          <a href="#" class="nav-link" data-toggle="tooltip" data-placement="bottom" title="Dashboard Store">
            <i class="fas fa-store"></i></a> 
        </li> --}}
        {{-- @endrole --}}
        {{-- <li class="nav-item d-none d-sm-inline-block">
          <a href="#" class="nav-link"><i class="fas fa-store"></i></a>
        </li> --}}
      </ul>



      <!-- Right navbar links -->
      <ul class="navbar-nav ml-auto">
        <li class="nav-item">
          <a class="nav-link" href="{{ route('logout')}}"
                    data-toggle="tooltip" data-placement="left" title="Sign Out"
                    onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();">
                    <i class="nav-icon fas fa-sign-out-alt"></i>
          </a>
        </li>
      </ul>
    </nav>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
           @csrf
    </form>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
      <!-- Brand Logo -->
      <a href="{{ route('login') }}" class="brand-link">
        <img src="{{asset('images/logo_ahmad.jpg')}}" alt="Agile" class="brand-image img-circle elevation-3"
             style="opacity: .8">
             @guest
               <span class="brand-text font-weight-light">AHMaD Dashboard</span>
             @else
               <span class="brand-text font-weight-light">{{ Auth::user()->name}}</span>
             @endguest
      </a>

      <!-- Sidebar -->
      <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
          <div class="image">
            <img src="{{asset('images/logo_ahmad.png')}}" class="img-circle elevation-2" alt="User">
          </div>
          <div class="info">
            <a href="#" class="d-block"><span class="text-muted">{{ Auth::user()->name }}</span></a>
          </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <!-- Add icons to the links using the .nav-icon class
                 with font-awesome or any other icon font library -->
                 {{-- <li class="nav-item has-treeview menu-open"> --}}
            {{-- <li class="nav-item">
              <a href="pages/widgets.html" class="nav-link">
                <i class="nav-icon fas fa-th"></i>
                <p>
                  Widgets
                  <span class="right badge badge-danger">New</span>
                </p>
              </a>
            </li> --}}

             @hasrole('manajer')
            <li class="nav-item has-treeview">
              <a href="#" class="nav-link active"> 
                <i class="nav-icon fas fa-tachometer-alt"></i>
                <p>
                  Dashboard
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="#" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Donatur</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="#" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Santri</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="#" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Pendamping</p>
                  </a>
                </li>
              </ul>
            </li>
            @endrole
            @hasrole('helpdesk')
            <li class="nav-header">FILE</li>
            <li class="nav-item has-treeview">
              <a href="#" class="nav-link">
                <i class="nav-icon fas fa-archive"></i>
                <p>
                  Master
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="{{route('lembaga.index')}}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Lembaga</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{route('faq.index')}}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>FAQ</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{route('rekeningbank.index')}}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Rekening Bank</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{route('kuesioner.index')}}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Kuisioner</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{route('hadiah.index')}}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Hadiah</p>
                  </a>
                </li>
              </ul>
            </li>
            <li class="nav-item has-treeview">
              <a href="#" class="nav-link">
                <i class="nav-icon fas fa-coins"></i>
                <p>
                  Donasi
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="{{route('donasi.pending.list')}}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Daftar Pending</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{route('donasi.random.index')}}"  class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Pemilihan Santri</p>
                  </a>
                </li>
              </ul>
            </li>
            @endrole
            @hasrole('helpdesk')
            <li class="nav-header">ENTITAS</li>
            <li class="nav-item has-treeview">
              <a href="#" class="nav-link">
                <i class="nav-icon fas fa-donate"></i>
                <p>
                  Donatur
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="{{route('donatur.index')}}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>List Donatur</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{route('donatur.create')}}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Buat Baru</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{route('donatur.pembaharuan.index')}}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Pembaharuan</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{route('donatur.donasi.index')}}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Daftar Cicilan</p>
                  </a>
                </li>
              </ul>
            </li>
            <li class="nav-item has-treeview">
              <a href="" class="nav-link">
                <i class="nav-icon fas fa-book-reader"></i>
                <p>
                  Santri
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="{{route('santri.index')}}"  class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>List Santri</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{route('santri.create')}}"  class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Buat Baru</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{route('santri.pembaharuan.index')}}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Pembaharuan</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{route('santri.otorisasi.index')}}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Otorisasi</p>
                  </a>
                </li>
              </ul>
            </li>
            <li class="nav-item has-treeview">
              <a href="#" class="nav-link">
                <i class="nav-icon fas fa-chalkboard-teacher"></i>
                <p>
                  Pendamping
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="{{route('pendamping.index')}}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>List Pendamping</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{route('pendamping.create')}}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Buat Baru</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{route('pendamping.pembaharuan.index')}}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Pembaharuan</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{route('pendamping.otorisasi.index')}}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Otorisasi</p>
                  </a>
                </li>
              </ul>
            </li>
            @endrole
            @hasrole('helpdesk')
            <li class="nav-header">PRODUK & MATERI</li>
            <li class="nav-item has-treeview">
              <a href="#" class="nav-link">
                <i class="nav-icon 	fas fa-gift"></i>
                <p>
                  Produk
                  <i class="fas fa-angle-left right"></i>
                  {{-- <span class="badge badge-info right">6</span> --}}
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="{{route('produk.index')}}" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                    <p>Buat Baru</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{route('produk.edit','1')}}" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                  <p>Perbaharui</p>
                </a>
              </li>
              </ul>
            </li>
            <li class="nav-item has-treeview">
              <a href="#" class="nav-link">
                <i class="nav-icon 	fas fa-shipping-fast"></i>
                <p>
                  Pengiriman
                  <i class="fas fa-angle-left right"></i>
                  {{-- <span class="badge badge-info right">6</span> --}}
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="{{route('kirimproduk.create')}}"  class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Buat Baru</p>
                  </a>
                </li>  
                <li class="nav-item">
                  <a href="{{route('kirimproduk.index')}}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                  <p>Daftar Kirim</p>
                </a>
              </li>
              </ul>
            </li>
            <li class="nav-item has-treeview">
              <a href="#" class="nav-link">
                <i class="nav-icon 	fas fa-book-open"></i>
                <p>
                  Materi
                  <i class="fas fa-angle-left right"></i>
                  {{-- <span class="badge badge-info right">6</span> --}}
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="{{route('materi.index')}}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Materi Belajar</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{route('bimbingan.index')}}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Bimbingan</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="#" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                  <p>Daftar Nilai</p>
                </a>
              </li>              
              </ul>
            </li> 
            @endrole
            @hasrole('helpdesk')
            <li class="nav-header">PENGINGAT & BERITA</li>
            <li class="nav-item has-treeview">
              <a href="#" class="nav-link">
                <i class="nav-icon far fa-bell"></i>
                <p>
                  Pengingat
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="{{route('pengingat.index')}}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Daftar Pengingat</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{route('pengingat.video.index')}}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Konten Video</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{route('pesan.index')}}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Konten Gambar</p>
                  </a>
                </li>
              </ul>
            </li>
            <li class="nav-item has-treeview">
              <a href="#" class="nav-link">
                <i class="nav-icon 	fas fa-bullhorn"></i>
                <p>
                  Berita
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="{{route('berita.index')}}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Daftar Berita</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{route('berita.kampanye.index')}}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Kampanye</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{route('berita.video.index')}}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Video</p>
                  </a>
                </li>
              </ul>
            </li>
            <li class="nav-item has-treeview">
              <a href="#" class="nav-link">
                <i class="nav-icon fas fa-share-alt"></i>
                <p>
                  Referral
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="{{route('referral.index')}}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Daftar Referral</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{route('referral.konten.index')}}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Konten Referral</p>
                  </a>
                </li>
              </ul>
            </li>
            @endrole
            @hasrole('helpdesk')
            <li class="nav-header">HADIST</li>
            <li class="nav-item has-treeview">
              <a href="#" class="nav-link">
                <i class="nav-icon fas fa-layer-group"></i>
                <p>
                  Hadist
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="{{route('hadist.index')}}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Daftar Hadist & Do'a</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{route('hadist.video.index')}}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Daftar Video</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="#" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Daftar Gambar</p>
                  </a>
                </li>
              </ul>
            </li>
            @endrole
            @hasrole('helpdesk')
            <li class="nav-header">LAPORAN</li>
            <li class="nav-item has-treeview">
              <a href="#" class="nav-link">
                <i class="nav-icon 	fas fa-gift"></i>
                <p>
                  Produk
                  <i class="fas fa-angle-left right"></i>
                  {{-- <span class="badge badge-info right">6</span> --}}
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="{{route('produk.index')}}" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                    <p>Buat Baru</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{route('produk.edit','1')}}" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                  <p>Perbaharui</p>
                </a>
              </li>
                <li class="nav-item">
                  <a href="{{route('kirimproduk.create')}}"  class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                  <p>Pengiriman</p>
                </a>
              </li>  
              </ul>
            </li>
            @endrole
            @hasrole('manajer')            
            <li class="nav-header">TRANSAKSI</li>
            <li class="nav-item has-treeview">
              <a href="#" class="nav-link">
                <i class="nav-icon fas fa-luggage-cart"></i>
                <p>
                  Distribusi
                  <i class="fas fa-angle-left right"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item has-treeview">
                  <a href="#" class="nav-link">
                    <i class="fas fa-arrow-circle-down nav-icon"></i>
                    <p>
                      Pengiriman
                      <i class="right fas fa-angle-left"></i>
                    </p>
                  </a>
                  <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="fas fa-arrow-alt-circle-right nav-icon"></i>
                        <p>Buat Baru</p>
                      </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="fas fa-arrow-alt-circle-right nav-icon"></i>
                        <p>Lacak</p>
                      </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="fas fa-arrow-alt-circle-right nav-icon"></i>
                        <p>Pembatalan</p>
                      </a>
                    </li>
                  </ul>
                </li>
                <li class="nav-item has-treeview">
                  <a href="#" class="nav-link">
                    <i class="far fa-arrow-alt-circle-up nav-icon"></i>
                    <p>
                      Retur
                      <i class="right fas fa-angle-left"></i>
                    </p>
                  </a>
                  <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="far fa-arrow-alt-circle-right nav-icon"></i>
                        <p>Buat Baru</p>
                      </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="far fa-arrow-alt-circle-right nav-icon"></i>
                        <p>Daftar</p>
                      </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="far fa-arrow-alt-circle-right nav-icon"></i>
                        <p>Pembatalan</p>
                      </a>
                    </li>
                  </ul>
                </li>
              </ul>
            </li>
            <li class="nav-item has-treeview">
              <a href="#" class="nav-link">
                <i class="nav-icon far fa-file"></i>
                <p>
                  Laporan
                  <i class="fas fa-angle-left right"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                    <p>Stok Produk</p>
                  </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                    <p>Penjualan Harian</p>
                  </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                    <p>Penjualan Periodik</p>
                  </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                    <p>Rugi Laba Gross</p>
                  </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                    <p>Rincian Daftar Menu Resto</p>
                  </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                    <p>Penjualan Pembayaran</p>
                  </a>
                </li>
              </ul>
            </li>
            @endrole
            @hasrole('super-admin')
            <li class="nav-header">PENGATURAN</li>
            <li class="nav-item has-treeview">
              <a href="#" class="nav-link">
                <i class="nav-icon fa fa-wrench"></i>
                <p>
                  Pengguna
                  <i class="fas fa-angle-left right"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="{{route('users.index')}}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                    <p>Atur Pengguna</p>
                  </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                    <p>Level Akses</p>
                  </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                    <p>Akses Holding</p>
                  </a>
                </li>
                <li class="nav-item">
                    <a href="{{route('users.approve.index')}}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Daftar Pengajuan User</p>
                    </a>
                </li>
              </ul>
            </li>
            @endrole
            @hasrole('helpdesk')
            <li class="nav-header">AKUN</li>
            <li class="nav-item has-treeview">
              <a href="#" class="nav-link">
                <i class="nav-icon 	fas fa-user-shield"></i>
                <p>
                  Pengguna
                  <i class="fas fa-angle-left right"></i>
                  {{-- <span class="badge badge-info right">6</span> --}}
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="{{route('produk.index')}}" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                    <p>Buat Baru</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{route('produk.edit','1')}}" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                  <p>Perbaharui</p>
                </a>
              </li>
                <li class="nav-item">
                  <a href="{{route('kirimproduk.create')}}"  class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                  <p>Pengiriman</p>
                </a>
              </li>  
              </ul>
            </li>
            <li class="nav-item has-treeview">
              <a href="#" class="nav-link">
                <i class="nav-icon far fa-comments"></i>
                <p>
                  Pesan
                  <i class="fas fa-angle-left right"></i>
                  {{-- <span class="badge badge-info right">6</span> --}}
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="{{route('produk.index')}}" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                    <p>Buat Baru</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{route('produk.edit','1')}}" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                  <p>Perbaharui</p>
                </a>
              </li>
                <li class="nav-item">
                  <a href="{{route('kirimproduk.create')}}"  class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                  <p>Pengiriman</p>
                </a>
              </li>  
              </ul>
            </li>
            @endrole
            <li class="nav-item">
              <a class="nav-link" href="{{ route('logout')}}"
                data-toggle="tooltip" data-placement="left" title="Sign Out"
                onclick="event.preventDefault();
                document.getElementById('logout-form').submit();">
                <i class="nav-icon fas fa-sign-out-alt"></i>
                <p>Sign Out</p>
              </a>
            </li>
 
          </ul>
        </nav>
       </div>
     </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <div class="container-fluid">
        @yield('content')
      </div>
    </div>
    <!-- /.content-wrapper -->
    <footer class="main-footer">
      <strong>Copyright &copy; 2021 <a href="http://agile.co.id">AHMaD Project</a></strong>
      All rights reserved.
      <div class="float-right d-none d-sm-inline-block">
        {{ Auth::user()->name}} - <b>Version</b> BETA 1.07
      </div>
    </footer>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
      <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
  </div>
  <!-- ./wrapper -->



<!-- mencegah injection -->
<!-- </body></html> -->
</body>
@yield('script')
<script>
$(document).ready(function(){
  $('[data-toggle="tooltip"]').tooltip();
});
</script>
</html>
