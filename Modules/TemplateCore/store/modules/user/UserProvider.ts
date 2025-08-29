import { reactive, provide, inject } from 'vue';
import PsUtils from '@templateCore/utils/PsUtils';

import PsApiService from '@templateCore/api/PsApiService';
import PsStatus from '@templateCore/api/common/PsStatus';
import PsResource from '@templateCore/api/common/PsResource';
import User from '@templateCore/object/User';
import UserLoginParameterHolder from '@templateCore/object/holder/UserLoginParameterHolder';
import UserListParameterHolder from '@templateCore/object/holder/UserListParameterHolder';
import { PsValueStore } from '@templateCore/store/modules/core/PsValueStore';
import ProductParameterHolder from '@templateCore/object/holder/ProductParameterHolder';
import UserFollowHolder from '@templateCore/object/holder/UserFollowHolder';
import DefaultPhoto from '@templateCore/object/DefaultPhoto';
import PsConst from '@templateCore/object/constant/ps_constants';
import UserRegisterParameterHolder from '@templateCore/object/holder/UserRegisterParameterHolder';
import UserEmailVerifyParameterHolder from '@templateCore/object/holder/UserEmailVerifyParameterHolder';
import PhoneLoginParameterHolder from '@templateCore/object/holder/PhoneLoginParameterHolder';
import ChangePasswordParameterHolder from '@templateCore/object/holder/ChangePasswordParameterHolder';
import UserBlockParameterHolder from '@templateCore/object/holder/UserBlockParameterHolder';
import UserBlueMarkParameterHolder from '@templateCore/object/holder/UserBlueMarkParameterHolder';
import UserDeleteItemParameterHolder from '@templateCore/object/holder/UserDeleteItemParameterHolder';
import UserLogoutParameterHolder from '@templateCore/object/holder/UserLogoutParameterHolder';
import ForgotpasswordParameterHolder from '@templateCore/object/holder/ForgotpasswordParameterHolder';
import ApiStatus from '@templateCore/object/ApiStatus';
// import firebaseApp from 'firebase/app';
// import "firebase/auth"
import GoogleLoginParameterHolder from '@templateCore/object/holder/GoogleLoginPatameterHolder';
import FbLoginParameterHolder from '@templateCore/object/holder/FbLoginParameterHolder';
import AppleLoginParameterHolder from '@templateCore/object/holder/AppleLoginParameterHolder';
import DealershipCanelParameterHolder from '@templateCore/object/holder/DealershipCanelParameterHolder';
import AddMemberShipParameterHolder from '@templateCore/object/holder/AddMemberShipParameterHolder';

export class UserProvider{

    isNoMoreRecord = reactive({
        value: false
    })
    userResource = reactive<PsResource<User>>(new PsResource<User>());
    userList = reactive<PsResource<User[]>>(new PsResource());
    user = reactive<PsResource<User>>(new PsResource());

    loading = reactive({
        value: false
    });

    limit: Number = 30;
    offset: Number = 0;
    userparamHolder = reactive(new UserListParameterHolder());

    private updateItemList(responseData: PsResource<User[]>) {
        if (this.userList != null
            && this.userList.data != null
            && this.userList.data.length > 0
            && this.offset != 0) {

            if (responseData.data != null && responseData.data.length > 0) {
                if(responseData.data?.length < this.limit){
                    this.isNoMoreRecord.value = true;
                } else {
                    this.isNoMoreRecord.value = false;
                }
                this.userList.data.push(...responseData.data);
            }else {
                this.isNoMoreRecord.value = true;
            }

            this.userList.code = responseData.code;
            this.userList.status = responseData.status;
            this.userList.message = responseData.message;

        } else {
            if(responseData.data?.length < this.limit || responseData.data == null){
                this.isNoMoreRecord.value = true;
            } else {
                this.isNoMoreRecord.value = false;
            }
            this.userList = responseData;


        }

        if (this.userList != null && this.userList.data != null) {
            this.offset = this.userList.data.length;
        }

    }
    setLoading(value) {
        this.loading.value = value;
    }
    async loginWithEmailId(email: string, password: string) {

        this.loading.value = true;

        await this.signInWithEmailAndPassword(email, email);

        // if(firebaseApp.auth() !=null){

        //     /// Submit to backend
        //     await this.submitLoginWithEmailId(email, password);

        //     this.loading.value = false;
        // }else {
        //     this.loading.value = false;
        // }
    }

