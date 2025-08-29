import { PsObject } from "@templateCore/object/core/PsObject";
import DefaultPhoto from "./DefaultPhoto";


export default class CartItem extends PsObject<CartItem> {

    cartItemId : string = '';
    itemId : string = '';
    itemName : string = '';
    originalPrice : string = '';
    salePrice : string = '';
    discountPrice : string = '';
    vendorCurrencySymbol: string = '';
    quantity: string = '';
    availableQuantity: string = '';
    subTotal: string = '';
    isSoldOut: string = '';
    isSelect: string = '';
    defaultPhoto: DefaultPhoto = new DefaultPhoto();

    init(
        cartItemId : string,
        itemId : string,
        itemName : string,
        originalPrice : string,
        salePrice : string,
        discountPrice : string,
        vendorCurrencySymbol: string,
        quantity: string,
        availableQuantity: string,
        subTotal: string,
        isSoldOut: string,
        isSelect: string,
        defaultPhoto: DefaultPhoto,
    ) {
        this.cartItemId = cartItemId;
        this.itemId = itemId;
        this.itemName = itemName;
        this.originalPrice = originalPrice;
        this.salePrice = salePrice;
        this.discountPrice = discountPrice;
        this.vendorCurrencySymbol = vendorCurrencySymbol;
        this.quantity = quantity;
        this.availableQuantity = availableQuantity;
        this.subTotal = subTotal;
        this.isSoldOut = isSoldOut;
        this.isSelect = isSelect;
        this.defaultPhoto = defaultPhoto;

        return this;

    }

    getPrimaryKey(): string {
        return this.cartItemId;
    }

    toMap(object: CartItem): any {
        const map = {};

        map['cart_item_id'] = object.cartItemId;
        map['item_id'] = object.itemId;
        map['item_name'] = object.itemName;
        map['original_price'] = object.originalPrice;
        map['sale_price'] = object.salePrice;
        map['discount_price'] = object.discountPrice;
        map['vendor_currency_symbol'] = object.vendorCurrencySymbol;
        map['quantity'] = object.quantity;
        map['available_quantity'] = object.availableQuantity;
        map['sub_total'] = object.subTotal;
        map['is_sold_out'] = object.isSoldOut;
        map['is_select'] = object.isSelect;
        map['default_photo'] = new DefaultPhoto().toMap(object.defaultPhoto);


        return map;
    }

    toMapList(objectList: CartItem[]): any[] {
        const mapList: any[] = [];
        for (let i = 0; i < objectList.length; i++) {
            if (objectList[i] != null) {
                mapList.push(this.toMap(objectList[i]));
            }
        }

        return mapList;
    }

    fromMap(obj: any) {
        return new CartItem().init(
            obj.cart_item_id,
            obj.item_id,
            obj.item_name,
            obj.original_price,
            obj.sale_price,
            obj.discount_price,
            obj.vendor_currency_symbol,
            obj.quantity,
            obj.available_quantity,
            obj.sub_total,
            obj.is_sold_out,
            obj.is_select,
            new DefaultPhoto().fromMap(obj.default_photo),
        );
    }

    fromMapList(objList: any[]): CartItem[] {
        const list: CartItem[] = [];
        for (const obj of objList as Array<CartItem>) {
            if (obj != null) {
                list.push(this.fromMap(obj));
            }
        }

        return list;
    }
}
