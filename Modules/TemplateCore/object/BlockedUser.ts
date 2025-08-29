import { PsObject } from "./core/PsObject";
import RatingDetail from "./RatingDetail";

export default class BlockedUser extends PsObject<BlockedUser>{

        userId : string = '';
        userIsSysAdmin : string = '';
        facebookId : string = '';
        googleId : string = '';
        phoneId : string = '';
        appleId : string = '';
        userName : string = '';
        userEmail : string = '';
        userPhone : string = '';
        userAddress : string = '';
        userAboutMe : string = '';
        userCoverPhoto : string = '';
        roleId : string = '';
        status : string = '';
        isBanned : string = '';
        code : string = '';
        overallRating : string = '';
        isShowEmail : string = '';
        isShowPhone : string = '';
        userLat : string = '';
        userLng : string = '';
        addedDate : string = '';
        activeItemCount : string = '';
        ratingCount : string = '';
        ratingDetail : RatingDetail = new RatingDetail();
        followerCount : string = '';
        followingCount : string = '';
        isFollowed : string = '';
        isBlocked : string = '';
        isVerifybluemark: string='';
        // city : string = '';
        // userPassword : string = '';
        // userProfilePhoto : string = '';
        // deviceToken : string = '';
        // whatsapp : string = '';
        // messenger : string = '';
        // emailVerify : string = '';
        // facebookVerify : string = '';
        // googleVerify : string = '';
        // phoneVerify : string = '';
        // isFavourited : string = '';
        // isOwner : string = '';


    init(

        userId : string ,
        userIsSysAdmin : string ,
        facebookId : string ,
        googleId : string ,
        phoneId : string ,
        appleId : string ,
        userName : string ,
        userEmail : string ,
        userPhone : string ,
        userAddress : string ,
        userAboutMe : string ,
        userCoverPhoto : string ,
        roleId : string ,
        status : string ,
        isBanned : string ,
        code : string ,
        overallRating : string ,
        isShowEmail : string ,
        isShowPhone : string ,
        userLat : string ,
        userLng : string ,
        addedDate : string ,
        activeItemCount : string ,
        ratingCount : string ,
        ratingDetail : RatingDetail,
        followerCount : string ,
        followingCount : string ,
        isFollowed : string ,
        isBlocked : string ,
        isVerifybluemark: string
    ) {
        this.userId = userId;
        this.userIsSysAdmin = userIsSysAdmin;
        this.facebookId = facebookId;
        this.googleId = googleId;
        this.phoneId = phoneId;
        this.appleId = appleId;
        this.userName = userName;
        this.userEmail = userEmail;
        this.userPhone = userPhone;
        this.userAddress = userAddress;
        this.userAboutMe = userAboutMe;
        this.userCoverPhoto = userCoverPhoto;
        this.roleId = roleId;
        this.status = status;
        this.isBanned = isBanned;
        this.code = code;
        this.overallRating = overallRating;
        this.isShowEmail = isShowEmail;
        this.isShowPhone = isShowPhone;
        this.userLat = userLat;
        this.userLng = userLng;
        this.addedDate = addedDate;
        this.activeItemCount = activeItemCount;
        this.ratingCount = ratingCount;
        this.ratingDetail = ratingDetail;
        this.followerCount = followerCount;
        this.followingCount = followingCount;
        this.isFollowed = isFollowed;
        this.isBlocked = isBlocked;
        this.isVerifybluemark = isVerifybluemark;

        return this;
    }
    getPrimaryKey(): string {
        return this.userId;
    }

    toMap(object: BlockedUser) : any {
        const map = {};

        map['id'] = object.userId;
        map['user_is_sys_admin'] = object.userIsSysAdmin;
        map['facebook_id'] = object.facebookId;
        map['google_id'] = object.googleId;
        map['phone_id'] = object.phoneId;
        map['apple_id'] = object.appleId;
        map['name'] = object.userName;
        map['email'] = object.userEmail;
        map['user_phone'] = object.userPhone;
        map['user_address'] = object.userAddress;
        map['user_about_me'] = object.userAboutMe;
        map['user_cover_photo'] = object.userCoverPhoto;
        map['role_id'] = object.roleId;
        map['status'] = object.status;
        map['is_banned'] = object.isBanned;
        map['code'] = object.code;
        map['overall_rating'] = object.overallRating;
        map['is_show_email'] = object.isShowEmail;
        map['is_show_phone'] = object.isShowPhone;
        map['user_lat'] = object.userLat;
        map['user_lng'] = object.userLng;
        map['added_date'] = object.addedDate;
        map['active_item_count'] = object.activeItemCount;
        map['rating_count'] = object.ratingCount;
        map['rating_details'] = new RatingDetail().toMap(object.ratingDetail);
        map['follower_count'] = object.followerCount;
        map['following_count'] = object.followingCount;
        map['is_followed'] = object.isFollowed;
        map['is_blocked'] = object.isBlocked;
        map['is_verify_blue_mark'] = object.isVerifybluemark;
        // map['city'] = object.city;
        // map['user_password'] = object.userPassword;
        // map['user_profile_photo'] = object.userProfilePhoto;
        // map['device_token'] = object.deviceToken;
        // map['whatsapp'] = object.whatsapp;
        // map['messenger'] = object.messenger;
        // map['email_verify'] = object.emailVerify;
        // map['facebook_verify'] = object.facebookVerify;
        // map['google_verify'] = object.googleVerify;
        // map['phone_verify'] = object.phoneVerify;
        // map['is_favourited'] = object.isFavourited;
        // map['is_owner'] = object.isOwner;



        return map;
    }

    toMapList(objectList: BlockedUser[]) : any[] {
        const mapList : any[] = [];
        for(let i = 0; i < objectList.length; i++) {
            if(objectList[i] != null) {
                mapList.push(this.toMap(objectList[i]));
            }
        }

        return mapList;
    }

    fromMap(obj : any) : BlockedUser {
        return new BlockedUser().init(

            obj.id,
            obj.user_is_sys_admin,
            obj.facebook_id,
            obj.google_id,
            obj.phone_id,
            obj.apple_id,
            obj.name,
            obj.email,
            obj.user_phone,
            obj.user_address,
            obj.user_about_me,
            obj.user_cover_photo,
            obj.role_id,
            obj.status,
            obj.is_banned,
            obj.code,
            obj.overall_rating,
            obj.is_show_email,
            obj.is_show_phone,
            obj.user_lat,
            obj.user_lng,
            obj.added_date,
            obj.active_item_count,
            obj.rating_count,
            new RatingDetail().fromMap(obj.rating_details),
            obj.follower_count,
            obj.following_count,
            obj.is_followed,
            obj.is_blocked,
            obj.is_verify_blue_mark,
        );
    }

    fromMapList(objList : any[] ) : BlockedUser[] {
        const blockedUserList : BlockedUser[] = [];
        for(const obj in objList) {
            if(obj != null) {
                blockedUserList.push(this.fromMap(obj));
            }
        }

        return blockedUserList;
    }


}
