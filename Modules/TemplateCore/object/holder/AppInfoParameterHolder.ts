export default class AppInfoParameterHolder {

    startDate: string = '';
    endDate: string = '';
    userId: string = '';

    AppInfoParameterHolder() {
        this.startDate = '';
        this.endDate = '';
        this.userId = '';
    }

    toMap() : {} {
        const map = {};
        map['start_date'] = this.startDate;
        map['end_date'] = this.endDate;
        map['user_id'] = this.userId;

        return map;
    }
}