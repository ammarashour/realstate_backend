<?php

namespace App\Http\Contracts\Vendor;

use App\Http\Contracts\Core\PsInterface;

interface VendorSubscriptionPlanBoughtTransactionServiceInterface extends PsInterface
{
    public function storeFromApi($request);

    public function upgradeSubscription($request);

    public function get($id = null, $conds = null, $relation = null);

    public function getAll($relation = null, $status = null, $limit = null, $offset = null, $conds = null, $pagPerPage = null, $searchConds = null);
}