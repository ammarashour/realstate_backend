export default class UpdateEndTimeHolder {

    itemId: string = '';
    sellerId: string = '';
    biddingStartDateTime: string = '';

    UpdateEndTimeHolder() {
        this.itemId = '';
        this.sellerId = '';
        this.biddingStartDateTime = '';
    }

    toMap(): {} {
        const map = {};
        map['item_id'] = this.itemId;
        map['seller_id'] = this.sellerId;
        map['bidding_start_date'] = this.biddingStartDateTime;

        return map;
    }
}