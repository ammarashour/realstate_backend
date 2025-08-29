<?php

namespace App\Http\Contracts\Location;

use App\Http\Contracts\Core\PsInterface;

interface LocationTownshipServiceInterface extends PsInterface
{
    public function save($townshipData);

    public function update($id, $townshipData);

    public function get($id = null, $status = null);

    public function getAll($relation = null, $status = null, $limit = null, $offset = null, $conds = null, $noPagination = null, $pagPerPage = null);

    public function delete($id);

    public function setStatus($id, $status);

    public function importCSVFile($townshipData);
}
