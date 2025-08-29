

export default class ResendCodeHolder {

    userEmail: string = '';


    ResendCodeHolder() {
        this.userEmail = '';

    }


    toMap(): {} {
        const map = {};
        map['user_email'] = this.userEmail;
        return map;
    }
}