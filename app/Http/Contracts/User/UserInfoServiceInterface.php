<?php

namespace App\Http\Contracts\User;

use App\Http\Contracts\Core\PsInterface;

interface UserInfoServiceInterface extends PsInterface
{
    public function save($parentId, $customFieldValues);

    public function update($parentId, $customFieldValues);

    public function deleteAll($customFieldValues);

    public function get($id = null, $relation = null, $parentId = null, $coreKeysId = null);

    public function getAll($coreKeysIds = null, $parentId = null, $relation = null, $noPagination = null, $pagPerPage = null);
}
