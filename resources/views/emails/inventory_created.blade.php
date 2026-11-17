<!DOCTYPE html>
<html>

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Inventory Created Notification</title>
        <!--[if mso]>
    <style type="text/css">
        body, table, td {font-family: Arial, sans-serif !important;}
    </style>
    <![endif]-->
    </head>

    <body
        style="margin: 0; padding: 0; background-color: #f5f5f5; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"
            style="background-color: #f5f5f5;">
            <tr>
                <td align="center" style="padding: 20px 0;">
                    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="700"
                        style="max-width: 700px; background-color: #ffffff; margin: 0 auto;">

                        <tr>
                            <td
                                style="background-color: #1e3c72; padding: 25px 30px; text-align: center; border-bottom: 3px solid #1a2f5a;">
                                <h1
                                    style="margin: 0 0 5px 0; font-size: 22px; font-weight: 600; letter-spacing: 0.5px; color: #ffffff; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
                                    INVENTORY CREATED
                                </h1>
                                <p
                                    style="margin: 0; font-size: 13px; color: #c5d0e6; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
                                    A new inventory record has been assigned to you
                                </p>
                            </td>
                        </tr>

                        <tr>
                            <td style="padding: 25px 30px;">

                                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"
                                    style="margin-bottom: 12px;">
                                    <tr>
                                        <td style="padding: 12px 15px; background-color: #ffffff;">
                                            <h3
                                                style="margin: 0 0 10px 0; font-size: 14px; font-weight: 600; color: #1e3c72; text-transform: uppercase; letter-spacing: 0.5px; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
                                                Inventory Information
                                            </h3>

                                            <table role="presentation" cellspacing="0" cellpadding="0" border="0"
                                                width="100%">
                                                <tr>
                                                    <td
                                                        style="width: 140px; font-weight: 600; font-size: 12px; color: #555; padding-bottom: 6px; vertical-align: top;">
                                                        Inventory No:
                                                    </td>
                                                    <td style="font-size: 12px; color: #2c3e50; padding-bottom: 6px;">
                                                        <strong
                                                            style="color: #1e3c72; font-weight: 600;">{{ $inventory->inventory_no ?? '-' }}</strong>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td
                                                        style="width: 140px; font-weight: 600; font-size: 12px; color: #555; padding-bottom: 6px; vertical-align: top;">
                                                        Input Date:
                                                    </td>
                                                    <td style="font-size: 12px; color: #2c3e50; padding-bottom: 6px;">
                                                        @if (!empty($inventory->input_date))
                                                            {{ \Carbon\Carbon::parse($inventory->input_date)->locale('id')->isoFormat('D MMMM YYYY') }}
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td
                                                        style="width: 140px; font-weight: 600; font-size: 12px; color: #555; padding-bottom: 6px; vertical-align: top;">
                                                        Asset:
                                                    </td>
                                                    <td style="font-size: 12px; color: #2c3e50; padding-bottom: 6px;">
                                                        {{ $inventory->asset_name ?? '-' }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td
                                                        style="width: 140px; font-weight: 600; font-size: 12px; color: #555; padding-bottom: 6px; vertical-align: top;">
                                                        Brand:
                                                    </td>
                                                    <td style="font-size: 12px; color: #2c3e50; padding-bottom: 6px;">
                                                        {{ $inventory->brand_name ?? '-' }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td
                                                        style="width: 140px; font-weight: 600; font-size: 12px; color: #555; padding-bottom: 6px; vertical-align: top;">
                                                        Model:
                                                    </td>
                                                    <td style="font-size: 12px; color: #2c3e50; padding-bottom: 6px;">
                                                        {{ $inventory->model_asset ?? '-' }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td
                                                        style="width: 140px; font-weight: 600; font-size: 12px; color: #555; padding-bottom: 6px; vertical-align: top;">
                                                        Serial No:
                                                    </td>
                                                    <td style="font-size: 12px; color: #2c3e50; padding-bottom: 6px;">
                                                        {{ $inventory->serial_no ?? '-' }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td
                                                        style="width: 140px; font-weight: 600; font-size: 12px; color: #555; padding-bottom: 6px; vertical-align: top;">
                                                        Quantity:
                                                    </td>
                                                    <td style="font-size: 12px; color: #2c3e50; padding-bottom: 6px;">
                                                        {{ $inventory->quantity ?? '-' }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td
                                                        style="width: 140px; font-weight: 600; font-size: 12px; color: #555; padding-bottom: 6px; vertical-align: top;">
                                                        Status:
                                                    </td>
                                                    <td style="font-size: 12px; color: #2c3e50; padding-bottom: 6px;">
                                                        {{ $inventory->inventory_status ?? '-' }}
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>

                                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"
                                    style="border: 1px solid #e0e0e0; border-left: 3px solid #2a5298; margin-bottom: 12px;">
                                    <tr>
                                        <td style="padding: 12px 15px; background-color: #ffffff;">
                                            <h3
                                                style="margin: 0 0 10px 0; font-size: 14px; font-weight: 600; color: #1e3c72; text-transform: uppercase; letter-spacing: 0.5px; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
                                                Assignment Details
                                            </h3>

                                            <table role="presentation" cellspacing="0" cellpadding="0" border="0"
                                                width="100%">
                                                <tr>
                                                    <td
                                                        style="width: 140px; font-weight: 600; font-size: 12px; color: #555; padding-bottom: 6px; vertical-align: top;">
                                                        Assigned To:
                                                    </td>
                                                    <td style="font-size: 12px; color: #2c3e50; padding-bottom: 6px;">
                                                        {{ $inventory->employee_name ?? '-' }}
                                                        @if (!empty($inventory->employee_nik))
                                                            ({{ $inventory->employee_nik }})
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td
                                                        style="width: 140px; font-weight: 600; font-size: 12px; color: #555; padding-bottom: 6px; vertical-align: top;">
                                                        Project:
                                                    </td>
                                                    <td style="font-size: 12px; color: #2c3e50; padding-bottom: 6px;">
                                                        {{ $inventory->project_code ?? '-' }}
                                                        @if (!empty($inventory->project_name))
                                                            — {{ $inventory->project_name }}
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td
                                                        style="width: 140px; font-weight: 600; font-size: 12px; color: #555; padding-bottom: 6px; vertical-align: top;">
                                                        Department:
                                                    </td>
                                                    <td style="font-size: 12px; color: #2c3e50; padding-bottom: 6px;">
                                                        {{ $inventory->dept_name ?? '-' }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td
                                                        style="width: 140px; font-weight: 600; font-size: 12px; color: #555; padding-bottom: 6px; vertical-align: top;">
                                                        Location:
                                                    </td>
                                                    <td style="font-size: 12px; color: #2c3e50; padding-bottom: 6px;">
                                                        {{ $inventory->location_name ?? '-' }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td
                                                        style="width: 140px; font-weight: 600; font-size: 12px; color: #555; padding-bottom: 6px; vertical-align: top;">
                                                        Remarks:
                                                    </td>
                                                    <td style="font-size: 12px; color: #2c3e50; padding-bottom: 6px;">
                                                        {{ $inventory->remarks ?? '-' }}
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>

                                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"
                                    style="margin-top: 15px; margin-bottom: 12px;">
                                    <tr>
                                        <td style="padding: 12px 15px; background-color: #ffffff;">
                                            <p
                                                style="margin: 0; font-size: 12px; color: #2c3e50; line-height: 1.6; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; text-align: justify;">
                                                Please take care of this inventory item and report any issues to the
                                                asset management team.
                                            </p>
                                        </td>
                                    </tr>
                                </table>

                            </td>
                        </tr>

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
