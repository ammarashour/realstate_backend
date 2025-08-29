

export default class ForgotpasswordParameterHolder {

    userEmail: string = '';


    ForgotpasswordParameterHolder() {
        this.userEmail = '';

    }


    toMap(): {} {
        const map = {};
        map['email'] = this.userEmail;
        return map;
    }
}