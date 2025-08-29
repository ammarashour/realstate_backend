<?php

namespace App\Http\Contracts\Location;

use App\Http\Contracts\Core\PsInterface;

interface LocationCityInfoServiceInterface extends PsInterface
{
    public function save($parentId, $customFieldValues);

    public function update($parentId, $customFieldValues);

    public function deleteAll($customFieldValues);

    public function getAll($coreKeysId = null, $locationCityId = null, $relation = null, $noPagination = null, $pagPerPage = null);

    public function get($id = null, $relation = null);

    public function getSqlForCustomField();
}
