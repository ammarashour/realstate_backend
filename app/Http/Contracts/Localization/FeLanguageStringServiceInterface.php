<?php

namespace App\Http\Contracts\Localization;

use App\Config\ps_constant;
use App\Http\Contracts\Core\PsInterface;

interface FeLanguageStringServiceInterface extends PsInterface
{
    public function save($langStringData, $isGenerateLangJson = true);

    public function update($languageStringId, $langStringData, $isGenerateLangJson = true);

    public function get($id = null, $key = null, $languageId = null);

    public function getAll($languageId = null, $relations = null, $pagPerPage = null, $conds = null, $id = null, $key = null);

    public function delete($languageId, $languageStringId);

    public function deleteByLanguageId($languageId);

    public function importCSV($languageId, $csvFile);

    public function exportJson($languageId);

    public function exportCSV($languageId);

    public function generateJsonFiles($languageId = '');

    public function copyAll($from, $to);

    public function importLanguageStrings(array $toLanguages, array $langStrings, $targetLanguage = null, $prefix = ps_constant::feLangStringPrefix);

    public function generateJsonFilesWithLanguageKeys(array $keys, $toLanguages = []);
}
