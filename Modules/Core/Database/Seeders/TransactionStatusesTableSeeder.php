<?php

namespace Modules\Core\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class TransactionStatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        DB::table('psx_transaction_statuses')->insert([
            'title' => 'Order Submitted',
            'ordering' => '1',
            'color_value' => '#F59E0B',
            'start_stage' => 1,
            'final_stage' => 0,
            'is_optional' => 0,
            'is_refundable' => 1,
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        DB::table('psx_transaction_statuses')->insert([
            'title' => 'Order Accepted',
            'ordering' => '2',
            'color_value' => '#34D399',
            'start_stage' => 0,
            'final_stage' => 0,
            'is_optional' => 0,
            'is_refundable' => 0,
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        DB::table('psx_transaction_statuses')->insert([
            'title' => 'Pick Up for Delivery',
            'ordering' => '3',
            'color_value' => '#FCD34D',
            'start_stage' => 0,
            'final_stage' => 0,
            'is_optional' => 0,
            'is_refundable' => 0,
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        DB::table('psx_transaction_statuses')->insert([
            'title' => 'Arriving Soon',
            'ordering' => '4',
            'color_value' => '#53D8FB',
            'start_stage' => 0,
            'final_stage' => 0,
            'is_optional' => 0,
            'is_refundable' => 0,
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        DB::table('psx_transaction_statuses')->insert([
            'title' => 'Rejected by Admin',
            'ordering' => '4',
            'color_value' => '#FCA5A5',
            'start_stage' => 0,
            'final_stage' => 0,
            'is_optional' => 1,
            'is_refundable' => 0,
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        DB::table('psx_transaction_statuses')->insert([
            'title' => 'Delivered',
            'ordering' => '5',
            'color_value' => '#10B981',
            'start_stage' => 0,
            'final_stage' => 1,
            'is_optional' => 0,
            'is_refundable' => 0,
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        DB::table('psx_transaction_statuses')->insert([
            'title' => 'Rejected by User',
            'ordering' => '5',
            'color_value' => '#EF4444',
            'start_stage' => 0,
            'final_stage' => 0,
            'is_optional' => 1,
            'is_refundable' => 0,
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        DB::table('psx_transaction_statuses')->insert([
            'title' => 'Refunded',
            'ordering' => '5',
            'color_value' => '#3B82F6',
            'start_stage' => 0,
            'final_stage' => 0,
            'is_optional' => 1,
            'is_refundable' => 0,
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);
    }
}
