<?php

namespace App\Http\Contracts\Financial;

use App\Http\Contracts\Core\PsInterface;

interface PromotionInAppPurchaseSettingServiceInterface extends PsInterface
{

    public function save($PromotionIAPData);

    public function update($id, $PromotionIAPData);

    public function getAll($relations = null, $limit = null, $offset = null, $conds = null);

    public function get($id, $relations = null, $conds = null);

    public function delete($id);

    public function setStatus($id, $status);


}