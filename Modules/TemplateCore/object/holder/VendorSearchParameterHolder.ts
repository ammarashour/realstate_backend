import ProductRelation from "../ProductRelation";

export default class VendorSearchParameterHolder {

    searchterm: string = '';
    ownerUserId: string = '';
    orderBy: string = '';
    orderType: string = '';
    status: string = '2';
    productRelation : ProductRelation[] = [new ProductRelation()];

    VendorSearchParameterHolder() {
        this.searchterm = '';
        this.ownerUserId = '';
        this.orderBy = '';
        this.orderType = '';
        this.status = '2';
        this.productRelation = [];
    }

    getAllVendorParameterHolder() : VendorSearchParameterHolder{
        this.searchterm = '' ;
        this.ownerUserId = '' ;
        this.orderBy = 'added_date' ;
        this.orderType = 'desc' ;
        this.status = '2';
        this.productRelation = [];

        return this;
    }

    toMap(): {} {
        const map = {};
        map['searchterm'] = this.searchterm;
        map['owner_user_id'] = this.ownerUserId;
        map['order_by'] = this.orderBy;
        map['order_type'] = this.orderType;
        map['status'] = this.status;
        map['product_relation'] = this.productRelation;

        return map;
    }
}
