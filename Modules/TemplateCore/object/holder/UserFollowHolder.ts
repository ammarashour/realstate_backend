export default class UserFollowHolder {

    userId: string = '';
    followedUserId: string = '';

    UserFollowHolder() {
        this.userId = '';
        this.followedUserId = '';
    }

    toMap(): {} {
        const map = {};
        map['user_id'] = this.userId;
        map['followed_user_id'] = this.followedUserId;

        return map;
    }
}