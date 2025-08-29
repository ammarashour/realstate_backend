export default class OrderHistoryParameterHolder {

    orderStatus: string = '';
    orderBy: string = '';
    orderType: string = '';
    page: string = '';

    OrderHistoryParameterHolder() {
        this.orderStatus = '';
        this.orderBy = '';
        this.orderType = '';
        this.page = '';
    }

    toMap(): {} {
        const map = {};
        map['order_status'] = this.orderStatus;
        map['order_by'] = this.orderBy;
        map['order_type'] = this.orderType;
        map['page'] = this.page;

        return map;
    }
}
