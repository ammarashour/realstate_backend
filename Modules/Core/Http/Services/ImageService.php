<?php

namespace Modules\Core\Http\Services;

use App\Config\Cache\CategoryCache;
use App\Config\Cache\ItemCache;
use App\Config\Cache\SubcategoryCache;
use App\Http\Services\PsService;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Core\Entities\CoreImage;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Modules\Core\Constants\Constants;
use Illuminate\Support\Facades\File;
use Modules\Core\Entities\Configuration\BackendSetting;
use Modules\Core\Http\Facades\PsCache;
use Modules\Core\Http\Services\Configuration\ColorService;

/**
 * @deprecated
 */
class ImageService extends PsService
{
    //    protected $upload_path = 'public/uploads/';
    //    protected $thumb1x_path = 'public/thumbnail/';
    //    protected $thumb2x_path = 'public/thumbnail2x/';
    //    protected $thumb3x_path = 'public/thumbnail3x/';

    // for under root public start
    protected $upload_path = 'storage/uploads/';
    protected $thumb1x_path = 'storage/thumbnail/';
    protected $thumb2x_path = 'storage/thumbnail2x/';
    protected $thumb3x_path = 'storage/thumbnail3x/';
    // for under root public end

    // protected $storage_thumb1x_path = '/storage/thumbnail/';
    // protected $storage_thumb2x_path = '/storage/thumbnail2x/';
    // protected $storage_thumb3x_path = '/storage/thumbnail3x/';

    protected $colorService, $coreImageIdCol, $coreImageImgTypeCol, $coreImageImgParentIdCol, $img_folder_path, $storage_upload_path, $storage_thumb1x_path, $storage_thumb2x_path, $storage_thumb3x_path;

    public function __construct(ColorService $colorService)
    {
        $this->coreImageIdCol = CoreImage::id;
        $this->coreImageImgTypeCol = CoreImage::imgType;
        $this->coreImageImgParentIdCol = CoreImage::imgParentId;
        $this->img_folder_path = Constants::folderPath;
        $this->storage_upload_path = '/storage/' . $this->getFolderImagePath() . '/uploads/';
        $this->storage_thumb1x_path = '/storage/' . $this->getFolderImagePath() . '/thumbnail/';
        $this->storage_thumb2x_path = '/storage/' . $this->getFolderImagePath() . '/thumbnail2x/';
        $this->storage_thumb3x_path = '/storage/' . $this->getFolderImagePath() . '/thumbnail3x/';
        $this->colorService = $colorService;
    }

    //    public function getImage($id = null, $imgParentId = null, $imgType = null){
    //        $image = CoreImage::when($id, function ($q, $id){
    //                    $q->where($this->coreImageIdCol, $id);
    //                })
    //                ->when($imgParentId, function ($q, $imgParentId){
    //                    $q->where($this->coreImageImgParentIdCol, $imgParentId);
    //                })
    //                ->when($imgType, function ($q, $imgType){
    //                    $q->where($this->coreImageImgTypeCol, $imgType);
    //                })
    //                ->first();
    //        return $image;
    //    }

    public function getFolderImagePath()
    {
        return $this->img_folder_path;
    }

    public function store($request)
    {
        DB::beginTransaction();
        try {
            $image = new CoreImage();
            if (isset($request->img_parent_id) && !empty($request->img_parent_id))
                $image->img_parent_id = $request->img_parent_id;

            if (isset($request->img_type) && !empty($request->img_type))
                $image->img_type = $request->img_type;

            if (isset($request->img_path) && !empty($request->img_path))
                $image->img_path = $request->img_path;

            if (isset($request->img_width) && !empty($request->img_width))
                $image->img_width = $request->img_width;

            if (isset($request->img_height) && !empty($request->img_height))
                $image->img_height = $request->img_height;

            if (isset($request->ordering) && !empty($request->ordering))
                $image->ordering = $request->ordering;
            else
                $image->ordering = 1;

            if (isset($request->added_user_id) && !empty($request->added_user_id))
                $image->added_user_id = $request->added_user_id;
            else
                $image->added_user_id = Auth::user()->id;

            $image->save();

            DB::commit();
            return $image;
        } catch (\Throwable $e) {
            DB::rollBack();
            return ['error' => $e];
        }
    }

