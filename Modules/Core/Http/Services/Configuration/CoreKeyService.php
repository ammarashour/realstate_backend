<?php

namespace Modules\Core\Http\Services\Configuration;

use App\Http\Contracts\Configuration\CoreKeyCounterServiceInterface;
use App\Http\Contracts\Configuration\CoreKeyServiceInterface;
use App\Http\Services\PsService;
use Illuminate\Support\Facades\DB;
use Modules\Core\Entities\Configuration\CoreKey;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Constants\Constants;
use Modules\Payment\Entities\CoreKeyPaymentRelation;

class CoreKeyService extends PsService implements CoreKeyServiceInterface
{
    public function __construct(protected CoreKeyCounterServiceInterface $coreKeyCounterService) {}
    public function save($coreKeyData, $code)
    {
        DB::beginTransaction();
        try {
            $coreKeyData['core_keys_id'] = $this->coreKeyCounterService->generate($code);
            $coreKey = $this->saveCoreKey($coreKeyData);
            DB::commit();
            return $coreKey;
        } catch (\Throwable $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function update($id, $coreKeyData)
    {
        DB::beginTransaction();
        try {
            $this->updateCoreKey($id, $coreKeyData);
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function delete($id = null, $conds = null)
    {
        try {
            $this->deleteCoreKey($id, $conds);
            return [
                'msg' => __('core__be_delete_success'),
                'flag' => Constants::success,
            ];
        } catch (\Throwable $e) {
            throw $e;
        }
    }

    public function get($id = null, $conds = null, $relation = null)
    {
        return CoreKey::when($id, function ($query, $id) {
                $query->where(CoreKey::id, $id);
            })
            ->when($conds, function ($query, $conds) {
                $query->where($conds);
            })
            ->when($relation, function ($query, $relation) {
                $query->with($relation);
            })
            ->first();
    }

    public function getAll($relations = null, $limit = null, $offset = null, $conds = null, $paymentId = null)
    {
        $coreKeys = CoreKey::when($relations, function ($query, $relations) {
            $query->with($relations);
        })
            ->when($conds, function ($query, $conds) {
                $query->where($conds);
            })
            ->when($paymentId, function ($query, $paymentId) {
                $query->join(CoreKeyPaymentRelation::tableName, CoreKey::tableName . '.' . CoreKey::coreKeysId, '=', CoreKeyPaymentRelation::tableName . '.' . CoreKeyPaymentRelation::coreKeysId)
                    ->where(CoreKeyPaymentRelation::paymentId, $paymentId);
            })
            ->when($limit, function ($query, $limit) {
                $query->limit($limit);
            })
            ->when($offset, function ($query, $offset) {
                $query->offset($offset);
            })
            ->latest()->get();
        return $coreKeys;
    }
    ////////////////////////////////////////////////////////////////////
    /// Private Functions
    ////////////////////////////////////////////////////////////////////

    //-------------------------------------------------------------------
    // Database
    //-------------------------------------------------------------------

    private function saveCoreKey($coreKeyData)
    {
        $coreKey = new CoreKey();
        $coreKey->fill($coreKeyData);
        $coreKey->added_user_id = Auth::user()->id;
        $coreKey->save();

        return $coreKey;
    }

    private function updateCoreKey($id, $coreKeyData)
    {
        $coreKey = $this->get($id);
        $coreKey->updated_user_id = Auth::user()->id;
        $coreKey->update($coreKeyData);

        return $coreKey;
    }

    private function deleteCoreKey($id, $conds)
    {
        $coreKey = $this->get($id, $conds);
        $coreKey->delete();
    }
}
