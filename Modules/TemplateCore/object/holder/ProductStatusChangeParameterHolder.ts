

export default class ProductStatusChangeParameterHolder {



    itemId: string = '';
    status: string = '';


    ProductStatusChangeParameterHolder() {

        this.itemId = '';
        this.status='';

    }


    ProductStatusChangeItemHolder(): ProductStatusChangeParameterHolder {

        this.itemId = '';
        this.status='';

        return this;
    }


    resetParameterHolder(): ProductStatusChangeParameterHolder {

        this.itemId = '';
        this.status='';

        return this;
    }


    toMap(): {} {
        const map = {};

        map['item_id'] = this.itemId;
        map['status'] = this.status;


        return map;
    }
}
