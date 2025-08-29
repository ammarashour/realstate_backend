export default class UserEmailVerifyParameterHolder {

     email: string = '';
     userId: string = '';
     code: string = '';

   UserEmailVerifyParameterHolder() {
       this. email = '';
       this.userId = '';
       this. code = '';
   }

   toMap(): {} {
       const map = {};
       map['email'] = this.email;
       map['user_id'] = this.userId;
       map['code'] = this.code;

       return map;
   }
}