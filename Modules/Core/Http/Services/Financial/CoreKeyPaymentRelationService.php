<?php

namespace Modules\Core\Http\Services\Financial;

use App\Http\Contracts\Financial\CoreKeyPaymentRelationServiceInterface;
use App\Http\Services\PsService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Core\Constants\Constants;
use Modules\Payment\Entities\CoreKeyPaymentRelation;

class CoreKeyPaymentRelationService extends PsService implements CoreKeyPaymentRelationServiceInterface
{
    public function save($coreKeyPaymentRelationData)
    {
        DB::beginTransaction();
        try {
            $this->saveCoreKeyPaymentRelation($coreKeyPaymentRelationData);
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function update($id, $paymentAttributeData)
    {
        DB::beginTransaction();

        try {
            $this->updateCoreKeyPaymentRelation($id, $paymentAttributeData);

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();

            throw $e;
        }
    }

    public function delete($id = null, $conds = null)
    {
        try {
            $this->deleteCoreKeyPaymentRelation($id, $conds);

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
        return CoreKeyPaymentRelation::when($id, function ($query, $id) {
            $query->where(CoreKeyPaymentRelation::id, $id);
        })
            ->when($conds, function ($query, $conds) {
                $query->where($conds);
            })
            ->when($relation, function ($query, $relation) {
                $query->with($relation);
            })
            ->first();
    }

    ////////////////////////////////////////////////////////////////////
    /// Private Functions
    ////////////////////////////////////////////////////////////////////

    //-------------------------------------------------------------------
    // Database
    //-------------------------------------------------------------------
    private function saveCoreKeyPaymentRelation($coreKeyPaymentRelationData)
    {
        $coreKeyPaymentRelation = new CoreKeyPaymentRelation();
        $coreKeyPaymentRelation->fill($coreKeyPaymentRelationData);
        $coreKeyPaymentRelation->added_user_id = Auth::user()->id;
        $coreKeyPaymentRelation->save();

        return $coreKeyPaymentRelation;
    }

    private function updateCoreKeyPaymentRelation($id, $coreKeyPaymentRelationData)
    {
        $coreKeyPaymentRelation = $this->get($id);
        $coreKeyPaymentRelation->updated_user_id = Auth::user()->id;
        $coreKeyPaymentRelation->update($coreKeyPaymentRelationData);

        return $coreKeyPaymentRelation;
    }

    private function deleteCoreKeyPaymentRelation($id, $conds)
    {
        $coreKeyPaymentRelation = $this->get($id, $conds);
        $coreKeyPaymentRelation->delete();
    }
}
