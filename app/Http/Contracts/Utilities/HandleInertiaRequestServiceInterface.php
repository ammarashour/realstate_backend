<?php

namespace App\Http\Contracts\Utilities;

use App\Http\Contracts\Core\PsInterface;

interface HandleInertiaRequestServiceInterface extends PsInterface
{
    public function getActivatedFileName($id = null);

    public function getCheckVersionUpdate($id = null);

    public function getBuilderAppInfoCache($id = null);

    public function saveBuilderAppInfoCache($builderAppInfoCacheData);

    public function updateBuilderAppInfoCache($builderAppInfoCacheData);
}
