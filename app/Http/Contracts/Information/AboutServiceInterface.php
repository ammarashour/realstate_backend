<?php

namespace App\Http\Contracts\Information;

use App\Http\Contracts\Core\PsInterface;

interface AboutServiceInterface extends PsInterface
{
    public function save($aboutData);

    public function update($id, $aboutData, $file);

    public function get($id = null, $relation = null);

}