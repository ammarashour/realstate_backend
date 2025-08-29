<?php

namespace App\Http\Contracts\Financial;

use App\Http\Contracts\Core\PsInterface;

interface PaymentInfoServiceInterface extends PsInterface
{
    public function save($paymentInfoData);

    public function update($id, $paymentInfoData);

    public function delete($id = null, $conds = null);

    public function get($id = null, $conds = null, $relation = null);

    public function getAll($relations = null, $limit = null, $offset = null, $conds = null, $noPagination = null, $pagPerPage = null, $attribute = null, $serviceFrom = null);
}
