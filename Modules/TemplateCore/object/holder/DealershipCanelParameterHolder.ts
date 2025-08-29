export default class DealershipCanelParameterHolder {

    userId: string = '';

    DealershipCanelParameterHolder() {
       this.userId = '';
   }

   toMap(): {} {
       const map = {};
       map['user_id'] = this.userId;

       return map;
   }
}