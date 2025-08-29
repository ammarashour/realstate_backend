import PsConst from "../constant/ps_constants";

export default class LocationParameterHolder {

        keyword : string = '';
        orderBy : string = '';
        orderType : string = '';

    LocationParameterHolder() {
        this.keyword ='';
        this.orderBy = PsConst.FILTERING__ADDED_DATE;
        this.orderType = PsConst.FILTERING__DESC;
    }


    getDefaultParameterHolder() : LocationParameterHolder{
        this.keyword ='';
        this.orderBy = PsConst.FILTERING__ORDERING;
        this.orderType =PsConst.FILTERING__DESC;

        return this;
    }


    getLatestParameterHolder() : LocationParameterHolder{
        this.keyword ='';
        this.orderBy = PsConst.FILTERING__ADDED_DATE;
        this.orderType = PsConst.FILTERING__DESC;
        return this;
    }


    toMap(): {} {
        const map = {};
        map['keyword'] = this. keyword;
        map['order_by'] = this. orderBy;
        map['order_type'] = this. orderType;

        return map;
    }
}