    async submitLoginWithEmailId(email: string, password: string) {

        this.loading.value = true;

        const holder = new UserLoginParameterHolder();
        holder.userEmail = email;
        holder.userPassword = password;
        holder.deviceToken = PsValueStore.psValueStore.deviceToken;
        holder.platformName = PsConst.PLATFORM;
        this.userResource = await PsApiService.postUserLogin<User>(new User(), holder.toMap());
        if (this.userResource.status == PsStatus.SUCCESS) {
            PsValueStore.psValueStore.login(this.userResource?.data?.userId ?? '', this.userResource?.data?.userName ?? '',this.userResource?.data?.deviceToken ?? '');

        }

        this.loading.value = false;
        return this.userResource;

    }

    async submitUserLoginWithPhoneId(phoneId: string, name: string, phoneNo: string) {

        this.loading.value = true;

        const holder = new PhoneLoginParameterHolder();
        holder.userName = name;
        holder.phoneId = phoneId;
        holder.userPhone = phoneNo;
        holder.deviceToken = PsValueStore.psValueStore.deviceToken;
        holder.platformName = PsConst.PLATFORM;

        this.userResource = await PsApiService.postPhoneLogin<User>(new User(), holder.toMap());
        if (this.userResource.status == PsStatus.SUCCESS) {
            PsValueStore.psValueStore.login(this.userResource?.data?.userId ?? '', this.userResource?.data?.userName ?? '',this.userResource?.data?.deviceToken ?? '');
        }

        this.loading.value = false;
        return this.userResource;

    }



    async signUpWithEmailId(name:string, email: string, password: string, openDashboardCallback: Function, openEmailVerificaitonCallback: Function) {

        this.loading.value = true;

        await this.createUserWithEmailAndPassword(email, email);

        // if(firebaseApp.auth() !=null){
        //     /// Submit to backend
        //     await this.submitSignUpWithEmailId(name,email, password, openDashboardCallback, openEmailVerificaitonCallback);

        //     this.loading.value = false;
        // }else {
        //     this.loading.value = false;
        // }
    }

    async submitSignUpWithEmailId(name:string,email: string, password: string, openDashboardCallback: Function, openEmailVerificaitonCallback: Function) {

        this.loading.value = true;

        const holder = new UserRegisterParameterHolder();
        holder.userId = '';
        holder.userName = name;
        holder.userEmail = email;
        holder.userPassword = password;
        holder.userPhone = '';
        holder.deviceToken = PsValueStore.psValueStore.deviceToken;
        holder.platformName = PsConst.PLATFORM;

        const returnData = await PsApiService.postUserRegister<User>(new User(), holder.toMap());
        ///change
        if (returnData.status == PsStatus.SUCCESS) {
            if (returnData.data?.status == PsConst.ONE) {
                this.userResource = returnData;
                //Approval Off
                PsValueStore.psValueStore.replaceVerifyUserData('', '', '', '');
                PsValueStore.psValueStore.login(this.userResource?.data?.userId ?? '', this.userResource?.data?.userName ?? '',this.userResource?.data?.deviceToken ?? '');

                openDashboardCallback();
            } else {
                //Approval On
                this.userResource = returnData;
                // PsValueStore.psValueStore.login(this.userResource.data.userId, this.userResource.data.userName,this.userResource?.data?.deviceToken ?? '');
                PsValueStore.psValueStore.replaceVerifyUserData(
                    this.userResource?.data?.userId ?? '',
                    this.userResource?.data?.userName ?? '',
                    this.userResource?.data?.userEmail ?? '',
                    password);
                openEmailVerificaitonCallback();

            // }
            }
        } else {
            this.userResource = returnData;
        }
        ///end

        this.loading.value = false;
        return this.userResource;
    }



