<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Success</title>
</head>
<body>
<h1>Order Successful!</h1>
<p>Dear {{ $order->name }},</p>

<p>Thank you for your purchase with us. Below are the details of your order:</p>

<h2>Order Details:</h2>
<p><strong>Order ID:</strong> {{ $order->uuid }}</p>
<p><strong>Date:</strong> {{ $order->created_at }}</p>

<h2>Ordered Products:</h2>
<ul>
    @foreach($order->orderItems as $orderItem)
        <li>{{ $orderItem->product->name }} - {{ $orderItem->quantity }} pcs - {{ $orderItem->price }} Ft</li>
    @endforeach
</ul>

<p><strong>Total:</strong> {{ $totalPrice }} Ft</p>

<p>Thank you,</p>
<p><strong>{{ config('app.name') }}</strong></p>
</body>
</html>
