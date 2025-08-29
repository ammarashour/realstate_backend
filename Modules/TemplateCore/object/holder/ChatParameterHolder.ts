import PsConst from '@templateCore/object/constant/ps_constants';

export default class ChatPrameterHolder {


    addedDateTimeStamp: Number = 0;
    id: string = '';
    isSold: Boolean = false;
    isUserBought: Boolean = false;
    itemId: string = '';
    message: string = '';
    offerStatus: Number = 0;
    sendByUserId: string = '';
    sessionId: string = '';
    type: Number = 0;

    ChatParameterHolder(){
        this.addedDateTimeStamp = Math.floor(Date.now());
        this.id = '';
        this.isSold = false;
        this.isUserBought = false;
        this.itemId = '';
        this.message = 'hi';
        this.offerStatus = PsConst.CHAT_STATUS_NULL;
        this.sendByUserId = '';
        this.sessionId = '';
        this.type = PsConst.CHAT_TYPE_TEXT;

        return this;


    }
    ChatAcceptParameterHolder(): ChatPrameterHolder{
        this.addedDateTimeStamp = Math.floor(Date.now());
        this.id = '';
        this.isSold = false;
        this.isUserBought = false;
        this.itemId = '';
        this.message = '';
        this.offerStatus = PsConst.CHAT_STATUS_ACCEPT;
        this.sendByUserId = '';
        this.sessionId = '';
        this.type = PsConst.CHAT_TYPE_ACCEPT;
        return this;


    }

    toMap(): {} {
        const map = {};
        map['item_id'] = this.itemId;
        map['send_by_user_id'] = this.sendByUserId;
        map['message'] = this.message;
        map['type'] = this.type;



        return map;
    }
}