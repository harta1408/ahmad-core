<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Tabel Cicilan</title>
    <link rel="stylesheet" href="{{public_path('css/bootstrap.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{asset('/css/bootstrap.min.css')}}" type="text/css">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins&amp;display=swap">
</head>

<body>
    <div style="text-align: center;"><img src="{{ public_path('images/logo.png') }}"></div>
    <h3 style="height: 48px;text-align: center;margin: 7px;font-size: 24px;font-family: Poppins, sans-serif;font-weight: bold;">Daftar Cicilan Donasi</h3>
    <div class="container">
        <div class="row" style="background: #fcc604;">
            <div class="col">
                <div class="table-responsive">
                    <table class="table">
                        <tbody style="border-style: none;">
                            <tr>
                                <td>No Donasi</td>
                                <td>: {!!$donasi->donasi_no!!}</td>
                            </tr>
                            <tr>
                                <td>Donatur</td>
                                <td>: {!!$donasi->donatur->donatur_nama!!}</td>
                            </tr>
                            <tr>
                                <td>Nominal Cicilan</td>
                                <td>: Rp. {!!number_format($donasi->donasi_nominal)!!}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col">
                <div class="table-responsive" style="border-style: none;">
                    <table class="table">
                        <tbody>
                            <tr>
                                <td>Tanggal Mulai</td>
                                <td>: {{date('d-m-Y',strtotime($donasi->donasi_tanggal))}}</td>
                            </tr>
                            <tr>
                                <td>Tanggal Berakhir</td>
                                <td>: {{date('d-m-Y',strtotime($donasi->donasi_tanggal_akhir))}}</td>
                            </tr>
                            <tr>
                                <td>Waktu Cicilan</td>
                                <td>: [Durasi]</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col"><span><strong>Informasi Pembayaran</strong></span></div>
        </div>
        <div class="row">
            <div class="col">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr></tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Nomor Rekening</td>
                                <td>: {!!$donasi->rekeningbank->rekening_no!!}</td>
                            </tr>
                            <tr>
                                <td>Bank</td>
                                <td>: {!!$donasi->rekeningbank->rekening_nama_bank!!}</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>&nbsp; {!!$donasi->rekeningbank->rekening_nama!!}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="table-responsive">
                    <table class="table">
                        <thead style="border-width: 1px;border-style: solid;">
                            <tr style="border-width: 1px;border-style: solid;">
                                <th style="background: rgb(222,226,230);">No Cicilan</th>
                                <th style="background: rgb(222,226,230);">Jatuh Tempo</th>
                                <th style="background: rgb(222,226,230);">Nominal</th>
                            </tr>
                        </thead>
                        <tbody style="border-width: 1px;border-style: solid;">
                            @foreach ($donasi->cicilan as $cicilan)
                            <tr style="border-width: 1px;border-style: solid;">
                                <td>{{$cicilan->cicilan_ke}}</td>
                                <td>{{date('d-m-Y',strtotime($cicilan->cicilan_jatuh_tempo))}}</td>
                                <td>Rp. {!!number_format($cicilan->cicilan_nominal)!!}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col"><span style="font-size: 12px;font-style: italic;">Dicetak tanggal : {{date('d-m-Y')}}</span></div>
        </div>
    </div>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
</body>

</html>