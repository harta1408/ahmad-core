<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Invoice AHSOHA</title>
    <link rel="stylesheet" href="{{public_path('css/bootstrap.min.css') }}" type="text/css">
    {{-- <link rel="stylesheet" type="text/css" href="{{ base_path().'/public/css/bootstrap.min.css' }}"> --}}

  </head>
<body>
    <div class="container">
        <div class="row">
            <div class="col offset-1 offset-sm-0">   
              <img src="{{ public_path('images/logo.png') }}">   
              {{-- <img src="{{asset('/images/logo.png')}}"
              srcset="https://ahmadproject.org/public/images/logo@2x.png 2x,
              https://ahmadproject.org/public/images/logo@3x.png 3x"> --}}
            </div>
            <div class="col">
              <div style="text-align: right">
                <span>No Invoice : [<span style="font-weight:bold">{!!$donasicicilan->donasi->donasi_no !!} - {!!$donasicicilan->cicilan_ke!!}</span>]&nbsp;</span></div>
              </div>
        </div>
      <div class="row">
          <div class="col"><span>Assallamuallaikum, <span style="font-weight:bold">{!!$donasicicilan->donasi->donatur->donatur_nama!!}</span></span></div>
      </div>
      <div class="row">
          <div class="col"><span><strong>Ringkasan Donasi</strong></span></div>
      </div>
        <div class="row">
            <div class="col">
                <div class="table-responsive">
                    <table class="table" >
                      <tbody style="background: #fcc604;border-radius: 5px;border-top-left-radius: 10px;border-top-right-radius: 10px;border-bottom-right-radius: 10px;border-bottom-left-radius: 10px;">
                            <tr>
                                <td>Tanggal Donasi</td>
                                <td style="text-align: right;">{{date('d-m-Y',strtotime($donasicicilan->cicilan_jatuh_tempo))}}</td>
                            </tr>
                            <tr>
                                <td>Total</td>
                                <td style="text-align: right;">Rp. {!!number_format($donasicicilan->donasi->donasi_total_harga)!!}</td>
                            </tr>
                            <tr>
                                <td>Jenis</td>
                                <td style="text-align: right;">{!!$donasicicilan->donasi->donasi_cara_bayar!!}</td>
                            </tr>
                            <tr>
                                <td>Nominal</td>
                                <td style="text-align: right;">Rp. {!!number_format($donasicicilan->bayar->bayar_total)!!}</td>
                            </tr>
                            <tr>
                                <td>Durasi</td>
                                <td style="text-align: right;">{!!$donasicicilan->donasi->donasi_total_harga/$donasicicilan->donasi->donasi_total_harga!!}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col"><span><strong>Rincian Pembayaran Donasi</strong></span></div>
        </div>
        <div class="row">
            <div class="col">
                <div class="table-responsive">
                    <table class="table">
                        <tbody style="background: rgb(201,204,207);">
                            <tr>
                                <td>Total Bayar (Donasi {{$donasicicilan->cicilan_ke}})</td>
                                <td style="text-align: right;">Rp. {!!number_format($donasicicilan->cicilan_nominal)!!}</td>
                            </tr>
                            <tr>
                                <td>Kode Unik</td>
                                <td style="text-align: right;">{!!$donasicicilan->bayar->bayar_kode_unik!!}</td>
                            </tr>
                            <tr>
                                <td>Grand Total</td>
                                <td style="text-align: right;">Rp. {!!number_format($donasicicilan->bayar->bayar_total)!!}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col" style="text-align: center;">#<span style="font-weight:bold;font-style:italic">{{Terbilang($donasicicilan->bayar->bayar_total)}} RUPIAH </span>#</div>
        </div>
        <div class="row">
            <div class="col"><span><strong>Informasi Bank</strong></span></div>
        </div>
        <div class="row">
            <div class="col" style="background: #ffffff;">
                <div class="table-responsive">
                    <table class="table">
                        <tbody style="background: #f7f2f2;">
                            <tr>
                                <td>Rekening Tujuan</td>
                                <td>{{$donasicicilan->donasi->rekeningbank->rekening_no}}</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>{{$donasicicilan->donasi->rekeningbank->rekening_nama_bank}}</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>{{$donasicicilan->donasi->rekeningbank->rekening_nama}}</td>
                            </tr>
                            <tr>
                                <td>Tanggal Pembayaran</td>
                                <td>{{date('d-m-Y',strtotime($donasicicilan->bayar->bayar_tanggal))}}</td>
                            </tr>
                            <tr>
                                <td>Status Pembayaran</td>
                                <td><strong>LUNAS</strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
</body>

</html>

<?php
  function Terbilang($x) {
    $angka = ["", "SATU", "DUA", "TIGA", "EMPAT", "LIMA", "ENAM", "TUJUH", "DELAPAN", "SEMBILAN", "SEPULUH", "SEBELAS"];
    if ($x < 12)
        return " " . $angka[$x];
    elseif ($x < 20)
        return terbilang($x - 10) . " BELAS";
    elseif ($x < 100)
        return terbilang($x / 10) . " PULUH" . terbilang($x % 10);
    elseif ($x < 200)
        return "SERATUS" . terbilang($x - 100);
    elseif ($x < 1000)
        return terbilang($x / 100) . " RATUS" . terbilang($x % 100);
    elseif ($x < 2000)
        return "SERIBU" . terbilang($x - 1000);
    elseif ($x < 1000000)
        return terbilang($x / 1000) . " RIBU" . terbilang($x % 1000);
    elseif ($x < 1000000000)
        return terbilang($x / 1000000) . " JUTA" . terbilang($x % 1000000);
    }
?>


