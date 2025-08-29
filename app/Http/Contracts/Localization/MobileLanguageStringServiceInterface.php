<?php

namespace App\Http\Contracts\Localization;

use App\Http\Contracts\Core\PsInterface;

interface MobileLanguageStringServiceInterface extends PsInterface
{
    public function save($mbLangStringData);

    public function update($mobileLanguageStringId, $mbLangStringData);

    public function get($id = null, $key = null, $languageId = null);

    public function getAll($mobileLanguageId = null, $relation = null, $limit = null, $offset = null, $conds = null, $noPagination = null,  $pagPerPage = null, $key = null);

    public function delete($mobileLanguageId, $mobileLanguageStringId);    

    public function deleteByLanguageId($languageId);
    
    public function importCSV($languageId, $csvFile);

    public function exportJson($languageId);

    public function exportCSV($languageId);

    public function updateCode($languageId);

    public function importLanguageStrings($toLanguages, $langStrings, $targetLanguage = null);

    public function copyAll($from, $to);
}
