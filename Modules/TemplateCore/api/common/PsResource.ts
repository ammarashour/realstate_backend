import PsStatus from "./PsStatus";

export default class PsResource<T> {

    message : string = "";
    code : Number = 0;
    status : PsStatus = PsStatus.NOACTION;
    data : T = null as any;

    init(status: PsStatus, code: Number, message: string, data: T) {
        if (message != null && message.includes('##')) {
            /**
             * Backend will reply error code within message 
             * Error code and message are seperated with '##' sign
             * 
             * Error code 10001 = // Totally No Record
             * Error code 10002 = // No More Record at pagination
             */

            const messageList : string[] = message.split('##');
            if (messageList != null && messageList.length > 1) {
                this.message = message;
                this.code = code;
            }
        }else {
            this.message = message;
            this.code = code;
        }
        this.data = data;
        this.status = status;
        return this;

    }
   

}
