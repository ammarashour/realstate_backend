

export default class MarkSoldOutItemParameterHolder {


    itemId: string = '';


    MarkSoldOutItemParameterHolder() {

        this.itemId = '';

    }


    markSoldOutItemHolder(): MarkSoldOutItemParameterHolder {

        this.itemId = '';


        return this;
    }


    resetParameterHolder(): MarkSoldOutItemParameterHolder {

        this.itemId = '';

        return this;
    }


    toMap(): {} {
        const map = {};

        map['item_id'] = this.itemId;


        return map;
    }
}