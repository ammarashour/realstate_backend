export default class BlogListParameterHolder {

    searchterm: string = '';
    locationId: string = '';
    orderBy: string;
    orderType: string;
    

    BlogListParameterHolder() {
        this.searchterm = '';
        this.locationId = '';
        this.orderBy = '';
        this.orderType = '';

        return this;
    }

    toMap(): {} {
        const map = {};
        map['keyword'] = this.searchterm;
        map['location_city_id'] = this.locationId;
        map['order_by'] = this.orderBy;
        map['order_type'] = this.orderType;

        return map;
    }
}