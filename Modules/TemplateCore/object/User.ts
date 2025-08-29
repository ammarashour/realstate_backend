import { PsObject } from "./core/PsObject";
import RatingDetail from "./RatingDetail";
import ProductRelation from "./ProductRelation";

export default class User extends PsObject<User>{

    userId : string = '';
    userIsSysAdmin : string = '';
    facebookId : string = '';
    googleId : string = '';
    phoneId : string = '';
    userName : string = '';
    username : string = '';
    userEmail : string = '';
    userPhone : string = '';
    userAddress : string = '';
    userLat : string = '';
    userLng : string = '';
    city : string = '';
    userPassword : string = '';
    userAboutMe : string = '';
    isShowEmail : string = '';
    isShowPhone : string = '';
    userCoverPhoto : string = '';
    userProfilePhoto : string = '';
    roleId : string = '';
    status : string = '';
    isBanned : string = '';
    addedDate : string = '';
    addedDateTimeStamp: string = '';
    deviceToken : string = '';
    hasCode : string = '';
    needVerify : string = '';
    overallRating : string = '';
    whatsapp : string = '';
    messenger : string = '';
    followerCount : string = '';
    followingCount : string = '';
    emailVerify : string = '';
    appleId : string = '';
    appleVerify : string = '';
    facebookVerify : string = '';
    googleVerify : string = '';
    phoneVerify : string = '';
    ratingCount : string = '';
    isFollowed : string = '';
    ratingDetail : RatingDetail = new RatingDetail();
    isFavourited : string = '';
    isOwner : string = '';
    isVerifybluemark : string = '';
    postCount : string = '';
    remainingPost : string = '';
    activeItemCount : string = '';
    userRelation : ProductRelation[] = [new ProductRelation()];

    init(
        userId: string,
        userIsSysAdmin: string,
        facebookId: string,
        googleId: string,
        phoneId: string,
        userName: string,
        username: string,
        userEmail: string,
        userPhone: string,
        userAddress: string,
        userLat: string,
        userLng: string,
        city: string,
        userPassword: string,
        userAboutMe: string,
        isShowEmail: string,
        isShowPhone: string,
        userCoverPhoto: string,
        userProfilePhoto: string,
        roleId: string,
        status: string,
        isBanned : string,
        addedDate: string,
        addedDateTimeStamp: string,
        deviceToken: string,
        hasCode: string,
        needVerify: string,
        overallRating: string,
        whatsapp: string,
        messenger: string,
        followerCount: string,
        followingCount: string,
        emailVerify: string,
        appleId : string ,
        appleVerify : string ,
        facebookVerify: string,
        googleVerify: string,
        phoneVerify: string,
        ratingCount: string,
        isFollowed: string,
        ratingDetail: RatingDetail,
        isFavourited: string,
        isOwner: string,
        isVerifybluemark: string,
        postCount : string,
        remainingPost : string,
        activeItemCount : string,
        userRelation : ProductRelation[],

        ) {
        this.userId = userId;
        this.userIsSysAdmin = userIsSysAdmin;
        this.facebookId = facebookId;
        this.googleId = googleId;
        this.phoneId = phoneId;
        this.userName = userName;
        this.username = username;
        this.userEmail = userEmail;
        this.userPhone = userPhone;
        this.userAddress = userAddress;
        this.userLat = userLat;
        this.userLng = userLng;
        this.city = city;
        this.userPassword = userPassword;
        this.userAboutMe = userAboutMe;
        this.isShowEmail = isShowEmail;
        this.isShowPhone = isShowPhone;
        this.userCoverPhoto = userCoverPhoto;
        this.userProfilePhoto = userProfilePhoto;
        this.roleId = roleId;
        this.status = status;
        this.isBanned = isBanned;
        this.addedDate = addedDate;
        this.addedDateTimeStamp =addedDateTimeStamp;
        this.deviceToken = deviceToken;
        this.hasCode = hasCode;
        this.needVerify = needVerify;
        this.overallRating = overallRating;
        this.whatsapp = whatsapp;
        this.messenger = messenger;
        this.followerCount = followerCount;
        this.followingCount = followingCount;
        this.emailVerify = emailVerify;
        this.appleId = appleId,
        this.appleVerify = appleVerify ,
        this.facebookVerify = facebookVerify;
        this.googleVerify = googleVerify;
        this.phoneVerify = phoneVerify;
        this.ratingCount = ratingCount;
        this.isFollowed = isFollowed;
        this.ratingDetail = ratingDetail;
        this.isFavourited = isFavourited;
        this.isOwner = isOwner;
        this.isVerifybluemark = isVerifybluemark;
        this.postCount = postCount;
        this.remainingPost = remainingPost;
        this.activeItemCount = activeItemCount;
        this.userRelation = userRelation;

        return this;
    }
    getPrimaryKey(): string {
        return this.userId;
    }

