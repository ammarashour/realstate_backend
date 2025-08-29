<?php

namespace Modules\Core\Http\Services\Utilities;

use App\Http\Contracts\Utilities\CustomFieldAttributeServiceInterface;
use App\Http\Contracts\Utilities\CustomFieldServiceInterface;
use App\Http\Services\PsService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Utilities\CustomFieldAttribute;

class CustomFieldAttributeService extends PsService implements CustomFieldAttributeServiceInterface
{
    public function __construct(
        protected CustomFieldServiceInterface $customFieldService
    ) {}

    public function save($customFieldAttributeData)
    {
        DB::beginTransaction();

        try {
            $customFieldAttribute = $this->saveCustomFieldAttribute($customFieldAttributeData);

            DB::commit();

        } catch (\Throwable $e) {

            DB::rollBack();

            throw $e;
        }
    }

    public function update($id, $customFieldAttributeData)
    {

        DB::beginTransaction();

        try {
            $this->updateCustomFieldAttribute($id, $customFieldAttributeData);

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();

            throw $e;
        }
    }

    public function get($id = null)
    {
        return CustomFieldAttribute::when($id, function ($q, $id) {
            $q->where(CustomFieldAttribute::id, $id);
        })
            ->first();
    }

    public function getAll($coreKeysId = null, $noPagination = null, $pagPerPage = null, $coreKeysIds = null, $id = null, $conds = null, $limit = null, $offset = null, $isLatest = null)
    {
        $customizeDetails = CustomFieldAttribute::when($coreKeysId, function ($q, $coreKeysId) {
            $q->where(CustomFieldAttribute::coreKeysId, $coreKeysId);
        })
            ->when($conds, function ($query, $conds) {
                $query = $this->searching($query, $conds);
            })
            ->when($id, function ($q, $id) {
                $q->where(CustomFieldAttribute::id, $id);
            })
            ->when($coreKeysIds, function ($q, $coreKeysIds) {
                $q->whereIn(CustomFieldAttribute::coreKeysId, $coreKeysIds);
            })
            ->when($limit, function ($q, $limit) {
                $q->limit($limit);
            })
            ->when($offset, function ($q, $offset) {
                $q->offset($offset);
            })
            ->when($isLatest, function ($q, $isLatest){
                $q->latest();
            });
        if ($pagPerPage) {
            return $customizeDetails->paginate($pagPerPage)->onEachSide(1)->withQueryString();
        } elseif ($noPagination) {
            return $customizeDetails->get();
        }
    }

    public function delete($id)
    {
        try {
            $name = $this->deleteCustomFieldAttribute($id);

            return [
                'msg' => __('core__be_delete_success', ['attribute' => $name]),
                'flag' => Constants::success,
            ];
        } catch (\Throwable $e) {
            throw $e;
        }
    }

    public function deleteAll($customFieldDetailValues)
    {
        if ($customFieldDetailValues->isNotEmpty()) {
            $customizeDetailIds = $customFieldDetailValues->pluck(CustomFieldAttribute::id);
            CustomFieldAttribute::destroy($customizeDetailIds);
        }
    }

    public function getCustomizeUiAndDetailNestedArray($moduleName)
    {
        $customizeUisByModule = $this->customFieldService->getAll(
            moduleName: $moduleName,
            isLatest: Constants::yes,
            withNoPag: Constants::yes
        );

        $customizeDetails = $this->getAll(
            coreKeysIds: $customizeUisByModule->pluck(CustomFieldAttribute::coreKeysId),
            noPagination: Constants::yes
        )
        ->groupBy(CustomFieldAttribute::coreKeysId)
        ->toArray();

        return $customizeDetails;
    }

    ////////////////////////////////////////////////////////////////////
    /// Private Functions
    ////////////////////////////////////////////////////////////////////

    //-------------------------------------------------------------------
    // Database
    //-------------------------------------------------------------------

    private function saveCustomFieldAttribute($customFieldAttributeData)
    {
        $customFieldAttribute = new CustomFieldAttribute();
        $customFieldAttribute->fill($customFieldAttributeData);
        $customFieldAttribute->added_user_id = Auth::id();
        $customFieldAttribute->save();

        return $customFieldAttribute;
    }

    private function updateCustomFieldAttribute($id, $customFieldAttributeData)
    {
        $customFieldAttribute = $this->get($id);
        $customFieldAttribute->updated_user_id = Auth::id();
        $customFieldAttribute->update($customFieldAttributeData);

        return $customFieldAttribute;
    }

    private function deleteCustomFieldAttribute($id)
    {
        $CustomFieldAttribute = $this->get($id);
        $name = $CustomFieldAttribute->name;
        $CustomFieldAttribute->delete();

        return $name;
    }

    private function searching($query, $conds)
    {
        // search term
        if (isset($conds['keyword']) && $conds['keyword']) {
            $conds['searchterm'] = $conds['keyword'];
        }
        if (isset($conds['searchterm']) && $conds['searchterm']) {
            $search = $conds['searchterm'];
            $query->where(function ($query) use ($search) {
                $query->where(CustomFieldAttribute::tableName.'.'.CustomFieldAttribute::name, 'like', '%'.$search.'%');
            });
        }

        // order by
        if (isset($conds['order_by']) && isset($conds['order_type']) && $conds['order_by'] && $conds['order_type']) {

            $query->orderBy($conds['order_by'], $conds['order_type']);
        }

        return $query;
    }
}
