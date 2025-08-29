<template>
    <ps-modal ref="psmodal" maxWidth="450px" line="hidden" :isClickOut='false' theme=" px-6 py-7 rounded-lg shadow-xl" class=' z-20'>
        <template #title>
            <ps-icon v-if="canClose" @click="close()" name="close" class="text-sm text-secondary-400 ms-auto my-auto focus:shadow-none hover:text-purple-500 flex justify-end"  />
            <div class="flex flex-col text-center justify-center items-center text-gray-300">
                <ps-icon class="flex-grow-0" name="alert-triangle" w="86" h="86" />
                <ps-label class="font-medium text-xl lg:text-2xl mt-3"> {{title}} </ps-label>
            </div>
        </template>
        <template #body>
            <div class="w-full text-center mt-4">
                <ps-label class="font-light text-sm lg:text-base"> {{message}} </ps-label>
                <div class="mb-2">
                    <a target="_blank" :href="link" class="underline text-indigo-500">
                        {{ linkString }}
                    </a>
                </div>
            </div>


        </template>
        <template #footer>
            <div v-if="canClose" class=" flex justify-center mt-6">
                <div class="flex-grow-0">
                    <ps-button rounded="rounded" @click="actionClicked()" textSize="text-sm" class="" colors="bg-red-500 text-white"  hover="hover:outline-none hover:ring hover:ring-red-100" focus="focus:outline-none focus:ring focus:ring-red-300" > {{okButton}} </ps-button>
                </div>

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
import PsIcon from "@/Components/Core/Icons/PsIcon.vue";

export default defineComponent({
    components : {
        PsModal,
        PsLabel,
        PsButton,
        PsIcon
    },
    setup() {
        const psmodal = ref();
        const title = ref(trans('ps_success_dialog__success'));
        const message = ref(trans('ps_success_dialog__success_message'));
        const okButton = ref(trans('ps_confirm_dialog__yes'));
        let okClicked: Function;
        const canClose = ref(false);
        const link = ref();
        const linkString = ref();

        function actionClicked() {
            psmodal.value.toggle(false);

            okClicked();

        }

        function openModal(
                titleString,
                messageString,
                okString,
                okClickedAction: Function,
                canCloseCondition,
                url,
                urlString) {
            title.value = titleString;
            message.value = messageString.toString();
            okButton.value = okString;
            psmodal.value.toggle(true);
            okClicked = okClickedAction;
            canClose.value = canCloseCondition;
            link.value = url;
            linkString.value = urlString;
        }

        function close() {
            psmodal.value.toggle(false);
        }

        return {
            psmodal,
            title,
            message,
            openModal,
            actionClicked,
            okButton,
            close,
            canClose,
            link,
            linkString
        }
    },
})
</script>
