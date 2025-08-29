<?php

namespace App\Http\Contracts\CustomizeTheme;

use App\Http\Contracts\Core\PsInterface;

interface ThemeScreenServiceInterface extends PsInterface
{
    public function save();

    public function update($id, $themeScreenData);

    public function delete();

    public function get($id = null, $relation = null, $conds = null);

    public function getAll($relation = null, $limit = null, $offset = null, $noPagination = null, $pagPerPage = null, $conds = null);
}
