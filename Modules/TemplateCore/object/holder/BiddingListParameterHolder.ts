export default class BiddingListParameterHolder {

    userId: string = '';
    
    BiddingListParameterHolder() {
        this.userId = '';
        
    }

    toMap(): {} {
        const map = {};
        map['user_id'] = this.userId;
        

        return map;
    }
}