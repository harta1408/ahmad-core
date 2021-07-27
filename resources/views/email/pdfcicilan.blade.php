<style type="text/css">
    .tg  {border-collapse:collapse;border-spacing:0;}
    .tg td{border-color:black;border-style:solid;border-width:1px;font-family:Arial, sans-serif;font-size:14px;
      overflow:hidden;padding:10px 5px;word-break:normal;}
    .tg th{border-color:black;border-style:solid;border-width:1px;font-family:Arial, sans-serif;font-size:14px;
      font-weight:normal;overflow:hidden;padding:10px 5px;word-break:normal;}
    .tg .tg-7klg{border-color:#ffffff;font-size:24px;font-weight:bold;text-align:center;vertical-align:top}
    .tg .tg-zv4m{border-color:#ffffff;text-align:left;vertical-align:top}
    .tg .tg-fx7h{background-color:#9b9b9b;border-color:#000000;font-size:18px;font-weight:bold;text-align:left;vertical-align:top}
    .tg .tg-73oq{border-color:#000000;text-align:left;vertical-align:top}
    </style>
    <table class="tg" style="undefined;table-layout: fixed; width: 970px">
    <colgroup>
    <col style="width: 135px">
    <col style="width: 232px">
    <col style="width: 189px">
    <col style="width: 414px">
    </colgroup>
    <thead>
      <tr>
        <th class="tg-7klg" colspan="4">Daftar Cicilan Donasi</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td class="tg-zv4m">No Donasi</td>
        <td class="tg-zv4m">: {!!$donasi->donasi_no!!}</td>
        <td class="tg-zv4m">Tanggal Mulai</td>
        <td class="tg-zv4m">: {{date('d-m-Y',strtotime($donasi->donasi_tanggal))}}</td>
      </tr>
      <tr>
        <td class="tg-zv4m"><span style="font-weight:400;font-style:normal">Nama Donatur</span></td>
        <td class="tg-zv4m">: {!!$donasi->donatur->donatur_nama!!}</td>
        <td class="tg-zv4m">Tanggal Akhir</td>
        <td class="tg-zv4m">: {{date('d-m-Y',strtotime($donasi->donasi_tanggal_akhir))}}</td>
      </tr>
      <tr>
        <td class="tg-zv4m">Nomor Rekening</td>
        <td class="tg-zv4m">: {!!$donasi->rekeningbank->rekening_no!!}</td>
        <td class="tg-zv4m">Nominal Cicilan</td>
        <td class="tg-zv4m">: Rp. {!!number_format($donasi->donasi_nominal)!!} </td>
      </tr>
      <tr>
        <td class="tg-zv4m">Nama Bank</td>
        <td class="tg-zv4m">: {!!$donasi->rekeningbank->rekening_nama_bank!!}</td>
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
        <td class="tg-fx7h">No Cicilan</td>
        <td class="tg-fx7h">Jatuh Tempo</td>
        <td class="tg-fx7h">Nominal</td>
        <td class="tg-zv4m"></td>
      </tr>
      @foreach ($donasi->cicilan as $cicilan)
      <tr>
        <td class="tg-73oq">{{$cicilan->cicilan_ke}}</td>
        <td class="tg-73oq">{{date('d-m-Y',strtotime($cicilan->cicilan_jatuh_tempo))}}<</td>
        <td class="tg-73oq">{!!number_format($cicilan->cicilan_nominal)!!}</td>
        <td class="tg-zv4m"></td>
      </tr>
      @endforeach
    </tbody>
    </table>