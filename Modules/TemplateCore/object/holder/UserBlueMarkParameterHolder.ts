export default class UserBlueMarkParameterHolder {

    loginUserId: string = '';
    note: string = '';

    UserBlueMarkParameterHolder() {
       this.loginUserId = '';
       this.note = '';
   }

   toMap(): {} {
       const map = {};
       map['user_id'] = this.loginUserId;
       map['note'] = this.note;

       return map;
   }
}