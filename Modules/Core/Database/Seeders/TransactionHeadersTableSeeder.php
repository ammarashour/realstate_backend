<?php

namespace Modules\Core\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class TransactionHeadersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        DB::table('psx_transaction_headers')->insert([
            "user_id" => "1",
            "shop_id"  => "1",
            "sub_total_amount" => "150",
            "discount_amount" => ".00",
            "coupon_discount_amount" => "0.0",
            "tax_amount" => ".00",
	        "tax_percent" => "0",
            "shipping_amount" => "10",
            "shipping_tax_percent" => "0",
            "balance_amount" => "150",
            "total_item_amount" => "150",
            "total_item_count" => "9",
            "contact_name" => "User 1",
            "contact_phone" => "09256253314",
            "contact_email" => "user1@gmail.com",
            "contact_address"  => "Mandalay, Myanmar",
            "shipping_id" => "1",
            "payment_method" => "COD",
            "transaction_status_id"=> "1",
            "currency_symbol" => "$",
            "currency_short_form" => "USD",
            "trans_code"=>"",
            "memo" => "memo",
            "trans_lat" => "16.805281",
            "trans_lng" => "96.156113",
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        DB::table('psx_transaction_headers')->insert([
            "user_id" => "1",
            "shop_id"  => "1",
            "sub_total_amount" => "150",
            "discount_amount" => ".00",
            "coupon_discount_amount" => "0.0",
            "tax_amount" => ".00",
	        "tax_percent" => "0",
            "shipping_amount" => "10",
            "shipping_tax_percent" => "0",
            "balance_amount" => "150",
            "total_item_amount" => "150",
            "total_item_count" => "9",
            "contact_name" => "User 1",
            "contact_phone" => "09256253314",
            "contact_email" => "user1@gmail.com",
            "contact_address"  => "Mandalay, Myanmar",
            "shipping_id" => "1",
            "payment_method" => "COD",
            "transaction_status_id"=> "2",
            "currency_symbol" => "$",
            "currency_short_form" => "USD",
            "trans_code"=>"",
            "memo" => "memo",
            "trans_lat" => "16.805281",
            "trans_lng" => "96.156113",
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        DB::table('psx_transaction_headers')->insert([
            "user_id" => "1",
            "shop_id"  => "1",
            "sub_total_amount" => "150",
            "discount_amount" => ".00",
            "coupon_discount_amount" => "0.0",
            "tax_amount" => ".00",
	        "tax_percent" => "0",
            "shipping_amount" => "10",
            "shipping_tax_percent" => "0",
            "balance_amount" => "150",
            "total_item_amount" => "150",
            "total_item_count" => "9",
            "contact_name" => "User 1",
            "contact_phone" => "09256253314",
            "contact_email" => "user1@gmail.com",
            "contact_address"  => "Mandalay, Myanmar",
            "shipping_id" => "1",
            "payment_method" => "COD",
            "transaction_status_id"=> "3",
            "currency_symbol" => "$",
            "currency_short_form" => "USD",
            "trans_code"=>"",
            "memo" => "memo",
            "trans_lat" => "16.805281",
            "trans_lng" => "96.156113",
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        DB::table('psx_transaction_headers')->insert([
            "user_id" => "1",
            "shop_id"  => "1",
            "sub_total_amount" => "150",
            "discount_amount" => ".00",
            "coupon_discount_amount" => "0.0",
            "tax_amount" => ".00",
	        "tax_percent" => "0",
            "shipping_amount" => "10",
            "shipping_tax_percent" => "0",
            "balance_amount" => "150",
            "total_item_amount" => "150",
            "total_item_count" => "9",
            "contact_name" => "User 1",
            "contact_phone" => "09256253314",
            "contact_email" => "user1@gmail.com",
            "contact_address"  => "Mandalay, Myanmar",
            "shipping_id" => "1",
            "payment_method" => "COD",
            "transaction_status_id"=> "6",
            "currency_symbol" => "$",
            "currency_short_form" => "USD",
            "trans_code"=>"",
            "memo" => "memo",
            "trans_lat" => "16.805281",
            "trans_lng" => "96.156113",
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        DB::table('psx_transaction_headers')->insert([
            "user_id" => "1",
            "shop_id"  => "1",
            "sub_total_amount" => "150",
            "discount_amount" => ".00",
            "coupon_discount_amount" => "0.0",
            "tax_amount" => ".00",
	        "tax_percent" => "0",
            "shipping_amount" => "10",
            "shipping_tax_percent" => "0",
            "balance_amount" => "150",
            "total_item_amount" => "150",
            "total_item_count" => "9",
            "contact_name" => "User 1",
            "contact_phone" => "09256253314",
            "contact_email" => "user1@gmail.com",
            "contact_address"  => "Mandalay, Myanmar",
            "shipping_id" => "1",
            "payment_method" => "COD",
            "transaction_status_id"=> "6",
            "currency_symbol" => "$",
            "currency_short_form" => "USD",
            "trans_code"=>"",
            "memo" => "memo",
            "trans_lat" => "16.805281",
            "trans_lng" => "96.156113",
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

    }
}
