<?php

namespace App\Http\Contracts\Utilities;

use App\Http\Contracts\Core\PsInterface;

interface DynamicColumnVisibilityServiceInterface extends PsInterface
{
    public function save($dynamicColumnVisibilityData);

    public function update($id, $dynamicColumnVisibilityData);

    public function delete($id);

    public function get($id = null, $key = null, $moduleName = null);

    public function getAll($relation = null, $moduleName = null, $key = null, $isShow = null, $noPagination = null, $pagPerPage = null);

    public function updateOrCreate($dataArrWhere, $dynamicColumnVisibilityData);
}
