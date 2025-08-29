<?php

use Illuminate\Support\Facades\Route;
use Modules\ItemPromotion\Http\Controllers\Backend\Controllers\PaidItemController;
use Modules\StoreFront\VendorPanel\Http\Controllers\Backend\Controllers\Order\OrderController;
use Modules\StoreFront\VendorPanel\Http\Controllers\Backend\Controllers\Item\VendorItemController;
use Modules\StoreFront\VendorPanel\Http\Controllers\Backend\Controllers\Vendor\MyVendorController;
use Modules\StoreFront\VendorPanel\Http\Controllers\Backend\Controllers\Payment\VendorPaymentController;
use Modules\StoreFront\VendorPanel\Http\Controllers\Backend\Controllers\Currency\VendorCurrencyController;
use Modules\StoreFront\VendorPanel\Http\Controllers\Backend\Controllers\Order\OrderTransactionReportController;
use Modules\StoreFront\VendorPanel\Http\Controllers\Backend\Controllers\Payment\VendorPaymentCoreKeyController;
use Modules\StoreFront\VendorPanel\Http\Controllers\Backend\Controllers\OrderStatus\VendorOrderStatusController;
use Modules\StoreFront\VendorPanel\Http\Controllers\Backend\Controllers\Subscription\SubscriptionHistoryController;
use Modules\StoreFront\VendorPanel\Http\Controllers\Backend\Controllers\Subscription\UpgradeSubscriptionController;
use Modules\StoreFront\VendorPanel\Http\Controllers\Backend\Controllers\PaymentStatus\VendorPaymentStatusController;
use Modules\StoreFront\VendorPanel\Http\Controllers\Backend\Controllers\PaymentSetting\VendorPaymentSettingController;
use Modules\StoreFront\VendorPanel\Http\Controllers\Backend\Controllers\DeliverySetting\VendorDeliverySettingController;

Route::prefix('vendor-panel/')->middleware(["vendorAccess"])->group(function () {

    Route::prefix('vendor_item')->group(function () {
        Route::controller(VendorItemController::class)->group(function () {
            Route::put('/duplicate/{item}', 'duplicateRow')->name('vendor_item.duplicate');
            Route::put('/deeplink/{item}', 'deeplink')->name('vendor_item.deeplink');
            Route::put('/', 'search')->name('vendor_item.search');
            Route::put('/screen-display-ui-setting', "screenDisplayUiStore")->name("vendor_item.screenDisplayUiSetting.store");
            // Route::post('/custom-field/image-replace/{id}', "customFieldImageReplace")->name('customField.imageReplace');
            Route::put('/status/{item}', 'statusChange')->name('vendor_item.statusChange');
            // Route::post('/upload-multi', 'uploadMulti');
            Route::post('/remove-multi', 'removeMulti')->name('vendor_item.removeMulti');

        });
    });

    Route::resource('/vendor_item', VendorItemController::class);

    Route::resource('/vendor_info', MyVendorController::class);

    Route::resource('/upgrade_subscription', UpgradeSubscriptionController::class);

    Route::get("/vendor_subscription_history",[SubscriptionHistoryController::class,"index"])->name("vendor_subscription_history.index");

    // For vendor payment list
    Route::prefix('vendor_payment')->controller(VendorPaymentController::class)->group(function () {
        Route::put('/status/{payment}', 'statusChange')->name('vendor_payment.statusChange');
    });
    Route::resource('vendor_payment', VendorPaymentController::class);

    // For Vendor Payment Core Key
    Route::prefix('vendor_payment/{payment}/core_key')->controller(VendorPaymentCoreKeyController::class)->group(function () {
        Route::get('/{core_key}/edit', 'edit')->name('vendor_payment_core_key.edit');
        Route::put('/{core_key}', 'update')->name('vendor_payment_core_key.update');
    });

    // For Vendor Payment Setting
    Route::prefix('vendor_payment_setting')->controller(VendorPaymentSettingController::class)->group(function () {
        Route::get('/', 'index')->name('vendor_payment_setting.index');
        Route::post('/{vendor_payment_setting}', 'store')->name('vendor_payment_setting.store');
    });

    // For Payment Status
    Route::resource('vendor_payment_status', VendorPaymentStatusController::class)
        ->except(['edit', 'update', 'destroy']);  // Exclude routes that you'll define in the group
    Route::prefix('vendor_payment_status/{vendor_id}/status')->controller(VendorPaymentStatusController::class)->group(function() {
        Route::get('/{id}/edit', 'edit')->name('vendor_payment_status.edit');
        Route::put('/{id}', 'update')->name('vendor_payment_status.update');
        Route::delete('/{id}', 'destroy')->name('vendor_payment_status.destroy');
        Route::put('/{id}/status_change', 'statusChange')->name('vendor_payment_status.statusChange');
    });

    // For Order Status
    Route::resource('vendor_order_status', VendorOrderStatusController::class)
        ->except(['edit', 'update', 'destroy']);  // Exclude routes that you'll define in the group
    Route::prefix('vendor_order_status/{vendor_id}/status')->controller(VendorOrderStatusController::class)->group(function() {
        Route::get('/{id}/edit', 'edit')->name('vendor_order_status.edit');
        Route::put('/{id}', 'update')->name('vendor_order_status.update');
        Route::delete('/{id}', 'destroy')->name('vendor_order_status.destroy');
        Route::put('/{id}/status_change', 'statusChange')->name('vendor_order_status.statusChange');
    });

    // Order List
    Route::resource('vendor_order_list', OrderController::class);
    // For Order Transaction Report
    Route::prefix('report')->controller(OrderTransactionReportController::class)->group(function () {
        Route::get('/order_transaction/csv/export', 'OrderTransactionReportCsvExport')->name('order_transaction_report.csv.export');
    });
    Route::resource('report/order_transaction', OrderTransactionReportController::class)->names("order_transaction_report");
    Route::resource('report/order_transaction/{id}/edit', OrderTransactionReportController::class)->names("order_transaction_report.edit");


    Route::resource('vendor_currency', VendorCurrencyController::class);
    Route::controller(VendorCurrencyController::class)->group(function () {
        Route::put('/vendor_currency/default/{currency}', 'defaultChange')->name('vendor_currency.defaultChange');
    });

    Route::resource('vendor_delivery_setting', VendorDeliverySettingController::class);

});

