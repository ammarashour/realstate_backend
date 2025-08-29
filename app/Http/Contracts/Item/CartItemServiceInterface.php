<?php

namespace App\Http\Contracts\Item;

use App\Http\Contracts\Core\PsInterface;

interface CartItemServiceInterface extends PsInterface
{

    public function get($id = null, $itemId = null, $cartId = null);

    public function getAll($id = null, $itemId = null, $cartId = null, $relation = null, $addedUserId = null, $pagPerPage = null, $noPagination = null);
}
