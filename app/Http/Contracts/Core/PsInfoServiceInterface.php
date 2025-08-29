<?php

namespace App\Http\Contracts\Core;

use App\Http\Contracts\Core\PsInterface;

interface PsInfoServiceInterface extends PsInterface
{
    public function save($code, $customFieldValues = [], $parentId, $relationClass, $parentIdFieldName);

    public function update($code, $customFieldValues = [], $parentId, $relationClass, $parentIdFieldName);

    public function deleteAll($customFieldValues = []);

    public function get($relationClass, $parentId = null, $coreKeysId = null, $parentIdFieldName = null);

    public function getAll($relationClass, $parentId = null, $parentIdFieldName = null, $pagPerPage = null, $noPagination = null);
}
