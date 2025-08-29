<?php

namespace Modules\Core\Http\Controllers\Backend\Controllers\Financial;

use App\Http\Controllers\PsController;
use App\Http\Contracts\Financial\TransactionStatusServiceInterface;
use Modules\Core\Http\Requests\Financial\StoreTransactionStatusRequest;
use Modules\Core\Http\Requests\Financial\UpdateTransactionStatusRequest;

class TransactionStatusController extends PsController
{
    private const parentPath = "transaction_status/";
    private const indexPath = self::parentPath."Index";
    private const createPath = self::parentPath."Create";
    private const editPath = self::parentPath."Edit";
    private const indexRoute = "transaction_status.index";
    private const createRoute = "transaction_status.create";
    private const editRoute = "transaction_status.edit";

    public function __construct(protected TransactionStatusServiceInterface $transactionStatusService)
    {
       parent::__construct();
    }

    public function index()
    {
        $dataArr = $this->prepareIndexData();
        return renderView(self::indexPath, $dataArr);
    }

    public function create()
    {
        return renderView(self::createPath);
    }

    public function store(StoreTransactionStatusRequest $request)
    {
        try {
            // Validate the request data
            $validData = $request->validated();

            // Save Blog
            $this->transactionStatusService->save($validData);

            // Success and Redirect
            return redirectView(self::indexRoute);
        } catch (\Exception $e) {
            return redirectViewWithError(self::createRoute, $e->getMessage());
        }
    }


    public function edit($id)
    {
        $dataArr = $this->prepareEditData($id);
        return renderView(self::editPath, $dataArr);    
    }

    public function update(UpdateTransactionStatusRequest $request, $id)
    {
        try {
            $validatedData = $request->validated();

            $this->transactionStatusService->update($id, $validatedData);

            return redirectView(self::indexRoute);
        } catch (\Exception $e) {
            return redirectViewWithError(self::editRoute, $e->getMessage(), $id);
        }
    }

    public function destroy($id)
    {
        try {
            $dataArr = $this->transactionStatusService->delete($id);

            return redirectView(self::indexRoute, $dataArr['msg'], $dataArr['flag']);
        } catch (\Exception $e) {
            return redirectViewWithError(self::indexRoute, $e->getMessage());
        }
    }

    ////////////////////////////////////////////////////////////////////
    /// Private Functions
    ////////////////////////////////////////////////////////////////////

    //-------------------------------------------------------------------
    // Data Preparation
    //-------------------------------------------------------------------

    private function prepareIndexData()
    {
        $transactionStatuses = $this->transactionStatusService->getAll();
        return [
            'transaction_statuses' => $transactionStatuses
        ];
    }

    private function prepareEditData($id)
    {
        $transactionStatus = $this->transactionStatusService->get($id);
        return [
            'transaction_status' => $transactionStatus
        ];
    }
}
