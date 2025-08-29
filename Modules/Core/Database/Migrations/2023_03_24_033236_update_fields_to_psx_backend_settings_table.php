<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Core\Entities\Configuration\BackendSetting;
use Modules\Core\Entities\CoreImage;
use Modules\Core\Http\Services\ImageService;
use Modules\Core\Http\Services\Configuration\ColorService;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Modules\Core\Constants\Constants;

return new class extends Migration
{

    protected $backendWaterMaskImgType, $backendWaterMaskBackgroundImage, $backendWaterMaskBackgroundImageOrg, $imageService;

    public function __construct()
    {

        $this->backendWaterMaskImgType = 'backend-water-mask-image';
        $this->backendWaterMaskBackgroundImage = 'water-mask-background';
        $this->backendWaterMaskBackgroundImageOrg = 'water-mask-background-original';
    }
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $colorService = (new ColorService);
        $folder_path = (new ImageService($colorService))->getFolderImagePath();


        $storage_upload_path = '/storage/' .  $folder_path . '/uploads/';

        $watermask_file_exist = File::exists(public_path() . $storage_upload_path . 'psx-watermark.png');
        // dd($watermask_file_exist);

        if ($watermask_file_exist) {
            $file = Image::make(public_path() . $storage_upload_path . 'psx-watermark.png');
            $image = new CoreImage();
            $image->img_parent_id = 1;
            $image->img_type = 'backend-water-mask-image';
            $image->img_path = $file->basename;
            $image->img_width = $file->width();
            $image->img_height = $file->height();
            $image->ordering =  1;
            $image->save();
        }

        $preview_file_exist = File::exists(public_path() . $storage_upload_path . 'psx-preview-watermask-background.jpeg');

        if ($preview_file_exist) {
            $file = Image::make(public_path() . $storage_upload_path . 'psx-preview-watermask-background.jpeg');
            $image = new CoreImage();
            $image->img_parent_id = 1;
            $image->img_type = 'water-mask-background';
            $image->img_path = $file->basename;
            $image->img_width = $file->width();
            $image->img_height = $file->height();
            $image->ordering =  1;
            $image->save();
        }

        $preview_file_exist = File::exists(public_path() . $storage_upload_path . 'preview_psx-preview-watermask-background.jpeg');

        if ($preview_file_exist) {


            $file = Image::make(public_path() . $storage_upload_path . 'preview_psx-preview-watermask-background.jpeg');
            $image = new CoreImage();
            $image->img_parent_id = 1;
            $image->img_type = $this->backendWaterMaskBackgroundImageOrg;
            $image->img_path = $file->basename;
            $image->img_width = $file->width();
            $image->img_height = $file->height();
            $image->ordering =  1;
            $image->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('psx_backend_settings', function (Blueprint $table) {});
    }
};