    async createUserWithEmailAndPassword(email: string, password: string) {
        // try {
        //     firebaseApp.auth().createUserWithEmailAndPassword(email, password);
        // } catch (e) {
        //     try{
        //         const signInMethod = await firebaseApp.auth().fetchSignInMethodsForEmail(email);
        //         if (PsConst.emailAuthProvider) {
        //             await this.signInWithEmailAndPassword(email, email);
        //         }

        //     }catch(e) {
        //         console.log(e)
        //     }
        // }

    }

    async signInWithEmailAndPassword(email: string, password: string) {
        // try {
        //     await firebaseApp.auth().signInWithEmailAndPassword(email, password);
        // } catch (e) {

        //     await this.createUserWithEmailAndPassword(email, password);

        //     if (this.userResource == null) {
        //         try {
        //             await firebaseApp.auth().signInWithEmailAndPassword(PsConst.defaultEmail, PsConst.defaultPassword);
        //         } catch (e) {
        //             await this.createUserWithEmailAndPassword(PsConst.defaultEmail, PsConst.defaultPassword);
        //         }
        //     }

        // }

    }

    async loginWithAppleSignIn() {
        // const provider = new firebaseApp.auth.OAuthProvider('apple.com');
        // const result = await firebaseApp.auth().signInWithPopup(provider).catch((error) => {
        //     // Handle Errors here.
        //     this.userResource.message = error.message;
        // });

        // if(result != null && result.credential != null) {
        //     const appCredential : firebaseApp.auth.OAuthCredential = result.credential ?? null;

        //     // This gives you a Google Access Token. You can use it to access the Google API.
        //     // const token = appCredential.accessToken;
        //     let appleCredential;
        //     try {
        //         appleCredential = await firebaseApp.auth().signInWithCredential(appCredential);
        //     } catch (e) {
        //         this.userResource.message = "Apple login failed.";
        //     }

        //     if(firebaseApp.auth() !=null && appleCredential != null && appleCredential.user != null){
        //         // The signed-in user info.
        //         const user = appleCredential.user;
        //         // user = null;
        //         if(user != null) {
        //             await this.submitLoginWithApple(user);
        //         }else {
        //             this.userResource.message = "Apple login failed.";
        //         }
        //     }

        // }

    }

    async submitLoginWithApple() {
        // console.log(user);
        // this.loading.value = true;
        // let email = '';
        // if(user != null) {
        //     email = user.email ?? '';
        //     if(email == null || email == '') {
        //         if(user.providerData != null && user.providerData.length > 0) {
        //             for( let i = 0; i<user.providerData.length; i++) {
        //                 if(user.providerData[i]?.email != null && user.providerData[i]?.email != '') {
        //                     email = user.providerData[i]?.email ?? '';
        //                 }
        //             }
        //         }
        //     }
        // }

        // const holder = new AppleLoginParameterHolder();
        // holder.appleId = user.uid;
        // holder.userName = user.displayName ?? '';
        // holder.userEmail = email;
        // holder.profilePhotoUrl = user.photoURL ?? '';
        // holder.deviceToken = PsValueStore.psValueStore.deviceToken;
        // holder.platformName = PsConst.PLATFORM;

        // const returnData = await PsApiService.postAppleLogin<User>(new User(), holder.toMap());
        // if (returnData.status == PsStatus.SUCCESS) {
        //     this.userResource = returnData;
        //     PsValueStore.psValueStore.login(this.userResource?.data?.userId ?? '', this.userResource?.data?.userName ?? '',this.userResource?.data?.deviceToken ?? '');
        //     PsValueStore.psValueStore.replaceVerifyUserData('', '', '', '');
        // }

        // this.loading.value = false;
    }

