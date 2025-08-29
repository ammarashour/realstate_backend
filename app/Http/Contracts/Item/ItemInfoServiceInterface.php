<?php

namespace App\Http\Contracts\Item;

use App\Http\Contracts\Core\PsInterface;

interface ItemInfoServiceInterface extends PsInterface
{

    public function save($parentId, $customFieldValues);

    public function update($parentId, $customFieldValues);

    public function deleteAll($customFieldValues);

    public function getAll($coreKeysIds = null, $itemId = null, $relation = null, $noPagination = null, $pagPerPage = null);

    public function get($id = null, $relation = null, $itemId = null, $coreKeysId = null);

}
