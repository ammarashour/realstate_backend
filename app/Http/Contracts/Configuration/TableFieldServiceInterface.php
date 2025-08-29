<?php

namespace App\Http\Contracts\Configuration;

use App\Http\Contracts\Core\PsInterface;

interface TableFieldServiceInterface extends PsInterface
{
    public function save($customFieldData, $tableId, $generatedData);

    public function deleteCoreField($id);

    public function deleteCustomField($id);

    public function getAll($tableId, $languageId, $isItemTable, $categoryId, $conds);

    public function generateCoreKeysId($tableId, $ui_type_id);

    public function updateCoreField($id, $coreFieldData);

    public function updateCustomField($id, $customFieldData);

    public function setCoreFieldEnable($id, $enable);

    public function setCustomFieldEnable($id, $enable);

    public function setCoreFieldIsShowSorting($id, $isShowSorting);

    public function setCustomFieldIsShowSorting($id, $isShowSorting);

    public function setCoreFieldMandatory($id, $mandatory);

    public function setCustomFieldMandatory($id, $mandatory);

    public function updateEyeStatusCoreField($id, $eyeStatusData);

    public function updateEyeStatusCustomField($id, $eyeStatusData);
}