    public function insertImage($file, $data, $image = false, $uploadType = null)
    {
        $isCreate = $image == false || empty($image) ? true : false;
        if ($uploadType == 'preview') {
            // dd($file);
            // $conds = [
            //     $this->coreImageImgTypeCol => 'backend-water-mask-image-original',
            // ];
            // $imageToDelete = $this->getImage($conds);
            // if($imageToDelete){
            //     // $this->deleteImage($image->img_path);
            // }

            $fileName = $file->basename;
            $fileName = 'preview_' . $fileName;
            $extension = $file->extension;
        } else {

            $fileName = newFileName($file);
            if ($uploadType == 'background') {
                $fileName = 'preview_' . $fileName;
            }
            $extension = $file->getClientOriginalExtension();
        }


        if ($extension == 'ico') {
            Storage::putFileAs($this->storage_upload_path, $file, $fileName);
            Storage::putFileAs($this->storage_thumb1x_path, $file, $fileName);
            Storage::putFileAs($this->storage_thumb2x_path, $file, $fileName);
            Storage::putFileAs($this->storage_thumb3x_path, $file, $fileName);
            $org_image = getimagesize($file);

            $width = $org_image[0];
            $height = $org_image[1];
        } else {
            // save original image to 'uploads'
            $org_image = $this->uploadOrgImage($file, $fileName, $uploadType);
            // save thumb 1x image to 'thumbnail'
            $this->createThumbnail1x($file, $fileName, $uploadType);

            // save thumb 2x image to 'thumbnail2x'
            $this->createThumbnail2x($file, $fileName, $uploadType);

            // save thumb 3x image to 'thumbnail3x'
            $this->createThumbnail3x($file, $fileName, $uploadType);

            $width = $org_image->width();
            $height = $org_image->height();
        }

        if ($isCreate) {
            $image = new CoreImage();
        }


        $image->img_parent_id = $data['img_parent_id'];
        $image->img_type = $data['img_type'];
        $image->img_path = $fileName;
        $image->img_width = $width;
        $image->img_height = $height;
        $image->ordering = isset($data['ordering']) ? $data['ordering'] : 1;


        if (array_key_exists("img_desc", $data)) {
            $image->img_desc = $data['img_desc'];
        }

        if ($isCreate) {
            $image->added_user_id = Auth::user()->id;
            $image->save();
        } else {
            $image->updated_user_id = Auth::user()->id;
            $image->update();
        }
    }

    public function insertImageToStorage($file, $old_path = false, $uploadType = null)
    {
        try {
            if ($old_path && !empty($old_path)) {
                $this->deleteImage($old_path);
            }
            $fileName = newFileName($file);
            $extension = $file->getClientOriginalExtension();

            if ($extension == 'ico') {
                Storage::putFileAs($this->storage_upload_path, $file, $fileName);
                Storage::putFileAs($this->storage_thumb1x_path, $file, $fileName);
                Storage::putFileAs($this->storage_thumb2x_path, $file, $fileName);
                Storage::putFileAs($this->storage_thumb3x_path, $file, $fileName);
                $org_image = getimagesize($file);
            } else {
                // save original image to 'uploads'
                $org_image = $this->uploadOrgImage($file, $fileName);



                // save thumb 1x image to 'thumbnail'
                $this->createThumbnail1x($file, $fileName);

                // save thumb 2x image to 'thumbnail2x'
                $this->createThumbnail2x($file, $fileName);

                // save thumb 3x image to 'thumbnail3x'
                $this->createThumbnail3x($file, $fileName);
            }
            return $fileName;
        } catch (\Throwable $e) {
            return ['error' => $e->getMessage()];
        }
    }
    public function getTextWidth($backend_setting)
    {
        $text = $backend_setting->watermask_title;

        // $img = $imagecreatetruecolor(100,100); // SIZE DOESN'T MATTER REALLY THIS TIME
        $fontSize = $backend_setting->font_size; //FONT SIZE
        $fontFile = public_path('Inter-VariableFont_slnt,wght.ttf'); // PUT THE RELEVANT FONT FILE IN THE SAME DIRECTORY AS THIS SCRIPT
        $boundingBox = imagettfbbox($fontSize, 0, $fontFile, $text); //RETRIEVE THE BOUNDING BOX OF THE TEXT RENDERED
        $watermask_text_width = $boundingBox[2] - $boundingBox[0]; //WIDTH IN PIXELS AT 72dpi

        return $watermask_text_width;
    }

    public function getTextHeight($backend_setting)
    {
        $text = $backend_setting->watermask_title;

        // $img = $imagecreatetruecolor(100,100); // SIZE DOESN'T MATTER REALLY THIS TIME
        $fontSize = $backend_setting->font_size; //FONT SIZE
        $fontFile = public_path('Inter-VariableFont_slnt,wght.ttf'); // PUT THE RELEVANT FONT FILE IN THE SAME DIRECTORY AS THIS SCRIPT
        $boundingBox = imagettfbbox($fontSize, 0, $fontFile, $text); //RETRIEVE THE BOUNDING BOX OF THE TEXT RENDERED
        $min_y = min(array($boundingBox[1], $boundingBox[3], $boundingBox[5], $boundingBox[7]));
        $max_y = max(array($boundingBox[1], $boundingBox[3], $boundingBox[5], $boundingBox[7]));
        $watermask_text_width = ($max_y - $min_y);

        return $watermask_text_width;
    }

    // save orginal image to upload
    public function uploadOrgImage($file, $fileName, $uploadType = null)
    {

        // dd($uploadType);
        $font_color = $this->colorService->get(key: 'backend_color');

        $org_image = Image::make($file);
        $backend_setting = BackendSetting::first();
        // dd($uploadType);
        // do not apply resize for banner FE
        if ($uploadType != 'frontend_banner') {
            if ($org_image->width() > $org_image->height()) {
                $image_type = 'Landscape';
                if ($org_image->width() > $backend_setting->landscape_width) {
                    $org_image = $org_image->resize($backend_setting->landscape_width, $org_image->height(), function ($constraint) {
                        $constraint->aspectRatio();
                    });
                }
            }

            if ($org_image->height() > $org_image->width()) {
                $image_type = 'Portrait';
                if ($org_image->height() > $backend_setting->potrait_height) {
                    $org_image = $org_image->resize($backend_setting->potrait_height, $org_image->height(), function ($constraint) {
                        $constraint->aspectRatio();
                    });
                }
            }

            if ($org_image->width() == $org_image->height()) {
                $image_type = 'Square';
                if ($org_image->height() > $backend_setting->square_height) {
                    $org_image = $org_image->resize($backend_setting->square_height, $org_image->height(), function ($constraint) {
                        $constraint->aspectRatio();
                    });
                }
            }
        }

        if (!File::isDirectory(public_path() . $this->storage_upload_path)) {
            File::makeDirectory(public_path() . $this->storage_upload_path, 0777, true, true);
        }


        if ($uploadType == "item" || $uploadType == "preview" || $uploadType == 'background' || $uploadType == "preview_image" || $uploadType == "chatApi" || $uploadType == 'itemMulti') {


            $conds = [
                $this->coreImageImgTypeCol => 'backend-water-mask-image',
            ];
            $image = $this->getImage($conds);
            if ($image) {
                $file_exist = File::exists(public_path() . $this->storage_upload_path  . $image->img_path);
                if ($file_exist) {

                    if ($backend_setting->is_watermask == 1) {
                        // dd("here");

                        $org_image = $this->applyWatermask($backend_setting, $org_image, $image);
                    }
                }
            }
        }

        // $canvas_image->save(public_path() . $this->storage_upload_path . $fileName, 50);


        $org_image->save(public_path() . $this->storage_upload_path . $fileName, 50);
        return $org_image;
    }

