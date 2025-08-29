<?php

namespace Modules\Core\Http\Services;

use App\Config\ps_config;
use App\Http\Services\PsService;
use Modules\Core\Entities\Utilities\CoreField;

/**
 *@deprecated
 */
class CoreFieldFilterSettingService extends PsService
{
    private $pagePerPag, $coreFieldFilterSettingIsDeleteCol;

    public function __construct()
    {
        $this->pagePerPag = ps_config::pagPerPage;
        $this->coreFieldFilterSettingIsDeleteCol = CoreField::isDelete;
        $this->coreFieldFilterSettingTableName = CoreField::tableName;
        $this->coreFieldFilterSettingTableIdCol = CoreField::tableId;
        $this->coreFieldIdCol  = CoreField::id;
        $this->coreFieldmoduleNameCol  = CoreField::moduleName;
        $this->coreFieldfieldNameCol  = CoreField::fieldName;
        $this->coreFieldlabelNameCol  = CoreField::labelName;
    }

    public function getCoreFieldsWithConds($conds)
    {
        $coreFields = CoreField::when($conds, function ($q, $conds) {
            $q->where($conds);
        })->get();

        return $coreFields;
    }

    public function getCoreFields($relation = null, $tableId = null, $select = null, $withNoPag = null, $isDelete = null, $ids = null, $sort = null, $order = null, $search = null, $moduleName = null)
    {
        $coreFields = CoreField::when($relation, function ($q, $relation) {
            $q->with($relation);
        })
            ->when($select, function ($q, $select) {
                $q->select($select, $this->coreFieldFilterSettingTableName . ".*");
            })
            ->when($search, function ($query, $conds) {
                $query = $this->searching($query, $search);
            })
            ->when($sort && $sort !== 'ui_type_id', function ($q) use ($sort, $order) {

                $q->orderBy($sort, $order);
            })
            ->when($tableId, function ($q, $tableId) {
                $q->where($this->coreFieldFilterSettingTableIdCol, $tableId);
            })
            ->when($ids, function ($q, $ids) {
                $q->whereIn($this->coreFieldIdCol, $ids);
            })
            ->when($moduleName, function ($q, $moduleName) {
                $q->where($this->coreFieldmoduleNameCol, $moduleName);
            })
            ->when($isDelete !== null, function ($q) use ($isDelete) {
                if ($isDelete !== null) {
                    $q->where($this->coreFieldFilterSettingIsDeleteCol, $isDelete);
                }
            });
        if ($withNoPag) {
            $coreFields = $coreFields->get();
        } else {
            $coreFields = $coreFields->paginate($this->pagePerPag)->withQueryString();
        }

        return $coreFields;
    }

    public function searching($query, $search)
    {

        // search term
        if ($search) {
            $search = $search;
            $query->where(function ($query) use ($search) {
                $query->where($this->coreFieldFilterSettingTableName . '.' . $this->coreFieldmoduleNameCol, 'like', '%' . $search . '%')
                    ->orWhere($this->coreFieldFilterSettingTableName . '.' . $this->coreFieldfieldNameCol, 'like', '%' . $search . '%')
                    ->orWhere($this->coreFieldFilterSettingTableName . '.' . $this->coreFieldlabelNameCol, 'like', '%' . $search . '%');
            });
        }

        return $query;
    }

    public function getCoreField($id = null, $tableId = null, $relation = null, $coreKeysId = null)
    {
        $coreField = CoreField::when($id, function ($q, $id) {
            $q->where($this->coreFieldIdCol, $id);
        })
            // ->when($relation, function ($q, $relation){
            //     $q->with($relation);
            // })
            // ->when($tableId, function ($q, $tableId){
            //     $q->where($this->customizeUiTableIdCol, $tableId);
            // })
            // ->when($coreKeysId, function ($q, $coreKeysId){
            //     $q->where($this->customizeUiCoreKeysIdCol, $coreKeysId);
            // })
            ->first();
        return $coreField;
    }
}
