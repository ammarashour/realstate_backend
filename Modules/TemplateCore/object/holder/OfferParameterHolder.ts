import PsConst from "../constant/ps_constants";

export default class OfferParameterHolder {

    userId: string = '';
    returnType: string = '';

    OfferParameterHolder() {
        this.userId = '';
        this.returnType = '';
    }


    getOfferSentList() : OfferParameterHolder{
        this.userId = '';
        this.returnType = PsConst.CHAT_TYPE_SELLER;

        return this;
    }


    getOfferReceivedList() : OfferParameterHolder{
        this.userId = '';
        this.returnType = PsConst.CHAT_TYPE_BUYER;

        return this;
    }


    resetParameterHolder() : OfferParameterHolder{
        this.userId = '';
        this.returnType = '';

        return this;
    }

    toMap(): {} {
        const map = {};
        map['user_id'] = this.userId;
        map['return_type'] = this.returnType;

        return map;
    }
}