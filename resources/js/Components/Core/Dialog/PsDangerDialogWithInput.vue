<template>
    <ps-modal ref="psmodal" maxWidth="370px" line="hidden" :isClickOut='false' theme=" px-6 py-6 rounded-lg shadow-xl me-3" class=' z-20'>
        <template #title>
            <div class="flex flex-row items-center ">
                <ps-icon name="info"  class="text-red-500 me-2.5"  />
                <ps-label class="text-lg font-semibold"> {{title}} </ps-label>
            </div>
        </template>
        <template #body>
            <div class="w-full text-start mt-2">
                <ps-label class="font-light text-sm lg:text-base mb-2"> {{message}} </ps-label>
                <ps-label class="font-weight-bold text-sm lg:text-base"> {{ $t('confirm_info1') }} "{{ projectName }}" {{ $t('confirm_info2 ') }} </ps-label>
                <ps-input type="text" v-model:value="form.name" @input="checkNameEqualOrNot" :placeholder="$t('type_here ')" />
            </div>
        </template>
        <template #footer>
            <div class=" flex flex-row justify-end">
                <ps-button rounded="rounded" @click="actionClicked('no')" textSize="text-xs lg:text-sm" class=" me-4" border="border border-gray-200" colors="bg-none" hover="hover:outline-none hover:ring hover:ring-gray-100" > {{cancelButton}} </ps-button>
                <ps-button :disabled="okBtnIsDisable" rounded="rounded" @click="actionClicked('yes')" textSize="text-xs lg:text-sm" class="" colors="bg-red-500 text-white"  hover="hover:outline-none hover:ring hover:ring-red-100" focus="focus:outline-none focus:ring focus:ring-red-300" > {{okButton}} </ps-button>
            </div>
        </template>
    </ps-modal>
</template>

<script lang="ts">
import { defineComponent,ref } from 'vue';
import PsModal from '../Modals/PsModal.vue';
import PsLabel from '../Label/PsLabel.vue';
import PsButton from '../Buttons/PsButton.vue';
import { trans } from 'laravel-vue-i18n';
import PsIcon from "../Icons/PsIcon.vue";
import { useForm } from '@inertiajs/vue3';
import PsInput from "@/Components/Core/Input/PsInput.vue";


export default defineComponent({
    name: "PsDangerDialogWithInput",
    components : {
        PsModal,
        PsLabel,
        PsButton,
        PsIcon,
        PsInput
    },
    setup() {
        const psmodal = ref();
        const title = ref(trans('ps_danger_dialog__danger'));
        const message = ref(trans('ps_danger_dialog__danger_message'));
        const okBtnIsDisable = ref(true);
        const projectName = ref();

        const okButton = ref(trans('ps_confirm_dialog__yes'));
        const cancelButton = ref(trans('ps_confirm_dialog__no'));
        let okClicked: Function;
        let cancelClicked: Function;
        let form = useForm({
            name : ''
        })
        function checkNameEqualOrNot(){
            if  (projectName.value == form.name){
                okBtnIsDisable.value = false;
            } else {
                okBtnIsDisable.value = true;
            }

        }

        function actionClicked(status) {
            if(status == 'yes') {
                if (!okBtnIsDisable.value){
                    okClicked();
                }
            }else {
                closeModal();
            }
            if (!okBtnIsDisable.value){
                psmodal.value.toggle(false);
            }
        }

        function openModal(titleStr,
                messageStr,
                okString,
                cancelString,
                name,
                okClickedAction: Function,
                cancelClickedAction: Function) {
             if(titleStr != null && titleStr.trim() != '') {
                title.value = titleStr;
            }

            if(messageStr != null && messageStr.trim() != '') {
                message.value = messageStr;
            }
            if(name != null && name.trim() != '') {
                projectName.value = name;
            }
            if(cancelString != null && cancelString.trim() != '') {
                cancelButton.value = cancelString;
            }

            if(okString != null && okString.trim() != '') {
                okButton.value = okString;
            }
            psmodal.value.toggle(true);
            okClicked = okClickedAction;
            cancelClicked= cancelClickedAction;

        }

        function closeModal() {
            psmodal.value.toggle(false);
        }
        return {
            psmodal,
            openModal,
            closeModal,
            title,
            actionClicked,
            okButton,
            cancelButton,
            message,
            okBtnIsDisable,
            checkNameEqualOrNot,
            form,
            projectName
        }
    },
})
</script>
