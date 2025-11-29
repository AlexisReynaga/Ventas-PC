<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recibo de Compra #{{ $ticket['ticket_id'] }}</title>
    <style>
        @page { margin: 0px; }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            margin: 0;
            padding: 40px;
            color: #333;
            font-size: 13px;
            line-height: 1.5;
        }
        .header {
            width: 100%;
            border-bottom: 2px solid #00D68F; 
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #000;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .logo span { color: #00D68F; }
        .company-info {
            text-align: right;
            font-size: 11px;
            color: #666;
        }
        
        .details-box {
            width: 100%;
            margin-bottom: 30px;
        }
        .client-box {
            width: 50%;
            float: left;
        }
        .ticket-data {
            width: 40%;
            float: right;
            text-align: right;
        }
        .label {
            font-weight: bold;
            text-transform: uppercase;
            font-size: 10px;
            color: #888;
            margin-bottom: 2px;
            display: block;
        }
        .value {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 10px;
            display: block;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        th {
            background-color: #f8f8f8;
            color: #555;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 10px;
            padding: 12px 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        td {
            padding: 12px 8px;
            border-bottom: 1px solid #eee;
        }
        .col-price { text-align: right; }
        .product-name { font-weight: bold; color: #000; }
        .product-cat { font-size: 10px; color: #888; display: block; }

        .totals-section {
            width: 100%;
            margin-top: 20px;
        }
        .totals-table {
            width: 40%;
            float: right;
        }
        .totals-table td {
            padding: 5px 0;
            border: none;
            text-align: right;
        }
        .total-final {
            font-size: 18px;
            font-weight: bold;
            color: #00D68F;
            border-top: 2px solid #eee;
            padding-top: 10px !important;
        }

        .footer {
            position: fixed;
            bottom: 40px;
            left: 40px;
            right: 40px;
            text-align: center;
            font-size: 10px;
            color: #999;
            border-top: 1px solid #eee;
            padding-top: 20px;
        }
    </style>
</head>
<body>

    <table class="header">
        <tr>
            <td valign="top">
                <div class="logo">Ventas <span>PC</span></div>
                <div style="font-size: 11px; color: #666;">Computing & Solutions</div>
            </td>
            <td valign="top" class="company-info">
                <strong>Ventas PC S.A. de C.V.</strong><br>
                Av. Tecnología 123, Centro<br>
                San Luis Potosí, SLP, México<br>
                soporte@ventaspc.com
            </td>
        </tr>
    </table>

    <div class="details-box">
        <div class="client-box">
            <span class="label">Cliente</span>
            <span class="value">{{ $ticket['user']->name ?? 'Cliente Invitado' }}</span>
            <div style="font-size: 12px; color: #555;">{{ $ticket['user']->email ?? 'Sin correo registrado' }}</div>
        </div>
        <div class="ticket-data">
            <span class="label">Número de Ticket</span>
            <span class="value">#{{ $ticket['ticket_id'] }}</span>
            <span class="label">Fecha de Emisión</span>
            <span style="font-size: 12px;">{{ $ticket['fecha'] }}</span>
        </div>
        <div style="clear: both;"></div>
    </div>

    <table>
        <thead>
            <tr>
                <th width="50%">Descripción</th>
                <th width="15%" style="text-align: center;">Tipo</th>
                <th width="10%" style="text-align: center;">Cant.</th>
                <th width="25%" class="col-price">Importe</th>
            </tr>
        </thead>
        <tbody>
            @php($granTotal = 0)

            @if(!empty($ticket['items']['products']))
                @foreach($ticket['items']['products'] as $p)
                    @php($sub = ($p['price'] ?? 0) * ($p['quantity'] ?? 1))
                    @php($granTotal += $sub)
                    <tr>
                        <td>
                            <span class="product-name">{{ $p['name'] }}</span>
                            <span class="product-cat">Hardware / Accesorio</span>
                        </td>
                        <td style="text-align: center; font-size: 10px; text-transform: uppercase;">Producto</td>
                        <td style="text-align: center;">{{ $p['quantity'] ?? 1 }}</td>
                        <td class="col-price">${{ number_format($sub, 2) }}</td>
                    </tr>
                @endforeach
            @endif

            @if(!empty($ticket['items']['services']))
                @foreach($ticket['items']['services'] as $s)
                    @php($granTotal += ($s['price'] ?? 0))
                    <tr>
                        <td>
                            <span class="product-name">{{ $s['name'] }}</span>
                            <span class="product-cat">
                                Cita: {{ $s['scheduled_date'] ?? 'N/A' }} {{ $s['scheduled_time'] ?? '' }}
                            </span>
                        </td>
                        <td style="text-align: center; font-size: 10px; text-transform: uppercase;">Servicio</td>
                        <td style="text-align: center;">1</td>
                        <td class="col-price">${{ number_format($s['price'] ?? 0, 2) }}</td>
                    </tr>
                @endforeach
            @endif

            @if(empty($ticket['items']['products']) && empty($ticket['items']['services']))
                <tr>
                    <td colspan="4" style="text-align: center; padding: 20px;">No hay items en este ticket.</td>
                </tr>
            @endif
        </tbody>
    </table>

    <div class="totals-section">
        <table class="totals-table">
            <tr>
                <td>Subtotal:</td>
                <td>${{ number_format($granTotal, 2) }}</td>
            </tr>
            <tr>
                <td>Impuestos (0%):</td>
                <td>$0.00</td>
            </tr>
            <tr>
                <td class="total-final">Total a Pagar:</td>
                <td class="total-final">${{ number_format($granTotal, 2) }}</td>
            </tr>
        </table>
        <div style="clear: both;"></div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>Gracias por confiar en Ventas PC.</p>
        <p>Este documento es un comprobante digital de su pedido. Para cualquier duda o aclaración sobre garantías, favor de presentar este ticket.</p>
    </div>

</body>
</html>