import PsConst from "../constant/ps_constants";

export default class LocationTownshipParameterHolder {

    keyword : string = '';
    orderBy : string = 'name';
    orderType : string = 'asc';
    locationId : string = '';

    LocationTownshipParameterHolder() {
        this.keyword ='';
        this.orderBy = 'name';
        this.orderType = 'asc';
        this.locationId = '';
    }

    toMap(): {} {
        const map = {};
        map['keyword'] = this.keyword;
        map['order_by'] = this.orderBy;
        map['order_type'] = this.orderType;
        map['city_id'] = this.locationId;

        return map;
    }
}