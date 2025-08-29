export default class UserCreateParameterHolder {


    loginUserId: string = '';
    username: string = '';
    password: string = '';
    google_id: string = '';
    facebook_id: string = '';
    apple_id: string = '';
    email: string = '';
    name: string = '';
    profile_photo_url: string = '';
    registerForm: number = 1;
    loginMethod:string='';
    user_phone:string='';
    phone_id:string='';


    UserCreateParameterHolder() {
        this.loginUserId = '';
        this.username = '';
        this.password = '';
        this.google_id = '';
        this.facebook_id = '';
        this.apple_id = '';
        this.email = '';
        this.name='';
        this.profile_photo_url='';
        this.registerForm = 1;
        this.loginMethod='';
        this.user_phone='';
        this.phone_id='';

    }

    toMap(): {} {
        const map = {};
        map['login_user_id'] = this.loginUserId;
        map['username'] = this.username;
        map['password'] = this.password;
        map['google_id'] = this.google_id;
        map['facebook_id'] = this.facebook_id;
        map['apple_id'] = this.apple_id;
        map['email'] = this.email;
        map['name'] = this.name;
        map['profile_photo_url'] = this.profile_photo_url;
        map['registerForm']=this.registerForm;
        map['loginMethod']=this.loginMethod;
        map['user_phone']=this.user_phone;
        map['phone_id']=this.phone_id;


        return map;
    }
}
