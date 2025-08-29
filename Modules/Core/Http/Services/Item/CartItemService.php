<?php
namespace Modules\Core\Http\Services\Item;

use App\Http\Contracts\Item\CartItemServiceInterface;
use stdClass;
use App\Http\Services\PsService;
use Modules\Core\Entities\Item\CartItem;

class CartItemService extends PsService implements CartItemServiceInterface
{

    public function __construct()
    {}

    public function get($id = null, $itemId = null, $cartId = null)
    {
        $cartItem = CartItem::when($id, function($q, $id){
            $q->where(CartItem::id, $id);
        })
        ->when($itemId, function($q, $itemId){
            $q->where(CartItem::itemId, $itemId);
        })
        ->when($cartId, function($q, $cartId){
            $q->where(CartItem::cartId, $cartId);
        })
        ->first();

        return $cartItem;
    }

    public function getAll($id = null, $itemId = null, $cartId = null, $relation = null, $addedUserId = null, $pagPerPage = null, $noPagination = null)
    {
        $cartItems = CartItem::when($id, function($q, $id){
            $q->where(CartItem::id, $id);
        })
        ->when($itemId, function($q, $itemId){
            $q->where(CartItem::itemId, $itemId);
        })
        ->when($cartId, function($q, $cartId){
            $q->where(CartItem::cartId, $cartId);
        })
        ->when($addedUserId, function($q, $addedUserId){
            $q->where(CartItem::addedUserId, $addedUserId);
        })
        ->when($relation, function($q, $relation){
            $q->with($relation);
        });
        if ($pagPerPage) {
            return $cartItems->paginate($pagPerPage)->onEachSide(1)->withQueryString();
        } elseif ($noPagination) {
            return $cartItems->get();
        }

        return $cartItems;
    }
}
