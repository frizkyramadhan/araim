<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Form Berita Acara Peminjaman Barang #{{ $bapb->bapb_no }} - Arkananta Asset Inventory Management</title>
  <style type="text/css">
    .kop {
      float: none;
      margin-left: auto;
      margin-right: auto;
      left: 0;
      right: 0;
      width: 1000px;
      font-size: 14px;
      --border: 2px solid black;
    }

    .no {
      float: inherit;
      margin-top: 5px;
      margin-left: auto;
      margin-right: auto;
      left: 0;
      right: 0;
      width: 1000px;
      font-size: 14px;
      /*border: 2px solid black;*/
    }

    .emp {
      float: inherit;
      margin-top: 5px;
      margin-left: auto;
      margin-right: auto;
      left: 0;
      right: 0;
      width: 900px;
      font-size: 18px;
      /*border: 2px solid black;*/
    }

    .inv {
      float: inherit;
      margin-top: 5px;
      margin-left: auto;
      margin-right: auto;
      left: 0;
      right: 0;
      width: 900px;
      font-size: 18px;
      border: 1px solid black;
      border-bottom: 2px solid black;
    }

    .inv th {
      border: solid 1px #000000;
    }

    .inv td {
      border-left: solid 1px #000000;
      border-right: solid 1px #000000;
      border-bottom: solid 1px #000000;
    }

    .inv tr {
      border: none;
    }

    .inv tr:last-child {
      border-bottom: solid 2px #000000;
    }

    .sign {
      float: inherit;
      margin-left: auto;
      margin-right: auto;
      left: 0;
      right: 0;
      width: 1000px;
      font-size: 14px;
      /* border: 2px solid black; */
    }

    .style1 {
      font-family: Arial, Helvetica, sans-serif;
      font-weight: bold;
      font-size: 24px;
    }

    .style2 {
      font-family: "Times New Roman", Times, serif;
      font-size: 24px;
    }

    .style3 {
      font-family: "Times New Roman", Times, serif;
      font-size: 20px;
    }

    .style4 {
      font-family: Arial, Helvetica, sans-serif
    }

  </style>
</head>

<body>
  <?php
