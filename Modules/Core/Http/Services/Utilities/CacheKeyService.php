<?php

namespace Modules\Core\Http\Services\Utilities;

use App\Http\Services\PsService;
use Illuminate\Support\Facades\DB;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Utilities\CacheKey;
use App\Http\Contracts\Utilities\CacheKeyServiceInterface;

class CacheKeyService extends PsService implements CacheKeyServiceInterface
{
    public function save($cacheKeyData)
    {
        DB::beginTransaction();
        try {
            $cacheKey = $this->get($cacheKeyData['id']);

            if (!$cacheKey) {
                $this->saveCacheKey($cacheKeyData);
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function delete($id)
    {
        try {
            $this->deleteCacheKey($id);

            return [
                'msg' => __('core__be_delete_success'),
                'flag' => Constants::success,
            ];
        } catch (\Throwable $e) {
            throw $e;
        }
    }

    public function deleteAll($baseKey1 = null, $conds = null)
    {
        try {
            $cacheKeys = $this->getAll($baseKey1, $conds);

            foreach($cacheKeys as $cacheKey){
                $this->deleteCacheKey($cacheKey->id);
            }

            return [
                'msg' => __('core__be_delete_success'),
                'flag' => Constants::success,
            ];
        } catch (\Throwable $e) {
            throw $e;
        }
    }

    public function get($id = null, $baseKey1 = null, $conds = null)
    {
        return CacheKey::when($id, function($query, $id) {
                $query->where(CacheKey::id, $id);
            })
            ->when($baseKey1, function($query, $baseKey1) {
                $query->where(CacheKey::baseKey1, $baseKey1);
            })
            ->when($conds, function($query, $conds) {
                $query->where($conds);
            })
            ->first();
    }

    public function getAll($baseKey1 = null, $conds = null)
    {
        return CacheKey::when($baseKey1, function($query, $baseKey1) {
                if(is_array($baseKey1)) {
                    $query->whereIn(CacheKey::baseKey1, $baseKey1);
                }else{
                    $query->where(CacheKey::baseKey1, $baseKey1);
                }
            })
            ->when($conds, function($query, $conds) {
                $query->where($conds);
            })
            ->latest()
            ->get();
    }

    public function remember($baseKey, $expiry, $param, $callback)
    {
        try{
            $key = $this->getCacheKey($baseKey, $param);

            $cacheKeyData = $this->prepareCacheKeyData($key, $baseKey); 
            
            return Cache()->remember($key, $expiry, function() use($cacheKeyData, $callback) {
                $this->save($cacheKeyData);
                return $callback();
            });

        }catch(\Throwable $e){
            throw $e;
        }
    }

    public function clear($baseKey1 = null, $baseKey2 = null, $baseKey3 = null)
    {
        $conds = [
            'base_key1' => $baseKey1,
            'base_key2' => $baseKey2,
            'base_key3' => $baseKey3,
        ];

        $conds = array_filter($conds, function($value) {
            return !is_null($value);
        });

        $cacheKeys = $this->getAll(conds: $conds);

        foreach($cacheKeys as $cacheKey) {
            Cache()->forget($cacheKey->id);
        }

        $this->deleteAll(conds: $conds);
    }

    ///////////////////////////////////////////////////////////////////
    /// Private Functions
    ///////////////////////////////////////////////////////////////////

    //=================================================================
    // Data Preparation
    //=================================================================
    private function prepareCacheKeyData($key ,$baseKeys)
    {
        $cacheKeyData = [];
        $cacheKeyData['id'] = $key;
        foreach($baseKeys as $key => $baseKey){
            $cacheKeyData['base_key'.$key+1] = $baseKey;
        }

        return $cacheKeyData;
    }

    //=================================================================
    // Database
    //=================================================================
    private function saveCacheKey($cacheKeyData)
    {
        $cacheKey = new CacheKey();
        $cacheKey->fill($cacheKeyData);
        $cacheKey->save();

        return $cacheKey;
    }

    private function updateCacheKey($id, $cacheKeyData)
    {
        $cacheKey = $this->get($id);
        $cacheKey->update($cacheKeyData);

        return $cacheKey;
    }

    private function deleteCacheKey($id)
    {
        $cacheKey = $this->get($id);
        $cacheKey->delete();
    }

    //=================================================================
    // Other
    //=================================================================
    private function getCacheKey($baseKey, $param)
    {
        $key = $baseKey[0].'_'.implode('_',$this->processCacheKey($param));
        return md5($key);
    }

    private function processCacheKey($element)
    {
        $result = [];

        if (is_array($element)) {
            // Handle associative and indexed arrays
            foreach ($element as $key => $value) {
                if ($value === null) {
                    continue; // Skip null values
                }

                if (is_array($value)) {
                    // Recursively process nested arrays
                    $nestedResult = $this->processCacheKey($value);
                    foreach ($nestedResult as $nestedValue) {
                        $result[] = is_string($key) ? $key . "_" . $nestedValue : $nestedValue;
                    }
                } else {
                    $result[] = is_string($key) ? $key . "_" . $value : $value;
                }
            }
        } else {
            // Directly add non-array elements, skip null values
            if ($element !== null) {
                $result[] = $element;
            }
        }

        return $result;
    }
}
