export default class ApproveRejectParameterHolder {

    biddingId: string = '';
    biddingStatus: string = '';

    ApproveRejectParameterHolder() {
        this.biddingId = '';
        this.biddingStatus = '';
    }

    toMap(): {} {
        const map = {};
        map['bidding_id'] = this.biddingId;
        map['bidding_status'] = this.biddingStatus;

        return map;
    }
}