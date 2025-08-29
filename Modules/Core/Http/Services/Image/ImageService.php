<?php

namespace Modules\Core\Http\Services\Image;

use InvalidArgumentException;
use App\Http\Services\PsService;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Entities\CoreImage;
use Modules\Core\Constants\Constants;
use Carbon\Exceptions\InvalidFormatException;
use App\Http\Contracts\Image\ImageServiceInterface;
use Intervention\Image\Exception\NotFoundException;
use App\Http\Contracts\Image\ImageProcessingServiceInterface;
use Throwable;
use Illuminate\Support\Facades\File;


class ImageService extends PsService implements ImageServiceInterface
{
    private $storage_upload_path = '/storage/'.Constants::folderPath.'/uploads/';

    public function __construct(
        protected ImageProcessingServiceInterface $imageProcessingService) {}

    public function save($file, $imgData, $extension = null)
    {

        // Validate Params
        $this->validateParams($file, $imgData);

        // Validate File
        if(empty($extension)){
            $extension = $file->getClientOriginalExtension();
        }

        $this->validateExtension($extension);

        // Get File Info
        $fileName = newFileName($file, null, $extension);

        // Prepare and Clear Image Exist
        $image = new CoreImage();

        // Save File
        $imgData = $this->saveFile($file, $fileName, $extension, $imgData);

        // Save Image
        $this->saveOrUpdateImgObj($image, $imgData, $fileName);

        return $fileName;

    }

    public function update($id, $file, $imgData = null)
    {
        try
        {

            // Validate Params
            if (empty($file)) {
                return '';
            }
    
            // Validate File
            $extension = $file->getClientOriginalExtension();
            $this->validateExtension($extension);
    
            // Get File Info
            $fileName = newFileName($file);
    
            // Get Image to update
            $image = $this->getImageById($id);
    
            // Delete Old Images
            $this->delete($image->img_path);
    
            // Save Updated Image
            $imgData = $this->saveFile($file, $fileName, $extension, $imgData);
    
            // Save Image
            $this->saveOrUpdateImgObj($image, $imgData, $fileName);
    
            return $fileName;
        }catch(\Throwable $e){
            dd($e->getMessage());
        }

    }

    public function deleteAll($imgParentId, $imgType)
    {
        $images = $this->getAll($imgParentId, $imgType);

        foreach ($images as $image) {
            $this->imageProcessingService->deleteImageFile($image->img_path);
            $image->delete();
        }
    }

    public function delete($img_path)
    {
        $this->imageProcessingService->deleteImageFile($img_path);
    }

    public function get($conds)
    {
        return CoreImage::where($conds)->orderBy('id', 'desc')->first();
    }

    public function getAll($imgParentId = null, $imgType = null, $limit = null, $offset = null, $notImgTypes = null, $conds = null)
    {
        return CoreImage::when($imgParentId, function ($q, $imgParentId) {
            $q->where(CoreImage::imgParentId, $imgParentId);
        })
            ->when($imgType, function ($q, $imgType) {
                if ($imgType === 'item_related') {
                    $q->where(CoreImage::imgType, 'like', '%item%');
                } else if($imgType === Constants::categoryCoverImgType) {
                    $q->whereIn(CoreImage::imgType, [Constants::categoryCoverImgType, Constants::categoryIconImgType]);
                } else if($imgType === Constants::subcategoryCoverImgType) {
                    $q->whereIn(CoreImage::imgType, [Constants::subcategoryCoverImgType, Constants::subcategoryIconImgType]);
                } else {
                    $q->where(CoreImage::imgType, $imgType);
                }

            })
            ->when($notImgTypes, function ($q, $notImgTypes) {
                $q->whereNotIn(CoreImage::imgType, $notImgTypes);
            })
            ->when($conds, function ($q, $conds) {
                // if (isset($conds['order_by'])) {
                $q->orderBy($conds['order_by'], $conds['order_type']);
                // }
            })
            ->when($limit, function ($query, $limit) {
                $query->limit($limit);
            })
            ->when($offset, function ($query, $offset) {
                $query->offset($offset);
            })->latest()->get();

    }

