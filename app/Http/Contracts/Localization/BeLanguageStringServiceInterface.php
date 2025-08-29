<?php

namespace App\Http\Contracts\Localization;

use App\Config\ps_constant;
use App\Enums\Language\InsertionSource;
use App\Http\Contracts\Core\PsInterface;

interface BeLanguageStringServiceInterface extends PsInterface
{
    public function save($langStringData, $isGenerateJson = true);

    public function update($languageStringId, $langStringData, $isGenerateJson = true);

    public function updateOrInsert($langStrings);

    public function getAll($languageId, $relations = null, $pagPerPage = null, $conds = null, $id = null, $key = null);

    public function get($id = null, $key = null, $languageId = null, $keyword = null);

    public function getLanguageStringsMapped($key);

    public function delete($languageId, $languageStringId);

    public function deleteByLanguageId($languageId);

    public function deleteByIsFromBuilderFlag($isFromBuilder);

    public function importCSV($languageId, $csvFile);

    public function exportJson($languageId);

    public function exportCSV($languageId);

    public function generateJsonFiles($languageId = '');

    public function copyAll($from, $to);
    
    public function importLanguageStrings(array $toLanguages, array $langStrings, $targetLanguage = null, $prefix = ps_constant::beLangStringPrefix, InsertionSource $insertionSource = InsertionSource::DEFAULT);

    public function generateJsonFilesWithLanguageKeys(array $keys, $toLanguages = []);

}