    async loginWithGoogleId() {
        // const provider = new firebaseApp.auth.GoogleAuthProvider();
        // const result = await firebaseApp.auth().signInWithPopup(provider).catch((error) => {
        //     // Handle Errors here.
        //     this.userResource.message = error.message;
        // });

        // if(result != null && result.credential != null) {
        //     const appCredential : firebaseApp.auth.OAuthCredential = result.credential ?? null;

        //     // This gives you a Google Access Token. You can use it to access the Google API.
        //     // const token = appCredential.accessToken;
        //     let fbCredential;
        //     try {
        //         fbCredential = await firebaseApp.auth().signInWithCredential(appCredential);
        //     } catch (e) {
        //         this.userResource.message = "Google login failed.";
        //     }

        //     if(firebaseApp.auth() !=null && fbCredential != null && fbCredential.user != null){
        //         // The signed-in user info.
        //         const user = fbCredential.user;
        //         // user = null;
        //         if(user != null) {
        //             await this.submitLoginWithGoogleId(user);
        //         }else {
        //             this.userResource.message = "Google login failed.";
        //         }
        //     }

        // }

    }

    async submitLoginWithGoogleId() {

        // this.loading.value = true;
        // let email = '';
        // if(user != null) {
        //     email = user.email ?? '';
        //     if(email == null || email == '') {
        //         if(user.providerData != null && user.providerData.length > 0) {
        //             for( let i = 0; i<user.providerData.length; i++) {
        //                 if(user.providerData[i]?.email != null && user.providerData[i]?.email != '') {
        //                     email = user.providerData[i]?.email ?? '';
        //                 }
        //             }
        //         }
        //     }
        // }

        // const holder = new GoogleLoginParameterHolder();
        // holder.googleId = user.uid;
        // holder.userName = user.displayName ?? '';
        // holder.userEmail = email;
        // holder.profilePhotoUrl = user.photoURL ?? '';
        // holder.deviceToken = PsValueStore.psValueStore.deviceToken;
        // holder.platformName = PsConst.PLATFORM;
        // console.log(holder.toMap());
        // const returnData = await PsApiService.postGoogleLogin<User>(new User(), holder.toMap());
        // if (returnData.status == PsStatus.SUCCESS) {
        //     this.userResource = returnData;
        //     PsValueStore.psValueStore.login(this.userResource?.data?.userId ?? '', this.userResource?.data?.userName ?? '',this.userResource?.data?.deviceToken ?? '');
        //     PsValueStore.psValueStore.replaceVerifyUserData('', '', '', '');
        // }
        // console.log(returnData);
        // this.loading.value = false;

    }

    async loginWithFacebookId() {
        // const provider = new firebaseApp.auth.FacebookAuthProvider();
        // const result = await firebaseApp.auth().signInWithPopup(provider).catch((error) => {
        //     // Handle Errors here.
        //     this.userResource.message = error.message;
        // });

        // if(result != null && result.credential != null) {
        //     const appCredential : firebaseApp.auth.OAuthCredential = result.credential ?? null;

        //     // This gives you a Google Access Token. You can use it to access the Google API.
        //     // const token = appCredential.accessToken;
        //     let fbCredential;
        //     try {
        //         fbCredential = await firebaseApp.auth().signInWithCredential(appCredential);
        //     } catch (e) {
        //         this.userResource.message = "Facebook login failed.";
        //     }

        //     if(firebaseApp.auth() !=null && fbCredential != null && fbCredential.user != null){
        //         // The signed-in user info.
        //         const user = fbCredential.user;
        //         // user = null;
        //         if(user != null) {
        //             await this.submitLoginWithFacebookId(user);
        //         }else {
        //             this.userResource.message = "Facebook login failed.";
        //         }
        //     }

        // }

    }

    async submitLoginWithFacebookId() {

        // this.loading.value = true;
        // let email = '';
        // if(user != null) {
        //     email = user.email ?? '';
        //     if(email == null || email == '') {
        //         if(user.providerData != null && user.providerData.length > 0) {
        //             for( let i = 0; i<user.providerData.length; i++) {
        //                 if(user.providerData[i]?.email != null && user.providerData[i]?.email != '') {
        //                     email = user.providerData[i]?.email ?? '';
        //                 }
        //             }
        //         }
        //     }
        // }

        // const holder = new FbLoginParameterHolder();
        // holder.facebookId = user.uid;
        // holder.userName = user.displayName ?? '';
        // holder.userEmail = email;
        // holder.profilePhotoUrl = user.photoURL ?? '';
        // holder.deviceToken = PsValueStore.psValueStore.deviceToken;
        // holder.platformName = PsConst.PLATFORM;
        // holder.profileImgId = user.providerData[0]?.uid ?? '';

        // const returnData = await PsApiService.postFBLogin<User>(new User(), holder.toMap());
        // if (returnData.status == PsStatus.SUCCESS) {
        //     this.userResource = returnData;
        //     PsValueStore.psValueStore.login(this.userResource?.data?.userId ?? '', this.userResource?.data?.userName ?? '',this.userResource?.data?.deviceToken ?? '');
        //     PsValueStore.psValueStore.replaceVerifyUserData('', '', '', '');
        // }

        // this.loading.value = false;

    }




