<?php

namespace Modules\Core\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class TransactionCountsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        DB::table('psx_transaction_counts')->insert([
            'transaction_header_id' => '1',
            'item_id' => '1',
            'category_id' => '1',
            'subcategory_id' => '1',
            'user_id' => '1',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        DB::table('psx_transaction_counts')->insert([
            'transaction_header_id' => '1',
            'item_id' => '2',
            'category_id' => '1',
            'subcategory_id' => '1',
            'user_id' => '1',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        // For transaction header 2
        DB::table('psx_transaction_counts')->insert([
            'transaction_header_id' => '2',
            'item_id' => '2',
            'category_id' => '1',
            'subcategory_id' => '1',
            'user_id' => '1',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);
        DB::table('psx_transaction_counts')->insert([
            'transaction_header_id' => '2',
            'item_id' => '2',
            'category_id' => '1',
            'subcategory_id' => '1',
            'user_id' => '1',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);
        DB::table('psx_transaction_counts')->insert([
            'transaction_header_id' => '2',
            'item_id' => '2',
            'category_id' => '1',
            'subcategory_id' => '1',
            'user_id' => '1',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);
        DB::table('psx_transaction_counts')->insert([
            'transaction_header_id' => '2',
            'item_id' => '2',
            'category_id' => '1',
            'subcategory_id' => '1',
            'user_id' => '1',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        // For transaction header 3
        DB::table('psx_transaction_counts')->insert([
            'transaction_header_id' => '3',
            'item_id' => '2',
            'category_id' => '1',
            'subcategory_id' => '1',
            'user_id' => '1',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        // For transaction header 4
        DB::table('psx_transaction_counts')->insert([
            'transaction_header_id' => '4',
            'item_id' => '2',
            'category_id' => '1',
            'subcategory_id' => '1',
            'user_id' => '1',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        // For transaction header 5
        DB::table('psx_transaction_counts')->insert([
            'transaction_header_id' => '5',
            'item_id' => '2',
            'category_id' => '1',
            'subcategory_id' => '1',
            'user_id' => '1',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        DB::table('psx_transaction_counts')->insert([
            'transaction_header_id' => '5',
            'item_id' => '2',
            'category_id' => '1',
            'subcategory_id' => '1',
            'user_id' => '1',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        DB::table('psx_transaction_counts')->insert([
            'transaction_header_id' => '5',
            'item_id' => '2',
            'category_id' => '1',
            'subcategory_id' => '1',
            'user_id' => '1',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        DB::table('psx_transaction_counts')->insert([
            'transaction_header_id' => '5',
            'item_id' => '2',
            'category_id' => '1',
            'subcategory_id' => '1',
            'user_id' => '1',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        DB::table('psx_transaction_counts')->insert([
            'transaction_header_id' => '5',
            'item_id' => '2',
            'category_id' => '1',
            'subcategory_id' => '1',
            'user_id' => '1',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);


    }
}
