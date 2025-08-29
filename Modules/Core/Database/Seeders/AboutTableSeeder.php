<?php

namespace Modules\Core\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class AboutTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        DB::table('psx_core_abouts')->insert([
            'about_title' => 'Panaces Soft',
            "about_description" => 'Panacea-Soft is a software development team that focuses on helping your business with mobile and web technology.We tried our best to delivery quality product on time according clientâ€™s requirements and exceptions. We are technology oriented team so before we code, we analyse for your requirements and brain storm then start for development. We donâ€™t over promise to client and trying our best to deliver awesome product package. Thanks for reaching out to us. We are happy to listen your world and enjoy to solve the problem using technology.',
            "about_email" => 'teamps.is.cool@gmail.com',
            "about_phone" => '+9592540942**',
            "about_address" => 'No.7, U Ba Han St, Lay Daunk Kan Qtr, Thingangyun-',
            "about_website" => 'http://www.panacea-soft.com',
            "facebook" => 'http://www.facebook.com',
            "google_plus" => 'http://www.google.com',
            "instagram" => 'http://www.instagram.com',
            "youtube" => 'http://www.youtube.com',
            "pinterest" => 'http://www.pinterest.com',
            "twitter" => 'http://www.twitter.com',
            "GDPR" => 'GDPR',
            "upload_point" => '50',
            "safety_tips" => "Meet the seller in person and transfer cash only if you have secured the item. As a seller, ensure that you have secured your payment and safely exchanged your item. Do not transfer money in advance! Unless verified by your own bank directly, don't trust any SMS or email about any money transferred to your account. Someone's claim to have paid extra money by mistake and asking to return/reverse is suspicious in nature and should be avoided in all instances.",
            "faq_pages" => "industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.",
            "terms_and_conditions" => "industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.",
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);
    }
}
