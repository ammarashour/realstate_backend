<?php

namespace App\Http\Contracts\Localization;

use App\Http\Contracts\Core\PsInterface;

interface CategoryLanguageStringServiceInterface extends PsInterface
{
    public function save($categoryLangStringData);

    public function update($id, $categoryLangStringData);

    public function getAll($languageId, $relations = null, $pagPerPage = null, $conds = null);

    public function get($id = null, $key = null, $languageId = null, $categoryId = null);

    public function delete($languageStringId);
}
