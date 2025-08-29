<?php

namespace App\Http\Contracts\Utilities;

use App\Http\Contracts\Core\PsInterface;

interface CoreFieldServiceInterface extends PsInterface
{
    public function save($coreFieldData);

    public function update($id, $coreFieldData);

    public function delete($id);

    public function deleteAll($isByTruncate = null);

    public function get($id = null);

    public function getAll($code = null, $relation = null, $limit = null, $offset = null, $isDel=null, $withNoPag = null, $pagPerPage = null, $projectId = null, $tableId = null, $conds = null, $notInFieldNames = null);
}
