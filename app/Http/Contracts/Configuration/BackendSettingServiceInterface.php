<?php

namespace App\Http\Contracts\Configuration;

use App\Http\Contracts\Core\PsInterface;

interface BackendSettingServiceInterface extends PsInterface
{
    public function save($backendSettingData, $backendLogo, $backendFavIcon, $waterMarkImage, $waterMarkBackground, $firebasePrivateKeyJsonFile);

    public function update($id, $backendSettingData,
                        $backendLogoId, $backendLogo,
                        $backendFavIconId, $backendFavIcon,
                        $waterMarkImageId, $waterMarkImage,
                        $waterMarkBackgroundId, $waterMarkBackground,
                        $firebasePrivateKeyJsonFile);

    public function get($id = null, $relation = null, $hideCredential = true);

    public function checkSmtpConfig($email, $mailData);
}