    async postUserRegister(holder: ProductParameterHolder) {

        this.loading.value = true;

        await PsApiService.postUserRegister<User>(new User(), holder.toMap());

        this.loading.value = false;

    }


    async postUserEmailVerify(holder: UserEmailVerifyParameterHolder) {

        this.loading.value = true;

        const returnData = await PsApiService.postUserEmailVerify<User>(new User(), holder.toMap());

        this.loading.value = false;

        return returnData;

    }

    async postUserLogin(holder: ProductParameterHolder) {

        this.loading.value = true;

        await PsApiService.postUserLogin<User>(new User(), holder.toMap());

        this.loading.value = false;

    }


    async postForgotPassword(holder: ForgotpasswordParameterHolder) {

        this.loading.value = true;

        //const returnData = await PsApiService.postForgotPassword<User>(new User(), holder.toMap());
        const status = await PsApiService.postForgotPassword<ApiStatus>( new ApiStatus(), holder.toMap());

        this.loading.value = false;

        return status;

    }


    async postChangePassword(holder: ChangePasswordParameterHolder) {

        this.loading.value = true;

        const status = await PsApiService.postChangePassword<ApiStatus>( new ApiStatus(), holder.toMap());

        this.loading.value = false;

        return status;


    }

    async postProfileUpdate(holder: any) {

        this.loading.value = true;
        const returnData = await PsApiService.postProfileUpdate<User>(new User(), holder.toMap());

        if(returnData.status == PsStatus.SUCCESS){
            this.user = returnData;
            PsValueStore.psValueStore.replaceLoginUserName(returnData.data?.userName ?? '');
        }
        this.loading.value = false;

        return returnData;

    }

    async postPhoneLogin(holder: PhoneLoginParameterHolder) {

        this.loading.value = true;

        await PsApiService.postPhoneLogin<User>(new User(), holder.toMap());

        this.loading.value = false;

    }

    async postUserFollow(holder: UserFollowHolder) {

        this.loading.value = true;

        await PsApiService.postUserFollow<User>(new User(), holder.toMap(),holder.userId);

        this.loading.value = false;

    }

    async postFBLogin(holder: ProductParameterHolder) {

        this.loading.value = true;

        await PsApiService.postFBLogin<User>(new User(), holder.toMap());

        this.loading.value = false;

    }


    async postAppleLogin(holder: ProductParameterHolder) {

        this.loading.value = true;

        await PsApiService.postAppleLogin<User>(new User(), holder.toMap());

        this.loading.value = false;

    }

    async postGoogleLogin(holder: ProductParameterHolder) {

        this.loading.value = true;

        await PsApiService.postGoogleLogin<User>(new User(), holder.toMap());

        this.loading.value = false;

    }



    async postAddMembership(holder: AddMemberShipParameterHolder) {

        this.loading.value = true;

        const status = await PsApiService.postAddMembership<User>(new User(), holder.toMap());

        this.loading.value = false;
        return status;
    }


    async postResendCode(holder: ProductParameterHolder) {

        this.loading.value = true;

        await PsApiService.postResendCode<User>(new User(), holder.toMap());

        this.loading.value = false;

    }


    async getUser(userId: string) {

        this.loading.value = true;

        const result = await PsApiService.getUser<User>(new User(), userId);

        if(result.data != null && result.data.length > 0) {
            this.user.data = result.data[0];
        }

        this.user.code = result.code;
        this.user.message = result.message;
        this.user.status = result.status;

        this.loading.value = false;

        return result;
    }



