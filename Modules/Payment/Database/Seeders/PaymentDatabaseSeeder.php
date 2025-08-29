<?php

namespace Modules\Payment\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Payment\Database\Seeders\PaymentTableSeeder;
use Modules\Payment\Database\Seeders\PaymentInfoTableSeeder;
use Modules\Payment\Database\Seeders\CoreKeyPaymentRelationTableSeeder;

class PaymentDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->call([
            PaymentTableSeeder::class,
            CoreKeyPaymentRelationTableSeeder::class,
            OfflinePaymentTableSeeder::class,
            PromotionIAPTableSeeder::class,
            PackageIAPTableSeeder::class
        ]);
    }
}