    // save image From URL
    public function saveImage($file, $fileName, $uploadType = null)
    {

        $this->uploadOrgImage($file, $fileName, $uploadType);
        $this->createThumbnail1x($file, $fileName, $uploadType);
        $this->createThumbnail2x($file, $fileName, $uploadType);
        $this->createThumbnail3x($file, $fileName, $uploadType);
    }

    // save image From URL
    public function saveImageFromUrl($fileName, $url)
    {
        // dd($url);
        //for uploads

        $data = file_get_contents($url);

        if (!File::isDirectory(public_path() . $this->storage_upload_path)) {
            File::makeDirectory(public_path() . $this->storage_upload_path, 0777, true, true);
        }

        file_put_contents(public_path() . $this->storage_upload_path . $fileName, $data);

        $this->createThumbnail1x($data, $fileName);
        $this->createThumbnail2x($data, $fileName);
        $this->createThumbnail3x($data, $fileName);
    }


    // save thumb 1x
    public function createThumbnail1x($file, $fileName, $uploadType = null)
    {
        $backend_setting = BackendSetting::first();




        if ($uploadType == 'preview') {
            $conds_background = [
                $this->coreImageImgTypeCol => 'water-mask-background',
            ];
            $image_background = $this->getImage($conds_background);
            if ($image_background) {
                $thumbnail1x = Image::make(public_path() . $this->storage_upload_path . $image_background->img_path);
            }
        } else {

            $thumbnail1x = Image::make($file);
        }

        if (!File::isDirectory(public_path() . $this->storage_thumb1x_path)) {
            File::makeDirectory(public_path() . $this->storage_thumb1x_path, 0777, true, true);
        }
        // $thumbnail1x->resize(200, null, function ($constraint) {
        //     $constraint->aspectRatio();
        //     $constraint->upsize();
        // });

        if ($thumbnail1x->width() > $thumbnail1x->height()) {
            $image_type = 'Landscape';


            $thumbnail1x = $thumbnail1x->resize($backend_setting->landscape_thumb_width, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
        }

        if ($thumbnail1x->width() == $thumbnail1x->height()) {
            $image_type = 'Square';

            // $thumbnail1x=$thumbnail1x->resize($backend_setting->square_height,$thumbnail1x->height());
            $thumbnail1x = $thumbnail1x->resize($backend_setting->square_thumb_height, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
        }

        if ($thumbnail1x->height() > $thumbnail1x->width()) {
            $image_type = 'Portrait';


            $thumbnail1x = $thumbnail1x->resize(null, $backend_setting->potrait_thumb_height, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
        }




        if ($uploadType == "item" || $uploadType == "preview" || $uploadType == 'background' || $uploadType == "chatApi" || $uploadType == 'itemMulti') {


            // dd($thumbnail1x);
            $conds = [
                $this->coreImageImgTypeCol => 'backend-water-mask-image',
            ];
            $image = $this->getImage($conds);
            if ($image) {
                $file_exist = File::exists(public_path() . $this->storage_upload_path  . $image->img_path);
                if ($file_exist) {
                    if ($backend_setting->is_watermask == 1) {
                        $thumbnail1x = $this->applyWatermask($backend_setting, $thumbnail1x, $image);
                    }
                }
            }
        }



        $thumbnail1x->save(public_path() . $this->storage_thumb1x_path . $fileName);
    }

    // save thumb 2x
    public function createThumbnail2x($file, $fileName, $uploadType = null)
    {

        $backend_setting = BackendSetting::first();
        if ($uploadType == 'preview') {
            $conds_background = [
                $this->coreImageImgTypeCol => 'water-mask-background',
            ];
            $image_background = $this->getImage($conds_background);
            if ($image_background) {
                $thumbnail2x = Image::make(public_path() . $this->storage_upload_path . $image_background->img_path);
            }
        } else {
            $thumbnail2x = Image::make($file);
        }

        if (!File::isDirectory(public_path() . $this->storage_thumb2x_path)) {
            File::makeDirectory(public_path() . $this->storage_thumb2x_path, 0777, true, true);
        }
        // $thumbnail2x->resize(400, null, function ($constraint) {
        //     $constraint->aspectRatio();
        //     $constraint->upsize();
        // });
        if ($thumbnail2x->width() > $thumbnail2x->height()) {
            $image_type = 'Landscape';


            $thumbnail2x = $thumbnail2x->resize($backend_setting->landscape_thumb2x_width, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
        }

        if ($thumbnail2x->width() == $thumbnail2x->height()) {
            $image_type = 'Square';


            $thumbnail2x = $thumbnail2x->resize($backend_setting->square_thumb2x_height, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
        }

        if ($thumbnail2x->height() > $thumbnail2x->width()) {
            $image_type = 'Portrait';
            // dd($backend_setting->potrait_thumb2x_height);


            $thumbnail2x = $thumbnail2x->resize(null, $backend_setting->potrait_thumb2x_height, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
        }


        if ($uploadType == "item" || $uploadType == "preview" || $uploadType == 'background' || $uploadType == "chatApi" || $uploadType == 'itemMulti') {


            $conds = [
                $this->coreImageImgTypeCol => 'backend-water-mask-image',
            ];
            $image = $this->getImage($conds);
            if ($image) {
                $file_exist = File::exists(public_path() . $this->storage_upload_path  . $image->img_path);
                if ($file_exist) {
                    if ($backend_setting->is_watermask == 1) {


                        $thumbnail2x = $this->applyWatermask($backend_setting, $thumbnail2x, $image);
                    }
                }
            }
        }
        $thumbnail2x->save(public_path() . $this->storage_thumb2x_path . $fileName);
    }

    // save thumb 3x
    public function createThumbnail3x($file, $fileName, $uploadType = null)
    {
        // dd($file);
        $backend_setting = BackendSetting::first();
        if ($uploadType == 'preview') {
            $conds_background = [
                $this->coreImageImgTypeCol => 'water-mask-background',
            ];
            $image_background = $this->getImage($conds_background);
            if ($image_background) {
                $thumbnail3x = Image::make(public_path() . $this->storage_upload_path . $image_background->img_path);
            }
        } else {
            $thumbnail3x = Image::make($file);
        }

        if (!File::isDirectory(public_path() . $this->storage_thumb3x_path)) {
            File::makeDirectory(public_path() . $this->storage_thumb3x_path, 0777, true, true);
        }
        // $thumbnail3x->resize(600, null, function ($constraint) {
        //     $constraint->aspectRatio();
        //     $constraint->upsize();
        // });
        if ($thumbnail3x->width() > $thumbnail3x->height()) {
            $image_type = 'Landscape';


            $thumbnail3x = $thumbnail3x->resize($backend_setting->landscape_thumb3x_width, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
        }

        if ($thumbnail3x->width() == $thumbnail3x->height()) {
            $image_type = 'Square';


            $thumbnail3x = $thumbnail3x->resize($backend_setting->square_thumb3x_height, $backend_setting->square_thumb3x_height, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
        }
        if ($thumbnail3x->height() > $thumbnail3x->width()) {
            $image_type = 'Portrait';


            $thumbnail3x = $thumbnail3x->resize(null, $backend_setting->potrait_thumb3x_height, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
        }

        if ($uploadType == "item" || $uploadType == "preview" || $uploadType == 'background' || $uploadType == "chatApi" || $uploadType == 'itemMulti') {

            $conds = [
                $this->coreImageImgTypeCol => 'backend-water-mask-image',
            ];
            $image = $this->getImage($conds);
            if ($image) {
                $file_exist = File::exists(public_path() . $this->storage_upload_path  . $image->img_path);
                if ($file_exist) {
                    if ($backend_setting->is_watermask == 1) {
                        $thumbnail3x = $this->applyWatermask($backend_setting, $thumbnail3x, $image);
                    }
                }
            }
        }
        $thumbnail3x->save(public_path() . $this->storage_thumb3x_path . $fileName);
    }

    public function applyWatermask($backend_setting, $image, $watermarkImage)
    {

        $watermark = Image::make(public_path() . $this->storage_upload_path . $watermarkImage->img_path);
        $watermark = $watermark->resize($backend_setting->watermask_image_size / 10, $backend_setting->watermask_image_size / 10, function ($constraint) {
            $constraint->aspectRatio();
        })
            ->opacity($backend_setting->opacity)
            ->rotate($backend_setting->watermask_angle);
        if ($backend_setting->position == 'bottom-right') {
            $image = $image->insert($watermark,  'bottom-right', $backend_setting->padding, $backend_setting->padding);
        }
        if ($backend_setting->position == 'bottom') {
            $image = $image->insert($watermark,  'bottom', 0, $backend_setting->padding);
        }
        if ($backend_setting->position == 'bottom-left') {
            $image = $image->insert($watermark,  'bottom-left', $backend_setting->padding, $backend_setting->padding);
        }
        if ($backend_setting->position == 'top-right') {
            $image = $image->insert($watermark,  'top-right', $backend_setting->padding, $backend_setting->padding);
        }
        if ($backend_setting->position == 'top') {
            $image = $image->insert($watermark,  'top', 0, $backend_setting->padding);
        }
        if ($backend_setting->position == 'top-left') {
            $image = $image->insert($watermark,  'top-left', $backend_setting->padding, $backend_setting->padding);
        }
        if ($backend_setting->position == 'left') {
            $image = $image->insert($watermark,  'left', $backend_setting->padding, $backend_setting->padding);
        }
        if ($backend_setting->position == 'center') {
            $image = $image->insert($watermark,  'center', 0, 0);
        }
        if ($backend_setting->position == 'right') {
            $image = $image->insert($watermark,  'right', $backend_setting->padding, $backend_setting->padding);
        }

        return $image;
    }

    // delete images
    public function clearUnuseImage()
    {
        //mauual change thumbnail,thumbnail2x,thumbnail3x,uploads
        $files = Storage::files('/storage/PSX_MPC/uploads/');
        $filenames = [];
        foreach ($files as $file) {
            array_push($filenames, explode('/', $file)[3]);
        }

        // $coreImages = DB::table('psx_core_images')->pluck('img_path')->toArray();
        $userImages = DB::table('users')->pluck('user_cover_photo')->toArray();

        $allCoreImages = DB::table('psx_core_images')->get();

        $coreImages =  $allCoreImages->pluck('img_path')->toArray();

        $backendIds = DB::table('psx_backend_settings')->pluck('id')->toArray();
        $blogIds = DB::table('psx_blogs')->pluck('id')->toArray();
        $categoryIds = DB::table('psx_categories')->pluck('id')->toArray();
        $chatIds = DB::table('psx_chat_histories')->pluck('id')->toArray();
        $landingIds = DB::table('psx_landing_pages')->pluck('id')->toArray();
        $itemIds = DB::table('psx_items')->pluck('id')->toArray();
        $notiIds = DB::table('psx_push_notification_messages')->pluck('id')->toArray();
        $subcatgoryIds = DB::table('psx_subcategories')->pluck('id')->toArray();
        $frontendIds = DB::table('psx_frontend_settings')->pluck('id')->toArray();
        // $userImages = DB::table('users')->pluck('user_cover_photo')->toArray();

        // $q->where($this->coreImageImgTypeCol,'like',"%item%");

        $useFiles = [];
        foreach ($filenames as $filename) {
            if (!in_array($filename, $coreImages) && !in_array($filename, $userImages)) {
                //delete images which doesn't exist in image table
                $this->deleteImage($filename);
            } else {
                //delete by checking ids
                foreach ($allCoreImages as $img) {
                    if ($img->img_path == $filename) {
                        if ($img->img_type == 'backend-logo' || $img->img_type == 'backend-meta-image' || $img->img_type == 'backend-water-mask-image') {
                            if (!in_array($img->img_parent_id, $backendIds)) {
                                $this->deleteImage($filename);
                            }
                        } else if ($img->img_type == 'blog') {
                            if (!in_array($img->img_parent_id, $blogIds)) {
                                $this->deleteImage($filename);
                            }
                        } else if ($img->img_type == 'category-cover' || $img->img_type == 'category-icon') {
                            if (!in_array($img->img_parent_id, $categoryIds)) {
                                $this->deleteImage($filename);
                            }
                        } else if ($img->img_type == 'chat') {
                            if (!in_array($img->img_parent_id, $chatIds)) {
                                $this->deleteImage($filename);
                            }
                        } else if ($img->img_type == 'landing-cover' || $img->img_type == 'landing-logo') {
                            if (!in_array($img->img_parent_id, $landingIds)) {
                                $this->deleteImage($filename);
                            }
                        } else if ($img->img_type == 'push_notification_message') {
                            if (!in_array($img->img_parent_id, $notiIds)) {
                                $this->deleteImage($filename);
                            }
                        } else if ($img->img_type == 'subCategory-cover' || $img->img_type == 'subCategory-icon') {
                            if (!in_array($img->img_parent_id, $subcatgoryIds)) {
                                $this->deleteImage($filename);
                            }
                        } else if ($img->img_type == 'item' || $img->img_type == 'item-video' || $img->img_type == 'item-video-icon' || $img->img_type == 'item-video') {
                            if (!in_array($img->img_parent_id, $itemIds)) {
                                $this->deleteImage($filename);
                            }
                        } else if ($img->img_type == 'fav-icon' || $img->img_type == 'frontend-banner' || $img->img_type == 'frontend-logo') {
                            if (!in_array($img->img_parent_id, $frontendIds)) {
                                $this->deleteImage($filename);
                            }
                        } else {
                            array_push($useFiles, $filename);
                        }
                    }
                }
            }
        }
        dd($useFiles);
    }
    public function deleteImage($img_path)
    {
        // dd($img_path);

        Storage::delete($this->upload_path . $img_path);
        Storage::delete($this->storage_upload_path . $img_path);
        Storage::delete($this->storage_thumb1x_path . $img_path);
        Storage::delete($this->storage_thumb2x_path . $img_path);
        Storage::delete($this->storage_thumb3x_path . $img_path);
        Storage::delete($this->thumb1x_path . $img_path);
        Storage::delete($this->thumb2x_path . $img_path);
        Storage::delete($this->thumb3x_path . $img_path);
    }
    public function deleteEditorImage($img_path)
    {
        // dd($img_path);
        Storage::delete($this->upload_path . $img_path);
        Storage::delete($this->storage_upload_path . $img_path);
        Storage::delete($this->thumb1x_path . $img_path);
        Storage::delete($this->thumb2x_path . $img_path);
        Storage::delete($this->thumb3x_path . $img_path);
    }

    // insert video
    public function insertVideo($file, $data, $video = false)
    {
        $isCreate = $video == false || empty($video) ? true : false;

        // save original video to 'uploads'
        $fileName = uniqid() . "_" . $file->getClientOriginalName();
        $file->move(public_path() . $this->storage_upload_path, $fileName);

        if ($isCreate) {
            $video = new CoreImage();
        }

        $video->img_parent_id = $data[$this->coreImageImgParentIdCol];
        $video->img_type = $data[$this->coreImageImgTypeCol];
        $video->img_path = $fileName;

        if (array_key_exists("img_desc", $data)) {
            $video->img_desc = $data['img_desc'];
        }

        if ($isCreate) {
            $video->added_user_id = Auth::user()->id;
            $video->save();
        } else {
            $video->updated_user_id = Auth::user()->id;
            $video->update();
        }
    }

    // update video
    public function updateVideo($request, $id)
    {
        // save video
        $conds = [
            $this->coreImageIdCol => $id,
        ];
        $video = $this->getImage($conds);
        $this->deleteImage($video->img_path);

        if ($request->file('video')) {
            $file = $request->file('video');
            $fileName = newFileName($file);

            $video->img_path = $fileName;
            $file->move(public_path() . $this->storage_upload_path, $fileName);

            // $org_image = $this->uploadOrgImage($file, $fileName);
        }

        if (isset($request->updated_user_id) && !empty($request->updated_user_id)) {
            $video->updated_user_id = $request->updated_user_id;
        } else {
            $video->updated_user_id = Auth::user()->id;
        }

        $video->update();
    }

    // delete video
    public function deleteVideo($img_path)
    {
        Storage::delete($this->upload_path . $img_path);
    }

    public function getImage($conds)
    {
        $image = CoreImage::where($conds)->orderBy('id', 'desc')->first();
        return $image;
    }

    public function getImages($imgParentId = null, $imgType = null, $limit = null, $offset = null, $notImgTypes = null, $conds = null)
    {
        $images = CoreImage::when($imgParentId, function ($q, $imgParentId) {
            $q->where($this->coreImageImgParentIdCol, $imgParentId);
        })
            ->when($imgType, function ($q, $imgType) {
                if ($imgType == 'item_related') {
                    $q->where($this->coreImageImgTypeCol, 'like', "%item%");
                } else {
                    $q->where($this->coreImageImgTypeCol, $imgType);
                }
            })
            ->when($notImgTypes, function ($q, $notImgTypes) {
                $q->whereNotIn($this->coreImageImgTypeCol, $notImgTypes);
            })
            ->when($conds, function ($q, $conds) {
                if (isset($conds['order_by'])) {
                    $q->orderBy($conds['order_by'], $conds['order_type']);
                }
            })
            ->when($limit, function ($query, $limit) {
                $query->limit($limit);
            })
            ->when($offset, function ($query, $offset) {
                $query->offset($offset);
            })->latest()->get();
        return $images;
    }

    // using image service as one function (from form request and Inertia edit request)

    public function update($request = null, $id = null, $file = null, $data = null, $image = false, $uploadType = null)
    {


        // check create or edit base on parameter
        $isCreate = $image == false || empty($image) ? true : false;

        //check file from form request
        if ($file) {
            $file = $file;
        } else {
            //request from inertia
            $file = $request->file('image');
            if ($file) {
                $fileName = newFileName($file);
                $extension = $file->getClientOriginalExtension();
            }
            $isCreate = false;
        }
        //check to watermask current item,preview,background,chat,preview_image(form request)
        if ($uploadType) {
            $uploadType = $uploadType;
        } else {
            // check upload type watermask (inertia request)
            $uploadType = $request->uploadType ?? null;
        }


        //check from form data request
        if ($data && $file) {
            if ($uploadType == 'preview_image' || $uploadType == 'itemMulti') {
                //preview_image upload type only work for under scenario.
                //when users change setting from backend but he dont want to reupload image


                if ($uploadType == 'preview_image') {
                    $fileName = $file->basename;
                    $fileName = 'preview_' . $fileName;
                } else {
                    $fileName = $file->basename;


                    // $fileName = newFileName($file);
                }
                $extension = $file->extension;
            } else {
                //upload new image from formdata
                $extension = $file->getClientOriginalExtension();
                $fileName = newFileName($file);
            }


            if ($uploadType == 'background') {
                //background image upload type only work for when users change background of preview image
                //this case need to save 2 time in backend 1 for background and 1 for wastermask background
                $fileName = newFileName($file);
                $fileName = 'preview_' . $fileName;
            }
        }

        // validation start



        if ($request && $request->uploadType == 'preview') {
            //if preview is come from inertia request
            //this will later will save
            $uploadType = null;
        }


        if ($extension !== 'ico' && $request) {
            $request->validate([
                'image' => 'nullable|sometimes|image'
            ]);
        }
        // validation end

        if ($isCreate) {

            $image = new CoreImage();
        }

        if (empty($image) && $id) {
            //delete image only from inertia request
            $conds = [
                $this->coreImageIdCol => $id,
            ];
            $image = $this->getImage($conds);
            $this->deleteImage($image->img_path);
        }



        //save image
        if ($request && $request->file('image') || $file) {
            $image->img_path = $fileName;

            if ($extension == 'ico') {

                if (!File::isDirectory(public_path() . $this->storage_upload_path)) {
                    File::makeDirectory(public_path() . $this->storage_upload_path, 0777, true, true);
                }

                if (!File::isDirectory(public_path() . $this->storage_thumb1x_path)) {
                    File::makeDirectory(public_path() . $this->storage_thumb1x_path, 0777, true, true);
                }
                if (!File::isDirectory(public_path() . $this->storage_thumb2x_path)) {
                    File::makeDirectory(public_path() . $this->storage_thumb2x_path, 0777, true, true);
                }
                if (!File::isDirectory(public_path() . $this->storage_thumb3x_path)) {
                    File::makeDirectory(public_path() . $this->storage_thumb3x_path, 0777, true, true);
                }

                Storage::putFileAs($this->storage_upload_path, $file, $fileName);
                Storage::putFileAs($this->storage_thumb1x_path, $file, $fileName);
                Storage::putFileAs($this->storage_thumb2x_path, $file, $fileName);
                Storage::putFileAs($this->storage_thumb3x_path, $file, $fileName);
                // $org_image = getimagesize($file);

                // $image->img_width = $org_image[0];
                // $image->img_height = $org_image[1];
            } else {
                $org_image = $this->uploadOrgImage($file, $fileName, $uploadType);
                $this->createThumbnail3x($file, $fileName, $uploadType);
                $this->createThumbnail2x($file, $fileName, $uploadType);
                $this->createThumbnail1x($file, $fileName, $uploadType);

                $image->img_width = $org_image->width();
                $image->img_height = $org_image->height();
            }
        }


        if (isset($request->ordering) && !empty($request->ordering)) {
            $image->ordering = $request->ordering;
        }

        if (isset($request->img_desc) && !empty($request->img_desc)) {
            $image->img_desc = $request->img_desc;
        }

        if (isset($request->img_path) && !empty($request->img_path)) {
            $image->img_path = $request->img_path;
        }

        if (isset($request->updated_user_id) && !empty($request->updated_user_id)) {
            $image->updated_user_id = $request->updated_user_id;
        } else {
            $image->updated_user_id = Auth::user()->id;
        }
        if ($data) {
            $image->img_parent_id = $data['img_parent_id'];
            $image->img_type = $data['img_type'];
            $image->img_path = $fileName;
            if (array_key_exists("img_desc", $data)) {
                $image->img_desc = $data['img_desc'];
            }


            $image->ordering = isset($data['ordering']) ? $data['ordering'] : 1;
            if (array_key_exists("img_desc", $data)) {
                $image->img_desc = $data['img_desc'];
            }
        }

        if ($isCreate) {
            $image->added_user_id = Auth::user()->id;
            $image->save();
        } else {
            $image->updated_user_id = Auth::user()->id;
            $image->update();
        }

        //save image from inertia request which is only preview
        if ($request && $request->uploadType == 'preview' && $extension !== 'ico') {

            $uploadType = 'preview';
            $conds = [
                $this->coreImageImgTypeCol => 'water-mask-background-original',
            ];
            $image = $this->getImage($conds);
            if (!empty($image)) {
                $this->deleteImage($image->img_path);
            }
            if ($request->file('image')) {
                $file = $request->file('image');
                $fileName = newFileName($file);
                $fileName = 'preview_' . $fileName;
                $image->img_path = $fileName;
                $extension = $file->getClientOriginalExtension();

                if ($extension == 'ico') {
                    if (!File::isDirectory(public_path() . $this->storage_upload_path)) {
                        File::makeDirectory(public_path() . $this->storage_upload_path, 0777, true, true);
                    }
                    if (!File::isDirector(public_path() . $this->storage_thumb1x_path)) {
                        File::makeDirectory(public_path() . $this->storage_thumb1x_path, 0777, true, true);
                    }
                    if (!File::isDirectory(public_path() . $this->storage_thumb2x_path)) {
                        File::makeDirectory(public_path() . $this->storage_thumb2x_path, 0777, true, true);
                    }
                    if (!File::isDirectory(public_path() . $this->storage_thumb3x_path)) {
                        File::makeDirectory(public_path() . $this->storage_thumb3x_path, 0777, true, true);
                    }
                    Storage::putFileAs($this->storage_upload_path, $file, $fileName);
                    Storage::putFileAs($this->storage_thumb1x_path, $file, $fileName);
                    Storage::putFileAs($this->storage_thumb2x_path, $file, $fileName);
                    Storage::putFileAs($this->storage_thumb3x_path, $file, $fileName);
                    $org_image = getimagesize($file);

                    $image->img_width = $org_image[0];
                    $image->img_height = $org_image[1];
                } else {
                    $org_image = $this->uploadOrgImage($file, $fileName, $uploadType);
                    $this->createThumbnail1x($file, $fileName, $uploadType);
                    $this->createThumbnail2x($file, $fileName, $uploadType);
                    $this->createThumbnail3x($file, $fileName, $uploadType);

                    $image->img_width = $org_image->width();
                    $image->img_height = $org_image->height();
                }
            }

            if (isset($request->ordering) && !empty($request->ordering)) {
                $image->ordering = $request->ordering;
            }

            if (isset($request->img_desc) && !empty($request->img_desc)) {
                $image->img_desc = $request->img_desc;
            }

            if (isset($request->img_path) && !empty($request->img_path)) {
                $image->img_path = $request->img_path;
            }

            if (isset($request->updated_user_id) && !empty($request->updated_user_id)) {
                $image->updated_user_id = $request->updated_user_id;
            } else {
                $image->updated_user_id = Auth::user()->id;
            }

            $image->update();
        }


        if(str_contains($image->img_type, 'item')){
            PsCache::clear(ItemCache::BASE);
        }

        if(str_contains($image->img_type, 'category')){
            PsCache::clear(CategoryCache::BASE);
        }

        if(str_contains($image->img_type, 'subCategory')){
            PsCache::clear(SubcategoryCache::BASE);
        }

        return $fileName;
    }

    public function editorUpdateOrCreateImage($request, $fileKey, $imgParentId, $imgType)
    {
        if ($request->file($fileKey)) {
            $conds[$this->coreImageImgParentIdCol] = $imgParentId;
            $conds[$this->coreImageImgTypeCol] = $imgType;


            $file = $request->file($fileKey);
            $data[$this->coreImageImgParentIdCol] = $imgParentId;
            $data[$this->coreImageImgTypeCol] = $imgType;


            $url = $this->insertEditorImage($file, $data);

            return $url;
        }
    }
    public function insertEditorImage($file, $data, $image = false)
    {
        try {

            $isCreate = true;

            $fileName = newFileName($file);
            $extension = $file->getClientOriginalExtension();


            if ($extension == 'ico') {
                Storage::putFileAs($this->storage_upload_path, $file, $fileName);
                Storage::putFileAs($this->storage_thumb1x_path, $file, $fileName);
                Storage::putFileAs($this->storage_thumb2x_path, $file, $fileName);
                Storage::putFileAs($this->storage_thumb3x_path, $file, $fileName);
                $org_image = getimagesize($file);

                $width = $org_image[0];
                $height = $org_image[1];
            } else {
                // save original image to 'uploads'
                $org_image = $this->uploadOrgImage($file, $fileName);

                // save thumb 1x image to 'thumbnail'

                $width = $org_image->width();
                $height = $org_image->height();
            }


            $image = new CoreImage();


            $image->img_parent_id = $data['img_parent_id'];
            $image->img_type = $data['img_type'];
            $image->img_path = $fileName;
            $image->img_width = $width;
            $image->img_height = $height;
            $image->ordering = isset($data['ordering']) ? $data['ordering'] : 1;


            if (array_key_exists("img_desc", $data)) {
                $image->img_desc = $data['img_desc'];
            }


            $image->added_user_id = Auth::user()->id;
            $image->save();
            $folder_path = 'storage/' . $this->getFolderImagePath() . '/uploads';
            $url = asset($folder_path) . '/' . $image->img_path;
            return $url;
        } catch (\Throwable $e) {
            DB::rollBack();
            return ['error' => $e->getMessage()];
        }
    }

    public function updateOrdering($request, $id)
    {
        // save image
        $conds = [
            $this->coreImageIdCol => $id,
        ];
        $image = $this->getImage($conds);

        if (isset($request->ordering) && !empty($request->ordering)) {
            $image->ordering = $request->ordering;
        }

        if (isset($request->updated_user_id) && !empty($request->updated_user_id)) {
            $image->updated_user_id = $request->updated_user_id;
        } else {
            $image->updated_user_id = Auth::user()->id;
        }

        $image->update();
    }

    public function destroy($id)
    {
        $conds = [
            $this->coreImageIdCol => $id,
        ];
        $image = $this->getImage($conds);
        $this->deleteImage($image->img_path);
        $image->delete();

        PsCache::clear(CategoryCache::BASE);
    }
    public function destroyEditor($id)
    {
        $conds = [
            $this->coreImageIdCol => $id,
        ];
        $image = $this->getImage($conds);
        $this->deleteEditorImage($image->img_path);
        $image->delete();
    }

    public function deleteImagesFromBoth($images)
    {
        if (count($images) > 0) {
            $imageIds = $images->pluck($this->coreImageIdCol);
            CoreImage::destroy($imageIds);
            foreach ($images as $image) {
                // delete image from storage folder
                $this->deleteImage($image->img_path);
                //                $image->delete();
            }
        }
    }

    public function deleteMultiImage($img_parent_id, $img_type, $img_path)
    {
        // dd($img_parent_id);
        $this->coreImageImgTypeCol = CoreImage::imgType;
        $this->coreImageImgParentIdCol = CoreImage::imgParentId;
        $conds = [
            $this->coreImageImgTypeCol => $img_type,
            $this->coreImageImgParentIdCol => $img_parent_id,
            'img_path' => $img_path,
        ];
        $image = $this->getImage($conds);
        // $image = CoreImage::where($this->coreImageImgParentIdCol,$img_parent_id)->get();
        // dd($image);
        $this->deleteImage($image->img_path);
        $image->delete();
        return $img_path;
    }

    public function deleteAllBy($conds = null)
    {
        DB::beginTransaction();
        try {
            CoreImage::when($conds, function ($query, $conds) {
                $query->where($conds);
            })->delete();

            DB::commit();
            return true;
        } catch (\Throwable $e) {
            DB::rollBack();
            return ['error' => $e->getMessage()];
        }
    }

    /**
     * @deprecated
     */
    public function updateOrCreateImage($request, $fileKey, $imgParentId, $imgType)
    {
        if ($request->file($fileKey)) {

            $conds[CoreImage::imgParentId] = $imgParentId;
            $conds[CoreImage::imgType] = $imgType;
            $image = $this->getImage($conds);

            $file = $request->file($fileKey);
            $data[CoreImage::imgParentId] = $imgParentId;
            $data[CoreImage::imgType] = $imgType;

            // if image, delete existing file and update
            if (!empty($image)) {
                // delete image from storage folder
                $this->deleteImage($image->img_path);
                $this->update(null, null, $file, $data, $image);
            } else {
                $this->update(null, null, $file, $data);
            }
        }
    }
}
