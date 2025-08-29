export default class UserListParameterHolder{

    keyword: string = '';
    orderBy: string = 'name';
    orderType: string = 'asc';
    

    UserListParameterHolder() {
        this.keyword = '';
        this.orderBy = 'name';
        this.orderType = 'asc';

        return this;
    }

    toMap(): {} {
        const map = {};
        map['keyword'] = this.keyword;
        map['order_by'] = this.orderBy;
        map['order_type'] = this.orderType;

        return map;
    }
}