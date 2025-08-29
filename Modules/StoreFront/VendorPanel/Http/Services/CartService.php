<?php

namespace Modules\StoreFront\VendorPanel\Http\Services;

use stdClass;
use App\Models\User;
use App\Config\ps_constant;
use App\Http\Services\PsService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\ItemInfo;
use Modules\Core\Http\Services\UserAccessApiTokenService;
use Modules\StoreFront\VendorPanel\Entities\Cart;
use Modules\Core\Entities\Item\CartItem;
use Modules\Core\Http\Services\Item\CartItemService;
use Modules\StoreFront\VendorPanel\Transformers\Api\App\V1_0\Cart\ItemFromCartApiResource;

class CartService extends PsService
{
    const parentPath = "Pages/vendor/views/order_list/";
    const indexPath = self::parentPath . 'Index';
    const createPath = self::parentPath . 'Create';
    const editPath = self::parentPath . 'Edit';
    const indexRoute = "vendor_order_list.index";
    const createRoute = "vendor_order_list.create";
    const editRoute = "vendor_order_list.edit";

    protected $userAccessApiTokenService, $cartItemService;

    public function __construct(UserAccessApiTokenService $userAccessApiTokenService, CartItemService $cartItemService)
    {
        $this->userAccessApiTokenService = $userAccessApiTokenService;
        $this->cartItemService = $cartItemService;
    }

    public function getCart($id = null, $userId = null, $relation = null)
    {
        $cart = Cart::when($id, function ($q, $id) {
            $q->where(Cart::id, $id);
        })
            ->when($userId, function ($q, $userId) {
                $q->where(Cart::userId, $userId);
            })
            ->when($relation, function ($q, $relation) {
                $q->with($relation);
            })
            ->first();

        return $cart;
    }

    public function addToCartFromApi($request)
    {

        /// check permission start
        $deviceToken = $request->header(ps_constant::deviceTokenKeyFromApi);
        $userId = $request->login_user_id;

        // user token layer permission start
        $userAccessApiToken = $this->userAccessApiTokenService->getUserAccessApiToken($userId, $deviceToken);
        // user token layer permission end

        if (empty($userAccessApiToken)) {
            $msg = __('shipping_and_billing__api_store_info_no_permission', [], $request->language_symbol);
            return responseMsgApi($msg, Constants::forbiddenStatusCode);
        }
        /// check permission end

        $userId = $request->user_id;
        $vendorId = $request->vendor_id;
        $quantity = $request->quantity;
        $itemId = $request->item_id;
        $isSelect = $request->is_select;

        $getItemInfo = ItemInfo::where(ItemInfo::itemId, $itemId)
            ->where(ItemInfo::coreKeysId, 'ps-itm00046')
            ->first();

        $availableQty = !empty($getItemInfo->value) ? $getItemInfo->value : 0;
        if ($quantity > $availableQty) {
            $msg = __("item_qty_insufficient", [], $request->language_symbol) . " " . $availableQty;
            return responseMsgApi($msg, Constants::badRequestStatusCode);
        }

        DB::beginTransaction();

        try {

            $existingCart = $this->getCart(null, $userId);
            if (!empty($this->getCart(null, $userId))) {
                if ((int)$existingCart->vendor_id !== (int)$vendorId) {
                    $existingCart->delete();
                }
            }

            if (empty($this->getCart(null, $userId))) {
                $cart = new Cart();
                $cart->vendor_id = $vendorId;
                $cart->user_id = $userId;
                $cart->added_user_id = $userId;
                $cart->save();
            }


            $cartId = $this->getCart(null, $userId)->id;
            $cartItem = $this->cartItemService->get(null, $itemId);
            if (!empty($cartItem)) {
                $cartItem->cart_id = $cartId;
                $cartItem->quantity = $quantity;
                $cartItem->item_id = $itemId;
                $cartItem->added_user_id = $userId;
                $cartItem->is_select = $isSelect;
                $cartItem->update();
            } else {
                $cartItem = new CartItem();
                $cartItem->cart_id = $cartId;
                $cartItem->quantity = $quantity;
                $cartItem->item_id = $itemId;
                $cartItem->added_user_id = $userId;
                $cartItem->is_select = $isSelect;
                $cartItem->save();
            }

            DB::commit();
            $msg = __("item_add_to_cart_success", [], $request->language_symbol);
            return responseMsgApi($msg, Constants::okStatusCode, Constants::successStatus);
        } catch (\Throwable $e) {
            DB::rollBack();
            $msg = __($e->getMessage(), [], $request->language_symbol);
            return responseMsgApi($msg, Constants::badRequestStatusCode);
        }
    }

