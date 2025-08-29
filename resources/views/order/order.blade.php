<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Order</title>
</head>
<body>
    <?php
        $currencySymbol = $order->vendorTransaction?->currency?->currency_symbol;
        $totalAmount = 0;
        $totalDiscount = 0;
        foreach ($order->orderItems as $key => $item) {
            $totalAmount = $totalAmount + $item->sub_total;
            $totalDiscount = $totalDiscount + $item->discount_price;
        }
    ?>
    <h3>{{ __('order_details') }}</h3>
    <h4>{{ __('order_summary') }}</h4>
    <div>
        <p>{{ __('order_code') }} : {{ $order->order_code }}</p>
        <p>{{ __('order_status') }} : {{ $order->orderStatus?->name }}</p>
        <p>{{ __('order_date') }} : {{ $order->order_date }}</p>
        <p>{{ __('order_name') }} : {{ $order->shippingAndBilling->shipping_first_name}} {{ $order->shippingAndBilling->shipping_last_name }}</p>
        <p>{{ __('order_email') }} : {{ $order->shippingAndBilling->shipping_email }}</p>
        <p>{{ __('shipping_address') }} : {{ $order->shippingAndBilling->shipping_first_name}} {{ $order->shippingAndBilling->shipping_last_name }} ,
            {{ $order->shippingAndBilling->shipping_phone_no }},
            {{ $order->shippingAndBilling->shipping_email }},
            {{ $order->shippingAndBilling->shipping_address }},
            {{ $order->shippingAndBilling->shipping_country }},
            {{ $order->shippingAndBilling->shipping_state }},
            {{ $order->shippingAndBilling->shipping_city }}
        </p>
        <p>{{ __('payment_method') }} : {{ $order->vendorTransaction->payment_method }}</p>
        <p>{{ __('order_payment_status') }} : {{ $order->vendorTransaction->vendorPaymentStatus->name }}</p>
        {{-- <p>{{ __('total_order_amount') }} : {{ $order->vendorTransaction->currency->currency_symbol }}{{ $order->vendorTransaction->payment_amount }}</p> --}}
        <p>{{ __('billing_address') }} : {{ $order->shippingAndBilling->billing_first_name}} {{ $order->shippingAndBilling->billing_last_name }} ,
            {{ $order->shippingAndBilling->billing_phone_no }},
            {{ $order->shippingAndBilling->billing_email }},
            {{ $order->shippingAndBilling->billing_address }},
            {{ $order->shippingAndBilling->billing_country }},
            {{ $order->shippingAndBilling->billing_state }},
            {{ $order->shippingAndBilling->billing_city }}
        </p>
    </div>
    <h4>{{ $order->vendor->name }}</h4>
    <div>
        <h5>{{ __('product_info') }}</h5>
        @foreach ($order->orderItems as $item)
            <ul>
                <li>{{ __('order_product_name') }}: {{ $item->item->title }}</li>
                <li>{{ __('order_product_quantity')}}: {{ $item->quantity }}</li>
                <li>{{ __('order_product_price')}}: {{ $currencySymbol }}{{ $item->sale_price }}</li>
                <li>{{ __('order_product_discount')}}: {{ $currencySymbol }}{{ $item->discount_price }}</li>
                <li>{{ __('order_product_sub_total')}}: {{ $currencySymbol }}{{ $item->sub_total }}</li>
            </ul>
        @endforeach
        <hr>
        <p>{{ __('order_product_sub_total')}} : {{ $currencySymbol }}{{ $totalAmount }}</p>
        <p>{{ __('total_discount') }} : {{ $currencySymbol }}{{ $totalDiscount }}</p>
        <p>{{ __('delivery_charges') }} : {{ $currencySymbol }}{{ $order->delivery_fee }}</p>
        <hr>
        <p>{{ __('total_price') }} : {{ $currencySymbol }}{{ $order->total_price }}</p>

    </div>
</body>
</html>
