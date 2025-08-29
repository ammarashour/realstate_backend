export default class BuyerCreditCardParameterHolder {

    nameOnCard: string = '';
    cardNo: string = '';
    cvv: string = '';
    month: string = '';
    year: string = '';
    paymentMethodNonce: string = '';
    userId: string = '';

    BuyerCreditCardParameterHolder() {
        this.nameOnCard = '';
        this.cardNo = '';
        this.cvv = '';
        this.month = '';
        this.year = '';
        this.paymentMethodNonce = '';
        this.userId = '';

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


        return map;
    }
}