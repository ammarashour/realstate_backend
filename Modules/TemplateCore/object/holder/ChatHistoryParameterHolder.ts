import PsConst from "../constant/ps_constants";

export default class ChatHistoryParameterHolder {

    userId: string = '';
    returnType: string = '';

    ChatHistoryPrameterHolder() {
        this.userId = '';
        this.returnType = '';
    }


    getSellerHistoryList() : ChatHistoryParameterHolder{
        this.userId = '';
        this.returnType = PsConst.CHAT_TYPE_SELLER;

        return this;
    }


    getBuyerHistoryList() : ChatHistoryParameterHolder{
        this.userId = '';
        this.returnType = PsConst.CHAT_TYPE_BUYER;

        return this;
    }


    resetParameterHolder() : ChatHistoryParameterHolder{
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