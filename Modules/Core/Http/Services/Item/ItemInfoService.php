<?php

namespace Modules\Core\Http\Services\Item;

use App\Http\Contracts\Core\PsInfoServiceInterface;
use App\Http\Contracts\Item\ItemInfoServiceInterface;
use App\Http\Contracts\Utilities\CustomFieldServiceInterface;
use App\Http\Services\PsService;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\ItemInfo;

class ItemInfoService extends PsService implements ItemInfoServiceInterface
{
    public function __construct(
        protected PsInfoServiceInterface $psInfoServiceInterface,
        protected CustomFieldServiceInterface $customFieldServiceInterface) {}

    public function save($parentId, $customFieldValues)
    {
        $this->psInfoServiceInterface->save(Constants::item, $customFieldValues, $parentId, ItemInfo::class, "item_id");
    }

    public function update($parentId, $customFieldValues)
    {
        // $coreKeysIds = array_keys($customFieldValues);
        // $getOldInfoValues = $this->getAll($coreKeysIds, $parentId, null, Constants::yes);

        $this->psInfoServiceInterface->update(Constants::item, $customFieldValues, $parentId, ItemInfo::class, "item_id");
        // $this->deleteAll($getOldInfoValues, $toNotDeleteImageCoreKey);
    }

    public function deleteAll($customFieldValues)
    {
        $this->psInfoServiceInterface->deleteAll($customFieldValues);
    }

    public function getAll($coreKeysIds = null, $itemId = null, $relation = null, $noPagination = null, $pagPerPage = null)
    {
        $itemInfos = ItemInfo::when($relation, function ($q, $relation) {
                                $q->with($relation);
                            })
                            ->when($coreKeysIds,function ($q, $coreKeysIds){
                                $q->whereIn(ItemInfo::coreKeysId, $coreKeysIds);
                            })
                            ->when($itemId, function ($q, $itemId){
                                $q->where(ItemInfo::itemId, $itemId);
                            });
        if ($pagPerPage) {
            return $itemInfos->paginate($pagPerPage)->onEachSide(1)->withQueryString();
        } elseif ($noPagination) {
            return $itemInfos->get();
        }

    }

    public function get($id = null, $relation = null, $itemId = null, $coreKeysId = null)
    {
        return ItemInfo::when($id, function ($q, $id) {
                $q->where(ItemInfo::id, $id);
            })
            ->when($relation, function ($q, $relation) {
                $q->with($relation);
            })
            ->when($itemId, function ($q, $itemId) {
                $q->where(ItemInfo::itemId, $itemId);
            })
            ->when($coreKeysId, function ($q, $coreKeysId) {
                $q->where(ItemInfo::coreKeysId, $coreKeysId);
            })
            ->first();
    }


}
