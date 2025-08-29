type ProfileUpdateViewHolderInterface ={
    userId: string;
    userName: string;
    name: string;
    userEmail: string;
    userPhone: string;
    userAboutMe: string;
    isShowEmail: string;
    isShowEmailBool: Boolean;
    isShowPhone: string;
    isShowPhoneBool: Boolean;
    userAddress: string;
    streetName: string;
    city: string;
    stateId: string;
    stateName: string;
    zipCode: string;
    apartmentNo: string;
    deviceToken: string;
    birthday: string;
    emailNoti: string;
    emailNotiBool: Boolean;
    phoneNoti: string;
    phoneNotiBool: Boolean;
    itemLocationId: string;
    lat: string;
    lng: string;
    userRelation: [];

}

export default class ProfileUpdateViewHolder implements ProfileUpdateViewHolderInterface {

    userId: string = '';
    userName: string = '';
    name: string = '';
    userEmail: string = '';
    userPhone: string = '';
    userAboutMe: string = '';
    isShowEmail: string = '';
    isShowEmailBool: Boolean = false;
    isShowPhone: string = '';
    isShowPhoneBool: Boolean = false;
    userAddress: string = '';
    streetName: string = '';
    city: string = '';
    stateId: string = '';
    stateName: string = '';
    zipCode: string = '';
    apartmentNo: string = '';
    deviceToken: string = '';
    birthday: string = '';
    emailNoti: string = '';
    emailNotiBool: Boolean = false;
    phoneNoti: string = '';
    phoneNotiBool: Boolean = false;
    itemLocationId: string = '';
    lat: string = '';
    lng: string = '';
    userRelation: [] = [];


    ProfileUpdateViewHolder() {
        this.userId = '';
        this.userAddress = '';
        this.userName = '';
        this.name = '';
        this.userEmail = '';
        this.userPhone = '';
        this.userAboutMe = '';
        this.isShowEmail = '';
        this.isShowPhone = '';
        this.streetName = '';
        this.city = '';
        this.stateId = '';
        this.stateName = '';
        this.zipCode = '';
        this.apartmentNo = '';
        this.deviceToken = '';
        this.birthday = '';
        this.emailNoti = '';
        this.phoneNoti = '';
        this.itemLocationId = '';
        this.lat = '';
        this.lng = '';
        this.userRelation = [];

    }

    toMap(): {} {
        const map = {};
        map['user_id'] = this.userId;
        // map['user_address'] = this.userAddress;
        map['username'] = this.userName;
        map['name'] = this.name;
        map['email'] = this.userEmail;
        map['user_phone'] = this.userPhone;
        map['user_about_me'] = this.userAboutMe;
        map['is_show_email'] = this.isShowEmail;
        map['is_show_phone'] = this.isShowPhone;
        // map['street_name'] = this.streetName;
        // map['city'] = this.city;
        // map['state_id'] = this.stateId;
        // map['state_name'] = this.stateName;
        // map['zipcode'] = this.zipCode;
        // map['apartment_no'] = this.apartmentNo;
        // map['device_token'] = this.deviceToken;
        // map['birthday'] = this.birthday;
        // map['email_noti'] = this.emailNoti;
        // map['phone_noti'] = this.phoneNoti;
        // map['item_location_id'] = this.itemLocationId;
        // map['lat'] = this.lat;
        // map['lng'] = this.lng;
        map['user_relation'] = this.userRelation

        return map;
    }
}