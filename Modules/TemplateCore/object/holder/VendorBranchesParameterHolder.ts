export default class VendorBranchesParameterHolder {

    vendorId : string = '' ;
    keyword : string = '' ;
    orderBy : string = 'name' ;
    orderType : string = 'asc' ;

    VendorBranchesParameterHolder() {
        this.vendorId = '' ;
        this.keyword = '' ;
        this.orderBy = 'name' ;
        this.orderType = 'asc' ;

        return this;
    }

    resetParameterHolder() : VendorBranchesParameterHolder{
        this.vendorId = '' ;
        this.keyword = '' ;
        this.orderBy = '' ;
        this.orderType = '' ;

        return this;
    }

    toMap(): {} {
        const map = {};
        map['vendor_id'] = this.vendorId;
        map['keyword'] = this.keyword;
        map['order_by'] = this.orderBy;
        map['order_type'] = this.orderType;

        return map;
    }
}
