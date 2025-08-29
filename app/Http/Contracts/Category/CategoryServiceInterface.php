<?php

namespace App\Http\Contracts\Category;

use App\Http\Contracts\Core\PsInterface;

interface CategoryServiceInterface extends PsInterface
{
    public function save($categoryData, $categoryImage, $categoryIcon);

    public function update($id, $categoryData, $categoryImageId, $categoryImage, $categoryIconId, $categoryIcon);

    public function delete($id);

    public function get($id = null, $relation = null, $languageId = null, $conds = null);

    public function getAll($relation = null, $status = null, $languageId = null, $limit = null, $offset = null, $conds = null,$noPagination = null,  $pagPerPage = null,$touchCount = null, $itemCount = null);

    public function setStatus($id, $status);

    public function importCSVFile($categoryData);
}
