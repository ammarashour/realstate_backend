<?php

namespace App\Http\Contracts\Utilities;

use App\Http\Contracts\Core\PsInterface;

interface CustomFieldServiceInterface extends PsInterface
{
    public function save($customFieldObj);

    public function update($id, $customFieldObj);

    public function delete($id);

    public function deleteAll($isByTruncate = null);

    public function get($id = null, $tableId = null, $relation = null, $coreKeysId = null, $code = null);

    public function getAll($relation = null, $tableId = null, $withNoPag = null, $tableIds = null, $coreKeysIds = null, $sort = null, $order = null, $search = null, $row = null, $isDelete = null, $ids = null, $code = null, $notStartWithAtCoreKeysIdCol = null, $isCoreField = null, $moduleName = null, $isLatest = null, $uiTypeId = null, $categoryId = null, $limit = null, $offset = null, $categoryIdOnly = false);

    // public function getCustomizeFields($relations = [], $filters = [], $limit = null, $offset = null );
}
