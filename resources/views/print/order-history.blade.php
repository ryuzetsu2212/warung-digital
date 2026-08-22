<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Warung Digital - Riwayat Pesanan - {{ $periodLabels[$period] }}</title>
    <script>
        window.onload = function() {
            document.getElementById('btn-print').addEventListener('click', () => window.print());
        };
    </script>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', Arial, sans-serif; color: #111; background: #fff; padding: 40px; }
        .toolbar { display: none; }
        .header { text-align: center; border-bottom: 3px double #111; padding-bottom: 16px; margin-bottom: 24px; }
        .header h1 { font-size: 22px; margin-bottom: 6px; }
        .header p { font-size: 13px; color: #444; }
        .meta { display: flex; justify-content: space-between; margin-bottom: 20px; font-size: 13px; }
        .summary { display: flex; gap: 32px; margin-bottom: 24px; font-size: 14px; }
        .summary strong { display: block; border: 1px solid #ccc; padding: 10px 16px; border-radius: 6px; }
        table { width: 100%; border-collapse: collapse; font-size: 12px; }
        th, td { border: 1px solid #999; padding: 8px 10px; text-align: left; vertical-align: top; }
        th { background: #eee; text-transform: uppercase; font-size: 11px; }
        .right { text-align: right; }
        .total-row { font-weight: bold; background: #f5f5f5; }
        .footer { margin-top: 32px; text-align: center; font-size: 11px; color: #777; border-top: 1px solid #ccc; padding-top: 12px; }
        @media print {
            .toolbar { display: none !important; }
        }
        @media screen {
            body { background: #f0f2f5; }
            .sheet { background: #fff; max-width: 900px; margin: 0 auto 24px; padding: 40px; border-radius: 8px; box-shadow: 0 2px 12px rgba(0,0,0,.15); }
            .toolbar { display: flex; justify-content: flex-end; margin-bottom: 16px; }
            .toolbar button { background: #2563eb; color: #fff; border: 0; padding: 10px 18px; border-radius: 6px; font-size: 14px; cursor: pointer; }
        }
        @media screen and (max-width: 600px) {
            body { padding: 10px; }
            .sheet { padding: 20px; margin: 0; overflow-x: auto; }
            .summary { flex-direction: column; gap: 8px; }
            .meta { flex-direction: column; gap: 4px; }
            table { font-size: 10px; }
            th, td { padding: 4px 6px; }
            .header h1 { font-size: 18px; }
            .toolbar { justify-content: center; }
        }
    </style>
</head>
<body>
    <div class="toolbar">
        <button id="btn-print">🖨️ Cetak / Simpan PDF</button>
    </div>
    <div class="sheet">
        <div class="header">
            <h1>📋 LAPORAN RIWAYAT PESANAN</h1>
            <p>Warung Digital — {{ $periodLabels[$period] }}</p>
        </div>

        <div class="meta">
            <span>Periode: {{ $start->format('d/m/Y H:i') }} — {{ $end->format('d/m/Y H:i') }}</span>
            <span>Dicetak: {{ now()->format('d/m/Y H:i') }}</span>
        </div>

        @php
            $completed = $orders->where('status', 'selesai');
            $revenue = 0;
            foreach ($completed as $ord) {
                foreach ($ord->orderItems as $item) {
                    if ($item->product && $item->status_item !== 'dibatalkan') {
                        $revenue += $item->product->harga * $item->qty;
                    }
                }
            }
        @endphp

        <div class="summary">
            <strong>Total Pesanan: {{ $orders->count() }}</strong>
            <strong>Pesanan Selesai: {{ $completed->count() }}</strong>
            <strong>Total Pendapatan: Rp {{ number_format($revenue, 0, ',', '.') }}</strong>
        </div>

        @if($orders->isEmpty())
            <p style="text-align:center; padding: 40px; color: #777;">Belum ada pesanan pada periode ini.</p>
        @else
            <table>
                <thead>
                    <tr>
                        <th>ID / Waktu</th>
                        <th>Meja & Pemesan</th>
                        <th>Detail Item</th>
                        <th class="right">Total Harga</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        @php
                            $total = 0;
                            foreach ($order->orderItems as $item) {
                                if ($item->product && $item->status_item !== 'dibatalkan') {
                                    $total += $item->product->harga * $item->qty;
                                }
                            }
                        @endphp
                        <tr>
                            <td>#{{ $order->id }}<br>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            <td>Meja {{ $order->table->nomor_meja ?? '-' }}<br>{{ $order->customer_name }}</td>
                            <td>
                                @foreach($order->orderItems as $oi)
                                    {{ $oi->product->nama ?? 'Menu' }} x{{ $oi->qty }} ({{ $oi->status_item }})<br>
                                @endforeach
                            </td>
                            <td class="right">Rp {{ number_format($total, 0, ',', '.') }}</td>
                            <td>
                                {{ ucfirst($order->status) }}<br>
                                {{ str_replace('_', ' ', $order->status_pembayaran) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        <div class="footer">
            <p>Laporan ini dibuat otomatis oleh Sistem Warung Digital Management System.</p>
        </div>
    </div>
</body>
</html>