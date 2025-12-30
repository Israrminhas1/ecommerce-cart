<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Low Stock Alert</title>
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
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            background: #f9fafb;
            padding: 30px;
            border: 1px solid #e5e7eb;
            border-top: none;
            border-radius: 0 0 8px 8px;
        }
        .alert-box {
            background: #fef3c7;
            border: 1px solid #f59e0b;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .product-info {
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .product-info h3 {
            margin-top: 0;
            color: #111827;
        }
        .stock-level {
            font-size: 32px;
            font-weight: bold;
            color: #dc2626;
        }
        .threshold {
            color: #6b7280;
            font-size: 14px;
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
        <h1>Low Stock Alert</h1>
    </div>

    <div class="content">
        <div class="alert-box">
            <strong>Attention Required!</strong>
            <p>The following product is running low on stock and may need to be restocked soon.</p>
        </div>

        <div class="product-info">
            <h3>{{ $product->name }}</h3>
            <p><strong>Category:</strong> {{ $product->category->name ?? 'N/A' }}</p>
            <p><strong>Price:</strong> ${{ number_format($product->price, 2) }}</p>
            <p>
                <strong>Current Stock:</strong>
                <span class="stock-level">{{ $product->stock_quantity }}</span>
                <span class="threshold">(Threshold: {{ $product->low_stock_threshold }})</span>
            </p>
        </div>

        <p style="margin-top: 20px;">
            Please take action to replenish the stock to avoid any customer dissatisfaction.
        </p>
    </div>

    <div class="footer">
        <p>This is an automated notification from {{ config('app.name') }}.</p>
        <p>{{ now()->format('F j, Y \a\t g:i A') }}</p>
    </div>
</body>
</html>
