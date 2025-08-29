<?php

namespace App\Http\Contracts\Localization;

use App\Http\Contracts\Core\PsInterface;

interface LanguageServiceInterface extends PsInterface
{
    public function save($beLangData);

    public function update($id, $beLangData);

    public function get($id = null, $conds = null);

    public function getAll($relations = null, $pagPerPage = null, $conds = null);

    public function delete($id);

    public function setStatus($id, $status);
    
}
