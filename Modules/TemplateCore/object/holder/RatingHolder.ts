export default class RatingHolder {

    fromUserId: string = '';
    toUserId: string = '';
    title: string = '';
    description: string = '';
    rating: string = '';
    type: string = '';


    RatingHolder() {
        this.fromUserId = '';
        this.toUserId = '';
        this.title = '';
        this.description = '';
        this.rating = '';
        this.type = '';

    }

    toMap(): {} {
        const map = {};
        map['from_user_id'] = this.fromUserId;
        map['to_user_id'] = this.toUserId;
        map['title'] = this.title;
        map['description'] = this.description;
        map['rating'] = this.rating;
        map['type'] = this.type;

        return map;
    }
}
