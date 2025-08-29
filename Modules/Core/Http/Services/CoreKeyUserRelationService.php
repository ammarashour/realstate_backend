<?php

namespace Modules\Core\Http\Services;

use App\Http\Services\PsService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Entities\Configuration\CoreKey;
use Modules\Core\Entities\CoreKeyUserRelation;

class CoreKeyUserRelationService extends PsService
{
    protected $coreKeyUserRelationIdCol, $coreKeyUserRelationCoreKeysIdCol, $coreKeyUserRelationUserIdCol;

    public function __construct()
    {
        $this->coreKeyUserRelationTableName = CoreKeyUserRelation::tableName;
        $this->coreKeyUserRelationIdCol = CoreKeyUserRelation::id;
        $this->coreKeyUserRelationCoreKeysIdCol = CoreKeyUserRelation::coreKeysId;
        $this->coreKeyUserRelationUserIdCol = CoreKeyUserRelation::userId;

        $this->coreKeyTableName = CoreKey::tableName;
        $this->coreKeyIdCol = CoreKey::id;
        $this->coreKeyCoreKeysIdCol = CoreKey::coreKeysId;
    }

    public function store($request)
    {
        DB::beginTransaction();

        try {
            $coreKeyUserRelation = new CoreKeyUserRelation();

            if (isset($request->core_keys_id) && !empty($request->core_keys_id))
                $coreKeyUserRelation->core_keys_id = $request->core_keys_id;

            if (isset($request->user_id) && !empty($request->user_id))
                $coreKeyUserRelation->user_id = $request->user_id;

            if (isset($request->added_user_id) && !empty($request->added_user_id))
                $coreKeyUserRelation->added_user_id = $request->added_user_id;
            else
                $coreKeyUserRelation->added_user_id = Auth::user()->id;

            $coreKeyUserRelation->save();

            DB::commit();
            return $coreKeyUserRelation;
        } catch (\Throwable $e) {
            DB::rollBack();
            return ['error' => $e->getMessage()];
        }
    }

    public function update($id, $request)
    {
        DB::beginTransaction();

        try {
            $coreKeyUserRelation = $this->getCoreKeyUserRelation($id);

            if (isset($request->core_keys_id) && !empty($request->core_keys_id))
                $coreKeyUserRelation->core_keys_id = $request->core_keys_id;

            if (isset($request->user_id) && !empty($request->user_id))
                $coreKeyUserRelation->user_id = $request->user_id;

            if (isset($request->updated_date_id) && !empty($request->updated_date_id))
                $coreKeyUserRelation->updated_date_id = $request->updated_date_id;
            else
                $coreKeyUserRelation->updated_date_id = Auth::user()->id;

            $coreKeyUserRelation->update();

            DB::commit();
            return $coreKeyUserRelation;
        } catch (\Throwable $e) {
            DB::rollBack();
            return ['error' => $e->getMessage()];
        }
    }

    public function getCoreKeyUserRelations($relations = null, $limit = null, $offset = null, $conds = null)
    {
        $coreKeyUserRelations = CoreKeyUserRelation::when($relations, function ($query, $relations) {
            $query->with($relations);
        })
            ->when($conds, function ($query, $conds) {
                $query->where($conds);
            })
            ->when($limit, function ($query, $limit) {
                $query->limit($limit);
            })
            ->when($offset, function ($query, $offset) {
                $query->offset($offset);
            })
            ->latest()->get();
        return $coreKeyUserRelations;
    }

    public function getCoreKeyUserRelation($id, $relations = null, $conds = null)
    {
        $coreKeyUserRelation = CoreKeyUserRelation::where($this->coreKeyUserRelationIdCol, $id)
            ->when($relations, function ($query, $relations) {
                $query->with($relations);
            })
            ->when($conds, function ($query, $conds) {
                $query->where($conds);
            })
            ->first();
        return $coreKeyUserRelation;
    }

    public function getUserCoreKeys($user_id)
    {
        $coreKeys = CoreKey::join($this->coreKeyUserRelationTableName, $this->coreKeyTableName . '.' . $this->coreKeyCoreKeysIdCol, '=', $this->coreKeyUserRelationTableName . '.' . $this->coreKeyUserRelationCoreKeysIdCol)
            ->where($this->coreKeyUserRelationTableName . '.' . $this->coreKeyUserRelationUserIdCol, $user_id)
            ->get([$this->coreKeyTableName . '.*']);
        return $coreKeys;
    }
}
