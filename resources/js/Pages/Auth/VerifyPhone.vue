<template>
    <div class="text-center items-center justify-center flex flex-col w-full h-screen">
        <ps-label-title class="mb-4"> Please verify your phone </ps-label-title>
        <ps-label>We have sent verification code to this phone : <span class="text-fePrimary-500">{{authUser?.user_phone}}</span></ps-label>
        <ps-input class="my-1 w-60" v-model:value="code"/>
        <ps-button  class="my-1" @click="clicked"> submit</ps-button>
        <ps-button class="my-1"  @click="resendMessage"> resend</ps-button>
        <ps-error-dialog ref="ps_error_dialog" />
        <div id="recaptcha-container"></div> <br>
    </div>
    

</template>

<script lang='ts'>

import { ref } from 'vue';
import { onMounted } from 'vue';
import firebaseApp from 'firebase/app';
import "firebase/auth";
import firebase from "firebase/app";

// Components
import PsLabel from '@template1/vendor/components/core/label/PsLabel.vue';
import PsLabelTitle from '@template1/vendor/components/core/label/PsLabelTitle.vue';
import PsLabelCaption from '@template1/vendor/components/core/label/PsLabelCaption.vue';
import PsButton from '@template1/vendor/components/core/buttons/PsButton.vue';
import PsInput from '@template1/vendor/components/core/input/PsInput.vue';
import PsErrorDialog from '@template1/vendor/components/core/dialog/PsErrorDialog.vue';
import { router } from '@inertiajs/vue3';

//language
import { trans } from 'laravel-vue-i18n';
import PsUtils from '@templateCore/utils/PsUtils';

export default {
    name : "VerifyPhone",
    components : {
        PsLabel,
        PsLabelTitle,
        PsButton,
        PsInput,
        PsLabelCaption,
        PsErrorDialog
    },
    props : ['authUser','firebaseConfig'],
    setup(props) {
        let appVerifier;
        let confirmationResult;
        const ps_error_dialog = ref();
        const code = ref('');

        const firebaseConfiguration = JSON.parse(props.firebaseConfig);

          if (firebase.apps.length < 1) {
            firebase.initializeApp(firebaseConfiguration);
        }

         onMounted(async() =>{
            // await loadAppVerifier();    
            (window as any).recaptchaVerifier = new firebaseApp.auth.RecaptchaVerifier('recaptcha-container', {
                'size': 'invisible',
                'callback': (response) => {
                    PsUtils.log("Callback");
                    PsUtils.log(response);
                },
                'expired-callback': () => {
                    PsUtils.log("expiry callback")
                }
            });
            
            appVerifier =  (window as any).recaptchaVerifier;
            
            const verifier = appVerifier;
            
            confirmationResult = await firebaseApp.auth().signInWithPhoneNumber(props.authUser.user_phone, verifier).catch((error) => {
            
            ps_error_dialog.value.openModal(
                trans('phone_login__error_in_sign_in'), error?.message);
            // loadAppVerifier();
            });

        })


        async function resendMessage(){
            const verifier = appVerifier;
            
            confirmationResult = await firebaseApp.auth().signInWithPhoneNumber(props.authUser.user_phone, verifier).catch((error) => {
            
            ps_error_dialog.value.openModal(
                trans('phone_login__error_in_sign_in'), error?.message);
            // loadAppVerifier();
            });
        }

        async function loadAppVerifier()  {
            // Init recaptchaVerifier
            setTimeout(()=>{
                (window as any).recaptchaVerifier = new firebaseApp.auth.RecaptchaVerifier('recaptcha-container', {
                    'size': 'invisible',
                    'callback': (response) => {
                        PsUtils.log("Callback");
                        PsUtils.log(response);
                    },
                    'expired-callback': () => {
                        PsUtils.log("expiry callback")
                    }
                });
                
                appVerifier =  (window as any).recaptchaVerifier;
            },700);
        }

        async function clicked() {

            if(confirmationResult != undefined) {
                confirmationResult.confirm(code.value).then(async (userCredential) => {
                    
                    if(userCredential != null
                        && userCredential.user != null
                        && userCredential.user.uid != null 
                        && userCredential.user.uid != '' ) {
                            // call backend server
                            const user = userCredential.user;
                            router.post(route('updateVerify'),{'verify_type' : '4','user_id' : props.authUser.id});

                        }else {
                            
                            
                        }
                }).catch((error) => {
                    
                    ps_error_dialog.value.openModal(trans('phone_login__error_in_sign_in'), error?.message);
                                
                });          
            }
           
        }

        return{
            clicked,
            ps_error_dialog,
            code,
            resendMessage
        }

        
    },
}
</script>
