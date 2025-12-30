<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daily Sales Report</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .header p {
            margin: 10px 0 0;
            opacity: 0.9;
        }
        .content {
            background: #f9fafb;
            padding: 30px;
            border: 1px solid #e5e7eb;
            border-top: none;
            border-radius: 0 0 8px 8px;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin-bottom: 30px;
        }
        .stat-box {
            background: white;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .stat-box .value {
            font-size: 28px;
            font-weight: bold;
            color: #111827;
        }
        .stat-box .label {
            color: #6b7280;
            font-size: 14px;
            margin-top: 5px;
        }
        .stat-box.revenue .value {
            color: #059669;
        }
        .products-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .products-table th {
            background: #f3f4f6;
            padding: 12px;
            text-align: left;
            font-weight: 600;
            color: #374151;
        }
        .products-table td {
            padding: 12px;
            border-top: 1px solid #e5e7eb;
        }
        .products-table tr:hover {
            background: #f9fafb;
        }
        .no-sales {
            text-align: center;
            color: #6b7280;
            padding: 40px;
            background: white;
            border-radius: 8px;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            color: #6b7280;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Daily Sales Report</h1>
        <p>{{ \Carbon\Carbon::parse($salesData['date'])->format('F j, Y') }}</p>
    </div>

    <div class="content">
        <div class="stats-grid">
            <div class="stat-box revenue">
                <div class="value">${{ number_format($salesData['total_revenue'], 2) }}</div>
                <div class="label">Total Revenue</div>
            </div>
            <div class="stat-box">
                <div class="value">{{ $salesData['total_orders'] }}</div>
                <div class="label">Orders</div>
            </div>
            <div class="stat-box">
                <div class="value">{{ $salesData['total_items_sold'] }}</div>
                <div class="label">Items Sold</div>
            </div>
            <div class="stat-box">
                <div class="value">
                    ${{ $salesData['total_orders'] > 0 ? number_format($salesData['total_revenue'] / $salesData['total_orders'], 2) : '0.00' }}
                </div>
                <div class="label">Avg. Order Value</div>
            </div>
        </div>

        @if(count($salesData['products_sold']) > 0)
            <h3 style="margin-bottom: 10px; color: #111827;">Products Sold</h3>
            <table class="products-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Qty</th>
                        <th>Revenue</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($salesData['products_sold'] as $product)
                        <tr>
                            <td>{{ $product['name'] }}</td>
                            <td>{{ $product['quantity'] }}</td>
                            <td>${{ number_format($product['revenue'], 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="no-sales">
                <p>No sales recorded today.</p>
            </div>
        @endif
    </div>

    <div class="footer">
        <p>This is an automated report from {{ config('app.name') }}.</p>
        <p>Generated at {{ now()->format('g:i A') }}</p>
    </div>
</body>
</html>
