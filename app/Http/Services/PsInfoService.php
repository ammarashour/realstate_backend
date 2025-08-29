<?php

namespace App\Http\Services;

use App\Http\Contracts\Core\PsInfoServiceInterface;
use App\Http\Contracts\Utilities\CustomFieldServiceInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Item;
use Modules\Core\Entities\ItemInfo;

class PsInfoService extends PsService implements PsInfoServiceInterface
{

    public function __construct(
        protected CustomFieldServiceInterface $customFieldServiceInterface,
    )
    {}

    public function save($code, $customFieldValues = [], $parentId, $relationClass, $parentIdFieldName)
    {

        $notEmtpyCoreKeysIds = [];

        // for save not emtpy custom fields
        foreach ($customFieldValues as $coreKeysId => $value) {
            /**
             * @todo if MB & FE fixed for sending data format, to change below code start
             */
            if (is_array($value)){
                $coreKeysId = $value['core_keys_id'];
                $value = $value['value'];
            }
            // end

            array_push($notEmtpyCoreKeysIds, $coreKeysId);
            $this->handleToSaveCustomFieldData($coreKeysId, $relationClass, $value, $parentIdFieldName, $parentId, $code);
        }

        // for save emtpy custom fields
        $emptyCustomFieldValues = $this->prepareEmptyData($code, $notEmtpyCoreKeysIds);
        foreach ($emptyCustomFieldValues as $coreKeysId => $value) {
            $this->handleToSaveCustomFieldData($coreKeysId, $relationClass, $value, $parentIdFieldName, $parentId, $code);
        }

    }

    public function update($code, $customFieldValues = [], $parentId, $relationClass, $parentIdFieldName)
    {
        if(empty($customFieldValues)){
            return;
        }

        foreach ($customFieldValues as $coreKeysId => $value) {
            /**
             * @todo if MB & FE fixed for sending data format, to change below code start
             */
            if (is_array($value)){
                $coreKeysId = $value['core_keys_id'];
                $value = $value['value'];
            }
            // end

            $this->handleToUpdateCustomFieldData($coreKeysId, $relationClass, $value, $parentIdFieldName, $parentId, $code);
        }
    }

    public function deleteAll($customFieldValues = []){
        foreach ($customFieldValues as $customFieldValue) {
            if($customFieldValue->ui_type_id == Constants::imageUi) {
                $this->delImageFromCustomFieldValue($customFieldValue->value);
            }
            $customFieldValue->delete();
        }
    }

    public function get($relationClass, $parentId = null, $coreKeysId = null, $parentIdFieldName = null)
    {
        return $relationClass::when($parentId, function ($q, $parentId) use ($relationClass, $parentIdFieldName){
                    $q->where($parentIdFieldName, $parentId);
                })
                ->when($coreKeysId, function ($q, $coreKeysId) use ($relationClass){
                    $q->where($relationClass::coreKeysId, $coreKeysId);
                })->first();
    }

    public function getAll($relationClass, $parentId = null, $parentIdFieldName = null, $pagPerPage = null, $noPagination = null)
    {
        $infos = $relationClass::when($parentId, function ($q, $parentId) use ($relationClass, $parentIdFieldName){
                    $q->where($parentIdFieldName, $parentId);
                });
        if ($pagPerPage) {
            return $infos->paginate($pagPerPage)->onEachSide(1)->withQueryString();
        } elseif ($noPagination) {
            return $infos->get();
        }
    }

    ////////////////////////////////////////////////////////////////////
    /// Private Functions
    ////////////////////////////////////////////////////////////////////

    //-------------------------------------------------------------------
    // Data Preparations
    //-------------------------------------------------------------------

    private function prepareRelationData($parentIdFieldName, $parentId, $value, $uiTypeId, $coreKeysId)
    {
        return [
            $parentIdFieldName => $parentId,
            "value" => $value,
            "ui_type_id" => $uiTypeId,
            "core_keys_id" => $coreKeysId,
        ];
    }

    private function validatedData($valueFromReq, $coreKeysIdFromReq, $parentId, $relationClass, $parentIdFieldName)
    {

        if($coreKeysIdFromReq == Constants::itemQty){
            if (empty($valueFromReq)) {
                return 0;
            }
            /**
             * @todo to move itemService after itemService refactor finish
             */
            if($valueFromReq > 0){
                $item = Item::where(Item::id, $parentId)->first();
                $item->is_sold_out = 0;
                $item->update();
            }
            // End
        }

        if ($valueFromReq === false) {
            return 0;
        }

        if (is_file($valueFromReq)) {
            if (str_contains($valueFromReq->getMimeType(), 'image')) {
                $oldValueObj = $this->get($relationClass, $parentId, $coreKeysIdFromReq, $parentIdFieldName);
                $this->delImageFromCustomFieldValue($oldValueObj?->value);
                return $this->checkFileInCustomFieldValue($valueFromReq);
            }
        }

        if (!is_file($valueFromReq)) {
            return $valueFromReq;
        }

        return null;
    }

