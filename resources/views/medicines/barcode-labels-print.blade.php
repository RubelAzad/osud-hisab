<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Barcode Labels</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 10px; }
        .sheet { display: grid; grid-template-columns: repeat(3, 1fr); gap: 8px; }
        .label { border: 1px solid #ccc; padding: 6px; text-align: center; page-break-inside: avoid; }
        .label .name { font-size: 11px; font-weight: bold; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .label .price { font-size: 11px; }
        .print-bar { margin-bottom: 10px; }
        @media print {
            .print-bar { display: none; }
        }
    </style>
</head>
<body>
    <div class="print-bar">
        <button onclick="window.print()">Print</button>
    </div>

    <div class="sheet">
        @forelse ($labels as $medicine)
            <div class="label">
                <div class="name">{{ $medicine->medicine_name }} {{ $medicine->strength }}</div>
                {!! \Milon\Barcode\Facades\DNS1DFacade::getBarcodeHTML($medicine->barcode, 'C128', 1.4, 40) !!}
                <div class="price">{{ number_format($medicine->sale_price, 2) }}</div>
            </div>
        @empty
            <p>No labels selected.</p>
        @endforelse
    </div>
</body>
</html>