    public function delItemFromCartFromApi($request)
    {

        /// check permission start
        $deviceToken = $request->header(ps_constant::deviceTokenKeyFromApi);
        $userId = $request->login_user_id;

        // user token layer permission start
        $userAccessApiToken = $this->userAccessApiTokenService->getUserAccessApiToken($userId, $deviceToken);
        // user token layer permission end

        if (empty($userAccessApiToken)) {
            $msg = __('shipping_and_billing__api_store_info_no_permission', [], $request->language_symbol);
            return responseMsgApi($msg, Constants::forbiddenStatusCode);
        }
        /// check permission end

        $cartItemIds = $request->cart_item_ids;

        DB::beginTransaction();

        try {

            CartItem::destroy($cartItemIds);

            DB::commit();
            $msg = __("item_delete_from_cart_success", [], $request->language_symbol);
            return responseMsgApi($msg, Constants::okStatusCode, Constants::successStatus);
        } catch (\Throwable $e) {
            DB::rollBack();
            $msg = __($e->getMessage(), [], $request->language_symbol);
            return responseMsgApi($msg, Constants::badRequestStatusCode);
        }
    }

    public function getAllItemFromCartFromApi($request)
    {

        /// check permission start
        $deviceToken = $request->header(ps_constant::deviceTokenKeyFromApi);
        $userId = $request->login_user_id;

        // user token layer permission start
        $userAccessApiToken = $this->userAccessApiTokenService->getUserAccessApiToken($userId, $deviceToken);
        // user token layer permission end

        if (empty($userAccessApiToken)) {
            $msg = __('shipping_and_billing__api_store_info_no_permission', [], $request->language_symbol);
            return responseMsgApi($msg, Constants::forbiddenStatusCode);
        }
        /// check permission end

        $userId = $request->login_user_id;
        $isCheckoutPage = $request->is_checkout_page;

        $relation = [
            "cartItems" => ["item" => ["cover"]],
            "vendor" => ["vendorCurrency", "vendorDeliverySetting"],
        ];

        $cart = $this->getCart(null, $userId, $relation);

        if (!empty($cart)) {

            if ($cart->cartItems->isNotEmpty()) {
                $data = new ItemFromCartApiResource($cart);
                return responseDataApi($data);
            }
        }

        $msg = __("core__api_record_not_found", [], $request->language_symbol);
        return responseMsgApi($msg, Constants::noContentStatusCode);
    }

    public function feAddToCart($request)
    {
        $existingCart = Cart::where('user_id', $request->user_id)
            ->first();

        DB::beginTransaction();

        try {
            if ($existingCart) {
                $existingVendorId = $existingCart->vendor_id;
                $requestVendorId = (int) $request->vendor_id;
                if ($existingVendorId !== $requestVendorId) {
                    return response()->json(['error' => 'Cannot add items from different vendors to the same cart.'], 400);
                } else {
                    $cartId = $existingCart->id;
                }
            } else {
                $cart = new Cart();
                if (isset($request->vendor_id) && !empty($request->vendor_id)) {
                    $cart->vendor_id = $request->vendor_id;
                }
                if (isset($request->user_id) && !empty($request->user_id)) {
                    $cart->user_id = $request->user_id;
                    $cart->added_user_id = $request->user_id;
                    $cart->updated_user_id = $request->user_id;
                }
                $cart->save();
                $cartId = $cart->id;
            }



            $cartItem = CartItem::where('cart_id', $cartId)
                ->where('item_id', $request->item_id)
                ->first();

            if ($cartItem) {
                // Update the quantity of the existing cart item
                $cartItem->quantity += $request->quantity;
                $cartItem->save();
            } else {
                // Create a new cart item
                $cartItem = new CartItem();
                $cartItem->cart_id = $cartId;
                $cartItem->item_id = $request->item_id;
                $cartItem->quantity = $request->quantity;
                $cartItem->added_user_id = $request->user_id;
                $cartItem->updated_user_id = $request->user_id;
                $cartItem->save();
            }
            DB::commit();

            $cart = Cart::find($cartId);
            $cartItems = CartItem::where('cart_id', $cartId)->get();

            $dataArr = [
                'cart' => $cart,
                'cartItems' => $cartItems,
                'query' => json_decode(json_encode($request->all()))
            ];

            return response()->json($dataArr);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function feShoppingCart($request)
    {
        $existingCart = Cart::where('user_id', $request->user_id)->first();
        //dd($existingCart);
        try {
            if (!$existingCart) {
                return response()->json(['Empty' => 'No Item in Shopping Cart.'], 400);
            } else {
                $cartId = $existingCart->id;
                $cartItem = CartItem::where('cart_id', $cartId)->get();

                $dataArr = [
                    'cart' => $existingCart,
                    'cartItems' => $cartItem,
                    'query' => json_decode(json_encode($request->all()))
                ];

                return response()->json($dataArr);
            }
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
