<template>
    <ps-modal ref="psmodal" maxWidth="370px" line="hidden" :isClickOut='false' theme=" px-6 py-6 rounded-lg" class=' z-50 h-56 bg-white'>
        <template #title>
            <div class="w-full flex flex-row justify-between items-center">
                <ps-label class="font-medium text-base lg:text-lg" :textColor="disabled?'text-secondary-300 dark:text-secondaryDark-white':'text-secondary-800 dark:text-white'"> {{title}} </ps-label>
                <ps-icon @click="close" name="cross" class="text-secondary-400 dark:text-secondary-500" />
            </div>
        </template>
        <template #body>
            <div v-if="dataReady" class=" flex flex-col justify-end mt-4">
                <ps-button :disabled="disabled" @click="actionClicked('yes')" textSize="text-sm" :class="showDelete ? 'mb-4' : ''" class="mt-2 w-full " >
                    <ps-icon :name="iconOne" :w="iconOneSize" :h="iconOneSize" class="me-2.5" />
                    <span> {{okButton}} </span>
                </ps-button>
                <ps-button v-if="showDelete" :disabled="disabled" @click="actionClicked('no')" textSize="text-sm" border="border border-secondary-200 dark:border-secondary-700" class="w-full"
                colors="bg-white text-primary-500 dark:bg-secondaryDark-black" hover="hover:outline-none hover:ring hover:ring-secondary-000 dark:hover:ring-secondary-600" focus="focus:outline-none focus:ring focus:ring-secondary-000 dark:focus:ring-secondary-600" >
                    <ps-icon :name="iconTwo" :w="iconTwoSize" :h="iconOneSize" class="me-2.5"/>
                    <span> {{cancelButton}} </span>
                </ps-button>
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
import PsIcon from '../Icons/PsIcon.vue';

export default defineComponent({
    name : "PsActionModal",
    props: {
        'disabled': { type: Boolean, default: false },
    },
    components : {
        PsModal,
        PsLabel,
        PsButton,
        PsIcon
    },
    setup() {
        const psmodal = ref();
        const dataReady = ref(false);
        const title = ref(trans('ps_confirm_dialog__confirm'));
        const okButton = ref(trans('ps_confirm_dialog__yes'));
        const cancelButton = ref(trans('ps_confirm_dialog__no'));
        let okClicked: Function;
        let cancelClicked: Function;
        let iconOne = ref('videoCamera');
        let iconTwo = ref('trash');
        let iconOneSize = ref('24');
        let iconTwoSize = ref('24');
        const showDelete = ref(true);

        function actionClicked(status) {
            if(status == 'yes') {
                okClicked();
            }else {
                cancelClicked();
            }
            psmodal.value.toggle(false);
            dataReady.value = false;
        }

        function close() {
            psmodal.value.toggle(false);
            dataReady.value = false;
        }

        function openModal(
                titleString,
                okString,
                cancelString,
                iconOneStr,
                iconTwoStr,
                iconOneSizeStr,
                iconTwoSizeStr,
                okClickedAction: Function,
                cancelClickedAction: Function,
                showDeleteBol = true) {

            iconOne.value = iconOneStr;
            iconTwo.value = iconTwoStr;
            iconOneSize.value = iconOneSizeStr;
            iconTwoSize.value = iconTwoSizeStr;
            title.value = titleString;
            okButton.value = okString;
            cancelButton.value = cancelString;
            dataReady.value = true;
            psmodal.value.toggle(true);
            okClicked = okClickedAction;
            cancelClicked= cancelClickedAction;
            showDelete.value = showDeleteBol;
        }

        return {
            psmodal,
            title,
            openModal,
            actionClicked,
            okButton,
            cancelButton,
            iconOne,
            iconTwo,
            iconOneSize,
            iconTwoSize,
            close,
            dataReady,
            showDelete
        }
    },
})
</script>
