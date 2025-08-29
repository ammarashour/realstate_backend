export default class UserReportItemParameterHolder {

    itemId: string = '';
    reportedUserId: string = '';

    UserReportItemParameterHolder() {
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