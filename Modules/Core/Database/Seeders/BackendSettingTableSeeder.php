<?php

namespace Modules\Core\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class BackendSettingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        DB::table('psx_backend_settings')->insert([
            'sender_name' => 'Team PS',
            'sender_email' => 'sender@blog.panacea-soft.com',
            'receive_email' => 'teamps.is.cool@gmail.com',
            'fcm_api_key' => 'AAAAdas8rOs:APA91bEhLizx8g2RQpdxf6eWfZitLrriVUiAYUIzN6DKKSmXsfyXk6Oq79iMFDZADnEHsIdu-67rue1sss0QmFzKWnIvQi54eWvtvsJLQAlLS0ow4pO_3aQN9RUSiqsvPKSPcuKH_05d',
            'app_token' => '126556525430166%7C8SLc_o-xHsF_zx7wwrQQtNQiYZs',
            'topics' => 'broadcast',
            'topics_fe' => 'fe_broadcast',
            'smtp_host' => 'smtp.dreamhost.com',
            'smtp_port' => '465',
            'smtp_encryption' => 'ssl',
            'smtp_user' => 'admin@panacea-soft.co',
            'smtp_pass' => 'Ft!dzg+UDM83UsSu',
            'landscape_width' => 1000,
            'potrait_height' => 1000,
            'square_height' => 1000,
            'landscape_thumb_width' => 100,
            'potrait_thumb_height' => 100,
            'square_thumb_height' => 100,
            'landscape_thumb2x_width' => 200,
            'potrait_thumb2x_height' => 200,
            'square_thumb2x_height' => 200,
            'landscape_thumb3x_width' => 350,
            'potrait_thumb3x_height' => 350,
            'square_thumb3x_height' => 350,
            'dyn_link_key' => 'AIzaSyAKBO26mCqOrmMbaBb2amplpgJ7nGRgekY',
            'dyn_link_url' => 'https://firebasedynamiclinks.googleapis.com/v1/shortLinks?key=',
            'dyn_link_package_name' => 'com.panaceasoft.flutterbuyandsell',
            'dyn_link_domain' => 'buysell1.page.link',
            'dyn_link_deep_url' => 'https://www.panacea-soft.com/buysell-fe-dev/item/',
            'ios_boundle_id' => 'com.panaceasoft.flutterbuyandsell',
            'ios_appstore_id' => '1500242983',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);
    }
}
