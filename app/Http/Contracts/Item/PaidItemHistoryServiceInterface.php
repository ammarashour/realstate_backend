<?php

namespace App\Http\Contracts\Item;

use App\Http\Contracts\Core\PsInterface;

interface PaidItemHistoryServiceInterface extends PsInterface
{
    public function get($id = null, $itemId = null);

    public function getAll($id = null, $itemId = null, $status = null, $startTimeStamp = null, $endTimestamp = null);

    public function update($id, $paidItemHistoryData);

}