    /**
     * @deprecated
     * Use VideoService::uploadVideo() instead
     */
    public function saveVideo($file, $data)
    {
        try {
             // save video file
            $fileName = uniqid() . "_" . $file->getClientOriginalName();
            $file->move(public_path() . $this->storage_upload_path, $fileName);

            // save video data at core_images table
            $video = new CoreImage();
            $video->fill($data);
            $video->img_path = $fileName;
            $video->added_user_id = Auth::user()->id;
            $video->save();

        } catch(Throwable $e) {
            throw $e;
        }

    }

    /**
     * @deprecated
     * Use VideoService::uploadVideo() instead
     */
    public function updateVideo($id, $file, $data)
    {

        try {

            $video = $this->get(['id' => $id]);
            if(!empty($video)){
                $this->delete($video->img_path);
            }

            // save video file
            $fileName = uniqid() . "_" . $file->getClientOriginalName();
            $file->move(public_path() . $this->storage_upload_path, $fileName);

            // update video data at core_images table
            $video->fill($data);
            $video->img_path = $fileName;
            $video->updated_user_id = Auth::user()->id;
            $video->update();

       } catch(Throwable $e) {
           throw $e;
       }

    }

    ////////////////////////////////////////////////////////////////////
    /// Private Functions
    ////////////////////////////////////////////////////////////////////

    private function getImageById($id)
    {
        $image = $this->get([CoreImage::id => $id]);
        if ($image === null) {
            throw new NotFoundException('Image not found to edit.');
        }

        return $image;
    }

    private function saveFile($file, $fileName, $extension, $imgData)
    {
        $imageSizeInfo = $this->createImages($file, $fileName, $extension, $imgData['img_type']);
        $this->updateImgDataWithSizeInfo($imgData, $imageSizeInfo);

        return $imgData;
    }

    private function validateExtension($extension)
    {
        // Check the file extension
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'ico'];

        if (! in_array($extension, $allowedExtensions)) {
            throw new InvalidFormatException('Invalid Format Extension.');
        }

    }

    private function validateParams($file = null, $imgData = null)
    {
        if ($file === null || $imgData === null) {
            throw new InvalidArgumentException('Not allow empty params. : Image Validation');
        }

        $requiredKeys = ['img_parent_id', 'img_type'];
        foreach ($requiredKeys as $key) {
            if (empty($imgData[$key])) {
                throw new InvalidArgumentException("{$key} can't be empty.");
            }
        }

    }

    private function createImages($file, $fileName, $extension, $uploadType)
    {

        // To store original image width and height
        if ($extension === 'ico') {
            // For the ico format, we will not create thumbnails
            $this->imageProcessingService->createIcoFile(file: $file, fileName: $fileName);

            return $this->getImageWidthAndHeight($file);
        }

        // Will save 1x,2x,3x thumbnails and original image
        $resolutions = ['1x', '2x', '3x', 'original'];
        $rtnImages = $this->imageProcessingService->createImageFiles(file : $file,
            fileName : $fileName,
            imageType : $uploadType,
            resolutions : $resolutions);

        return $this->getImageWidthAndHeight($rtnImages);

    }

    private function getImageWidthAndHeight($rtnImages = [])
    {
        if (empty($rtnImages)) {
            return null;
        }

        return [
            'img_width' => $rtnImages[count($rtnImages) - 1]->width(),
            'img_height' => $rtnImages[count($rtnImages) - 1]->height(),
        ];

    }

    private function prepareImageData($image, $fileName)
    {
        return [
            'img_parent_id' => $image->img_parent_id ?? 0,
            'img_type' => $image->img_type ?? '',
            'img_width' => $image->img_width ?? 0,
            'img_height' => $image->img_height ?? 0,
            'ordering' => $image->ordering ?? 1,
            'img_path' => $fileName,
            'img_desc' => $image->img_desc ?? '',
        ];
    }

    private function addUserId(&$image)
    {
        if ($image->exists) {
            $image->updated_user_id = Auth::id();
        } else {
            $image->added_user_id = Auth::id();
        }
    }

    private function saveOrUpdateImgObj($image, $imgData, $fileName)
    {
        $defaults = $this->prepareImageData($image, $fileName);

        $image->fill(array_merge($defaults, array_intersect_key($imgData, $defaults)));
        $this->addUserId($image);

        $image->save();
    }

    private function updateImgDataWithSizeInfo(&$imgData, $imageSizeInfo)
    {
        $imgData['img_width'] = $imageSizeInfo['img_width'];
        $imgData['img_height'] = $imageSizeInfo['img_height'];
    }
}
