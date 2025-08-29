<?php
namespace Modules\StoreFront\VendorPanel\Http\Services;

use App\Http\Services\PsService;
use Modules\Core\Http\Services\UserAccessApiTokenService;
use Modules\StoreFront\VendorPanel\Entities\OrderItem;
use stdClass;

class OrderItemService extends PsService
{

    protected $userAccessApiTokenService;

    public function __construct(UserAccessApiTokenService $userAccessApiTokenService)
    {
        $this->userAccessApiTokenService = $userAccessApiTokenService;
    }

    public function getOrderItems($orderId = null)
    {
        $orderItems = OrderItem::when($orderId, function($query, $orderId){
            $query->where(OrderItem::orderId, $orderId);
        })
        ->get();

        return $orderItems;
    }

    public function getOrderItem($itemId = null)
    {
        $orderItem = OrderItem::when($itemId, function($query, $itemId){
            $query->where(OrderItem::itemId, $itemId);
        })
        ->first();

        return $orderItem;
    }

}
