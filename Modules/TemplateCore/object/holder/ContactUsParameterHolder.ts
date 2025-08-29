export default class ContactUsPrameterHolder {

    name: string = '';
    email: string = '';
    message: string = '';
    phone: string = '';


    ContactUsPrameterHolder() {
        this.name = '';
        this.email = '';
        this.message = '';
        this.phone = '';
    }

    resetParameterHolder() : ContactUsPrameterHolder{
        this.name = '';
        this.email = '';
        this.message = '';
        this.phone = '';

        return this;
    }

    toMap(): {} {
        const map = {};
        map['contact_name'] = this.name;
        map['contact_email'] = this.email;
        map['contact_message'] = this.message;
        map['contact_phone'] = this.phone;

        return map;
    }
}