    private function checkFileInCustomFieldValue($value)
    {
        $mimeType = $value->getMimeType();

        if ($this->isImage($mimeType)) {
            return $this->processImage($value);
        }

        if ($this->isVideo($mimeType)) {
            return $this->processVideo($value);
        }

        return "other";
    }

    private function prepareEmptyData($code, $notEmtpyCoreKeysIds)
    {
        $getCoreKeysIds = $this->customFieldServiceInterface
                                ->getAll(code:$code, withNoPag:Constants::yes, isDelete:Constants::unDelete)
                                ->pluck("core_keys_id")->toArray();

        $emptyValueKeys = array_diff($getCoreKeysIds, $notEmtpyCoreKeysIds);
        return array_combine($emptyValueKeys, array_fill(0, count($emptyValueKeys), null));

    }

    //-------------------------------------------------------------------
    // Database
    //-------------------------------------------------------------------

    private function saveInfoData($relationClass, $dataArr = [])
    {
        // create relation class instance
        $relationInstance = new $relationClass();

        foreach ($dataArr as $key => $value) {
            $relationInstance->$key = $value;
        }
        $relationInstance->added_user_id = Auth::id();      

        $relationInstance->save();
    }
    
    private function updateInfoData($relationClass, $dataArr = [])
    {
        if($dataArr[ItemInfo::uiTypeId] == Constants::imageUi && !$dataArr[ItemInfo::value]) {
            $this->delImageFromCustomFieldValue($relationClass->value);
        }
        $dataArr['updated_user_id'] = Auth::id();
        $relationClass->update($dataArr);
    }

    private function getUiTypeId($code, $coreKeysIdFromReq)
    {
        $customizeUiObj = $this->customFieldServiceInterface->get(code:$code, coreKeysId:$coreKeysIdFromReq);
        return $customizeUiObj->ui_type_id;
    }

    private function processImage($value)
    {
        $img = Image::make($value);

        // Change file to new name
        $file = uniqid() . "_city." . $value->getClientOriginalExtension();

        $this->saveImageWithThumbnails($value, $file);

        return $file;
    }

    //-------------------------------------------------------------------
    // Other
    //-------------------------------------------------------------------

    private function handleToSaveCustomFieldData($coreKeysId, $relationClass, $value, $parentIdFieldName, $parentId, $code)
    {

        // determine value
        $value = $this->validatedData($value, $coreKeysId, $parentId, $relationClass, $parentIdFieldName);

        // get ui type
        $uiTypeId = $this->getUiTypeId($code, $coreKeysId);

        // prepare Data
        $dataArr = $this->prepareRelationData($parentIdFieldName, $parentId, $value, $uiTypeId, $coreKeysId);

        // save
        $this->saveInfoData($relationClass, $dataArr);
    }

    private function handleToUpdateCustomFieldData($coreKeysId, $relationClass, $value, $parentIdFieldName, $parentId, $code)
    {
        // determine value
        $value = $this->validatedData($value, $coreKeysId, $parentId, $relationClass, $parentIdFieldName);

        // get ui type
        $uiTypeId = $this->getUiTypeId($code, $coreKeysId);

        // prepare Data
        $dataArr = $this->prepareRelationData($parentIdFieldName, $parentId, $value, $uiTypeId, $coreKeysId);

        // Try to find existing instance
        $relationInstance = $relationClass::where($parentIdFieldName, $parentId)
            ->where('core_keys_id', $coreKeysId)
            ->first();

        // If not found, create a new record, update if found
        if (!$relationInstance) {
            $this->saveInfoData($relationClass, $dataArr);
        } else {
            $this->updateInfoData($relationInstance, $dataArr);
        }
    }

    private function processVideo($value)
    {
        return 'This is video';
    }

    private function isImage($mimeType)
    {
        return str_contains($mimeType, 'image');
    }

    private function isVideo($mimeType)
    {
        return str_contains($mimeType, 'video');
    }

    private function saveImageWithThumbnails($value, $file)
    {
        saveImgAsOriginalThumbNail1x2x3x(
            $value,
            $file,
            public_path(Constants::storageOriginalPath),
            public_path(Constants::storageThumb1xPath),
            public_path(Constants::storageThumb2xPath),
            public_path(Constants::storageThumb3xPath)
        );
    }

    private function delImageFromCustomFieldValue($value)
    {
        // delete all photos
        Storage::delete(Constants::storageOriginalPath.$value);
        Storage::delete(Constants::storageThumb1xPath.$value);
        Storage::delete(Constants::storageThumb2xPath.$value);
        Storage::delete(Constants::storageThumb3xPath.$value);
    }

}
