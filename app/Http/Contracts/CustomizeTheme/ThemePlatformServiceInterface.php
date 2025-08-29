<?php

namespace App\Http\Contracts\CustomizeTheme;

use App\Http\Contracts\Core\PsInterface;

interface ThemePlatformServiceInterface extends PsInterface
{
    public function save();

    public function update($id, $themePlatformData);

    public function delete($id);

    public function get($id = null, $relation = null, $conds = null);

    public function getAll($relation = null, $limit = null, $offset = null, $noPagination = null, $conds = null);
}
