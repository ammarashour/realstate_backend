export default class TagListParameterHolder {

    searchterm: string = '';
    keyword: string = '';
    orderBy: string;
    orderType: string;
    

    TagListParameterHolder() {
        this.keyword = '';
        this.orderBy = 'name';
        this.orderType = 'asc';

        return this;
    }

    toMap(): {} {
        const map = {};
        map['keyword'] = this.searchterm;
        map['order_by'] = this.orderBy;
        map['order_type'] = this.orderType;

        return map;
    }
}