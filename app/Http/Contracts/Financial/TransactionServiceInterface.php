<?php

namespace App\Http\Contracts\Financial;

use App\Http\Contracts\Core\PsInterface;

interface TransactionServiceInterface extends PsInterface
{
    public function update($id, $transactionData);

    public function getAll($relation = null);

    public function get($id = null, $relation = null);

    public function delete($id);

    public function csvExport();
}