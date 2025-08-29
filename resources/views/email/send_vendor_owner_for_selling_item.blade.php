<!DOCTYPE html>
<html>
<head>
</head>
<body>

    Hi {{ $mail['to_name'] }},</p>

    <p>
        {{ __('new_order_is_received') }}
    </p>
    <p>
        {{ __('order_code') }} : {{ $data->order_code }}
    </p>
    <p>
        {{ __('order_status') }} : {{ $data->orderStatus->name }}
    </p>
    <p>
        {{ __('payment_method') }} : {{ $data->vendorTransaction->payment_method }}
    </p>
    <p>
        {{ __('shipping_customer_name') }} : {{ $data->shippingAndBilling->shipping_first_name." ".$data->shippingAndBilling->shipping_last_name }}
    </p>
    <p>
        {{ __('shipping_phone') }} : {{ $data->shippingAndBilling->shipping_phone_no }}
    </p>
    <p>
        {{ __('shipping_email') }} : {{ $data->shippingAndBilling->shipping_email }}
    </p>
    <p>
        {{ __('shipping_address') }} : {{ $data->shippingAndBilling->shipping_address }}
    </p>
    <p>
        {{ __('shipping_country') }} : {{ $data->shippingAndBilling->shipping_country }}
    </p>
    <p>
        {{ __('shipping_city') }} : {{ $data->shippingAndBilling->shipping_city }}
    </p>
    <p>
        {{ __('shipping_state') }} : {{ $data->shippingAndBilling->shipping_state }}
    </p>
    <p>
        {{ __('shipping_postal_code') }} : {{ $data->shippingAndBilling->shipping_postal_code }}
    </p>

    <p>
        {{ __('billing_customer_name') }} : {{ $data->shippingAndBilling->billing_first_name." ".$data->shippingAndBilling->billing_last_name }}
    </p>
    <p>
        {{ __('billing_phone') }} : {{ $data->shippingAndBilling->billing_phone_no }}
    </p>
    <p>
        {{ __('billing_email') }} : {{ $data->shippingAndBilling->billing_email }}
    </p>
    <p>
        {{ __('billing_address') }} : {{ $data->shippingAndBilling->billing_address }}
    </p>
    <p>
        {{ __('billing_country') }} : {{ $data->shippingAndBilling->billing_country }}
    </p>
    <p>
        {{ __('billing_city') }} : {{ $data->shippingAndBilling->billing_city }}
    </p>
    <p>
        {{ __('billing_state') }} : {{ $data->shippingAndBilling->billing_state }}
    </p>
    <p>
        {{ __('billing_postal_code') }} : {{ $data->shippingAndBilling->billing_postal_code }}
    </p>

    <h3>
        {{ __('product_detail_information_at_below') }} :
    </h3>

    <ol>
        @php
            $currencySymbol = $data->vendorTransaction?->currency?->currency_symbol;
            $totalDiscount = 0;
            foreach ($data->orderItems as $key => $orderItem) {
                $totalDiscount = $totalDiscount + $orderItem->discount_price;
            }
        @endphp
        @foreach ($data->orderItems as $key => $orderItem)
        <li>
            <p>
                {{ __('order_product_name') }} : {{ $orderItem->item->title }} <br>
                {{ __('order_product_price')}} : {{  $currencySymbol }}{{ $orderItem->sale_price }} <br>
                {{ __('order_product_quantity')}} : {{ $orderItem->quantity }} <br>
                {{ __('order_product_discount')}} : -{{  $currencySymbol }}{{ $orderItem->discount_price }} <br>
                {{ __('order_product_sub_total')}} : {{  $currencySymbol }}{{ $orderItem->sub_total }} <br>
            </p>
        </li>
        @endforeach
    </ol>
    <p>
        {{ __('total_discount') }} : -{{ $currencySymbol . $totalDiscount }}
    </p>
    <p>
        {{ __('delivery_charges') }} : {{ $currencySymbol . $data->delivery_fee }}
    </p>
    <p>
        {{ __('total_price') }} : {{ $currencySymbol . $data->vendorTransaction?->payment_amount }}
    </p>

    <br>
    <p>Best Regards,</p>
    <p>{{ env('APP_NAME') }}</p>


</body>
</html>
