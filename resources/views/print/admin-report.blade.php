<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Warung Digital - Laporan Keuangan - {{ $periodLabels[$period] }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            font-size: 12px;
            line-height: 1.4;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid #333;
        }
        
        .header h1 {
            font-size: 24px;
            margin-bottom: 5px;
            color: #1e40af;
        }
        
        .header h2 {
            font-size: 18px;
            color: #666;
            margin-bottom: 10px;
        }
        
        .header p {
            color: #888;
            font-size: 11px;
        }
        
        .info-box {
            background: #f3f4f6;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
        }
        
        .info-label {
            font-weight: bold;
            color: #374151;
        }
        
        .info-value {
            color: #6b7280;
        }
        
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-bottom: 30px;
        }
        
        .summary-card {
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
        }
        
        .summary-card h3 {
            font-size: 11px;
            color: #6b7280;
            margin-bottom: 8px;
            text-transform: uppercase;
        }
        
        .summary-card .value {
            font-size: 20px;
            font-weight: bold;
            color: #1f2937;
        }
        
        .section {
            margin-bottom: 30px;
        }
        
        .section-title {
            font-size: 16px;
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 2px solid #e5e7eb;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        thead {
            background: #f9fafb;
        }
        
        th {
            padding: 10px;
            text-align: left;
            font-size: 10px;
            text-transform: uppercase;
            color: #6b7280;
            border-bottom: 2px solid #e5e7eb;
        }
        
        td {
            padding: 10px;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: 600;
        }
        
        .badge-makanan {
            background: #d1fae5;
            color: #065f46;
        }
        
        .badge-minuman {
            background: #dbeafe;
            color: #1e40af;
        }
        
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #e5e7eb;
            text-align: center;
            color: #6b7280;
            font-size: 10px;
        }
        
        @media print {
            body {
                padding: 10px;
            }
            
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>📊 LAPORAN KEUANGAN</h1>
        <h2>Warung Digital</h2>
        <p>Periode: {{ $periodLabels[$period] }} ({{ $startDate->format('d M Y') }} - {{ $endDate->format('d M Y') }})</p>
        <p>Dicetak: {{ now()->format('d F Y, H:i') }} WIB</p>
    </div>

    <!-- Info Box -->
    <div class="info-box">
        <div class="info-row">
            <span class="info-label">Periode Laporan:</span>
            <span class="info-value">{{ $periodLabels[$period] }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Tanggal:</span>
            <span class="info-value">{{ $startDate->format('d M Y') }} - {{ $endDate->format('d M Y') }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Tanggal Cetak:</span>
            <span class="info-value">{{ now()->format('d F Y, H:i') }} WIB</span>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="summary-grid">
        <div class="summary-card">
            <h3>Total Pendapatan</h3>
            <div class="value">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
        </div>
        
        <div class="summary-card">
            <h3>Pendapatan Reservasi</h3>
            <div class="value">Rp {{ number_format($reservationRevenue, 0, ',', '.') }}</div>
        </div>
        
        <div class="summary-card">
            <h3>Total Pesanan</h3>
            <div class="value">{{ $totalOrders }}</div>
        </div>
        
        <div class="summary-card">
            <h3>Rata-rata per Pesanan</h3>
            <div class="value">Rp {{ number_format($totalOrders > 0 ? $totalRevenue / $totalOrders : 0, 0, ',', '.') }}</div>
        </div>
    </div>

    <!-- Top Products -->
    <div class="section">
        <h2 class="section-title">Top 10 Produk Terlaris</h2>
        <table>
            <thead>
                <tr>
                    <th class="text-center" style="width: 60px;">Rank</th>
                    <th>Nama Produk</th>
                    <th class="text-center" style="width: 100px;">Kategori</th>
                    <th class="text-right" style="width: 120px;">Jumlah Terjual</th>
                </tr>
            </thead>
            <tbody>
                @forelse($topProducts as $index => $item)
                    @php
                        $product = $item->product;
                    @endphp
                    @if($product)
                    <tr>
                        <td class="text-center" style="font-weight: bold;">{{ $index + 1 }}</td>
                        <td style="font-weight: 600;">{{ $product->nama }}</td>
                        <td class="text-center">
                            <span class="badge badge-{{ strtolower($product->kategori) }}">
                                {{ ucfirst($product->kategori) }}
                            </span>
                        </td>
                        <td class="text-right">{{ number_format($item->total_quantity) }} pcs</td>
                    </tr>
                    @endif
                @empty
                <tr>
                    <td colspan="4" class="text-center" style="padding: 30px; color: #9ca3af;">
                        Belum ada data produk untuk periode ini
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Order List -->
    <div class="section">
        <h2 class="section-title">Daftar Pesanan</h2>
        <table>
            <thead>
                <tr>
                    <th style="width: 80px;">ID Order</th>
                    <th style="width: 120px;">Tanggal</th>
                    <th>Meja</th>
                    <th class="text-right" style="width: 120px;">Total</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                <tr>
                    <td>#{{ $order->id }}</td>
                    <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ $order->table->nama_meja ?? '-' }}</td>
                    <td class="text-right" style="font-weight: 600;">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center" style="padding: 30px; color: #9ca3af;">
                        Belum ada pesanan untuk periode ini
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p><strong>Warung Digital</strong> - Sistem Manajemen Warung & Reservasi</p>
        <p>Laporan ini digenerate secara otomatis oleh sistem</p>
        <p style="margin-top: 5px;">© {{ date('Y') }} Warung Digital. All rights reserved.</p>
    </div>

    <script>
        // Auto print when page loads
        window.onload = function() {
            window.print();
        };
    </script>
</body>
</html>