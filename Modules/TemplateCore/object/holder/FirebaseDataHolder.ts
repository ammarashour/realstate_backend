export default class FirebaseDataHolder {

    id : string = '';
    winnerBidUserId : string = '';
    finishStatus : string = '';
    biddingStatus : string = '';
    depositStatus : string = '';
    currentBidPrice : string = '';
    currentBidUserName : string = '';
    currentBidUserImgUrl : string = '';
    biddingEndDatetime : string = '';
    
    AppInfoParameterHolder() {
        this.id = '';
        this.winnerBidUserId = '';
        this.finishStatus = '';
        this.biddingStatus = '';
        this.depositStatus = '';
        this.currentBidPrice = '';
        this.currentBidUserName = '';
        this.currentBidUserImgUrl = '';
        this.biddingEndDatetime = '';
    
    }

    parseData(data) {
        this.id = data.id;
        this.winnerBidUserId = data.winner_bid_user_id;
        this.finishStatus = data.finish_status;
        this.biddingStatus = data.bidding_status;
        this.depositStatus = data.deposit_status;
        this.currentBidPrice = data.current_bid_price;
        this.currentBidUserName = data.current_bid_user_name;
        this.currentBidUserImgUrl = data.current_bid_user_img_url;
        this.biddingEndDatetime = data.bidding_end_datetime;
    }

}