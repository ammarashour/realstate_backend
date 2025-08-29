<?php

namespace Modules\Core\Http\Services\CustomizeTheme;

use App\Config\Cache\ThemePlatformCache;
use App\Config\ps_config;
use App\Http\Contracts\CustomizeTheme\ThemePlatformServiceInterface;
use App\Http\Services\PsService;
use Illuminate\Database\Eloquent\Builder;
use Modules\Core\Entities\CustomizeTheme\ThemePlatform;
use Modules\Core\Http\Facades\PsCache;

class ThemePlatformService extends PsService implements ThemePlatformServiceInterface
{
    public function save()
    {
        //
    }

    public function update($id, $themePlatformData)
    {
        //
    }

    public function delete($id)
    {
        //
    }

    public function get($id = null, $relation = null, $conds = null)
    {
        $param = [$id, $relation, $conds];
        return PsCache::remember([ThemePlatformCache::GET_KEY], ThemePlatformCache::GET_EXPIRY, $param, function () use ($id, $relation, $conds) {
            return ThemePlatform::when($id, function ($query, $id) {
                $query->where(ThemePlatform::id, $id);
            })->when($relation, function ($query, $relation) {
                $query->with($relation);
            })->first();
        });
    }

    public function getAll($relation = null, $limit = null, $offset = null, $noPagination = null, $pagPerPage = null, $conds = null)
    {
        $param = [$relation, $limit, $offset, $noPagination, $pagPerPage, $conds];
        return PsCache::remember([ThemePlatformCache::GET_ALL_KEY], ThemePlatformCache::GET_ALL_EXPIRY, $param, function () use ($relation, $limit, $offset, $noPagination, $pagPerPage, $conds) {
            $query = ThemePlatform::when($relation, function ($query, $relation) {
                return $query->with($relation);
            })->when($limit, function ($query, $limit) {
                $query->limit($limit);
            })->when($offset, function ($query, $offset) {
                $query->offset($offset);
            })->when($conds, function ($query, $conds) {
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
        $query = $query->when(isset($conds['columnSearch']), function ($q) use ($conds) {
            $q->where($conds);
        });
        return $query;
    }
}
