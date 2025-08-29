<?php

namespace Modules\Core\Http\Controllers\Backend\Controllers\Financial;

use App\Http\Controllers\PsController;
use Modules\Core\Http\Requests\Financial\UpdateTransactionRequest;
use Modules\Core\Http\Services\PaymentStatusService;
use App\Http\Contracts\Financial\TransactionServiceInterface;
use App\Http\Contracts\Financial\TransactionStatusServiceInterface;

class TransactionController extends PsController
{
    private const parentPath = "transaction";
    private const indexPath = self::parentPath."/Index";
    private const createPath = self::parentPath."/Create";
    private const editPath = self::parentPath."/Edit";
    private const indexRoute = "transaction.index";
    private const createRoute = "transaction.create";
    private const editRoute = "transaction.edit";

    public function __construct(protected TransactionServiceInterface $transactionService,
    protected PaymentStatusService $paymentStatusService, protected TransactionStatusServiceInterface $transactionStatusService)
    {
        parent::__construct();
    }

    public function index()
    {
        $dataArr = $this->prepareIndexData();

        return renderView(self::indexPath, $dataArr);
    }

    public function edit($id)
    {
        $dataArr = $this->prepareEditData($id);

        return renderView(self::editPath, $dataArr);
    }

    public function update(UpdateTransactionRequest $request, $id)
    {
        try {

            $validateData = $request->validated();

            $this->transactionService->update($id, $validateData);

            return redirectView(self::indexRoute);

        } catch (\Exception $e) {
            return redirectViewWithError(self::editRoute, $e->getMessage(), $id);
        }

    }

    public function destroy($id)
    {
        try {
            $dataArr = $this->transactionService->delete($id);

            return redirectView(self::indexRoute, $dataArr['msg'], $dataArr['flag']);
        } catch (\Exception $e) {
            return redirectViewWithError(self::indexRoute, $e->getMessage());
        }

    }

    public function csvExport()
    {
        return $this->transactionService->csvExport();
    }

    ////////////////////////////////////////////////////////////////////
    /// Private Functions
    ////////////////////////////////////////////////////////////////////

    //-------------------------------------------------------------------
    // Data Preparation
    //-------------------------------------------------------------------

    private function prepareIndexData()
    {
        $transactionRelation = ['transaction_status'];
        $transactions = $this->transactionService->getAll($transactionRelation);
        return [
            'transactions' => $transactions
        ];
    }

    private function prepareEditData($id)
    {
        $transactionRelation = ['transaction_detail', 'shop', 'transaction_count'];

        $transaction = $this->transactionService->get($id, $transactionRelation);
        $transaction_statuses = $this->transactionStatusService->getAll();
        $payment_statuses = $this->paymentStatusService->getPaymentStatuses();

       return [
            'transaction' => $transaction,
            'transaction_statuses' => $transaction_statuses,
            'payment_statuses' => $payment_statuses,
        ];
    }
}
