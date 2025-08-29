<?php

namespace App\Http\Contracts\Vendor;

use App\Http\Contracts\Core\PsInterface;

interface VendorSubscriptionPlanSettingServiceInterface extends PsInterface
{
    public function save($vendorSubscriptionPlanData);

    public function update($id, $vendorSubscriptionPlanData);

    public function getAll($relations = null, $limit = null, $offset = null, $conds = null);

    public function get($id, $relations = null, $conds = null);

    public function delete($id);

    public function setStatus($id, $status);
}