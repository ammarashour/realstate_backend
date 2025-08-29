<?php

namespace Modules\Core\Http\Services\CustomizeTheme;

use App\Config\Cache\AppInfoCache;
use App\Config\Cache\ComponentAttributeCache;
use App\Config\Cache\MbSettingCache;
use App\Config\ps_config;
use App\Config\ps_constant;
use App\Http\Contracts\Configuration\MobileSettingServiceInterface;
use App\Http\Contracts\CustomizeTheme\ComponentAttributeServiceInterface;
use App\Http\Services\PsService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Core\Http\Facades\PsCache;
use Modules\Theme\Entities\ComponentAttribute;

class ComponentAttributeService extends PsService implements ComponentAttributeServiceInterface
{
    public function __construct(protected MobileSettingServiceInterface $mobileSettingService) {}

    public function save()
    {
        //
    }

    public function update($id, $componentAttributeData)
    {
        DB::beginTransaction();
        try {
            $componentAttribute = $this->updateComponentAttribute($id, $componentAttributeData);
            if($componentAttribute->platform_id == ps_constant::mobilePlatformId) {
                $this->updateThemeComponentAttrChangeCode();
                PsCache::clear(MbSettingCache::BASE);
            }

            DB::commit();
            PsCache::clear(AppInfoCache::BASE);
            PsCache::clear(ComponentAttributeCache::BASE);
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function delete($id)
    {
        //
    }


    public function get($id = null, $relation = null, $conds = null)
    {
        $param = [$id, $relation, $conds];
        return PsCache::remember([ComponentAttributeCache::BASE], ComponentAttributeCache::GET_EXPIRY, $param, function () use ($id, $relation, $conds) {
            return ComponentAttribute::when($id, function ($query, $id) {
                $query->where(ComponentAttribute::id, $id);
            })->when($relation, function ($query, $relation) {
                $query->with($relation);
            })->when($conds, function ($query, $conds) {
                $this->searching($query, $conds);
            })->first();
        });
    }

    public function getAll($relation = null, $limit = null, $offset = null, $noPagination = null, $pagPerPage = null, $platformId = null, $conds = null)
    {
        $param = [$relation, $limit, $offset, $noPagination, $pagPerPage, $platformId, $conds];
        return PsCache::remember([ComponentAttributeCache::BASE], ComponentAttributeCache::GET_ALL_EXPIRY, $param, function () use ($relation, $limit, $offset, $noPagination, $pagPerPage, $platformId, $conds) {
            $query = ComponentAttribute::when($relation, function ($query, $relation) {
                $query->with($relation);
            })->when($limit, function ($query, $limit) {
                $query->limit($limit);
            })->when($offset, function ($query, $offset) {
                $query->offset($offset);
            })->when($platformId, function ($query, $platformId) {
                $query->where(ComponentAttribute::platformId, $platformId);
            })
                ->when($conds, function ($query, $conds) {
                    $this->searching($query, $conds);
                });

            if ($noPagination) {
                return $query->get();
            } else {
                return $query->paginate($pagPerPage ?? ps_config::pagPerPage)->onEachSide(1)->withQueryString();
            }
        });
    }

    private function searching(Builder $query, $conds)
    {
        $query = $query->when(isset($conds['columnSearch']), function ($q) use ($conds) {});
        return $query;
    }

    private function updateThemeComponentAttrChangeCode()
    {
        $mobile_setting = $this->mobileSettingService->get();
        $mobile_setting->theme_component_attr_change_code = Carbon::now()->getPreciseTimestamp(3);
        $mobile_setting->update();
    }

    private function updateComponentAttribute($id, $componentAttributeData)
    {
        $componentAttribute = $this->get($id);
        $componentAttribute->updated_user_id = Auth::user()->id;
        $componentAttribute->update($componentAttributeData);

        return $componentAttribute;
    }
}
