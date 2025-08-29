import PsConst from "../constant/ps_constants";

export default class PhoneParameterHolder {

    /* URL Params & Query */
    urlParamsAndQuery = {
        'params' : {},
        'query' : {}
    };

    keyword: string = '';
    country_name: string = '';
    country_code: string= '';
    status: string = '';
    is_default: string = '';


    constructor() {

        this.keyword = '';
        this.country_name = '';
        this.country_code = '';
        this.status = '';
        this.is_default = '';

    }

    getUrlParamsAndQuery() {
        const query = {};
        const param = {};

        if(this.keyword != '') {
            query['keyword'] = this.keyword;
        }
        if(this.country_name != '') {
            query['country_name'] = this.country_name;
        }
        if(this.country_code != '') {
            query['country_code'] = this.country_code;
        }
        if(this.status != '') {
            query['status'] = this.status;
        }
        if(this.is_default != '') {
            query['is_default'] = this.is_default;
        }

        // if(this.catId != '') {
        //     query['cat_id'] = this.catId;
        //     param['catName'] = this.catName;
        // }

        // if(this.subCatId != '') {
        //     query['sub_cat_id'] = this.subCatId;
        //     query['sub_cat_name'] = this.subCatName;
        // }




        // this.urlParamsAndQuery['params'] = param;
        this.urlParamsAndQuery.params = param;
        this.urlParamsAndQuery.query = query;
        // this.urlParamsAndQuery['query'] = query;

        return this.urlParamsAndQuery;
    }




    parseParamsAndQuery(query) {


        this.keyword = query.keyword?.toString() ?? '';
        this.country_name = query.country_name?.toString() ?? '';
        this.country_code = query.country_code?.toString() ?? '';
        this.status = query.status?.toString() ?? '';
        this.is_default = query.is_default?.toString() ?? '';


    }

    getRecentParameterHolder() : PhoneParameterHolder{

        this.keyword =  '';
        this.country_name =  '';
        this.country_code =  '';
        this.status =  '';
        this.is_default =  '';


        return this;
    }














    resetParameterHolder() : PhoneParameterHolder{
        this.keyword = '';
        this.country_name = '';
        this.country_code = '';
        this.status = '';
        this.is_default = '';

        return this;
    }


    toMap() : {} {
        const map = {};
        map['keyword'] = this.keyword;
        map['country_name'] = this.country_name;
        map['country_code'] = this.country_code;
        // map['item_type_id'] = this.itemTypeId;
        // map['item_price_type_id'] = this.itemPriceTypeId;
        map['status'] = this.status;
        map['is_default'] = this.is_default;

        return map;
    }

}
