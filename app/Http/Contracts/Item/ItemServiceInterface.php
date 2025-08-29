<?php

namespace App\Http\Contracts\Item;

use App\Http\Contracts\Core\PsInterface;

interface ItemServiceInterface extends PsInterface
{
    public function getAll($relations = [], $filters = [], $sorting = [], $limit = null, $offset = null, $noPagination = null, $filterNotIn = [], $page = null);

    public function save($itemData, $itemVideoIconImage, $itemVideo, $relationalData);

    public function update($id, $itemData, $itemVideoIconImage, $itemVideo, $relationalData);

    public function delete($id);

    public function get($id = null, $relation = null);

    public function updateMultiImage($itemData, $file);

    public function setStatus($id, $status);

    public function duplicateItem($id);

    public function generateDeeplink($id);

    public function decreaseItemQuantity($itemId, $isSoldOut = false);

    public function sendApprovalNoti($id);

    public function saveFromApi($itemData, $relationalData, $userInfoObj, $isReduceRemainPostCount);

    public function updateFromApi($itemObj, $itemData, $relationalData);

    public function generateDynamicLinksForAllItems();

}
