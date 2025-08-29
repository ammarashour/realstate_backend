<?php

namespace Modules\Core\Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Modules\Core\Entities\Item;
use Modules\Core\Entities\Touch;
use Modules\Core\Entities\Favourite;
use Illuminate\Database\Eloquent\Model;
use Modules\Core\Database\Seeders\RoleTableSeeder;
use Modules\Core\Database\Seeders\ItemsTableSeeder;
use Modules\Core\Database\Seeders\ShopsTableSeeder;
use Modules\Core\Database\Seeders\TouchTableSeeder;
use Modules\Core\Database\Seeders\UsersTableSeeder;
use Modules\Core\Database\Seeders\CitiesTableSeeder;
use Modules\Core\Database\Seeders\ImagesTableSeeder;
use Modules\Core\Database\Seeders\UiTypeTableSeeder;
use Modules\Core\Database\Seeders\ModulesTableSeeder;
use Modules\Core\Database\Seeders\LanguagesTableSeeder;
use Modules\Core\Database\Seeders\ShippingsTableSeeder;
use Modules\Core\Database\Seeders\TownshipsTableSeeder;
use Modules\Core\Database\Seeders\CategoriesTableSeeder;
use Modules\Core\Database\Seeders\CurrenciesTableSeeder;
use Modules\Core\Database\Seeders\MenuGroupsTableSeeder;
use Modules\Core\Database\Seeders\PermissionTableSeeder;
use Modules\Core\Database\Seeders\AdPostTypesTableSeeder;
use Modules\Core\Database\Seeders\SubcategoriesTableSeeder;
use Modules\Core\Database\Seeders\SubMenuGroupsTableSeeder;
use Modules\Core\Database\Seeders\BackendSettingTableSeeder;
use Modules\Core\Database\Seeders\LanguageStringsTableSeeder;
use Modules\Core\Database\Seeders\PaymentStatusesTableSeeder;
use Modules\Core\Database\Seeders\TransactionCountsTableSeeder;
use Modules\Core\Database\Seeders\TransactionDetailsTableSeeder;
use Modules\Core\Database\Seeders\TransactionHeadersTableSeeder;
use Modules\Core\Database\Seeders\TransactionStatusesTableSeeder;
use Modules\Core\Database\Seeders\AboutTableSeeder;
use Modules\Core\Database\Seeders\UserInfoTableSeeder;
use Modules\Core\Database\Seeders\CoreKeyTableSeeder;
use Modules\Core\Database\Seeders\CoreKeyTypeTableSeeder;
use Modules\Core\Database\Seeders\CustomizeUiTableSeeder;
use Modules\Core\Database\Seeders\CustomizeUiDetailTableSeeder;
use Modules\Core\Database\Seeders\CoreFieldFilterSettingTableSeeder;
use Modules\Core\Database\Seeders\ScreenDisplayUiSettingTableSeeder;
use Modules\Core\Database\Seeders\AvailableCurrencyTableSeeder;
use Modules\Core\Database\Seeders\TableUsedTypeTableSeeder;

class CoreDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        // seeding data generate
        $this->call([
            UsersTableSeeder::class,
            BackendSettingTableSeeder::class,
            ItemsTableSeeder::class,
            ImagesTableSeeder::class,
            CategoriesTableSeeder::class,
            SubcategoriesTableSeeder::class,
            AdPostTypesTableSeeder::class,
            CitiesTableSeeder::class,
            CurrenciesTableSeeder::class,
            LanguagesTableSeeder::class,
            LanguageStringsTableSeeder::class,
            MenuGroupsTableSeeder::class,
            SubMenuGroupsTableSeeder::class,
            ModulesTableSeeder::class,
            AboutTableSeeder::class,
            PaymentStatusesTableSeeder::class,
            ShippingsTableSeeder::class,
            ShopsTableSeeder::class,
            TownshipsTableSeeder::class,
            TransactionStatusesTableSeeder::class,
            TransactionHeadersTableSeeder::class,
            TransactionDetailsTableSeeder::class,
            TransactionCountsTableSeeder::class,
            UiTypeTableSeeder::class,
            RoleTableSeeder::class,
            PermissionTableSeeder::class,
            TouchTableSeeder::class,
            UserInfoTableSeeder::class,
            CoreKeyTableSeeder::class,
            CoreKeyTypeTableSeeder::class,
            // CustomizeUiTableSeeder::class,
            CustomizeUiDetailTableSeeder::class,
            // CoreFieldFilterSettingTableSeeder::class,
            // ScreenDisplayUiSettingTableSeeder::class,
            MobileLanaguageTableSeeder::class,
            MobileLanaguageStringTableSeeder::class,
            UserPermissionTableSeeder::class,
            RolePermissionTableSeeder::class,
            BlogTableSeeder::class,
            PersonalAccessTokenTableSeeder::class,
            PrivacyPolicyTableSeeder::class,
            AvailableCurrencyTableSeeder::class,
            MobileSettingTableSeeder::class,
            FrontendSettingTableSeeder::class,
            SystemConfigTableSeeder::class,
            TableUsedTypeTableSeeder::class,
            PhoneCountryCodeTableSeeder::class,
        ]);

        // factory data generate
        User::factory()->count(50)->create();
        Item::factory()->count(50)->create();
        Touch::factory()->count(50)->create();
        Favourite::factory()->count(50)->create();
    }
}
