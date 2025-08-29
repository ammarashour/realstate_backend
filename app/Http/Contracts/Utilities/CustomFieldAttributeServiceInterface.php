<?php

namespace App\Http\Contracts\Utilities;

use App\Http\Contracts\Core\PsInterface;

interface CustomFieldAttributeServiceInterface extends PsInterface
{
    public function save($customFieldAttributeData);

    public function update($id, $customFieldAttributeData);

    public function get($id = null);

    public function getAll($coreKeysId = null, $noPagination = null, $pagPerPage = null, $coreKeysIds = null, $id = null, $conds = null, $limit = null, $offset = null, $isLatest = null);

    public function delete($id);

    public function deleteAll($customFieldDetailValues);

    public function getCustomizeUiAndDetailNestedArray($moduleName);
}