    async getOtherUserData(holder: UserListParameterHolder) {

        this.loading.value = true;

        await PsApiService.getUserDetail<User>(new User(), holder.toMap());

        this.loading.value = false;

    }

    async getSearchUserData(holder: UserListParameterHolder) {

        this.offset = 0;

        this.loading.value = true;

        await PsApiService.getSearchUser<User>(new User(), this.limit, this.offset, holder.toMap());

        this.loading.value = false;

    }


    async userReportItem(holder: ProductParameterHolder) {

        this.loading.value = true;

        await PsApiService.getUserDetail<User>(new User(), holder.toMap());

        this.loading.value = false;

    }

    async blueMarkUser(loginId, holder: UserBlueMarkParameterHolder) {

        this.loading.value = true;

        const status = await PsApiService.blueMarkUser<ApiStatus>( new ApiStatus(), loginId, holder.toMap());

        this.loading.value = false;

        return status;

    }

    async blockUser(holder: UserBlockParameterHolder) {

        this.loading.value = true;

        const status = await PsApiService.blockUser<ApiStatus>( new ApiStatus(), holder.toMap());

        this.loading.value = false;

        return status;

    }


    async postUnBlockUser(holder: ProductParameterHolder) {

        this.loading.value = true;

        await PsApiService.postUnBlockUser<User>(new User(), holder.toMap());

        this.loading.value = false;

    }


    async postDeleteUser(holder: UserDeleteItemParameterHolder) {

        this.loading.value = true;

        const status = await PsApiService.postDeleteUser<ApiStatus>( new ApiStatus(), holder.toMap());


        this.loading.value = false;

        return status;

    }



    async userLogout(holder: UserLogoutParameterHolder) {

        this.loading.value = true;

        await PsApiService.postUserLogout<User>(new User(), holder.toMap());

        this.loading.value = false;

    }

    async postImageUpload(
        userId: string,
        platformName: string,
        imageFile: File,
        imgId: string
    ) {
        this.loading.value = true;

        await PsApiService.postImageUpload<DefaultPhoto>(new DefaultPhoto(), userId, platformName, imageFile, imgId);

        this.loading.value = false;
    }

    async postDealerPdfUpload(imgParentId : string, pdfFile: File) : Promise<PsResource<DefaultPhoto>>  {

        this.loading.value = true;

        const returnData = await PsApiService.postDealerPdfUpload<DefaultPhoto>(new DefaultPhoto(), imgParentId, pdfFile);

        this.loading.value = false;

        return returnData;
    }

    async cancelDealership(holder: DealershipCanelParameterHolder) {

        this.loading.value = true;

        const status = await PsApiService.cancelDealership<ApiStatus>( new ApiStatus(), holder.toMap());

        this.loading.value = false;

        return status;

    }

    navigateUserLoginTo(to, router, redirect, query, params ) {

        if(to == undefined || to == '') {
            to = 'dashboard';
        }

        if(redirect != undefined && redirect != '') {

            if(redirect == 'item' || redirect == 'item-list') {
                router.push({ name : to, query : query, params : params});
            }else {
                router.push({ name : to});
            }
        } else {
            router.push({ name : to });
        }
    }

    navigateUserLoginRedirect(to, defaultTo, router, redirect, query, params ) {

        if(to == undefined || to == '') {
            to = 'dashboard';
        }

        if(defaultTo == undefined || defaultTo == '') {
            defaultTo = to;
        }

        if(redirect != undefined && redirect != '') {

            if(redirect == 'item' || redirect == 'item-list') {
                router.push({ name : to, query : query, params : params});
            }else {
                router.push({ name : defaultTo});
            }
        } else {
            router.push({ name : defaultTo });
        }
    }

}

export const userProviderSymbol = Symbol('UserProvider')
export const createUserProviderState = () => {
    return reactive(new UserProvider())
}

export const useUserProviderState = () => inject(userProviderSymbol) as UserProvider
export const provideUserProviderState = () => provide(
    userProviderSymbol,
    createUserProviderState()
)
