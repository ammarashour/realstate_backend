<?php

namespace Modules\Core\Http\Services\Configuration;

use App\Config\Cache\AppInfoCache;
use App\Config\Cache\BeSettingCache;
use App\Config\ps_constant;
use App\Http\Contracts\Configuration\BackendSettingServiceInterface;
use App\Http\Contracts\Image\ImageProcessingServiceInterface;
use App\Http\Contracts\Image\ImageServiceInterface;
use App\Http\Services\PsService;
use App\Mail\TestMail;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Configuration\BackendSetting;
use Modules\Core\Http\Facades\PsCache;

class BackendSettingService extends PsService implements BackendSettingServiceInterface
{
    public function __construct(protected ImageServiceInterface $imageService) {}

    public function save($backendSettingData, $backendLogo, $backendFavIcon, $waterMarkImage, $waterMarkBackground, $firebasePrivateKeyJsonFile)
    {
        DB::beginTransaction();
        try {
            $prepareData = $this->prepareUpdateData($backendSettingData);
            $this->saveBackendSetting($prepareData);

            // backend logo
            $logoData = $this->prepareImageData(Constants::backendLogo);
            $this->imageService->save($backendLogo, $logoData);

            // backend fav icon
            $iconData = $this->prepareImageData(Constants::backendFavIcon);
            $this->imageService->save($backendFavIcon, $iconData);

            // water mark image
            $waterMarkData = $this->prepareImageData(Constants::beWaterMarkImage);
            $this->imageService->save($waterMarkImage, $waterMarkData);

            // water mark backgorund for preview
            $waterMarkBgData = $this->prepareImageData(Constants::waterMarkBackground);
            $this->imageService->save($waterMarkBackground, $waterMarkBgData);

            // water mark background original
            $orgImgData = $this->prepareImageData(Constants::waterMarkBackgroundOrg);
            $this->imageService->save($waterMarkBackground, $orgImgData);

            $this->saveFirebasePrivateKeyJson($firebasePrivateKeyJsonFile);

            PsCache::clear(AppInfoCache::BASE);

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollback();

            throw $e;
        }
    }

    public function update($id, $backendSettingData, $backendLogoId, $backendLogo, $backendFavIconId, $backendFavIcon, $waterMarkImageId, $waterMarkImage, $waterMarkBackgroundId, $waterMarkBackground, $firebasePrivateKeyJsonFile)
    {
        PsCache::clear(BeSettingCache::BASE);

        DB::beginTransaction();
        try {
            $initialData = $this->get($id);
            $prepareData = $this->prepareUpdateData($backendSettingData);
            $this->updateBackendSetting($id, $prepareData);

            // backend logo
            $logoData = $this->prepareImageData(Constants::backendLogo);
            $this->imageService->update($backendLogoId, $backendLogo, $logoData);

            // backend fav icon
            $iconData = $this->prepareImageData(Constants::backendFavIcon);
            $this->imageService->update($backendFavIconId, $backendFavIcon, $iconData);

            // water mark image
            $waterMarkData = $this->prepareImageData(Constants::beWaterMarkImage);
            if($waterMarkImageId == null && $waterMarkImage !== null){
                $this->imageService->save($waterMarkImage, $waterMarkData);
            }else{
                $this->imageService->update($waterMarkImageId, $waterMarkImage, $waterMarkData);
            }

            // water mark background
            $waterMarkBgData = $this->prepareImageData(Constants::waterMarkBackground);
            if($waterMarkBackgroundId == null && $waterMarkBackground !== null){
                $this->imageService->save($waterMarkBackground, $waterMarkBgData);
            }else{
                $this->imageService->update($waterMarkBackgroundId, $waterMarkBackground, $waterMarkBgData);
            }

            // water mark background org
            if ($waterMarkBackground !== null) {
                $waterMarkBgOrgData = $this->prepareImageData(Constants::waterMarkBackgroundOrg);
                $this->imageService->deleteAll(1, Constants::waterMarkBackgroundOrg);
                $this->imageService->save($waterMarkBackground, $waterMarkBgOrgData);
            }elseif($waterMarkBackgroundId && $initialData->position != $prepareData['position'] && $waterMarkBackground == null) {
                $image = $this->imageService->get(['id' => $waterMarkBackgroundId]);
                $imagePath = public_path("storage/PSX_MPC/uploads/{$image->img_path}");
                if(file_exists($imagePath)) {
                    $uploadedFile = new UploadedFile(
                        $imagePath,
                        basename($imagePath),
                        mime_content_type($imagePath),
                        null,
                        true
                    );
                    $waterMarkBgOrgData = $this->prepareImageData(Constants::waterMarkBackgroundOrg);
                    $this->imageService->deleteAll(1, Constants::waterMarkBackgroundOrg);
                    $this->imageService->save($uploadedFile,$waterMarkBgOrgData);
                }
            }

            $this->saveFirebasePrivateKeyJson($firebasePrivateKeyJsonFile);

            PsCache::clear(AppInfoCache::BASE);

            DB::commit();

            PsCache::clear(BeSettingCache::BASE);
        } catch (\Throwable $e) {
            DB::rollback();
            dd($e->getMessage(), $e->getLine(), $e->getFile());
            throw $e;
        }
    }

