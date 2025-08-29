type SubCategoryListParameterHolderInterface = {
    keyword: string;
    orderBy: string;
    orderType: string;
}

export default class SubCategoryListParameterHolder implements SubCategoryListParameterHolderInterface{

    keyword: string = '';
    catId: string = '';
    catName : string = '';
    orderBy: string = '';
    orderType: string = '';
    

    SubCategoryListParameterHolder() {
        this.keyword = '';
        this.catId = '';
        this.orderBy = '';
        this.orderType = '';

        return this;
    }

    toMap(): {} {
        const map = {};
        map['keyword'] = this.keyword;
        map['category_id'] = this.catId;
        map['order_by'] = this.orderBy;
        map['order_type'] = this.orderType;

        return map;
    }
}