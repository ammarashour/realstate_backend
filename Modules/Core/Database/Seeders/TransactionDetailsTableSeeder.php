<?php

namespace Modules\Core\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class TransactionDetailsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        // For transaction header 1
        DB::table('psx_transaction_details')->insert([
            'transaction_header_id' => '1',
            "shop_id" => "1",
			"item_id" => "1",
			"item_name" => "Spicy Noodle Salad JV",
			"price" => "30",
			"original_price" => "30",
			"discount_amount" => "0",
			"discount_value" => "0",
			"discount_percent" => "0",
			"qty" => "3",
			"item_unit" => "",
            "item_measurement" => "",
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        DB::table('psx_transaction_details')->insert([
            'transaction_header_id' => '1',
            "shop_id" => "1",
			"item_id" => "2",
			"item_name" => "Apple",
			"price" => "10",
			"original_price" => "10",
			"discount_amount" => "0",
			"discount_value" => "0",
			"discount_percent" => "0",
			"qty" => "6",
			"item_unit" => "",
            "item_measurement" => "",
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        // For transaction header 2
        DB::table('psx_transaction_details')->insert([
            'transaction_header_id' => '2',
            "shop_id" => "1",
			"item_id" => "1",
			"item_name" => "Spicy Noodle Salad JV",
			"price" => "30",
			"original_price" => "30",
			"discount_amount" => "0",
			"discount_value" => "0",
			"discount_percent" => "0",
			"qty" => "3",
			"item_unit" => "",
            "item_measurement" => "",
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        DB::table('psx_transaction_details')->insert([
            'transaction_header_id' => '2',
            "shop_id" => "1",
			"item_id" => "2",
			"item_name" => "Apple",
			"price" => "10",
			"original_price" => "10",
			"discount_amount" => "0",
			"discount_value" => "0",
			"discount_percent" => "0",
			"qty" => "6",
			"item_unit" => "",
            "item_measurement" => "",
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        DB::table('psx_transaction_details')->insert([
            'transaction_header_id' => '2',
            "shop_id" => "1",
			"item_id" => "1",
			"item_name" => "Spicy Noodle Salad JV",
			"price" => "30",
			"original_price" => "30",
			"discount_amount" => "0",
			"discount_value" => "0",
			"discount_percent" => "0",
			"qty" => "3",
			"item_unit" => "",
            "item_measurement" => "",
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        DB::table('psx_transaction_details')->insert([
            'transaction_header_id' => '2',
            "shop_id" => "1",
			"item_id" => "2",
			"item_name" => "Apple",
			"price" => "10",
			"original_price" => "10",
			"discount_amount" => "0",
			"discount_value" => "0",
			"discount_percent" => "0",
			"qty" => "6",
			"item_unit" => "",
            "item_measurement" => "",
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        // For transaction header 3
        DB::table('psx_transaction_details')->insert([
            'transaction_header_id' => '3',
            "shop_id" => "1",
			"item_id" => "1",
			"item_name" => "Spicy Noodle Salad JV",
			"price" => "30",
			"original_price" => "30",
			"discount_amount" => "0",
			"discount_value" => "0",
			"discount_percent" => "0",
			"qty" => "3",
			"item_unit" => "",
            "item_measurement" => "",
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        // For transaction header 4
        DB::table('psx_transaction_details')->insert([
            'transaction_header_id' => '4',
            "shop_id" => "1",
			"item_id" => "1",
			"item_name" => "Spicy Noodle Salad JV",
			"price" => "30",
			"original_price" => "30",
			"discount_amount" => "0",
			"discount_value" => "0",
			"discount_percent" => "0",
			"qty" => "3",
			"item_unit" => "",
            "item_measurement" => "",
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        // For transaction header 5
        DB::table('psx_transaction_details')->insert([
            'transaction_header_id' => '5',
            "shop_id" => "1",
			"item_id" => "1",
			"item_name" => "Spicy Noodle Salad JV",
			"price" => "30",
			"original_price" => "30",
			"discount_amount" => "0",
			"discount_value" => "0",
			"discount_percent" => "0",
			"qty" => "3",
			"item_unit" => "",
            "item_measurement" => "",
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        DB::table('psx_transaction_details')->insert([
            'transaction_header_id' => '5',
            "shop_id" => "1",
			"item_id" => "1",
			"item_name" => "Spicy Noodle Salad JV",
			"price" => "30",
			"original_price" => "30",
			"discount_amount" => "0",
			"discount_value" => "0",
			"discount_percent" => "0",
			"qty" => "3",
			"item_unit" => "",
            "item_measurement" => "",
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        DB::table('psx_transaction_details')->insert([
            'transaction_header_id' => '5',
            "shop_id" => "1",
			"item_id" => "1",
			"item_name" => "Spicy Noodle Salad JV",
			"price" => "30",
			"original_price" => "30",
			"discount_amount" => "0",
			"discount_value" => "0",
			"discount_percent" => "0",
			"qty" => "3",
			"item_unit" => "",
            "item_measurement" => "",
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        DB::table('psx_transaction_details')->insert([
            'transaction_header_id' => '5',
            "shop_id" => "1",
			"item_id" => "1",
			"item_name" => "Spicy Noodle Salad JV",
			"price" => "30",
			"original_price" => "30",
			"discount_amount" => "0",
			"discount_value" => "0",
			"discount_percent" => "0",
			"qty" => "3",
			"item_unit" => "",
            "item_measurement" => "",
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        DB::table('psx_transaction_details')->insert([
            'transaction_header_id' => '5',
            "shop_id" => "1",
			"item_id" => "1",
			"item_name" => "Spicy Noodle Salad JV",
			"price" => "30",
			"original_price" => "30",
			"discount_amount" => "0",
			"discount_value" => "0",
			"discount_percent" => "0",
			"qty" => "3",
			"item_unit" => "",
            "item_measurement" => "",
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);
    }
}
