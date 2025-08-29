<?php

namespace App\Http\Contracts\CustomizeTheme;

use App\Http\Contracts\Core\PsInterface;

interface ComponentAttributeServiceInterface extends PsInterface
{

    public function save();

    public function update($id, $componentAttributeData);

    public function delete($id);

    public function get($id = null, $relation = null, $conds = null);

    public function getAll($relation = null, $limit = null, $offset = null, $noPagination = null, $platformId = null, $conds = null);
}
