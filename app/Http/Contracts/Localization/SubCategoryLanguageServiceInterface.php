<?php

namespace App\Http\Contracts\Localization;

use App\Http\Contracts\Core\PsInterface;

interface SubCategoryLanguageServiceInterface extends PsInterface
{
    public function save($values, $key, $subCategoryId);

    public function get($id = null, $key = null, $languageId = null, $subCategoryId = null);

}
