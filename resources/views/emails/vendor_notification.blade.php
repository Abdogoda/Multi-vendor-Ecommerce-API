<!-- resources/views/emails/vendor_notification.blade.php -->

<!DOCTYPE html>
<html>
<head>
    <title>New Order Received</title>
</head>
<body>
    <h1>New Order Received</h1>
    <p>Dear {{ $vendor->user->first_name }},</p>
    <p>You have received a new order.</p>
    <p>Order Details:</p>
    <ul>
        <?php $total = 0;?>
        @foreach($items as $item)
            <?php $total += $item['quantity'] * $item['price'];?>
            <li><b>{{ $item['product_id'] }}- {{ $item['product_name'] }}</b> : {{ $item['quantity'] }} x {{ $item['price'] }}</li>
        @endforeach
    </ul>
    <br>
    <h5><b>TOTAL: </b> <i>{{ $total }}</i></h5>
</body>
</html>