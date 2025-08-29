<?php

namespace Modules\Core\Http\Services\Financial;

use App\Http\Services\PsService;
use Illuminate\Support\Facades\DB;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Financial\TransactionHeader;
use Modules\Core\Entities\Financial\TransactionDetail;
use Modules\Core\Entities\Financial\TransactionCount;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Exports\TransactionsExport;
use Modules\Core\Http\Services\PaymentStatusService;
use App\Http\Contracts\Financial\TransactionServiceInterface;
use App\Http\Contracts\Financial\TransactionStatusServiceInterface;

class TransactionService extends PsService implements TransactionServiceInterface
{
    public function __construct(
        protected PaymentStatusService $paymentStatusService,
        protected TransactionStatusServiceInterface $transactionStatusService
    ) {}

    public function update($id, $transactionData)
    {
        DB::beginTransaction();

        try {

            $this->updateTransaction($id, $transactionData);

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return ['error' => $e->getMessage()];
        }
    }

    public function getAll($relation = null)
    {
        $transactionHeaders = TransactionHeader::when($relation, function ($q, $relation) {
            $q->with($relation);
        })
            ->latest()->get();
        return $transactionHeaders;
    }

    public function get($id = null, $relation = null)
    {
        $transaction = TransactionHeader::when($id, function ($q, $id) {
            $q->where('id', $id);
        })
            ->when($relation, function ($q, $relation) {
                $q->with($relation);
            })
            ->first();
        return $transaction;
    }

    public function delete($id)
    {
        DB::beginTransaction();
        try {
            $name = $this->deleteTransaction($id);
            DB::commit();

            return [
                'msg' => __('core__be_delete_success', ['attribute' => $name]),
                'flag' => Constants::success,
            ];

        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function csvExport()
    {
        $filename = newFileNameForExport('transactions');
        return (new TransactionsExport)->download($filename, \Maatwebsite\Excel\Excel::CSV, [
            'Content-Type' => 'text/csv',
        ]);
    }

    ////////////////////////////////////////////////////////////////////
    /// Private Functions
    ////////////////////////////////////////////////////////////////////

    //-------------------------------------------------------------------
    // Data Preparations
    //-------------------------------------------------------------------

    private function updateTransaction($id, $transactionData)
    {
        $transaction = $this->get($id);
        $transaction->updated_user_id = Auth::user()->id;
        $transaction->update($transactionData);
    }

    private function deleteTransaction($id)
    {
        $transaction = $this->get($id);
        $transaction_counts = TransactionCount::where('transaction_header_id', $transaction->id)->get();
        TransactionCount::destroy($transaction_counts->pluck('id'));

        $transaction_details = TransactionDetail::where('transaction_header_id', $transaction->id)->get();
        foreach ($transaction_details as $transaction_detail) {
            $transaction_detail->delete();
        }

        $name = $transaction->trans_code;
        $transaction->update();
        $transaction->delete();

        return $name;

    }
}
