<?php

namespace App\Http\Contracts\Location;

use App\Http\Contracts\Core\PsInterface;

interface LocationCityServiceInterface extends PsInterface
{
    public function save($locationCityData, $relationalData);

    public function update($id, $locationCityData, $relationalData);

    public function delete($id);

    public function get($id, $relation = null);

    public function getAll($relation = null, $status = null, $limit = null, $offset = null, $conds = null, $noPagination = null, $pagPerPage = null, $condsIn = null);

    public function setStatus($id, $status);

    public function importCSVFile($locationCityData);
}
