import PsConst from "../constant/ps_constants";

export default class ManufacturerParameterHolder {


    orderBy: string = '';


    ManufacturerParameterHolder() {

        this.orderBy = PsConst.FILTERING__ADDED_DATE;

    }


    getTrendingParameterHolder(): ManufacturerParameterHolder {

        this.orderBy = PsConst.FILTERING__TRENDING;


        return this;
    }


    getLatestParameterHolder(): ManufacturerParameterHolder {

        this.orderBy = PsConst.FILTERING__ADDED_DATE;

        return this;
    }


    toMap(): {} {
        const map = {};

        map['order_by'] = this.orderBy;


        return map;
    }
}