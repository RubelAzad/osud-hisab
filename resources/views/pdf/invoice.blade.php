<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $sale->invoice_no }}</title>
    <style>
        body { font-family: Helvetica, Arial, sans-serif; font-size: 12px; color: #222; }
        h2 { margin: 0 0 4px; }
        .muted { color: #666; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        th, td { border: 1px solid #ccc; padding: 6px 8px; text-align: left; }
        th { background: #f2f2f2; }
        .right { text-align: right; }
        .totals td { border: none; padding: 3px 8px; }
        .totals tr.grand td { font-weight: bold; font-size: 14px; border-top: 1px solid #333; }
        .header { overflow: hidden; margin-bottom: 10px; }
        .header .left { float: left; }
        .header .right-block { float: right; text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <div class="left">
            <h2>{{ $pharmacy->name ?? config('app.name') }}</h2>
            <div class="muted">{{ $pharmacy->address }}</div>
            <div class="muted">{{ $pharmacy->phone }} {{ $pharmacy->email }}</div>
        </div>
        <div class="right-block">
            <h2>INVOICE</h2>
            <div>{{ $sale->invoice_no }}</div>
            <div class="muted">{{ $sale->sale_date->format('Y-m-d') }}</div>
            <div class="muted">{{ $sale->location->name ?? '' }}</div>
        </div>
    </div>

    <div>
        <strong>Bill To:</strong> {{ $sale->customer->name ?? 'Walk-in Customer' }}
        @if ($sale->customer?->phone) &middot; {{ $sale->customer->phone }} @endif
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Medicine</th>
                <th class="right">Qty</th>
                <th class="right">Price</th>
                <th class="right">Discount</th>
                <th class="right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($sale->items as $i => $item)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $item->medicine->medicine_name ?? '-' }}</td>
                    <td class="right">{{ $item->qty }}</td>
                    <td class="right">{{ number_format($item->price, 2) }}</td>
                    <td class="right">{{ number_format($item->discount, 2) }}</td>
                    <td class="right">{{ number_format($item->total, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="totals" style="width:250px; margin-left: auto;">
        <tr><td>Subtotal</td><td class="right">{{ number_format($sale->subtotal, 2) }}</td></tr>
        <tr><td>Discount</td><td class="right">{{ number_format($sale->discount, 2) }}</td></tr>
        <tr><td>VAT</td><td class="right">{{ number_format($sale->vat, 2) }}</td></tr>
        <tr class="grand"><td>Total</td><td class="right">{{ number_format($sale->total, 2) }}</td></tr>
        <tr><td>Paid</td><td class="right">{{ number_format($sale->paid, 2) }}</td></tr>
        <tr><td>Due</td><td class="right">{{ number_format($sale->due, 2) }}</td></tr>
    </table>

    <p class="muted" style="margin-top: 30px;">Thank you for your business.</p>
</body>
</html>
