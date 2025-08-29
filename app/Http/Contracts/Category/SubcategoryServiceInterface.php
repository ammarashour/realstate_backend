<?php

namespace App\Http\Contracts\Category;

use App\Http\Contracts\Core\PsInterface;

interface SubcategoryServiceInterface extends PsInterface
{
    public function save($subcategoryData, $subcategoryImage, $subcategoryIcon);

    public function update($id, $subcategoryData, $subcategoryImageId, $subcategoryImage, $subcategoryIconId, $subcategoryIcon);

    public function delete($id);

    public function get($id = null, $name = null, $languageId = null, $relation = null, $conds = null);

    public function getAll($relation = null, $status = null, $languageId = null, $limit = null, $offset = null, $conds = null, $noPagination = null, $pagPerPage = null);

    public function setStatus($id, $status);

    public function importCSVFile($subcategoryData);
}
