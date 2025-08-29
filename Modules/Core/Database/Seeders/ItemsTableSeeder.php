<?php

namespace Modules\Core\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Modules\Core\Entities\Item;
use Modules\Core\Entities\ItemInfo;
use Faker\Generator;
use Illuminate\Container\Container;

class ItemsTableSeeder extends Seeder
{

    protected $faker;
    public function __construct()
    {
        $this->faker = $this->withFaker();
    }
    protected function withFaker()
    {
        return Container::getInstance()->make(Generator::class);
    }
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $ps_item00002 = [9,10,14,20];
        $ps_item00003 =[5,6,15,16,17,19];
        $ps_item00004 = [23,24];
        $ps_item00006 = [7,8];
        $ps_item00007 = [25,26];
        Model::unguard();

        Item::factory()->count(10000)->create()->each(function ($item) use($ps_item00002,$ps_item00003,$ps_item00004,$ps_item00006,$ps_item00007){
            DB::table('psx_item_infos')->insert([
                'item_id' => $item->id,
                    'core_keys_id'=>'ps-itm00001',
                    'value'=>$this->faker->word,
                    'ui_type_id'=>'uit00002',
                    'updated_user_id'=>1,
            ]);
            DB::table('psx_item_infos')->insert([
                'item_id' => $item->id,
                'core_keys_id'=>'ps-itm00002',
                'value'=>$ps_item00002[array_rand($ps_item00002)],
                'ui_type_id'=>'uit00001',
                'updated_user_id'=>1,
            ]);
            DB::table('psx_item_infos')->insert([
                'item_id' => $item->id,
                'core_keys_id'=>'ps-itm00003',
                'value'=>$ps_item00003[array_rand($ps_item00003)],
                'ui_type_id'=>'uit00001',
                'updated_user_id'=>1,
            ]);
            DB::table('psx_item_infos')->insert([
                'item_id' => $item->id,
                'core_keys_id'=>'ps-itm00004',
                'value'=>$ps_item00004[array_rand($ps_item00004)],
                'ui_type_id'=>'uit00001',
                'updated_user_id'=>1,
            ]);
            DB::table('psx_item_infos')->insert([
                'item_id' => $item->id,
                'core_keys_id'=>'ps-itm00005',
                'value'=>$this->faker->word,
                'ui_type_id'=>'uit00006',
                'updated_user_id'=>1,
            ]);
            DB::table('psx_item_infos')->insert([
                'item_id' => $item->id,
                'core_keys_id'=>'ps-itm00006',
                'value'=>$this->faker->paragraph,
                'ui_type_id'=>'uit00006',
                'updated_user_id'=>1,
            ]);
            DB::table('psx_item_infos')->insert([
                'item_id' => $item->id,
                'core_keys_id'=>'ps-itm00007',
                'value'=>$ps_item00007[array_rand($ps_item00007)],
                'ui_type_id'=>'uit00001',
                'updated_user_id'=>1,
            ]);
            DB::table('psx_item_infos')->insert([
                'item_id' => $item->id,
                'core_keys_id'=>'ps-itm00008',
                'value'=>rand(0,1),
                'ui_type_id'=>'uit00004',
                'updated_user_id'=>1,
            ]);
            DB::table('psx_item_infos')->insert([
                'item_id' => $item->id,
                'core_keys_id'=>'ps-itm00009',
                'value'=>$this->faker->address,
                'ui_type_id'=>'uit00006',
                'updated_user_id'=>1,
            ]);
        });

        // DB::table('psx_items')->insert([
        //     'title' => 'Myanmar Dress for Sell',
        //     'category_id' => '4',
        //     'subcategory_id' => '12',
        //     'currency_id' => '1',
        //     'location_city_id' => '1',
        //     'location_township_id' => '1',
        //     'price' => '15',
        //     'original_price' => '15',
        //     'description' => 'myanmar dress desc',
        //     'search_tag' => 'myanmser_dress',
        //     'lat' => '16.865044',
        //     'lng' => '96.203674',
        //     'status' => '1',
        //     'ordering' => '1',
        //     'is_available' => '1',
        //     'added_user_id' => '1',
        //     'added_date'=>Carbon::now(),
        // ]);

