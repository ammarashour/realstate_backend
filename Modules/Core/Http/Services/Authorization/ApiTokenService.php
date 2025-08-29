<?php

namespace Modules\Core\Http\Services\Authorization;

use App\Config\Cache\PersonalAccessTokenCache;
use App\Http\Services\PsService;
use Modules\Core\Entities\Authorization\ApiToken;
use Modules\Core\Constants\Constants;;
use Modules\Core\Http\Facades\PsCache;
use App\Http\Contracts\Authorization\ApiTokenServiceInterface;

class ApiTokenService extends PsService implements ApiTokenServiceInterface
{
    public function __construct()
    {}

    public function getAll($status = null, $limit = null, $offset = null, $noPagination = null, $pagPerPage = null, $conds = null, $abilities = null)
    {

        $param = [$status, $limit, $offset, $noPagination, $pagPerPage, $conds, $abilities];

        return PsCache::remember(
            [PersonalAccessTokenCache::BASE],
            PersonalAccessTokenCache::GET_ALL_EXPIRY,
            $param,
            function () use ($status, $limit, $offset, $noPagination, $pagPerPage, $conds, $abilities) {
                $apiTokens = ApiToken::when($conds, function ($query, $conds) {
                    $query = $this->searching($query, $conds);
                })
                    ->when($limit, function ($query, $limit) {
                        $query->limit($limit);
                    })
                    ->when($offset, function ($query, $offset) {
                        $query->offset($offset);
                    })
                    ->when($abilities, function ($query, $abilities) {
                        $query->where('abilities', 'like', '%' . $abilities . '%');
                    })
                    ->latest();
                if ($pagPerPage) {
                    $apiTokens = $apiTokens->paginate($pagPerPage)->onEachSide(1)->withQueryString();
                } elseif ($noPagination == 1) {
                    $apiTokens = $apiTokens->get();
                }
                return $apiTokens;
            }
        );
    }

    public function get($id = null, $conds = null)
    {
        $param = [$id, $conds];
        return PsCache::remember(
            [PersonalAccessTokenCache::BASE],
            PersonalAccessTokenCache::GET_EXPIRY,
            $param,
            function () use ($id, $conds) {
                $available_currency = ApiToken::when($id, function ($query, $id) {
                    $query->where(
                        ApiToken::id,
                        $id
                    );
                })
                    ->when($conds, function ($query, $conds) {
                        $query->where($conds);
                    })->first();
                return $available_currency;
            }
        );
    }

    public function delete($id)
    {
        try {
            $name = $this->deleteApiToken($id);

            PsCache::clear(PersonalAccessTokenCache::BASE);

            return [
                'msg' => __('core__be_delete_success', ['attribute' => $name]),
                'flag' => Constants::success,
            ];
        } catch (\Throwable $e) {
            throw $e;
        }

        
    }

    //-------------------------------------------------------------------
    // Private Functions
    //-------------------------------------------------------------------

    //-------------------------------------------------------------------
    // Database
    //-------------------------------------------------------------------
    
    private function searching($query, $conds)
    {

        // search term
        if (isset($conds['searchterm']) && $conds['searchterm']) {
            $search = $conds['searchterm'];
            $query->where(function ($query) use ($search) {
                $query->where(ApiToken::name, 'like', '%' . $search . '%');
            });
        }

        if (isset($conds['added_date']) && $conds['added_date']) {
            $date_filter = $conds['added_date'];
            $query->whereHas(ApiToken::addedDate, function ($q) use ($date_filter) {
                $q->where(ApiToken::addedDate, $date_filter);
            });
        }

        if (isset($conds['added_user_id']) && $conds['added_user_id']) {
            $query->where(ApiToken::addedDate, $conds['added_user_id']);
        }

        // order by
        if (isset($conds['order_by']) && isset($conds['order_type']) && $conds['order_by'] && $conds['order_type']) {
            if ($conds['order_by'] == 'id') {
                $query->orderBy('id', $conds['order_type']);
            } else {
                $query->orderBy($conds['order_by'], $conds['order_type']);
            }
        }

        return $query;
    }

    private function deleteApiToken($id)
    {
        $apiToken = $this->get($id);
        $name = $apiToken->name;
        $apiToken->delete();

        return $name;
    }

}
