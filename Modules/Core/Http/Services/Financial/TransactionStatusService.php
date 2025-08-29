<?php

namespace Modules\Core\Http\Services\Financial;

use App\Http\Services\PsService;
use Illuminate\Support\Facades\DB;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Financial\TransactionStatus;
use Illuminate\Support\Facades\Auth;
use App\Http\Contracts\Financial\TransactionStatusServiceInterface;

class TransactionStatusService extends PsService implements TransactionStatusServiceInterface
{

    public function __construct() {}

    public function save($transactionStatusData)
    {
        DB::beginTransaction();

        try {
            $this->saveTransactionStatus($transactionStatusData);

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function update($id, $transactionStatusData)
    {
        DB::beginTransaction();

        try {

            $this->updateTransactionStatus($id, $transactionStatusData);

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function getAll()
    {
        $transactionStatuses = TransactionStatus::latest()->get();
        return $transactionStatuses;
    }

    public function get($id = null)
    {
        $transactionStatuses = TransactionStatus::when($id, function ($q, $id) {
            $q->where(TransactionStatus::id, $id);
        })->first();
        return $transactionStatuses;
    }

    public function delete($id)
    {
        try {
            
            $name = $this->deleteTransactionStatus($id);

            return [
                'msg' => __('core__be_delete_success', ['attribute' => $name]),
                'flag' => Constants::danger,
            ];
        } catch (\Throwable $e) {
            throw $e;
        }
    }

    ////////////////////////////////////////////////////////////////////
    /// Private Functions
    ////////////////////////////////////////////////////////////////////

    //-------------------------------------------------------------------
    // Database
    //-------------------------------------------------------------------

    private function saveTransactionStatus($transactionStatusData)
    {
        $transactionStatus = new TransactionStatus();
        $transactionStatus->fill($transactionStatusData);
        $transactionStatus->added_user_id = Auth::user()->id;
        $transactionStatus->save();
    }

    private function updateTransactionStatus($id, $transactionStatusData)
    {
        $transaction_status = $this->get($id);

        $transaction_status->updated_user_id = Auth::user()->id;
        $transaction_status->update($transactionStatusData);

        DB::commit();
    }

    private function deleteTransactionStatus($id)
    {
        $transaction_status = $this->get($id);
        $name = $transaction_status->title;
        $transaction_status->delete();

        return $name;
    }
}
