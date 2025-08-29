<!DOCTYPE html>
<html>
<head>
</head>
<body>

    Hi {{ $mail['to_name'] }},</p>

    <p>
        {{ __('order_status_updated') }}
    </p>
    @php
        $order = $data
    @endphp
    <ul>
        <li>{{ __('order_code') }} : {{ $data->order_code }}</li>
        <li>{{ __('order_date') }} : {{ $data->order_date }}</li>
        <li>{{ __('order_status') }} : <b>{{ $data->orderStatus->name }}</b></li>
        <li>{{ __('payment_status') }} : <b>{{ $data->vendorTransaction->vendorPaymentStatus->name }}</b></li>
    </ul>

    <p>
        {{ __('order_status_change_note',['attribute'=>$order->vendor->name]) }}
    </p>

    <p>
        {{ __('best_regrads') }}
    </p>
    <p>
        {{ $data->vendor->name }}
    </p>
</body>
</html>
