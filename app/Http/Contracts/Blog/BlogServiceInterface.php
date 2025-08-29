<?php

namespace App\Http\Contracts\Blog;

use App\Http\Contracts\Core\PsInterface;

interface BlogServiceInterface extends PsInterface
{
    public function save($blogData, $blogImage);

    public function update($id, $blogData, $blogImageId, $blogImage);

    public function delete($id);

    public function get($id, $relation = null);

    public function getAll(
        $relation = null,
        $status = null,
        $limit = null,
        $offset = null,
        $noPagination = null,
        $pagPerPage = null,
        $conds = null);

    public function setStatus($id, $status);
}
