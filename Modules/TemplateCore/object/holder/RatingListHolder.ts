export default class RatingListHolder {


    userId: string = '';


    RatingListHolder() {

        this.userId = '';

    }

    toMap(): {} {
        const map = {};

        map['user_id'] = this.userId;


        return map;
    }
}