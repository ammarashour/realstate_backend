<?php

namespace App\Http\Contracts\Localization;

use App\Http\Contracts\Core\PsInterface;

interface MobileLanguageServiceInterface extends PsInterface
{
    public function save($mbLangData);

    public function update($id, $mbLangData);

    public function getAll($enable = null, $limit = null, $offset = null, $conds = null, $noPagination = null,  $pagPerPage = null);

    public function get($id = null, $conds = null);

    public function delete($id);

    public function setStatus($id, $status);

    public function enableDisable($id, $status);
}