export default class UserExistParameterHolder {

    id: string = '';
    google_id: string = '';
    email: string = '';
    facebook_id: string = '';
    apple_id: string = '';
    loginMethod: string = '';

    UserExistParameterHolder() {
        this.id = '';
        this.google_id = '';
        this.email = '';
        this.facebook_id = '';
        this.apple_id = "";
        this.loginMethod = '';
    }

    toMap(): {} {
        const map = {};
        map['id'] = this.id;
        map['google_id'] = this.google_id;
        map['email'] = this.email;
        map['facebook_id'] = this.facebook_id;
        map['apple_id'] = this.apple_id;
        map['loginMethod'] = this.loginMethod;


        return map;
    }
}