        // DB::table('psx_items')->insert([
        //     'title' => 'Mi A2 for Buy',
        //     'category_id' => '7',
        //     'subcategory_id' => '23',
        //     'currency_id' => '1',
        //     'location_city_id' => '1',
        //     'location_township_id' => '2',
        //     'price' => '15',
        //     'original_price' => '15',
        //     'description' => 'desc',
        //     'search_tag' => '',
        //     'lat' => '16.865044',
        //     'lng' => '96.203674',
        //     'status' => '1',
        //     'ordering' => '1',
        //     'is_available' => '1',
        //     'added_user_id' => '1',
        //     'added_date'=>Carbon::now(),
        // ]);

        // DB::table('psx_items')->insert([
        //     'title' => 'Apartment for Buy',
        //     'category_id' => '6',
        //     'subcategory_id' => '18',
        //     'currency_id' => '1',
        //     'location_city_id' => '2',
        //     'location_township_id' => '3',
        //     'price' => '1500',
        //     'original_price' => '1500',
        //     'description' => 'desc',
        //     'search_tag' => '',
        //     'lat' => '16.865044',
        //     'lng' => '96.203674',
        //     'status' => '1',
        //     'ordering' => '1',
        //     'is_available' => '1',
        //     'added_user_id' => '1',
        //     'added_date'=>Carbon::now(),
        // ]);

        // DB::table('psx_items')->insert([
        //     'title' => 'Nokia Keypad for Sell',
        //     'category_id' => '7',
        //     'subcategory_id' => '24',
        //     'currency_id' => '1',
        //     'location_city_id' => '1',
        //     'location_township_id' => '2',
        //     'price' => '1500',
        //     'original_price' => '1500',
        //     'description' => 'desc',
        //     'search_tag' => '',
        //     'lat' => '16.865044',
        //     'lng' => '96.203674',
        //     'status' => '1',
        //     'ordering' => '1',
        //     'is_available' => '1',
        //     'added_user_id' => '1',
        //     'added_date'=>Carbon::now(),
        // ]);

        // DB::table('psx_items')->insert([
        //     'title' => 'Gaming PC For Buy',
        //     'category_id' => '1',
        //     'subcategory_id' => '3',
        //     'currency_id' => '1',
        //     'location_city_id' => '2',
        //     'location_township_id' => '4',
        //     'price' => '300',
        //     'original_price' => '300',
        //     'description' => 'desc',
        //     'search_tag' => '',
        //     'lat' => '21.959900',
        //     'lng' => '96.086601',
        //     'status' => '1',
        //     'ordering' => '1',
        //     'is_available' => '1',
        //     'added_user_id' => '1',
        //     'added_date'=>Carbon::now(),
        // ]);

        // DB::table('psx_items')->insert([
        //     'title' => 'U 12 girl dress for Sell',
        //     'category_id' => '4',
        //     'subcategory_id' => '11',
        //     'currency_id' => '1',
        //     'location_city_id' => '2',
        //     'location_township_id' => '4',
        //     'price' => '300',
        //     'original_price' => '300',
        //     'description' => 'desc',
        //     'search_tag' => '',
        //     'lat' => '21.959900',
        //     'lng' => '96.086601',
        //     'status' => '1',
        //     'ordering' => '1',
        //     'is_available' => '1',
        //     'added_user_id' => '1',
        //     'added_date'=>Carbon::now(),
        // ]);

        // DB::table('psx_items')->insert([
        //     'title' => 'My Skirt For Sell',
        //     'category_id' => '4',
        //     'subcategory_id' => '9',
        //     'currency_id' => '1',
        //     'location_city_id' => '2',
        //     'location_township_id' => '4',
        //     'price' => '300',
        //     'original_price' => '300',
        //     'description' => 'desc',
        //     'search_tag' => '',
        //     'lat' => '21.959900',
        //     'lng' => '96.086601',
        //     'status' => '1',
        //     'ordering' => '1',
        //     'is_available' => '1',
        //     'added_user_id' => '1',
        //     'added_date'=>Carbon::now(),
        // ]);

        // DB::table('psx_items')->insert([
        //     'title' => 'My HP Ultrabook For Sell',
        //     'category_id' => '1',
        //     'subcategory_id' => '2',
        //     'currency_id' => '1',
        //     'location_city_id' => '2',
        //     'location_township_id' => '4',
        //     'price' => '300',
        //     'original_price' => '300',
        //     'description' => 'desc',
        //     'search_tag' => '',
        //     'lat' => '21.959900',
        //     'lng' => '96.086601',
        //     'status' => '1',
        //     'ordering' => '1',
        //     'is_available' => '1',
        //     'added_user_id' => '1',
        //     'added_date'=>Carbon::now(),
        // ]);
    }
}
