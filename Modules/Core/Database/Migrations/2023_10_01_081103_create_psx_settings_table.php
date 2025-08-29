<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Core\Entities\Configuration\Setting;

return new class extends Migration
{
    public function up()
    {
        Schema::create('psx_settings', function (Blueprint $table) {
            $table->id();
            $table->String('setting_env');
            $table->json('setting')->nullable();
            $table->json('ref_selection')->nullable();
            $table->timestamps();
        });

        $system_config = new Setting();
        $system_config->setting_env = 'system_config';
        $system_config->save();

        $ref_types = [];
        $selected_types = [];

        //item prices type Ref JSON data
        $item_price_data['price_selections'] = array(
            [
                'id' =>  'NORMAL_PRICE',
                'value' => 'Normal Price with currency'
            ],
            [
                'id' =>  'NO_PRICE',
                "value" => "No Price"
            ],
            [
                'id' =>  'PRICE_RANGE',
                "value" => "Price Range ( $, $$, $$$, $$$$, $$$$$ )"
            ],
        );

        $item_price_range_data['item_price_range_selections'] = array(
            [
                'id' =>  '1',
                'value' => '$'
            ],
            [
                'id' =>  '2',
                'value' => '$$'
            ],
            [
                'id' =>  '3',
                'value' => '$$$'
            ],
            [
                'id' =>  '4',
                'value' => '$$$$'
            ],
            [
                'id' =>  '5',
                'value' => '$$$$$'
            ],

        );

        //chat types Ref Json data
        $chat_types['item_chat_selections'] = array(
            [
                'id' =>  'CHAT_AND_OFFER',
                "value" => "Chat & Make Offer"
            ],
            [
                'id' =>  'CHAT_ONLY',
                "value" => "Chat Only"
            ],
            [
                'id' =>  'CHAT_AND_APPOINTMENT',
                "value" => "Chat & Appointment"
            ],
            [
                'id' =>  'NO_CHAT',
                "value" => "No Chat"
            ],
        );

        //selected types data
        $selected_price_data = array(
            'id' => $item_price_data['price_selections'][0]['id']
        );

        $selected_chat_data = array(
            'id' => $chat_types['item_chat_selections'][0]['id'],
        );



        //prepare json for mysql
        $selected_types['selected_price_type'] = $selected_price_data;
        $selected_types['selected_chat_data'] = $selected_chat_data;

        $ref_types['price_type'] = $item_price_data;
        $ref_types['item_chat_type'] = $chat_types;
        $ref_types['item_price_range'] = $item_price_range_data;

        //save in mysql
        $system_config->ref_selection  = $ref_types;
        $system_config->setting = $selected_types;

        $system_config->save();
    }

    public function down()
    {
        Schema::dropIfExists('psx_settings');
    }
};
