<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Order Invoice</title>

    <style>
        body {
            font-family: 'Helvetica', sans-serif;
            line-height: 1.6;
            color: #212529;
        }

        section {
            margin-top: 50px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
        }

        .btn-primary {
            display: inline-block;
            font-weight: 400;
            color: #fff;
            text-align: center;
            vertical-align: middle;
            user-select: none;
            background-color: #007bff;
            border: 1px solid #007bff;
            padding: 10px 20px;
            font-size: 16px;
            line-height: 1.5;
            border-radius: 5px;
            transition: background-color 0.3s ease;
            text-decoration: none;
        }

        h1 {
            font-size: 28px;
            margin-bottom: 20px;
        }

        .invoice-details {
            margin-top: 20px;
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            display: flex;
            justify-content: space-between;
        }

        .invoice-details h6 {
            font-size: 14px;
            color: #6c757d;
            margin: 5px 0;
        }

        .shipping-address {
            max-width: 60%;
        }

        .order-items {
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            padding: 15px;
            border: 1px solid #dee2e6;
            text-align: left;
        }

        th {
            background-color: #007bff;
            color: #fff;
        }

        .order-total {
            margin-top: 20px;
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
        }

        .order-total h3 {
            margin-bottom: 15px;
        }

        ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        ul li {
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <section>
        <div class="container">

            <div>
                <h1>Invoice for Order #{{ $order->id }}</h1>

                <div class="invoice-details">
                    <div class="shipping-address">
                        <h6>Order Date:</h6>
                        <p>{{ $order->created_at->format('F j, Y') }}</p>

                        <h6>Order Amount:</h6>
                        <p>NRs {{ number_format($order->grand_total,2) }}</p>
                    </div>

                    <div class="shipping-address">
                        <h6>Shipping Address</h6>
                        <address>
                            <strong>{{ $order->first_name }} {{ $order->last_name }}</strong><br>
                            {{ $order->address }}<br>
                            {{ $order->city }}, {{ $order->zip }} {{ $order->country->name }}<br>
                            Phone: {{ $order->mobile }}<br>
                            Email: {{ $order->email }}
                        </address>
                    </div>
                </div>
            </div>

            <div class="order-items">
                <h3>Order Items ({{ $orderItemsCount }})</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Qty</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (!empty($order->orderItems))
                        @foreach ($order->orderItems as $item)
                        <tr>
                            <td>{{ $item->name }}</td>
                            <td>NRs {{ number_format($item->price,2) }}</td>
                            <td>{{ $item->qty }}</td>
                            <td>NRs {{ number_format($item->total,2) }}</td>
                        </tr>
                        @endforeach
                        @endif
                        <tr>
                            <td colspan="3"><strong>SubTotal</strong></td>
                            <td> NRs {{ number_format($order->subtotal,2) }}</td>
                        </tr> <tr>
                            <td colspan="3"><strong>Discount {{ (!empty($order->coupon_code)) ? '(' . $order->coupon_code . ')' : '' }}:</strong></td>
                            <td> NRs {{ number_format($order->discount,2) }}</td>
                        </tr> <tr>
                            <td colspan="3"><strong>shipping</strong></td>
                            <td> NRs {{ number_format($order->shipping,2) }}</td>
                        </tr> <tr>
                            <td colspan="3"><strong>SubTotal</strong></td>
                            <td> NRs {{ number_format($order->grand_total,2) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </section>
</body>

</html>
