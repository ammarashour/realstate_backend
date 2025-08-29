export default class ReportItemHolder {

    itemId: string = '';
    reportedUserId: string = '';



    reportItemHolder() {

        this.itemId = '';
        this.reportedUserId = '';


    }

    toMap(): {} {
        const map = {};
        map['item_id'] = this.itemId;
        map['reported_user_id'] = this.reportedUserId;
        

        return map;
    }
}