<?php
namespace Modules\Core\Http\Services\Utilities;

use App\Config\Cache\CacheBuilderAppInfoCache;
use App\Config\Cache\CheckVersionUpdateCache;
use App\Http\Contracts\Utilities\HandleInertiaRequestServiceInterface;
use App\Http\Services\PsService;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Entities\Utilities\ActivatedFileName;
use Modules\Core\Entities\Utilities\BuilderAppInfoCache;
use Modules\Core\Entities\Utilities\CheckVersionUpdate;
use Modules\Core\Http\Facades\PsCache;
use Throwable;

class HandleInertiaRequestService extends PsService implements HandleInertiaRequestServiceInterface
{

    public function __construct()
    {}

    public function getActivatedFileName($id = null, $fileName = null)
    {
        $param = [$id, $fileName];

        return ActivatedFileName::when($id, function ($query, $id) {
            $query->where(ActivatedFileName::id, $id);
        })->first();

        // return PsCache::remember([ActivatedFileNameCache::BASE], ActivatedFileNameCache::GET_EXPIRY, $param,
        //     function() use($id){
        //         return ActivatedFileName::when($id, function ($query, $id) {
        //             $query->where(ActivatedFileName::id, $id);
        //         })->first();
        //     });
    }

    public function getCheckVersionUpdate($id = null)
    {
        $param = [$id];

        return PsCache::remember([CheckVersionUpdateCache::BASE], CheckVersionUpdateCache::GET_EXPIRY, $param,
            function() use($id){
                return CheckVersionUpdate::when($id, function ($query, $id) {
                    $query->where(CheckVersionUpdate::id, $id);
                })->first();
            });
    }

    public function getBuilderAppInfoCache($id = null)
    {
        $param = [$id];

        return PsCache::remember([CacheBuilderAppInfoCache::BASE], CacheBuilderAppInfoCache::GET_EXPIRY, $param,
            function() use($id){
                return BuilderAppInfoCache::when($id, function ($query, $id) {
                    $query->where(BuilderAppInfoCache::id, $id);
                })->first();
            });
    }

    public function saveBuilderAppInfoCache($builderAppInfoCacheData)
    {

        try{
            $builderAppInfoCache = new BuilderAppInfoCache();
            $builderAppInfoCache->fill($builderAppInfoCacheData);
            $builderAppInfoCache->added_user_id = Auth::id();
            $builderAppInfoCache->save();
        }catch(Throwable $e){
            throw $e;
        }

    }

    public function updateBuilderAppInfoCache($builderAppInfoCacheData)
    {
        try{
            $builderAppInfoCache = $this->getBuilderAppInfoCache();
            $builderAppInfoCache->fill($builderAppInfoCacheData);
            $builderAppInfoCache->updated_user_id = Auth::id();
            $builderAppInfoCache->update();
        }catch(Throwable $e){
            throw $e;
        }

    }



    ////////////////////////////////////////////////////////////////////
    /// Private Functions
    ////////////////////////////////////////////////////////////////////



}
