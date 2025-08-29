<?php

use Modules\Core\Entities\Configuration\Color;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->down();
        Schema::create('psx_colors', function (Blueprint $table) {
            $table->id();
            $table->string("key");
            $table->string("value");
            $table->string("title");
            $table->boolean("fe_color");
            $table->boolean("mb_color");
            $table->timestamp('added_date')->nullable();
            $table->foreignId('added_user_id')->nullable();
            $table->timestamp('updated_date')->nullable();
            $table->foreignId('updated_user_id')->nullable();
            $table->smallInteger('updated_flag')->nullable();
        });

        $mb_titles = [
            "primary", "text", "accent", "success", "error", "warning", "info", "achromatic"
        ];
        $fe_titles = [
            "fePrimary", "feSecondary", "feAccent", "feSuccess", "feError", "feWarning", "feInfo", "feAchromatic"
        ];
        $series = [
            "50", "100", "200", "300", "400", "500", "600","700", "800", "900",
        ];
        $primary_series = [
            "#FBEAEA", "#F4CDCE", "#F0B8B9", "#EBA3A5", "#E48184", "#A92428", "#972024", "#76191C", "#5C1416", "#470F11"
        ];
        $secondary_series = [
            "#F1F2F3", "#DEDFE3", "#D0D2D8", "#C2C5CC", "#ACB0B9", "#6B7280", "#545964", "#41454E", "#33363D", "#272A2F"
        ];
        $accent_series = [
            "#FCE8F2", "#F9C8E0", "#F6B1D3", "#F49AC6", "#F075B1", "#EB4898", "#A6125A", "#810E46", "#650B37", "#4E092B"
        ];
        $success_series = [
            "#E8FDF6", "#C7FAE9", "#AFF8E0", "#98F6D7", "#72F3C8", "#10B981", "#0FA976", "#0B835C", "#096748", "#075038"
        ];
        $error_series = [
            "#FDE8E8", "#FAC7C7", "#F8AFAF", "#F69898", "#F37272", "#EF4444", "#A90E0E", "#840B0B", "#670909", "#500707"
        ];
        $warning_series = [
            "#FEF5E7", "#FDE8C4", "#FCDEAC", "#FBD493", "#F9C56C", "#F59E0B", "#B07107", "#895806", "#6C4504", "#533603"
        ];
        $info_series = [
            "#E7EFFE", "#C4DAFC", "#ACCAFB", "#94BBFA", "#6DA2F8", "#3B82F6", "#0848B0", "#063889", "#052C6B", "#042253"
        ];
        $achromatic_series = [
            "#FFFFFF", "#EBEBEB", "#D6D6D6", "#B8B8B8", "#999999", "#858585", "#666666", "#363636", "#292929", "#121212"
        ];

        $brands = [
            "facebook", "google", "phone", "apple", "paypal", "stripe", "razor", "paystack", "twitter", "linkedin"
        ];
        $brands_series = [
            "#4267B2", "#EA4335", "#38C141", "#000000", "#3B7BBF", "#008CDD", "#012652", "#00C3F7", "#1DA1F2", "#0A66C2"
        ];

        foreach($mb_titles as $mb_title){
            foreach($series as $key=>$mb_series){
                $color = new Color();
                $color->key = $mb_title.'_'.$mb_series;
                if($mb_title == 'primary'){
                    $color->value = $primary_series[$key];
                }else if($mb_title == 'text'){
                    $color->value = $secondary_series[$key];
                }else if($mb_title == 'accent'){
                    $color->value = $accent_series[$key];
                }else if($mb_title == 'success'){
                    $color->value = $success_series[$key];
                }else if($mb_title == 'error'){
                    $color->value = $error_series[$key];
                }else if($mb_title == 'warning'){
                    $color->value = $warning_series[$key];
                }else if($mb_title == 'info'){
                    $color->value = $info_series[$key];
                }else if($mb_title == 'achromatic'){
                    $color->value = $achromatic_series[$key];
                }
                $color->title = $mb_title;
                $color->fe_color = 0;
                $color->mb_color = 1;
                $color->save();
            }
        }

        foreach($brands as $key=>$brand){
            $color = new Color();
            $color->key = 'brand_'.$brand;
            $color->title = "brand";
            $color->value = $brands_series[$key];
            $color->fe_color = 0;
            $color->mb_color = 1;
            $color->save();
        }

        foreach($fe_titles as $fe_title){
            foreach($series as $key=>$fe_series){
                $color = new Color();
                $color->key = $fe_title.'-'.$fe_series;
                if($fe_title == 'fePrimary'){
                    $color->value = $primary_series[$key];
                }else if($fe_title == 'feSecondary'){
                    $color->value = $secondary_series[$key];
                }else if($fe_title == 'feAccent'){
                    $color->value = $accent_series[$key];
                }else if($fe_title == 'feSuccess'){
                    $color->value = $success_series[$key];
                }else if($fe_title == 'feError'){
                    $color->value = $error_series[$key];
                }else if($fe_title == 'feWarning'){
                    $color->value = $warning_series[$key];
                }else if($fe_title == 'feInfo'){
                    $color->value = $info_series[$key];
                }else if($fe_title == 'feAchromatic'){
                    $color->value = $achromatic_series[$key];
                }
                $color->title = $fe_title;
                $color->fe_color = 1;
                $color->mb_color = 0;
                $color->save();
            }
        }

        foreach($brands as $key=>$brand){
            $color = new Color();
            $color->key = 'feBrand-'.$brand;
            $color->title = "feBrand";
            $color->value = $brands_series[$key];
            $color->fe_color = 1;
            $color->mb_color = 0;
            $color->save();
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('psx_colors');
    }
};
