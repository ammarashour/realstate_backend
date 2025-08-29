export default class AddBiddingParameterHolder {

    itemId: string = '';
    bidPrice: string = '';
    currentBidPrice: string = '';
    currentBidUserId: string = '';
    currentBidDateTime: string = '';
    addedUserId: string = '';


    AppInfoParameterHolder() {
        this.itemId = '';
        this.bidPrice = '';
        this.currentBidPrice = '';
        this.currentBidUserId = '';
        this.currentBidDateTime = '';
        this.addedUserId = '';
    }

    toMap() : {} {
        const map = {};
        map['item_id'] = this.itemId;
        map['bid_price'] = this.bidPrice;
        map['current_bid_price'] = this.currentBidPrice;
        map['current_bid_user_id'] = this.currentBidUserId;
        map['current_bid_date_time'] = this.currentBidDateTime;
        map['added_user_id'] = this.addedUserId;
        

        return map;
    }
}