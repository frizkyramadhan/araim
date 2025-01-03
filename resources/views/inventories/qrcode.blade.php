<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>{{ $title }}</title>
  <style>
    body {
      font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
      font-size: 11px;
    }

    @media print {

      html,
      body {
        align-content: center;
        width: 2.35cm;
        height: 1.5cm;
        margin: 0;
      }
    }

  </style>
</head>

<body>
  @if ($tag == 'qrcode')
  <table width="320" border="1" cellspacing="0" cellpadding="0">
    <tr>
      <td width="146" rowspan="6">
        <div align="center"><img src="{{ asset('storage/qrcode/' . $inventory->qrcode) }}" width="146"></div>
      </td>
      <td width="174">
        <div align="center"><img src="{{ asset('assets/dist/img/logo-bw.jpg') }}" width="100"></div>
      </td>
    </tr>
    <tr>
      <td>
        <div align="center">{{ $inventory->inventory_no }}</div>
      </td>
    </tr>
    <tr>
      <td>
        <div align="center">
          {{ $inventory->asset_name }} {{ $inventory->brand_name }} {{ $inventory->model_asset }}
        </div>
      </td>
    </tr>
    <tr>
      <td>
        <div align="center">
          {{ $inventory->category_name }}
        </div>
      </td>
    </tr>
    <tr>
      <td>
        <div align="center">
          {{ $inventory->project_code }} - {{ $inventory->location_name }}
        </div>
      </td>
    </tr>
    <tr>
      <td>
        <div align="center">
          {{ $inventory->nik }} - {{ $inventory->fullname }}
        </div>
      </td>
    </tr>
  </table>
  <div style="margin-bottom: 3mm;"></div>
  @elseif ($tag == 'qrcodes')
  @foreach ($inventories as $inventory)
  <table width="320" border="1" cellspacing="0" cellpadding="0">
    <tr>
      <td width="146" rowspan="6">
        <div align="center"><img src="{{ asset('storage/qrcode/' . $inventory->qrcode) }}" width="146"></div>
      </td>
      <td width="174">
        <div align="center"><img src="{{ asset('assets/dist/img/logo-bw.jpg') }}" width="100"></div>
      </td>
    </tr>
    <tr>
      <td>
        <div align="center">{{ $inventory->inventory_no }}</div>
      </td>
    </tr>
    <tr>
      <td>
        <div align="center">
          {{ $inventory->asset_name }} {{ $inventory->brand_name }} {{ $inventory->model_asset }}
        </div>
      </td>
    </tr>
    <tr>
      <td>
        <div align="center">
          {{ $inventory->category_name }}
        </div>
      </td>
    </tr>
    <tr>
      <td>
        <div align="center">
          {{ $inventory->project_code }} - {{ $inventory->location_name }}
        </div>
      </td>
    </tr>
    <tr>
      <td>
        <div align="center">
          {{ $inventory->nik }} - {{ $inventory->fullname }}
        </div>
      </td>
    </tr>
  </table>
  <div style="margin-bottom: 3mm;"></div>
  @endforeach
  @endif
</body>

</html>