$date = $bapb->bapb_date;
$year = date("Y", strtotime($date));
$month = date("m", strtotime($date));
$day = date("d", strtotime($date));
$hari = date("l", strtotime($date));
if ($month == "01"){
    $bulan = "Januari";
}else if ($month == "02"){
    $bulan = "Februari";
}else if ($month == "03"){
    $bulan = "Maret";
}else if ($month == "04"){
    $bulan = "April";
}else if ($month == "05"){
    $bulan = "Mei";
}else if ($month == "06"){
    $bulan = "Juni";
}else if ($month == "07"){
    $bulan = "Juli";
}else if ($month == "08"){
    $bulan = "Agustus";
}else if ($month == "09"){
    $bulan = "September";
}else if ($month == "10"){
    $bulan = "Oktober";
}else if ($month == "11"){
    $bulan = "November";
}else if ($month == "12"){
    $bulan = "Desember";
}
if ($hari == "Monday"){
    $h = "Senin";
}else if ($hari == "Tuesday"){
    $h = "Selasa";
}else if ($hari == "Wednesday"){
    $h = "Rabu";
}else if ($hari == "Thursday"){
    $h = "Kamis";
}else if ($hari == "Friday"){
    $h = "Jum'at";
}else if ($hari == "Saturday"){
    $h = "Sabtu";
}else if ($month == "Sunday"){
    $bulan = "Minggu";
}
?>
  <table width="100%" border="0" cellspacing="0" class="kop">
    <tr>
      <td width="256" rowspan="4">
        <div align="center"><img src="{{ asset('assets/dist/img/logo.png') }}" width="225" height="55" /></div>
      </td>
      <td width="521" rowspan="4">
        <div align="center" class="style1"></div>
      </td>
      <td width="74" style="border: #000000 solid 1px"><span class="style4">Doc. No</span></td>
      <td width="137" style="border: #000000 solid 1px"><span class="style4">&nbsp; ARKA/ITY/IV/02.01</span></td>
    </tr>
    <tr>
      <td width="74" style="border: #000000 solid 1px"><span class="style4">Rev. No</span></td>
      <td style="border: #000000 solid 1px"><span class="style4">&nbsp; 0</span></td>
    </tr>
    <tr>
      <td width="74" style="border: #000000 solid 1px"><span class="style4">Eff. Date</span></td>
      <td style="border: #000000 solid 1px"><span class="style4">&nbsp; 1-Oct-13</span></td>
    </tr>
    <tr>
      <td width="74" style="border: #000000 solid 1px"><span class="style4">Page</span></td>
      <td style="border: #000000 solid 1px"><span class="style4">&nbsp; 1</span></td>
    </tr>
  </table>
  <br><br>
  <table width="100%" border="0" cellspacing="0" class="no">
    <tr>
      <td class="style2">
        <div align="center"><b>BERITA ACARA PEMINJAMAN BARANG</b></div>
      </td>
    </tr>
    <tr>
      <td class="style3">
        <div align="center"><b>No : {{ $bapb->bapb_reg }}</b></div>
      </td>
    </tr>
  </table>
  <br><br>
  <table width="100%" border="0" cellspacing="15" class="emp">
    <tr>
      <td colspan="4">Pada hari ini <?php echo $h;?>, <?php echo $day;?> <?php echo $bulan;?> <?php echo $year;?>, yang bertanda tangan di bawah ini:</td>
    </tr>
    <tr>
      <td width="60">&nbsp;</td>
      <td width="92">Nama</td>
      <td width="14">:</td>
      <td width="826">{{ $bapb->submit_name }}</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>NIK</td>
      <td>:</td>
      <td>{{ $bapb->submit_nik }}</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>Jabatan</td>
      <td>:</td>
      <td>{{ $bapb->submit_pos }}</td>
    </tr>
    <tr>
      <td colspan="4">Dengan ini menyerahkan barang kepada:</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>Nama</td>
      <td>:</td>
      <td>{{ $bapb->receive_name }}</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>NIK</td>
      <td>:</td>
      <td>{{ $bapb->receive_nik }}</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>Jabatan</td>
      <td>:</td>
      <td>{{ $bapb->receive_pos }}</td>
    </tr>
    <tr>
      <td colspan="4">Barang tersebut berupa:</td>
    </tr>
  </table>

  <table width="100%" border="0" cellspacing="0" class="inv">
    <tr>
      <th width="52" class="style3" style="border-bottom: solid 2px black">No.</th>
      <th width="372" class="style3" style="border-bottom: solid 2px black">Nama Barang</th>
      <th width="65" class="style3" style="border-bottom: solid 2px black">Qty</th>
      <th width="184" class="style3" style="border-bottom: solid 2px black">S/N</th>
      <th width="215" class="style3" style="border-bottom: solid 2px black">Keterangan</th>
    </tr>
    <tr>
      <?php $i = 1 ?>
      @foreach ($bapb_row as $row)
      <td class="style3">
        <div align="center">{{ $loop->iteration }}</div>
      </td>
      <td class="style3">{{ $row->asset_name }} {{ $row->brand_name }} {{ $row->model_asset }}</td>
      <td class="style3">
        <div align="center">{{ $row->quantity }}</div>
      </td>
      <td class="style3">
        <div align="center">{{ $row->serial_no }}</div>
      </td>
      <td class="style3">
        <div align="center">{{ $row->remarks }}</div>
      </td>
    </tr>
    @endforeach
  </table>

  <table width="100%" border="0" cellspacing="15" class="emp">
    <tr>
      <td>Barang-barang tersebut dipinjamkan selama <b>{{ $bapb->duration }} hari</b> terhitung dari tanggal berita acara peminjaman barang ini dibuat. Barang tersebut dipinjamkan dengan keadaan baik dan diketahui oleh manager / pimpinan departemen yang bersangkutan. Barang di atas menjadi tanggung jawab departemen dan penerima, serta wajib menjaga dan merawat barang tersebut.</td>
    </tr>
  </table>
  <table width="948" border="0" cellspacing="0" class="sign">
    <tr>
      <td width="274" class="style3">
        <div align="center">
          <p>&nbsp;</p>
          <p>Yang Menyerahkan,
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            ({{ $bapb->submit_name }})</p>
        </div>
      </td>
      <td class="style3">
      </td>
      <td width="267" class="style3">
        <div align="center">
          <p align="left">Balikpapan, <?php echo $day;?> <?php echo $bulan;?> <?php echo $year;?></p>
          <p>Yang Menerima,
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            ({{ $bapb->receive_name }})</p>
        </div>
      </td>
    </tr>
  </table>
  <table width="948" border="0" cellspacing="0" class="sign">
    <tr>
      <td>
        <div align="center" class="style3">
          <p>&nbsp;</p>
          <p>Mengetahui,
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            (DEPT HEAD / MANAGER) </p>
        </div>
      </td>
      <td class="style3">
      </td>
      <td>
        <div align="center" class="style3">
          <p>&nbsp;</p>
          <p>Mengetahui,
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            (DEPT HEAD / MANAGER) </p>
        </div>
      </td>
    </tr>
  </table>
  <table width="100%" border="0" cellspacing="15" class="emp">
    <tr>
      <td>
        <div align="center"><i>Nb: Berita Acara Serah Terima( asli) yang telah ditanda tangani dikirim kembali Ke HO Balikpapan</i></div>
      </td>
    </tr>
  </table>
</body>
</html>