    public function get($id = null, $relation = null, $hideCredential = true)
    {
        $param = [$id, $relation, $hideCredential];

        return PsCache::remember([BeSettingCache::BASE], BeSettingCache::GET_EXPIRY, $param,
            function() use($id, $relation, $hideCredential) {
                $result = BackendSetting::when($id, function ($query, $id) {
                    $query->where(BackendSetting::id, $id);
                })
                    ->when($relation, function ($query, $relation) {
                        $query->with($relation);
                    })
                    ->first();

                return $hideCredential ? $result->makeHidden(['smtp_pass']) : $result;
            });
    }

    public function checkSmtpConfig($email, $mailData)
    {
        try {
            Mail::to($email)->send(new TestMail($mailData));

            return [
                'msg' => 'Smtp Configuration is finished Successfully',
                'flag' => Constants::success,
            ];
        } catch (\Throwable $e) {
            throw $e;
        }
    }

    ///////////////////////////////////////////////////////////////////////
    /// Private Function
    ///////////////////////////////////////////////////////////////////////

    ///--------------------------------------------------------------------
    /// Data Preparation
    ///--------------------------------------------------------------------
    private function prepareUpdateData($backendSettingData)
    {
        if ($backendSettingData['is_google_map'] == 'Google Map') {
            $backendSettingData['is_google_map'] = 1;
            $backendSettingData['is_open_street_map'] = 0;
        } else {
            $backendSettingData['is_google_map'] = 0;
            $backendSettingData['is_open_street_map'] = 1;
        }

        return $backendSettingData;
    }

    private function prepareImageData($imageType)
    {
        return [
            'img_parent_id' => 1,
            'img_type' => $imageType,
        ];
    }

    ///--------------------------------------------------------------------
    /// Database
    ///--------------------------------------------------------------------
    private function saveBackendSetting($backendSettingData)
    {
        $backendSetting = new BackendSetting;
        $backendSetting->fill($backendSettingData);
        $backendSetting->added_user_id = Auth::id();
        $backendSetting->save();

        return $backendSetting;
    }

    private function updateBackendSetting($id, $backendSettingData)
    {
        $backendSetting = $this->get($id);
        $backendSetting->updated_user_id = Auth::id();
        $backendSetting->update($backendSettingData);

        return $backendSetting;
    }

    ///--------------------------------------------------------------------
    /// Others
    ///--------------------------------------------------------------------
    private function saveFirebasePrivateKeyJson($firebasePrivateKeyJsonFile)
    {
        if ($firebasePrivateKeyJsonFile !== null) {
            $newFileName = ps_constant::privateKeyFileNameForFCM;

            $filePath = base_path('storage/firebase/');

            if (!file_exists($filePath)) {
                mkdir($filePath, 0755, true);
            }

            $firebasePrivateKeyJsonFile->move($filePath, $newFileName);
        }
    }
}
