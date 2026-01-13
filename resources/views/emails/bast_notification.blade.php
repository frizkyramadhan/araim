<!DOCTYPE html>
<html>

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>BAST Notification</title>
        <!--[if mso]>
    <style type="text/css">
        body, table, td {font-family: Arial, sans-serif !important;}
    </style>
    <![endif]-->
    </head>

    <body
        style="margin: 0; padding: 0; background-color: #f5f5f5; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
        <!-- Wrapper Table -->
        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"
            style="background-color: #f5f5f5;">
            <tr>
                <td align="center" style="padding: 20px 0;">
                    <!-- Email Container -->
                    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="700"
                        style="max-width: 700px; background-color: #ffffff; margin: 0 auto;">

                        <!-- Header -->
                        <tr>
                            <td
                                style="background-color: #1e3c72; padding: 25px 30px; text-align: center; border-bottom: 3px solid #1a2f5a;">
                                <h1
                                    style="margin: 0 0 5px 0; font-size: 22px; font-weight: 600; letter-spacing: 0.5px; color: #ffffff; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
                                    BERITA ACARA SERAH TERIMA (BAST)
                                </h1>
                            </td>
                        </tr>

                        <!-- Content -->
                        <tr>
                            <td style="padding: 25px 30px;">

                                <!-- Document Information, Submitted By, Received By Section -->
                                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"
                                    style="margin-bottom: 12px;">
                                    <tr>
                                        <td style="padding: 12px 15px; background-color: #ffffff;">

                                            <!-- Document Information -->
                                            <h3
                                                style="margin: 0 0 10px 0; font-size: 14px; font-weight: 600; color: #1e3c72; text-transform: uppercase; letter-spacing: 0.5px; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
                                                Document Information
                                            </h3>

                                            <table role="presentation" cellspacing="0" cellpadding="0" border="0"
                                                width="100%">
                                                <tr>
                                                    <td
                                                        style="width: 120px; font-weight: 600; font-size: 12px; color: #555; padding-bottom: 6px; vertical-align: top;">
                                                        BAST Number:
                                                    </td>
                                                    <td style="font-size: 12px; color: #2c3e50; padding-bottom: 6px;">
                                                        <strong
                                                            style="color: #1e3c72; font-weight: 600;">{{ $bast->bast_reg }}</strong>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td
                                                        style="width: 120px; font-weight: 600; font-size: 12px; color: #555; padding-bottom: 6px; vertical-align: top;">
                                                        Date:
                                                    </td>
                                                    <td style="font-size: 12px; color: #2c3e50; padding-bottom: 6px;">
                                                        {{ \Carbon\Carbon::parse($bast->bast_date)->locale('id')->isoFormat('D MMMM YYYY') }}
                                                    </td>
                                                </tr>
                                            </table>

                                            <!-- Submitted By -->
                                            <h3
                                                style="margin: 15px 0 10px 0; font-size: 14px; font-weight: 600; color: #1e3c72; text-transform: uppercase; letter-spacing: 0.5px; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
                                                Submitted By
                                            </h3>

                                            <table role="presentation" cellspacing="0" cellpadding="0" border="0"
                                                width="100%">
                                                <tr>
                                                    <td
                                                        style="width: 120px; font-weight: 600; font-size: 12px; color: #555; padding-bottom: 6px; vertical-align: top;">
                                                        Name:
                                                    </td>
                                                    <td style="font-size: 12px; color: #2c3e50; padding-bottom: 6px;">
                                                        {{ $bast->submit_name }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td
                                                        style="width: 120px; font-weight: 600; font-size: 12px; color: #555; padding-bottom: 6px; vertical-align: top;">
                                                        NIK:
                                                    </td>
                                                    <td style="font-size: 12px; color: #2c3e50; padding-bottom: 6px;">
                                                        {{ $bast->submit_nik }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td
                                                        style="width: 120px; font-weight: 600; font-size: 12px; color: #555; padding-bottom: 6px; vertical-align: top;">
                                                        Position:
                                                    </td>
                                                    <td style="font-size: 12px; color: #2c3e50; padding-bottom: 6px;">
                                                        {{ $bast->submit_pos }}
                                                    </td>
                                                </tr>
                                            </table>

                                            <!-- Received By -->
                                            <h3
                                                style="margin: 15px 0 10px 0; font-size: 14px; font-weight: 600; color: #1e3c72; text-transform: uppercase; letter-spacing: 0.5px; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
                                                Received By
                                            </h3>

                                            <table role="presentation" cellspacing="0" cellpadding="0" border="0"
                                                width="100%">
                                                <tr>
                                                    <td
                                                        style="width: 120px; font-weight: 600; font-size: 12px; color: #555; padding-bottom: 6px; vertical-align: top;">
                                                        Name:
                                                    </td>
                                                    <td style="font-size: 12px; color: #2c3e50; padding-bottom: 6px;">
                                                        {{ $bast->receive_name }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td
                                                        style="width: 120px; font-weight: 600; font-size: 12px; color: #555; padding-bottom: 6px; vertical-align: top;">
                                                        NIK:
                                                    </td>
                                                    <td style="font-size: 12px; color: #2c3e50; padding-bottom: 6px;">
                                                        {{ $bast->receive_nik }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td
                                                        style="width: 120px; font-weight: 600; font-size: 12px; color: #555; padding-bottom: 6px; vertical-align: top;">
                                                        Position:
                                                    </td>
                                                    <td style="font-size: 12px; color: #2c3e50; padding-bottom: 6px;">
                                                        {{ $bast->receive_pos }}
                                                    </td>
                                                </tr>
                                            </table>

                                        </td>
                                    </tr>
                                </table>

                                <!-- Inventory Items Section -->
                                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"
                                    style="border: 1px solid #e0e0e0; border-left: 3px solid #2a5298; margin-bottom: 12px;">
                                    <tr>
                                        <td style="padding: 12px 15px; background-color: #ffffff;">
                                            <h3
                                                style="margin: 0 0 10px 0; font-size: 14px; font-weight: 600; color: #1e3c72; text-transform: uppercase; letter-spacing: 0.5px; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
                                                Inventory Items
                                            </h3>

                                            <table role="presentation" cellspacing="0" cellpadding="0" border="0"
                                                width="100%"
                                                style="margin-top: 10px; font-size: 11px; border-collapse: collapse;">
                                                <thead>
                                                    <tr>
                                                        <th
                                                            style="background-color: #1e3c72; color: #ffffff; padding: 8px 10px; text-align: left; font-weight: 600; font-size: 11px; text-transform: uppercase; letter-spacing: 0.3px; border: 1px solid #1e3c72;">
                                                            No
                                                        </th>
                                                        <th
                                                            style="background-color: #1e3c72; color: #ffffff; padding: 8px 10px; text-align: left; font-weight: 600; font-size: 11px; text-transform: uppercase; letter-spacing: 0.3px; border: 1px solid #1e3c72;">
                                                            Inventory No
                                                        </th>
                                                        <th
                                                            style="background-color: #1e3c72; color: #ffffff; padding: 8px 10px; text-align: left; font-weight: 600; font-size: 11px; text-transform: uppercase; letter-spacing: 0.3px; border: 1px solid #1e3c72;">
                                                            Asset
                                                        </th>
                                                        <th
                                                            style="background-color: #1e3c72; color: #ffffff; padding: 8px 10px; text-align: left; font-weight: 600; font-size: 11px; text-transform: uppercase; letter-spacing: 0.3px; border: 1px solid #1e3c72;">
                                                            Brand
                                                        </th>
                                                        <th
                                                            style="background-color: #1e3c72; color: #ffffff; padding: 8px 10px; text-align: left; font-weight: 600; font-size: 11px; text-transform: uppercase; letter-spacing: 0.3px; border: 1px solid #1e3c72;">
                                                            Model
                                                        </th>
                                                        <th
                                                            style="background-color: #1e3c72; color: #ffffff; padding: 8px 10px; text-align: left; font-weight: 600; font-size: 11px; text-transform: uppercase; letter-spacing: 0.3px; border: 1px solid #1e3c72;">
                                                            Serial No
                                                        </th>
                                                        <th
                                                            style="background-color: #1e3c72; color: #ffffff; padding: 8px 10px; text-align: left; font-weight: 600; font-size: 11px; text-transform: uppercase; letter-spacing: 0.3px; border: 1px solid #1e3c72;">
                                                            Date
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($bastRow as $index => $row)
                                                        <tr
                                                            style="background-color: {{ $loop->even ? '#f9f9f9' : '#ffffff' }};">
                                                            <td
                                                                style="border: 1px solid #e0e0e0; padding: 8px 10px; color: #2c3e50;">
                                                                {{ $loop->iteration }}
                                                            </td>
                                                            <td
                                                                style="border: 1px solid #e0e0e0; padding: 8px 10px; color: #2c3e50;">
                                                                {{ $row->inventory_no }}
                                                            </td>
                                                            <td
                                                                style="border: 1px solid #e0e0e0; padding: 8px 10px; color: #2c3e50;">
                                                                {{ $row->asset_name }}
                                                            </td>
                                                            <td
                                                                style="border: 1px solid #e0e0e0; padding: 8px 10px; color: #2c3e50;">
                                                                {{ $row->brand_name }}
                                                            </td>
                                                            <td
                                                                style="border: 1px solid #e0e0e0; padding: 8px 10px; color: #2c3e50;">
                                                                {{ $row->model_asset }}
                                                            </td>
                                                            <td
                                                                style="border: 1px solid #e0e0e0; padding: 8px 10px; color: #2c3e50;">
                                                                {{ $row->serial_no }}
                                                            </td>
                                                            <td
                                                                style="border: 1px solid #e0e0e0; padding: 8px 10px; color: #2c3e50;">
                                                                {{ \Carbon\Carbon::parse($row->input_date)->locale('id')->isoFormat('D MMMM YYYY') }}
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                </table>

                                <!-- Terms and Conditions Section -->
                                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"
                                    style="margin-top: 15px; margin-bottom: 12px;">
                                    <tr>
                                        <td style="padding: 12px 15px; background-color: #ffffff;">
                                            <p
                                                style="margin: 0 0 10px 0; font-size: 12px; color: #2c3e50; line-height: 1.6; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; text-align: justify;">
                                                Barang tersebut diserahkan dengan keadaan baik dan diketahui oleh
                                                manager / pimpinan departemen yang bersangkutan. Barang di atas menjadi
                                                tanggung jawab departemen dan penerima, serta wajib menjaga dan merawat
                                                barang tersebut.
                                            </p>
                                            <p
                                                style="margin: 0; font-size: 12px; color: #2c3e50; line-height: 1.6; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; text-align: justify;">
                                                Demikian berita acara serah terima barang ini dibuat untuk dapat
                                                digunakan sebagaimana mestinya.
                                            </p>
                                        </td>
                                    </tr>
                                </table>

                                <!-- Document Attachment / Note -->
                                @if ($bast->signed_document)
                                    <table role="presentation" cellspacing="0" cellpadding="0" border="0"
                                        width="100%"
                                        style="background-color: #e8f5e9; border: 1px solid #c8e6c9; border-left: 3px solid #4caf50; margin-top: 15px;">
                                        <tr>
                                            <td style="padding: 10px 15px;">
                                                <p
                                                    style="margin: 0; font-size: 12px; color: #2e7d32; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
                                                    <strong style="color: #1b5e20;">Document Attachment:</strong>
                                                    Signed BAST document is attached to this email.<br>
                                                    File: <strong
                                                        style="color: #1b5e20;">BAST_Signed_Document_{{ $bast->bast_reg }}.{{ pathinfo($bast->signed_document, PATHINFO_EXTENSION) }}</strong>
                                                </p>
                                            </td>
                                        </tr>
                                    </table>
                                @else
                                    <table role="presentation" cellspacing="0" cellpadding="0" border="0"
                                        width="100%"
                                        style="background-color: #fff3e0; border: 1px solid #ffcc80; border-left: 3px solid #ff9800; margin-top: 15px;">
                                        <tr>
                                            <td style="padding: 10px 15px;">
                                                <p
                                                    style="margin: 0; font-size: 12px; color: #e65100; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
                                                    <strong>Note:</strong> Signed document is not available for this
                                                    BAST.
                                                </p>
                                            </td>
                                        </tr>
                                    </table>
                                @endif

                            </td>
                        </tr>

                        <!-- Footer -->
                        <tr>
                            <td
                                style="background-color: #f5f5f5; padding: 15px 30px; text-align: center; border-top: 1px solid #e0e0e0; font-size: 11px; color: #7f8c8d;">
                                <p
                                    style="margin: 3px 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
                                    <strong>ARAIM v2 - Arkananta Asset Inventory Management</strong>
                                </p>
                                <p
                                    style="margin: 3px 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
                                    This is an automated notification. Please do not reply to this email.
                                </p>
                            </td>
                        </tr>

                    </table>
                </td>
            </tr>
        </table>
    </body>

</html>
