<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Receipt {{ $sale->invoice_no }}</title>
    <style>
        body { font-family: 'Courier New', monospace; width: 300px; margin: 0 auto; padding: 10px; font-size: 13px; }
        h3 { text-align: center; margin: 0 0 4px; }
        .center { text-align: center; }
        .line { border-top: 1px dashed #000; margin: 6px 0; }
        table { width: 100%; border-collapse: collapse; }
        td { padding: 2px 0; vertical-align: top; }
        .right { text-align: right; }
        .totals td { font-weight: normal; }
        .totals tr.grand td { font-weight: bold; font-size: 15px; }
        .actions { margin-top: 12px; text-align: center; }
        .actions button, .actions a { font-size: 13px; padding: 6px 12px; }
        @media print {
            .actions { display: none; }
        }
    </style>
</head>
<body>
    <h3>{{ currentPharmacy()?->name ?? config('app.name') }}</h3>
    <div class="center">{{ $sale->location->name ?? '' }}</div>
    <div class="center">{{ currentPharmacy()?->phone }}</div>
    <div class="line"></div>
    <div>Invoice: {{ $sale->invoice_no }}</div>
    <div>Date: {{ $sale->sale_date->format('Y-m-d') }} {{ $sale->created_at->format('H:i') }}</div>
    <div>Customer: {{ $sale->customer->name ?? 'Walk-in' }}</div>
    <div>Cashier: {{ $sale->creator->name ?? '-' }}</div>
    <div class="line"></div>

    <table>
        @foreach ($sale->items as $item)
            <tr>
                <td colspan="3">{{ $item->medicine->medicine_name ?? '-' }}</td>
            </tr>
            <tr>
                <td>{{ $item->qty }} x {{ number_format($item->price, 2) }}</td>
                <td></td>
                <td class="right">{{ number_format($item->total, 2) }}</td>
            </tr>
        @endforeach
    </table>
    <div class="line"></div>

    <table class="totals">
        <tr><td>Subtotal</td><td class="right">{{ number_format($sale->subtotal, 2) }}</td></tr>
        <tr><td>Discount</td><td class="right">{{ number_format($sale->discount, 2) }}</td></tr>
        <tr><td>VAT</td><td class="right">{{ number_format($sale->vat, 2) }}</td></tr>
        <tr class="grand"><td>Total</td><td class="right">{{ number_format($sale->total, 2) }}</td></tr>
        <tr><td>Paid</td><td class="right">{{ number_format($sale->paid, 2) }}</td></tr>
        <tr><td>Due</td><td class="right">{{ number_format($sale->due, 2) }}</td></tr>
    </table>
    <div class="line"></div>
    <div class="center">Thank you for your purchase!</div>

    <div class="actions">
        <button onclick="window.print()" class="btn">Print</button>
        <a href="{{ route('pos.index') }}">New Sale</a>
    </div>
</body>
</html>
