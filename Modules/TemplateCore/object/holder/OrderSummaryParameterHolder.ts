export default class OrderSummaryParameterHolder{

    itemId: string = '';
    quantity: string = '';
    originalPrice: string = '';
    salePrice: string = '';
    subTotal: string = '';
    discountPrice: string = '';

    OrderSummaryParameterHolder(){
        this.itemId = '';
        this.quantity = '';
        this.originalPrice = '';
        this.salePrice = '';
        this.subTotal = '';
        this.discountPrice = '';
    }

    toMap(): {} {
        const map = {};
        map['item_id'] = this.itemId;
        map['quantity'] = this.quantity;
        map['original_price'] = this.originalPrice;
        map['sale_price'] = this.salePrice;
        map['sub_total'] = this.subTotal;
        map['discount_price'] = this.discountPrice;
        return map;
    }

}

