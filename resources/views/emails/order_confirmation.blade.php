<!-- resources/views/emails/order_confirmation.blade.php -->

<!DOCTYPE html>
<html>
<head>
    <title>Order Confirmation</title>
</head>
<body>
    <h1>Your Order Confirmation</h1>
    <h3>Thank you for your order, {{ $order->user->first_name }}!</h3>
    <p>Your order ID is: {{ $order->id }}</p>
    <p>Order Items:</p>
    <ul>
     <?php $total = 0;?>
     @foreach($order->items as $item)
       <?php $total += $item->pivot->quantity * $item->pivot->price;?>
       <li><b>{{ (app()->getLocale() === 'ar') ? ($item->name_ar ?? $item->name_en) : $item->name_en }}</b> : {{ $item->pivot->quantity }} x {{ $item->pivot->price }} = {{ $item->pivot->quantity * $item->pivot->price }} EGP</li>
     @endforeach
    </ul>
    <br>
    <h5><b>TOTAL: </b> <i>{{ $total }}</i></h5>
    <hr>
    
    <h3>Order Details:</h3>
    <p><b>Order Date: </b> {{ $order->order_date }}</p>
    <p><b>Shipping Address: </b> {{ $order->shipping_address }}</p>
</body>
</html>
