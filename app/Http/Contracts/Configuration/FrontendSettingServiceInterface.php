<?php

namespace App\Http\Contracts\Configuration;

use App\Http\Contracts\Core\PsInterface;

interface FrontendSettingServiceInterface extends PsInterface
{
    public function save($frontendSettingData, $frontendColors = [],
                            $frontendLogo = null,
                            $frontendIcon = null,
                            $frontendBanner = null,
                            $appBrandingImage = null,
                            $frontendMetaImage = null);

    public function update($id, $frontendSettingData, $frontendColors = [],
                            $frontendLogoId = null, $frontendLogo = null,
                            $frontendIconId = null, $frontendIcon = null,
                            $frontendBannerId = null, $frontendBanner = null,
                            $appBrandingImageId = null, $appBrandingImage = null,
                            $frontendMetaImageId = null, $frontendMetaImage = null);

    public function get($id = null, $relation = null);

    public function colorGenerate($frontendColors);
}
