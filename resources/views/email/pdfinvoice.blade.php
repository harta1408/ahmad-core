<style type="text/css">
    .tg  {border-collapse:collapse;border-spacing:0;}
    .tg td{border-color:black;border-style:solid;border-width:1px;font-family:Arial, sans-serif;font-size:14px;
      overflow:hidden;padding:10px 5px;word-break:normal;}
    .tg th{border-color:black;border-style:solid;border-width:1px;font-family:Arial, sans-serif;font-size:14px;
      font-weight:normal;overflow:hidden;padding:10px 5px;word-break:normal;}
    .tg .tg-7klg{border-color:#ffffff;font-size:24px;font-weight:bold;text-align:center;vertical-align:top}
    .tg .tg-zv4m{border-color:#ffffff;text-align:left;vertical-align:top}
    .tg .tg-8jgo{border-color:#ffffff;text-align:center;vertical-align:top}
    .tg .tg-b5lz{border-color:#ffffff;font-family:"Arial Black", Gadget, sans-serif !important;;font-style:italic;font-weight:bold;
      text-align:left;vertical-align:top}
    </style>
    <table class="tg" style="undefined;table-layout: fixed; width: 1046px">
    <colgroup>
    <col style="width: 145px">
    <col style="width: 251px">
    <col style="width: 203px">
    <col style="width: 447px">
    </colgroup>
    <thead>
      <tr>
        <th class="tg-7klg" colspan="4">INVOICE</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td class="tg-8jgo" colspan="4">NO : {!!$donasicicilan->donasi->donasi_no !!} - {!!$donasicicilan->cicilan_ke!!}</td>
      </tr>
      <tr>
        <td class="tg-b5lz" colspan="2">Telah terima pembayaran dari :</td>
        <td class="tg-zv4m"></td>
        <td class="tg-zv4m"></td>
      </tr>
      <tr>
        <td class="tg-zv4m">Dari</td>
        <td class="tg-zv4m">{!!$donasicicilan->donasi->donatur->donatur_nama!!}</td>
        <td class="tg-zv4m"></td>
        <td class="tg-zv4m"></td>
      </tr>
      <tr>
        <td class="tg-zv4m">Terbilang</td>
        <td class="tg-zv4m">{{Terbilang($donasicicilan->bayar->bayar_total)}} RUPIAH</td>
        <td class="tg-zv4m"></td>
        <td class="tg-zv4m"></td>
      </tr>
      <tr>
        <td class="tg-zv4m"><span style="font-weight:400;font-style:normal">Keperluan</span></td>
        <td class="tg-zv4m" colspan="3">Pembayaran Cicilan ke {{$donasicicilan->cicilan_ke}} No Donasi {{$donasicicilan->donasi->donasi_no}} atas nama {{$donasicicilan->donasi->donatur->donatur_nama}}</td>
      </tr>
      <tr>
        <td class="tg-zv4m">Nominal</td>
        <td class="tg-zv4m">Rp. {!!number_format($donasicicilan->bayar->bayar_total)!!}</td>
        <td class="tg-zv4m"></td>
        <td class="tg-zv4m"></td>
      </tr>
      <tr>
        <td class="tg-zv4m">Tanggal</td>
        <td class="tg-zv4m">{{date('d-m-Y',strtotime($donasicicilan->bayar->bayar_tanggal))}}</td>
        <td class="tg-zv4m"></td>
        <td class="tg-zv4m"></td>
      </tr>
      <tr>
        <td class="tg-zv4m"></td>
        <td class="tg-zv4m"></td>
        <td class="tg-zv4m"></td>
        <td class="tg-zv4m"></td>
      </tr>
      <tr>
        <td class="tg-zv4m"></td>
        <td class="tg-zv4m"></td>
        <td class="tg-zv4m"></td>
        <td class="tg-zv4m">AHMaD Project</td>
      </tr>
    </tbody>
    </table>

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