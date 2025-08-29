<?php

namespace App\Http\Contracts\Financial;

use App\Http\Contracts\Core\PsInterface;

interface TransactionStatusServiceInterface extends PsInterface
{
    public function save($transactionStatusData);

    public function update($id, $transactionStatusData);

    public function getAll();

    public function get($id = null);

    public function delete($id);

}