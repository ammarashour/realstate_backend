<?php

namespace App\Http\Contracts\Image;

use App\Http\Contracts\Core\PsInterface;

interface ImageServiceInterface extends PsInterface
{
    public function save($file, $imgData, $extension = null);

    public function update($id, $file, $imgData);

    public function delete($img_path);

    public function deleteAll($imgParentId, $imgType);

    public function get($conds);

    public function getAll($imgParentId = null, $imgType = null, $limit = null, $offset = null, $notImgTypes = null, $conds = null);

    public function saveVideo($file, $data);

    public function updateVideo($id, $file, $data);
}
