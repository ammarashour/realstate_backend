export default class CreditCardPrameterHolder {

    nameOnCard: string = '';
    cardNo: string = '';
    oldCardNo: string = '';
    cvv: string = '';
    expiryDate: string = '';
    expiryMonth: string = '';
    expiryYear: string = '';
    paymentMethodNonce: string = '';
    userId: string = '';
    streetName : string = '';
    city : string = '';
    stateId : string = '';
    stateName : string = '';
    zipCode : string = '';
    apartmentNo : string = '';

    CreditCardPrameterHolder() {
        this.nameOnCard = '';
        this.cardNo = '';
        this.oldCardNo = '';
        this.cvv = '';
        this.expiryDate = '';
        this.expiryMonth = '';
        this.expiryYear = '';
        this.paymentMethodNonce = '';
        this.userId = '';
        this.streetName = '';
        this.city = '';
        this.stateId = '';
        this.stateName = '';
        this.zipCode = '';
        this.apartmentNo = '';

    }

    toMap(): {} {
        const map = {};
        
        map['name_on_card'] = this.nameOnCard;
        map['card_no'] = this.cardNo;
        map['old_card_no'] = this.oldCardNo;
        map['cvc'] = this.cvv;
        map['expiry_date'] = this.expiryDate;
        map['month'] = this.expiryMonth;
        map['year'] = this.expiryYear;
        map['payment_method_nonce'] = this.paymentMethodNonce;
        map['user_id'] = this.userId;
        map['street_name'] = this.streetName;
        map['city'] = this.city;
        map['state_id'] = this.stateId;
        map['state_name'] = this.stateName;
        map['zip_code'] = this.zipCode;
        map['apartment_no'] = this.apartmentNo;

        return map;
    }
}