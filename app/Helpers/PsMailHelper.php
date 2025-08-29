<?php

use Modules\Core\Emails\PsMail;
use Illuminate\Support\Facades\Mail;
use Modules\Core\Emails\PsContactMail;
use Modules\Core\Emails\SendBuyerForOrderSummary;
use Modules\Core\Emails\SendBuyerForStatusesChanges;
use Modules\Core\Emails\SendVendorOwnerForSellingItem;
use App\Http\Contracts\Configuration\BackendSettingServiceInterface;

if(!function_exists('sendMail')){
    function sendMail($to, $to_name, $subject = null, $title = null, $body = null, $subbody = null, $flag= null){

        $backendSettingService = app()->make(BackendSettingServiceInterface::class);
        $backendSetting = $backendSettingService->get();
        $from_name = $backendSetting ? $backendSetting->sender_name : '';

        $mail = [
            'from_name' => $from_name, // required from db
            'to_name' => $to_name,
            'subject' => $subject,
            'title' => $title,
            'body' => $body,
            'subbody' => $subbody,
            'salutation' => __('mail__salutation'),
            'closing' => __('mail__closing')
        ];

        try{
            Mail::to($to)->send(new PsMail($mail));
            return true;
        }catch(Throwable $e){
            return false;
        }
    }
}

if(!function_exists('sendContactMail')){
    function sendContactMail($contact_name, $contact_email, $contact_phone, $contact_msg,$contactNameStr,$contactEmailStr,$contactPhoneStr,$contactMessageStr){

        $backendSettingService = app()->make(BackendSettingServiceInterface::class);
        $backendSetting = $backendSettingService->get();
        $to_emai = $backendSetting ? $backendSetting->receive_email : '';

        $mail = [
            'from_name' => $contact_name, // required from db
            'to_name' => 'Admin',
            'subject' => Null,
            'contact_phone' => $contact_phone,
            'contact_email' => $contact_email,
            'body' => $contact_msg,
            'contactNameStr' => $contactNameStr,
            'contactEmailStr' => $contactEmailStr,
            'contactPhoneStr' => $contactPhoneStr,
            'contactMessageStr' => $contactMessageStr,
            'subbody' => Null,
            'salutation' => __('mail__salutation'),
            'closing' => __('mail__closing')
        ];

        try{
            Mail::to($to_emai)->send(new PsContactMail($mail));
            return true;
        }catch(Throwable $e){
            return false;

        }
    }
}

if(!function_exists('sendVendorOwnerForSellingItemMail')){
    function sendVendorOwnerForSellingItemMail($data){

        $mail =  [
            'to_name' => $data->vendorTransaction?->vendor->name,
            'subject' => "New Order has been received",
            'to_email' => $data->vendorTransaction?->vendor->email,
            'from_name' => $data->vendorTransaction?->vendor->name
        ];

        try{
            Mail::to($mail['to_email'])
                ->send(new SendVendorOwnerForSellingItem($data, $mail));
        }catch(Throwable $e){
            return $e->getMessage();

        }
    }
}

if(!function_exists('sendBuyerForOrderSummaryMail')){
    function sendBuyerForOrderSummaryMail($data){
        $shippingAndBillingInfo = $data->shippingAndBilling;
        $shippingEmail = $shippingAndBillingInfo->shipping_email;
        $billingEmail = $shippingAndBillingInfo->billing_email;

        if($shippingEmail !== $billingEmail){
            $mails = [
                [
                    'to_name' => $shippingAndBillingInfo->shipping_first_name." ".$shippingAndBillingInfo->shipping_last_name,
                    'subject' => "New Order has been received",
                    'to_email' => $shippingAndBillingInfo->shipping_email,
                    'from_name' => $data->vendorTransaction?->vendor->name
                ],
                [
                    'to_name' => $shippingAndBillingInfo->billing_first_name." ".$shippingAndBillingInfo->billing_last_name,
                    'subject' => "New Order has been received",
                    'to_email' => $shippingAndBillingInfo->billing_email,
                    'from_name' => $data->vendorTransaction?->vendor->name
                ]
            ];

            foreach($mails as $mail){
                try{
                    Mail::to($mail['to_email'])
                        ->send(new SendBuyerForOrderSummary($data, $mail));
                }catch(Throwable $e){
                    return $e->getMessage();

                }
            }

        } else {
            $mail =  [
                'to_name' => $shippingAndBillingInfo->shipping_first_name." ".$shippingAndBillingInfo->shipping_last_name,
                'subject' => "New Order has been received",
                'to_email' => $shippingAndBillingInfo->shipping_email,
                'from_name' => $data->vendorTransaction?->vendor->name
            ];
            try{
                Mail::to($mail['to_email'])
                    ->send(new SendBuyerForOrderSummary($data, $mail));
            }catch(Throwable $e){
                return $e->getMessage();

            }
        }

    }
}

if(!function_exists('sendBuyerForOrderStatusMail')){
    function sendBuyerForOrderStatusMail($data){
        $shippingAndBillingInfo = $data->shippingAndBilling;
        $shippingEmail = $shippingAndBillingInfo->shipping_email;
        $billingEmail = $shippingAndBillingInfo->billing_email;

        if($shippingEmail !== $billingEmail){
            $mails = [
                [
                    'to_name' => $shippingAndBillingInfo->shipping_first_name." ".$shippingAndBillingInfo->shipping_last_name,
                    'subject' => "Order statuses have been Updated",
                    'to_email' => $shippingAndBillingInfo->shipping_email,
                    'from_name' => $data->vendorTransaction?->vendor->name
                ],
                [
                    'to_name' => $shippingAndBillingInfo->billing_first_name." ".$shippingAndBillingInfo->billing_last_name,
                    'subject' => "Order statuses have been Updated",
                    'to_email' => $shippingAndBillingInfo->billing_email,
                    'from_name' => $data->vendorTransaction?->vendor->name
                ]
            ];

            foreach($mails as $mail){
                try{
                    Mail::to($mail['to_email'])
                        ->send(new SendBuyerForStatusesChanges($data, $mail));
                }catch(Throwable $e){
                    return $e->getMessage();

                }
            }

        } else {
            $mail =  [
                'to_name' => $shippingAndBillingInfo->shipping_first_name." ".$shippingAndBillingInfo->shipping_last_name,
                'subject' => "Order statuses have been Updated",
                'to_email' => $shippingAndBillingInfo->shipping_email,
                'from_name' => $data->vendorTransaction?->vendor->name
            ];
            try{
                Mail::to($mail['to_email'])
                    ->send(new SendBuyerForStatusesChanges($data, $mail));
            }catch(Throwable $e){
                return $e->getMessage();

            }
        }

    }
}

?>
