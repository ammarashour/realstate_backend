import PsConst from "../constant/ps_constants";

export default class UserParameterHolder {

    id : string = '' ;
    overallRating : string = '' ;
    returnTypes : string = '' ;
    loginUserId : string = '' ;
    userName : string = '' ;

    UserParameterHolder() {
        this.id = '' ;
        this.overallRating = '' ;
        this.returnTypes = '' ;
        this.loginUserId = '' ;
        this.userName = '' ;
    }


    getFollowingUsers() : UserParameterHolder{
        this.id = '' ;
        this.overallRating = '' ;
        this.returnTypes = PsConst.FILTERING__FOLLOWING;
        this.loginUserId = '' ;
        this.userName = '' ;

        return this;
    }


    getFollowerUsers() : UserParameterHolder{
        this.id = '' ;
        this.overallRating = '' ;
        this.returnTypes = PsConst.FILTERING__FOLLOWER;
        this.loginUserId = '' ;
        this.userName = '' ;

        return this;
    }


    getOtherUserData() : UserParameterHolder{
        this.id = '' ;
        this.overallRating = '' ;
        this.returnTypes = '' ;
        this.loginUserId = '' ;
        this.userName = '' ;

        return this;
    }


    resetParameterHolder() : UserParameterHolder{
        this.id = '' ;
        this.overallRating = '' ;
        this.returnTypes = '' ;
        this.loginUserId = '' ;
        this.userName = '' ;

        return this;
    }

    toMap(): {} {
        const map = {};
        map['id'] = this.id;
        map['overall_rating'] = this.overallRating;
        map['return_types'] = this.returnTypes;
        map['login_user_id'] = this.loginUserId;
        map['user_name'] = this.userName;

        return map;
    }
}