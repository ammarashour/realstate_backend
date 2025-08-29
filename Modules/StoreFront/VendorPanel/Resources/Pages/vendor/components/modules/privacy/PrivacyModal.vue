<template>
    <ps-modal ref="psmodal" maxWidth="650px" :isClickOut='false' class="z-50"  theme="p-6 rounded-md w-auto overflow-hidden">
        <template #title>
            <div class="flex justify-between">
                <ps-label-title> {{ $t('privact_modal__privacy_policy') }} </ps-label-title>
                <ps-icon name="close" class="text-feAchromatic-400 hover:cursor-pointer" @click.prevent="psmodal.toggle(false)"></ps-icon>
            </div>
        </template>
        <template #body>
                <div class="h-52 overflow-y-auto">
                    <ps-label v-html="aboutUsStore.aboutus?.data?.privacypolicy"> </ps-label>
                </div>
        </template>
        <template #footer>
            <div class="flex justify-end">
                <ps-button @click="userAction(true)"> {{ $t('privact_modal__accept') }} </ps-button>
                <ps-button @click="userAction(false)" class='ms-4' textSize="text-xxs lg:text-sm" colors="bg-feAchromatic-50 dark:bg-feAchromatic-800 dark:text-feAchromatic-200 hover:text-feAchromatic-50" border="border border-feAchromatic-300 dark:border-feAchromatic-500">  {{ $t('privact_modal__reject') }} </ps-button>
            </div>
        </template>
    </ps-modal>
</template>


<script lang='ts'>

import { defineComponent,ref } from 'vue';
import PsModal from '@template1/vendor/components/core/modals/PsModal.vue';
import PsLabelTitle from '@template1/vendor/components/core/label/PsLabelTitle.vue';
import PsLabel from '@template1/vendor/components/core/label/PsLabel.vue';
import PsButton from '@template1/vendor/components/core/buttons/PsButton.vue';
import { useAboutUsStoreState } from "@templateCore/store/modules/aboutus/AboutUsStore";
import PsConst from '@templateCore/object/constant/ps_constants';
import { PsValueStore } from '@templateCore/store/modules/core/PsValueStore';
import PsIcon from '@template1/vendor/components/core/icons/PsIcon.vue';

export default defineComponent({
    name: "PrivacyModel",
    components : {
        PsModal,
        PsLabel,
        PsLabelTitle,
        PsButton,
        PsIcon
    },
    setup() {
        const psmodal = ref();
        const aboutUsStore = useAboutUsStoreState();
        let psValueStore = PsValueStore();
        const loginUserId = psValueStore.getLoginUserId();
        aboutUsStore.loadAboutUs(loginUserId);
        let isAccept;
        function openModal(isAcceptFun?) {

            psmodal.value.toggle(true);
            isAccept = isAcceptFun;
        }

        function userAction(status: Boolean) {
            isAccept(status);
            closeModal();
        }

        function closeModal() {
            psmodal.value.toggle(false);
        }
        return {
            psmodal,
            openModal,
            closeModal,
            aboutUsStore,
            userAction
        }
    },
})
</script>
