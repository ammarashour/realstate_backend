type CategoryListParameterHolderInterface = {
    keyword: string;
    orderBy: string;
    orderType: string;
}

export default class CategoryListParameterHolder implements CategoryListParameterHolderInterface{

    keyword: string = '';
    orderBy: string = '';
    orderType: string = '';
    

    CategoryListParameterHolder() {
        this.keyword = '';
        this.orderBy = '';
        this.orderType = '';

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