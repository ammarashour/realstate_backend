<?php

namespace Modules\Core\Http\Services\Financial;

use App\Config\Cache\AppInfoCache;
use App\Http\Contracts\Financial\PaymentAttributeServiceInterface;
use App\Http\Services\PsService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Core\Constants\Constants;
use Modules\Core\Http\Facades\PsCache;
use Modules\Payment\Entities\PaymentAttribute;

class PaymentAttributeService extends PsService implements PaymentAttributeServiceInterface
{
    public function save($paymentAttributeData)
    {
        DB::beginTransaction();
        try {
            $this->savePaymentAttribute($paymentAttributeData);

            PsCache::clear(AppInfoCache::BASE);

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
            $this->updatePaymentAttribute($id, $paymentAttributeData);

            PsCache::clear(AppInfoCache::BASE);

            DB::commit();
        } catch (\Throwable $e) {
            dd($e->getMessage(), $e->getLine(), $e->getFile());
            DB::rollBack();

            throw $e;
        }
    }

    public function delete($id = null, $conds = null)
    {
        try {
            $this->deletePaymentAttribute($id, $conds);

            PsCache::clear(AppInfoCache::BASE);

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
        return PaymentAttribute::when($id, function ($query, $id) {
            $query->where(PaymentAttribute::id, $id);
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
    private function savePaymentAttribute($paymentAttributeData)
    {
        $paymentAttribute = new PaymentAttribute();
        $paymentAttribute->fill($paymentAttributeData);
        $paymentAttribute->added_user_id = Auth::user()->id;
        $paymentAttribute->save();

        return $paymentAttribute;
    }

    private function updatePaymentAttribute($id, $paymentAttributeData)
    {
        $paymentAttribute = $this->get($id);
        $paymentAttribute->updated_user_id = Auth::user()->id;
        $paymentAttribute->update($paymentAttributeData);
        return $paymentAttribute;
    }

    private function deletePaymentAttribute($id, $conds)
    {
        $paymentAttribute = $this->get($id, $conds);
        $paymentAttribute->delete();
    }
}