    toMap(object: User): any {
        const map = {};
        map['id'] = object.userId;
        map['user_is_sys_admin'] = object.userIsSysAdmin;
        map['facebook_id'] = object.facebookId;
        map['google_id'] = object.googleId;
        map['phone_id'] = object.phoneId;
        map['name'] = object.userName;
        map['username'] = object.username;
        map['email'] = object.userEmail;
        map['user_phone'] = object.userPhone;
        map['user_address'] = object.userAddress;
        map['user_lat'] = object.userLat;
        map['user_lng'] = object.userLng;
        map['city'] = object.city;
        map['user_password'] = object.userPassword;
        map['user_about_me'] = object.userAboutMe;
        map['is_show_email'] = object.isShowEmail;
        map['is_show_phone'] = object.isShowPhone;
        map['user_cover_photo'] = object.userCoverPhoto;
        map['user_profile_photo'] = object.userProfilePhoto;
        map['role_id'] = object.roleId;
        map['status'] = object.status;
        map['is_banned'] = object.isBanned;
        map['added_date'] = object.addedDate;
        map['added_date_timestamp'] = object.addedDateTimeStamp;
        map['device_token'] = object.deviceToken;
        map['has_code'] = object.hasCode;
        map['need_verify'] = object.needVerify;
        map['overall_rating'] = object.overallRating;
        map['whatsapp'] = object.whatsapp;
        map['messenger'] = object.messenger;
        map['follower_count'] = object.followerCount;
        map['following_count'] = object.followingCount;
        map['email_verify'] = object.emailVerify;
        map['apple_id'] = object.appleId;
        map['apple_verify'] = object.appleVerify;
        map['facebook_verify'] = object.facebookVerify;
        map['google_verify'] = object.googleVerify;
        map['phone_verify'] = object.phoneVerify;
        map['rating_count'] = object.ratingCount;
        map['is_followed'] = object.isFollowed;
        map['rating_details'] = new RatingDetail().toMap(object.ratingDetail);
        map['is_favourited'] = object.isFavourited;
        map['is_owner'] = object.isOwner;
        map['is_verify_blue_mark'] = object.isVerifybluemark;
        map['post_count'] = object.postCount;
        map['remaining_post'] = object.remainingPost;
        map['active_item_count']= object.activeItemCount;
        map['userRelation'] = new ProductRelation().toMapList(object.userRelation);
        return map;
    }

    toMapList(objectList: User[]): any[] {
        const mapList: any[] = [];
        for (let i = 0; i < objectList.length; i++) {
            if (objectList[i] != null) {
                mapList.push(this.toMap(objectList[i]));
            }
        }

        return mapList;
    }

    fromMap(obj: any): User {
        return new User().init(

            obj.id,
            obj.user_is_sys_admin,
            obj.facebook_id,
            obj.google_id,
            obj.phone_id,
            obj.name,
            obj.username,
            obj.email,
            obj.user_phone,
            obj.user_address,
            obj.user_lat,
            obj.user_lng,
            obj.city,
            obj.user_password,
            obj.user_about_me,
            obj.is_show_email,
            obj.is_show_phone,
            obj.user_cover_photo,
            obj.user_profile_photo,
            obj.role_id,
            obj.status,
            obj.is_banned,
            obj.added_date,
            obj.added_date_timestamp,
            obj.device_token,
            obj.has_code,
            obj.need_verify,
            obj.overall_rating,
            obj.whatsapp,
            obj.messenger,
            obj.follower_count,
            obj.following_count,
            obj.email_verify,
            obj.apple_id,
            obj.apple_verify,
            obj.facebook_verify,
            obj.google_verify,
            obj.phone_verify,
            obj.rating_count,
            obj.is_followed,
            new RatingDetail().fromMap(obj.rating_details),
            obj.is_favourited,
            obj.is_owner,
            obj.is_verify_blue_mark,
            obj.post_count,
            obj.remaining_post,
            obj.active_item_count,
            new ProductRelation().fromMapList(obj.userRelation),
        );
    }

    fromMapList(objList : any[] ) : User[] {
        const userList : User[] = [];
        for(const obj of objList as Array<User>) {
            if(obj != null) {
                userList.push(this.fromMap(obj));
            }
        }

        return userList;
    }


}
