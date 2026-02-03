<!DOCTYPE html>
<html>
<head>
    <title>Recibo de Compra</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #eee; border-radius: 5px; }
        .header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 20px; margin-bottom: 20px; }
        .details { margin-bottom: 20px; }
        .items { width: 100%; border-collapse: collapse; }
        .items th, .items td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .items th { background-color: #f2f2f2; }
        .totals { margin-top: 20px; text-align: right; }
        .footer { margin-top: 30px; font-size: 12px; text-align: center; color: #777; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Recibo de Compra</h2>
            <p><strong>{{ session('business_name', 'AgendaPOS PRO') }}</strong></p>
        </div>
        
        <div class="details">
            <p><strong>Fecha:</strong> {{ $sale->sale_date }}</p>
            <p><strong>Recibo #:</strong> {{ str_pad($sale->id, 6, '0', STR_PAD_LEFT) }}</p>
            <p><strong>Cliente:</strong> {{ $sale->customer_name ?? 'Cliente General' }}</p>
            <p><strong>Atendido por:</strong> {{ $sale->user->username ?? 'Staff' }}</p>
        </div>

        <table class="items">
            <thead>
                <tr>
                    <th>Descripción</th>
                    <th>Cant.</th>
                    <th>Precio</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($cart as $item)
                <tr>
                    <td>{{ $item['name'] }}</td>
                    <td>{{ $item['quantity'] ?? 1 }}</td>
                    <td>${{ number_format($item['price'], 0) }}</td>
                    <td>${{ number_format(($item['quantity'] ?? 1) * $item['price'], 0) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="totals">
            <p>Subtotal: ${{ number_format($sale->subtotal, 0) }}</p>
            @if($sale->discount > 0)
            <p>Descuento: -${{ number_format($sale->discount, 0) }}</p>
            @endif
            <h3>Total: ${{ number_format($sale->total, 0) }}</h3>
            <p><small>Método de Pago: {{ ucfirst($sale->payment_method) }}</small></p>
        </div>

        <div class="footer">
            <p>¡Gracias por tu visita!</p>
            <p>Este es un comprobante electrónico generado automáticamente.</p>
        </div>
    </div>
</body>
</html>
