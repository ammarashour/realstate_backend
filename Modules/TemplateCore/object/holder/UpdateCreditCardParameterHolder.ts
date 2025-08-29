export default class UpdateCreditCardPrameterHolder {

    oldCardNo: string = '';
    oldMonth: string = '';
    oldYear: string = '';
    userId: string = '';
    nameOnCard: string = '';
    newCardNo: string = '';
    cvv: string = '';
    month: string = '';
    year: string = '';
    oldExpiryDate: string = '';
    newExpiryDate: string = '';

    UpdateCreditCardPrameterHolder() {
        this.oldCardNo = '';
        this.oldMonth = '';
        this.oldYear = '';
        this.nameOnCard = '';
        this.userId = '';
        this.newCardNo = '';
        this.cvv = '';
        this.month = '';
        this.year = '';
        this.oldExpiryDate = '';
        this.newExpiryDate = '';
    }

    toMap(): {} {
        const map = {};
        
        map['old_card_no'] = this.oldCardNo;
        map['old_month'] = this.oldMonth;
        map['old_year'] = this.oldYear;
        map['user_id'] = this.userId;
        map['name_on_card'] = this.nameOnCard;
        map['card_no'] = this.newCardNo;
        map['cvc'] = this.cvv;
        map['month'] = this.month;
        map['year'] = this.year;
        map['old_exp_date'] = this.oldExpiryDate;
        map['new_exp_date'] = this.newExpiryDate;

        return map;
    }
}