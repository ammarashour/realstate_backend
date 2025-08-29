export default class ChangePasswordParameterHolder {

    userId: string = '';
    userPassword: string = '';
    confPassword: string = '';
    userOldPassword: string = '';

    ChangePasswordParameterHolder() {
        this.userId = '';
        this.userPassword = '';
        this.confPassword = '';
        this.userOldPassword = '';
    }

    toMap(): {} {
        const map = {};
        map['user_id'] = this.userId;
        map['user_password'] = this.userPassword;
        map['conf_password'] = this.confPassword;
        map['old_password'] = this.userOldPassword;

        return map;
    }
}