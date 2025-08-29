<?php

namespace App\Http\Contracts\Financial;

use App\Http\Contracts\Core\PsInterface;

interface CoreKeyPaymentRelationServiceInterface extends PsInterface
{
    public function save($coreKeyPaymentRelationData);

    public function update($id, $coreKeyPaymentRelationData);

    public function delete($id = null, $conds = null);

    public function get($id = null, $conds = null, $relation = null);

    // public function getAll(
    //     $relation = null,
    //     $status = null,
    //     $limit = null,
    //     $offset = null,
    //     $noPagination = null,
    //     $pagPerPage = null,
    //     $conds = null);

    // public function setStatus($id, $status);
}
