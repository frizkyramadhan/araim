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
				margin-top: 0px;
				margin-left: 0px;
				margin-right: 0px;
				margin-bottom: 0px;
			}
		}
	</style>
</head>

<body>
	@if ($tag == 'qrcode')
		<table width="320" border="1" cellspacing="0" cellpadding="0">
			<tr>
				<td width="146" rowspan="4">
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
						{{ $inventory->asset->asset_name }} {{ $inventory->brand }} {{ $inventory->model_asset }}
					</div>
				</td>
			</tr>
			<tr>
				<td height="26">
					<div align="center">
						{{ $inventory->employee->nik }} - {{ $inventory->employee->fullname }}
					</div>
				</td>
			</tr>
		</table>
		<div style="margin-bottom: 3mm;"></div>
	@elseif ($tag == 'qrcodes')
		@foreach ($inventories as $inventory)
			<table width="320" border="1" cellspacing="0" cellpadding="0">
				<tr>
					<td width="146" rowspan="4">
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
							{{ $inventory->asset_name }} {{ $inventory->brand }} {{ $inventory->model_asset }}
						</div>
					</td>
				</tr>
				<tr>
					<td height="26">
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
