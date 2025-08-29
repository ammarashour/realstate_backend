<template>
    <ps-modal ref="psmodal" maxWidth="370px" line="hidden" :isClickOut='false' theme=" px-6 py-6 rounded-lg shadow-xl me-3" class=' z-20'>
        <template #title>
            <div class="flex flex-row items-center ">
                <ps-icon name="mailOpen"  class="text-primary-500 me-2.5"  />
                <ps-label class="text-lg font-semibold"> {{title}} </ps-label>
            </div>
        </template>
        <template #body>
            <slot name="body"/>
        </template>
        <template #footer>
            <div class=" flex flex-row justify-end">
                <ps-button rounded="rounded" @click="actionClicked('no')" textSize="text-xs lg:text-sm" class=" me-4" border="border border-gray-200" colors="bg-none" hover="hover:outline-none hover:ring hover:ring-gray-100" > {{cancelButton}} </ps-button>
                <ps-button rounded="rounded" @click="actionClicked('yes')" textSize="text-xs lg:text-sm" class="" colors="bg-primary-500 text-white"  hover="hover:outline-none hover:ring hover:ring-red-100" focus="focus:outline-none focus:ring focus:ring-red-300" > {{okButton}} </ps-button>
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


export default defineComponent({
    name: "PsDialogWithInput",
    components : {
        PsModal,
        PsLabel,
        PsButton,
        PsIcon,
    },
    props: ['errors'],
    setup(props) {
        const psmodal = ref();
        const title = ref(trans('ps_danger_dialog__danger'));
        const message = ref(trans('ps_danger_dialog__danger_message'));
        const projectName = ref();

        const okButton = ref(trans('ps_confirm_dialog__yes'));
        const cancelButton = ref(trans('ps_confirm_dialog__no'));
        let okClicked: Function;
        let cancelClicked: Function;
        let form = useForm({
            email : ''
        })


        function actionClicked(status) {
            if(status == 'yes') {
                okClicked();
            }else {
                closeModal();
            }
        }

        function openModal(titleStr,
                messageStr,
                okString,
                cancelString,
                okClickedAction: Function,
                cancelClickedAction: Function) {
             if(titleStr != null && titleStr.trim() != '') {
                title.value = titleStr;
            }

            if(messageStr != null && messageStr.trim() != '') {
                message.value = messageStr;
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
            form,
            projectName,

        }
    },
})
</script>
