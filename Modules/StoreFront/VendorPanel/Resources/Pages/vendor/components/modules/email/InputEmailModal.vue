<template>
    <ps-modal ref="psmodal" maxWidth="350px" :isClickOut='false' class="z-50" line="hidden" >
        <template #body>
           <div class="flex justify-between container w-full px-4">
                <!-- Start Left Screen -->
                <div class="flex flex-col w-full">
                    
                    <ps-label class="mt-4 mx-5 "> {{ $t('register__email') }} <span style="font-size: 17px; color: red;">*</span> </ps-label>
                    <ps-input class="mt-2 mx-5 mb-4"  type="email" :v-bindplaceholder="$t('password_update_modal__password')" v-model:value="userEmail" v-on:keyup.enter="actionClicked('yes')" @blur="validateEmail"></ps-input>
                    <ps-label class="lg:mt-2 mt-1  w-full" textColor="text-fePrimary-500" v-if="validationEmail"> {{ $t("login__email_required_error") }} </ps-label>

                </div>   
                <!-- End Left Screen -->

            </div>
        </template>
         <template #footer>
            <div class=" flex flex-row justify-between">
                <ps-button @click="actionClicked('no')" textSize="text-xxs lg:text-sm" class="py-3 " theme="bg-feSecondary-50 dark:bg-feAchromatic-500 text-fePrimary-500 dark:text-feAchromatic-50"> {{ $t('profile__cancel')}} </ps-button>  
                <ps-button @click="actionClicked('yes')" textSize="text-xxs lg:text-sm" class="py-3"  > {{ $t('profile__confirm')}}</ps-button>                 
            </div>
        </template>
    </ps-modal>

</template>

<script lang='ts'>

// Libs
import { defineComponent,ref } from 'vue';

// Compone
import PsModal from '@template1/vendor/components/core/modals/PsModal.vue';
import PsLabel from '@template1/vendor/components/core/label/PsLabel.vue';
import PsButton from '@template1/vendor/components/core/buttons/PsButton.vue';
import PsInput from '@template1/vendor/components/core/input/PsInput.vue';

export default defineComponent({
    name: "InputEmailModal",
    components: {
        PsModal,
        PsLabel,
        PsButton,
        PsInput,
    },
    setup() {

        const psmodal = ref();
        const userEmail = ref();
        const validationEmail = ref(false);

        let okClicked: Function;
        let cancelClicked: Function;

        function validateEmail(e) {
            const pattern = /^([a-zA-Z0-9.]+)@([a-zA-Z0-9]+)\.([a-zA-Z]{2,5})$/;
            const res = pattern.test(e.target.value);
            if (!res) {
                validationEmail.value = true;
            } else {
                validationEmail.value = false;
            }
        }

        function actionClicked(status) {
            psmodal.value.toggle(false);
            if(status == 'yes') {
                if(userEmail.value == ''){
                    validationEmail.value = false;
                }else{
                    okClicked(userEmail.value);     
                }
            }else {
                cancelClicked();
            }
            
        }
     
        async function openModal(okClickedAction: Function,cancelClickedAction : Function) {

            okClicked = okClickedAction;
            cancelClicked= cancelClickedAction;    

            psmodal.value.toggle(true);
                        
        }



        return {
            psmodal,
            openModal,
            userEmail,
            validateEmail,
            validationEmail,
            actionClicked
        }
    },
})
</script>
