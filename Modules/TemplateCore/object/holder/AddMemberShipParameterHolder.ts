export default class AddMemberShipParameterHolder {

    nameOnCard: string = '';
    cardNo: string = '';
    cvv: string = '';
    month: string = '';
    year: string = '';
    paymentMethodNonce: string = '';
    userId: string = '';
    startDate: string = '';

    AddMemberShipParameterHolder() {
        this.nameOnCard = '';
        this.cardNo = '';
        this.cvv = '';
        this.month = '';
        this.year = '';
        this.paymentMethodNonce = '';
        this.userId = '';
        this.startDate = '';
    }

    toMap(): {} {
        const map = {};
        map['name_on_card'] = this.nameOnCard;
        map['card_no'] = this.cardNo;
        map['cvc'] = this.cvv;
        map['month'] = this.month;
        map['year'] = this.year;
        map['payment_method_nonce'] = this.paymentMethodNonce;
        map['user_id'] = this.userId;
        map['start_date'] = this.startDate;


        return map;
    }
}