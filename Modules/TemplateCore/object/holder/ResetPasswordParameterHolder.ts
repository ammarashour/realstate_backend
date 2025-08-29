export default class ResetPasswordParameterHolder {

    userId: string = '';
    userPassword: string = '';
    confPassword: string = '';
    code: string = '';

    ResetPasswordParameterHolder() {
        this.userId = '';
        this.userPassword = '';
        this.confPassword = '';
        this.code = '';
    }

    toMap(): {} {
        const map = {};
        map['user_id'] = this.userId;
        map['user_password'] = this.userPassword;
        map['conf_password'] = this.confPassword;
        map['code'] = this.code;

        return map;
    }
}