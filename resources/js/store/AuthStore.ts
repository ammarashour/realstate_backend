
import { defineStore  } from 'pinia'
import firebaseApp from 'firebase/app';

import  "firebase/auth"
import { ref,reactive } from 'vue';
import PsApiService from '@templateCore/api/PsApiService';
import ApiStatus from '@templateCore/object/ApiStatus'
import ValidationStatus from '@templateCore/object/ValidationStatus'
import UserExistParameterHolder from '@templateCore/object/holder/UserExistParameterHolder';
import ForgotpasswordParameterHolder from '@templateCore/object/holder/ForgotpasswordParameterHolder';
import UserEmailVerifyParameterHolder from '@templateCore/object/holder/UserEmailVerifyParameterHolder';
import User from '@templateCore/object/User';
import ResetPasswordParameterHolder from '@templateCore/object/holder/ResetPasswordParameterHolder';
import UserRegisterParameterHolder from '@templateCore\object\holder\UserRegisterParameterHolder.ts';


import UserCreateParameterHolder from '@templateCore/object/holder/UserCreateParameterHolder';

export const useAuthStore = defineStore('authStore',
() => {

    const loading = reactive({
        value: false
    });

    const errorMessage = ref('');
    const googleUser = ref();

    async function createUserWithEmailAndPassword(email: string, password: string) {
        try {
            // console.log("creating")
            await firebaseApp.auth().createUserWithEmailAndPassword(email, password);
            return true;
        } catch (e) {
            errorMessage.value = e;

            try{
                const signInMethod = await firebaseApp.auth().fetchSignInMethodsForEmail(email);
                if ('email') {
                    // console.log("here signin method ")
                    // console.log(signInMethod)
                    // await signInWithEmailAndPassword(email, email);
                }
                return true;

            }catch(e) {
                errorMessage.value = e;
                console.log(e)
                return false;
            }

        }
    }



    async function  signInWithEmailAndPassword(email: string, password: string) {

        // const token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpc3MiOiJmaXJlYmFzZS1hZG1pbnNkay1nM3RlaEBwc3gtbXVsdGktcHVycG9zZS1jbGFzc2lmaWVkLmlhbS5nc2VydmljZWFjY291bnQuY29tIiwic3ViIjoiZmlyZWJhc2UtYWRtaW5zZGstZzN0ZWhAcHN4LW11bHRpLXB1cnBvc2UtY2xhc3NpZmllZC5pYW0uZ3NlcnZpY2VhY2NvdW50LmNvbSIsImF1ZCI6Imh0dHBzOi8vaWRlbnRpdHl0b29sa2l0Lmdvb2dsZWFwaXMuY29tL2dvb2dsZS5pZGVudGl0eS5pZGVudGl0eXRvb2xraXQudjEuSWRlbnRpdHlUb29sa2l0IiwiaWF0IjoxNjg0OTA3MjQxLCJleHAiOjE2ODQ5MTA4NDEsInVpZCI6IndBMjNWTHlNeWZoSjVWSk9vaTY0a3JhblJWVTIifQ.OXDFlWhR6wLFnhWnWP1JltkqWLk21hJM36_iAHzwmjd4AiAxgTQfT_wwYLCRQGOwnvBfHbkeuSkeQdEc38D_pXFdxuQN9uQ7iocKH2aVh9Xg5AUMyz8qNhgTC6y2U6sS8SiGcwhe4faVQDG01RNac6q3sOz4UOWDMSnNALHAKgAoJNK0eKjxHELBShgB7c4wh0XGrAFg8goWLyDySiYOue8bEvOk00nlgFJQqVALcqPzTjP2Zv3O5pGbV-6ppu415Dj0qcXf3k70HtxuGsMk5ZwzpL2SQZHXttFJtLTZe20jyQPCikv4dADgKbEaMyrz_riULQpF-rDXBFIxcP2ZhQ';
        const token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpc3MiOiJmaXJlYmFzZS1hZG1pbnNkay1nM3RlaEBwc3gtbXVsdGktcHVycG9zZS1jbGFzc2lmaWVkLmlhbS5nc2VydmljZWFjY291bnQuY29tIiwic3ViIjoiZmlyZWJhc2UtYWRtaW5zZGstZzN0ZWhAcHN4LW11bHRpLXB1cnBvc2UtY2xhc3NpZmllZC5pYW0uZ3NlcnZpY2VhY2NvdW50LmNvbSIsImF1ZCI6Imh0dHBzOi8vaWRlbnRpdHl0b29sa2l0Lmdvb2dsZWFwaXMuY29tL2dvb2dsZS5pZGVudGl0eS5pZGVudGl0eXRvb2xraXQudjEuSWRlbnRpdHlUb29sa2l0IiwiaWF0IjoxNjg0OTA3ODk3LCJleHAiOjE2ODQ5MTE0OTcsInVpZCI6IjY0NmRhNzc5YmQ4OWEiLCJlbWFpbCI6InRlc3RAcHN0ZWFtaXNjb29sMTIzLmNvbSJ9.QsTle8v_4551ndpyJsBt6pzrU6xfl78J0D1bS-YM70gopDTfANhbb_a6UljyDGqlfrBF-vZOnDa6DNa_C4nZGiN26cnGpOLSPWSgwg_5HQCDa7ceaTv5pRqiFmsVjXBzIcfvmCjw57DNU7H7p7e1OFZ9sbcSa1TdzJT9LhQVujo-K0_CPl0Nq0W2GF5BYOix0T3GnZC7SMywff1M70twsmV8pTFgece6stBBfZR3bL8y6FXg1bDeJi5imrcV2jovQbzCZdwnD5OgnkGpBnvHMND3mKKfkKrstDKhUsc73vc4jckEFptu5OGSwSYN9VW94LdkA-6YXbjbPD-Wk4NJkg'; // unique id

        // let status = false;
        // await firebaseApp.auth().signInWithCustomToken(token)
        //     .then((userCredential) => {
        //         // Signed in
        //         var user = userCredential.user;
        //         console.log(user)
        //          status = true;
        //         // ...
        //     })
        //     .catch((error) => {
        //         var errorCode = error.code;
        //         var errorMessage = error.message;
        //         return false;
        //         console.log(errorCode)
        //         // ...
        //     });

        // return status;

        try {
            await firebaseApp.auth().signInWithEmailAndPassword(email, email);
            return true;
        } catch (e) {

            // errorMessage.value = e;
            console.log(e)
            try{
                await createUserWithEmailAndPassword(email, email);
                return true;

            }catch(e) {
                errorMessage.value = e;
                console.log(e)
                return false;
            }

        }

    }

    function isEmpty(obj) {
        for (var prop in obj) {
          if (Object.prototype.hasOwnProperty.call(obj, prop)) {
            return false;
          }
        }

        return true
      }


    function setLoading(value) {
        loading.value = value;
    }

    async function loginWithGoogleId() {
        const provider = new firebaseApp.auth.GoogleAuthProvider();
        provider.addScope('email');
        // console.log(JSON.stringify(provider));
        const result = await firebaseApp.auth().signInWithPopup(provider).catch((error) => {
            // Handle Errors here.
            errorMessage.value = error.message;
            console.log(errorMessage.value)
        });

        if(result != null && result.credential != null) {
            const appCredential : firebaseApp.auth.OAuthCredential = result.credential ?? null;

            // console.log(appCredential);

            // This gives you a Google Access Token. You can use it to access the Google API.
            // const token = appCredential.accessToken;
            let fbCredential;
            try {
                fbCredential = await firebaseApp.auth().signInWithCredential(appCredential);
                const firebaseUser = firebaseApp.auth().currentUser;
                // console.log(JSON.stringify(firebaseUser));
            } catch (e) {
                errorMessage.value = "Google login failed.";
            }

            if(firebaseApp.auth() !=null && fbCredential != null && fbCredential.user != null){
                // The signed-in user info.
                const user = fbCredential.user;

                // user = null;
                if(user != null) {
                    return user;
                    // await submitLoginWithGoogleId(user);
                }else {
                    errorMessage.value = "Google login failed.";
                }
            }

        }

    }

    async function loginWithAppleSignIn() {
        const provider = new firebaseApp.auth.OAuthProvider('apple.com');
        provider.addScope('email');
        provider.addScope('name');
        const result = await firebaseApp.auth().signInWithPopup(provider).catch((error) => {
            // Handle Errors here.
            // alert(error.message)
            errorMessage.value = error.message;
        });

        if(result != null && result.credential != null) {
            const appCredential : firebaseApp.auth.OAuthCredential = result.credential ?? null;

            // This gives you a Google Access Token. You can use it to access the Google API.
            // const token = appCredential.accessToken;
            let appleCredential;
            try {
                appleCredential = await firebaseApp.auth().signInWithCredential(appCredential);
            } catch (e) {
                errorMessage.value = "Apple login failed.";
            }

            if(firebaseApp.auth() !=null && appleCredential != null && appleCredential.user != null){
                // The signed-in user info.
                const user = appleCredential.user;
                // user = null;
                if(user != null) {
                    return user;
                    // await this.submitLoginWithApple(user);
                }else {
                    errorMessage.value = "Apple login failed.";
                }
            }

        }

    }

    async function loginWithFacebookId() {
        const provider = new firebaseApp.auth.FacebookAuthProvider();
        provider.addScope('email');
        // provider.addScope('email');
        const result = await firebaseApp.auth().signInWithPopup(provider).catch((error) => {
            // Handle Errors here.
            errorMessage.value = error.message;
        });

        if(result != null && result.credential != null) {
            const appCredential : firebaseApp.auth.OAuthCredential = result.credential ?? null;

            // This gives you a Google Access Token. You can use it to access the Google API.
            // const token = appCredential.accessToken;
            let fbCredential;
            try {
                fbCredential = await firebaseApp.auth().signInWithCredential(appCredential);
            } catch (e) {
                errorMessage.value = "Facebook login failed.";
            }

            if(firebaseApp.auth() !=null && fbCredential != null && fbCredential.user != null){
                // The signed-in user info.
                const user = fbCredential.user;
                // user = null;
                if(user != null) {
                    return user;
                    // await this.submitLoginWithFacebookId(user);
                }else {
                    errorMessage.value = "Facebook login failed.";
                }
            }

        }

    }

    async function existUser(holder : UserExistParameterHolder) {
        loading.value = true;
        // loading.value = false;
        return await PsApiService.getUserExist<ApiStatus>(new ApiStatus(),holder.toMap());



    }

    async function createUserwithUsername(holder : UserCreateParameterHolder) {

        return await PsApiService.postUserCreate<ValidationStatus>(new ValidationStatus(),holder.toMap());

    }

    async function submitLoginWithGoogleId(user : firebaseApp.User) {
        // console.log(JSON.stringify(user));

        loading.value = true;
        let email = '';
        if(user != null) {
            email = user.email ?? '';
            if(email == null || email == '') {
                if(user.providerData != null && user.providerData.length > 0) {
                    for( let i = 0; i<user.providerData.length; i++) {
                        if(user.providerData[i]?.email != null && user.providerData[i]?.email != '') {
                            email = user.providerData[i]?.email ?? '';
                        }
                    }
                }
            }
        }

        // googleUser.value = user;
       return user;



        // const holder = new GoogleLoginParameterHolder();
        // holder.googleId = user.uid;
        // holder.userName = user.displayName ?? '';
        // holder.userEmail = email;
        // holder.profilePhotoUrl = user.photoURL ?? '';
        // holder.deviceToken = PsValueProvider.psValueHolder.deviceToken;
        // holder.platformName = PsConst.PLATFORM;
        // console.log(holder.toMap());
        // const returnData = await PsApiService.postGoogleLogin<User>(new User(), holder.toMap());
        // if (returnData.status == PsStatus.SUCCESS) {
        //     errorMessage.value = returnData;
        //     PsValueProvider.psValueHolder.login(errorMessage.value?.data?.userId ?? '', errorMessage.value?.data?.userName ?? '',errorMessage.value?.data?.deviceToken ?? '');
        //     PsValueProvider.psValueHolder.replaceVerifyUserData('', '', '', '');
        // }
        // console.log(returnData);
        // loading.value = false;

    }

    async function postForgotPassword(holder: ForgotpasswordParameterHolder) {

        loading.value = true;

        //const returnData = await PsApiService.postForgotPassword<User>(new User(), holder.toMap());
        const status = await PsApiService.postForgotPassword<ApiStatus>( new ApiStatus(), holder.toMap());

        loading.value = false;

        return status;

    }

    async function postUserCodeVerify(holder: UserEmailVerifyParameterHolder) {

        loading.value = true;

        const returnData = await PsApiService.postUserCodeVerify<User>(new User(), holder.toMap());

        loading.value = false;

        return returnData;

    }

    async function postResetPassword(holder: ResetPasswordParameterHolder) {

        loading.value = true;

        const status = await PsApiService.postResetPassword<ApiStatus>( new ApiStatus(), holder.toMap());

        loading.value = false;

        return status;


    }


    async function registerUser(holder :UserRegisterParameterHolder) {

        this.loading.value = true;

        const returnData = await PsApiService.postUserRegister<User>(new User(), holder.toMap());

        this.loading.value = false;

        return returnData;
    }

    async function postUserEmailVerify(holder: UserEmailVerifyParameterHolder) {

        loading.value = true;

        const returnData = await PsApiService.postUserEmailVerify<User>(new User(), holder.toMap());

        loading.value = false;

        return returnData;

    }

    async function postResendCode(map: any) {

        loading.value = true;

        const returnData = await PsApiService.postResendCode<ApiStatus>(new ApiStatus(), map);

        loading.value = false;
        return returnData;

    }

    return{
        postResendCode,
        postUserEmailVerify,
        registerUser,
        postUserCodeVerify,
        postResetPassword,
        postForgotPassword,
        createUserWithEmailAndPassword,
        signInWithEmailAndPassword,
        errorMessage,
        loginWithGoogleId,
        loading,
        submitLoginWithGoogleId,
        googleUser,
        existUser,
        createUserwithUsername,
        setLoading,
        isEmpty,
        loginWithFacebookId,
        loginWithAppleSignIn



        }
    })
