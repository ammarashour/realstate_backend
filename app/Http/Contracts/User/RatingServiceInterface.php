<?php

namespace App\Http\Contracts\User;

use App\Http\Contracts\Core\PsInterface;

interface RatingServiceInterface extends PsInterface
{
    public function save($ratingData);

    public function update($id, $ratingData);

    // public function delete($ratingData);

    public function get($id = null, $conds = null, $relation = null);

    public function getAll($relation = null, $conds = null, $limit = null, $offset = null);
}
