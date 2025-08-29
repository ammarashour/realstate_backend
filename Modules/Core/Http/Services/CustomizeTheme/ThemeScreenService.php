<?php

namespace Modules\Core\Http\Services\CustomizeTheme;

use App\Config\Cache\ThemeScreenCache;
use App\Config\ps_config;
use App\Http\Contracts\CustomizeTheme\ThemeScreenServiceInterface;
use App\Http\Services\PsService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Core\Entities\CustomizeTheme\ThemeScreen;
use Modules\Core\Http\Facades\PsCache;
use Illuminate\Database\Eloquent\Builder;

class ThemeScreenService extends PsService implements ThemeScreenServiceInterface
{
    public function save()
    {
        //
    }

    public function update($id, $themeScreenData)
    {
        DB::beginTransaction();
        try {
            $themeScreen = $this->updateThemeScreen($id, $themeScreenData);

            DB::commit();
            PsCache::clear(ThemeScreenCache::GET_KEY, $id);
            PsCache::clear(ThemeScreenCache::GET_ALL_KEY);
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function delete()
    {
        //
    }

    public function get($id = null, $relation = null, $conds = null)
    {
        $param = [$id, $relation, $conds];
        return PsCache::remember([ThemeScreenCache::GET_KEY, $id], ThemeScreenCache::GET_EXPIRY, $param, function () use ($id, $relation, $conds) {
            return ThemeScreen::when($id, function ($query, $id) {
                $query->where(ThemeScreen::id, $id);
            })->when($relation, function ($query, $relation) {
                $query->with($relation);
            })->when($conds, function ($query, $conds) {
                $this->searching($query, $conds);
            })->first();
        });
    }

    public function getAll($relation = null, $limit = null, $offset = null, $noPagination = null, $pagPerPage = null, $conds = null)
    {
        $params = [$relation, $limit, $offset, $noPagination, $pagPerPage, $conds];
        return PsCache::remember([ThemeScreenCache::GET_ALL_KEY], ThemeScreenCache::GET_ALL_EXPIRY, $params, function () use ($relation, $limit, $offset, $noPagination, $pagPerPage, $conds) {
            $query = ThemeScreen::when($relation, function ($query, $relation) {
                $query->with($relation);
            })
                ->when($limit, function ($query, $limit) {
                    $query->limit($limit);
                })
                ->when($offset, function ($query, $offset) {
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

    private function updateThemeScreen($id, $themeScreenData)
    {
        $themeScreen = $this->get($id);
        $themeScreen->updated_user_id = Auth::user()->id;
        $themeScreen->update($themeScreenData);

        return $themeScreen;
    }

    private function searching(Builder $query, $conds)
    {
        $query = $query->when(isset($conds['columnSearch']), function ($q) use ($conds) {
            $q->where($conds['columnSearch']);
        });
        return $query;
    }
}
