<template>
    <ps-modal ref="psmodal" maxWidth="408px" :isClickOut='true' theme="dark:bg-feSecondary-800 px-3 py-6 sm:p-6 border rounded-lg" class="z-50 " line="hidden">
        <template #title>
            <div class="flex gap-2 items-center">
                <ps-icon name="checkCircle" class="text-feInfo-500" w="24" h="24" @click="psmodal.toggle(false)"/>
                <ps-label-title class="grow"> {{ $t('blue_mark_user__verify_blue_mark') }} </ps-label-title>
                <ps-icon class="cursor-pointer dark_text-feSecondary-500" name="close" w="24" h="24" @click="psmodal.toggle(false)"/>
            </div>
        </template>
        
        <template #body>
            <ps-textarea theme="dark_bg-feSecondary-800 dark:text-feSecondary-200 mt-6" v-bind:placeholder="$t('blue_mark_user__enter_contact_info')" :rows="8" v-model:value="note"  @keypress="validateNote"></ps-textarea>
            <ps-label class="" textColor="text-fePrimary-500" v-if="validation"> {{ $t("blue_mark_user__note_required") }} </ps-label>
            <ps-label textColor="text-xs font-normal text-feSecondary-500 dark:text-feSecondary-400 mt-4">{{ $t('blue_mark_user__description') }}</ps-label>
        </template>

        <template #footer>
            <div class="mt-6 flex flex-col gap-4">
                <ps-button class="" @click="submitClicked" > {{ $t('blue_mark_user__apply') }} </ps-button>
                <ps-button colors="bg-feAchromatic-50 dark:bg-feSecondary-800 text-feSecondary-800 dark:text-feSecondary-200" focus="" hover="hover:bg-feSecondary-50" border="border border-feSecondary-300 dark:border-feSecondary-500" @click="psmodal.toggle(false)" > {{ $t('blue_mark_user__close') }}  </ps-button>
            </div>
        </template>
    </ps-modal>
    <ps-loading-dialog ref="ps_loading_dialog"  :isClickOut='false'/>

    <ps-error-dialog ref="ps_error_dialog" />

    <ps-success-dialog ref="ps_success_dialog" />
</template>

<script lang="ts">

// Libs
import { defineComponent, ref } from 'vue';

// Compone
import PsModal from '@template1/vendor/components/core/modals/PsModal.vue';
import PsLabelTitle from '@template1/vendor/components/core/label/PsLabelTitle.vue';
import PsLabel from '@template1/vendor/components/core/label/PsLabel.vue';
import PsTextarea from '@template1/vendor/components/core/textarea/PsTextarea.vue';
import PsButton from '@template1/vendor/components/core/buttons/PsButton.vue';
import PsErrorDialog from '@template1/vendor/components/core/dialog/PsErrorDialog.vue';
import PsSuccessDialog from '@template1/vendor/components/core/dialog/PsSuccessDialog.vue';
import PsLoadingDialog from '@template1/vendor/components/core/dialog/PsLoadingDialog.vue';
import PsStatus from '@templateCore/api/common/PsStatus';
import PsIcon from '@template1/vendor/components/core/icons/PsIcon.vue';

import { useUserStore } from '@templateCore/store/modules/user/UserStore';
import UserBlueMarkParameterHolder from '@templateCore/object/holder/UserBlueMarkParameterHolder';

export default defineComponent({
    name: "UserBlueMarkModal",
    components: {
        PsModal,
        PsLabelTitle,
        PsLabel,
        PsButton,
        PsIcon,
        PsErrorDialog,
        PsLoadingDialog,
        PsSuccessDialog,
        PsTextarea,
    },
    setup() {
        const psmodal = ref();
        const note = ref('');
        const ps_loading_dialog = ref();
        const ps_error_dialog = ref();
        const ps_success_dialog = ref();
        let user_id = '1';
        const validation =ref(false);
        const holder = new UserBlueMarkParameterHolder();
        // Init Provider
        const provider = useUserStore();

        function openModal(loginUserId) {
            user_id = loginUserId;
            psmodal.value.toggle(true);

        }
        function validateNote(e) {
            const pattern = /^\/$|(\/[a-zA-Z_0-9-]+)+$/;
            const res = pattern.test(e.target.value);
            if (!res) {
                validation.value = false;
            } else {
                validation.value = true;
            }
        }

        async function submitClicked() {
            holder.loginUserId = user_id;
            holder.note = note.value;
            if(note.value == '' || note.value == undefined){
                validation.value = true;
                return false;
            }

            ps_loading_dialog.value.closeModal();
            const user = await provider.blueMarkUser(user_id, holder);
            if(user.status == PsStatus.ERROR) {
                ps_error_dialog.value.openModal('', user.message);
            } else {
                ps_success_dialog.value.openModal('', user.message);
            }
            const loginUser = await provider.loadUser(user_id);
            ps_loading_dialog.value.closeModal();
            psmodal.value.toggle(false);
        }

        return {
            psmodal,
            openModal,
            note,
            provider,
            submitClicked,
            ps_loading_dialog,
            ps_error_dialog,
            ps_success_dialog,
            validateNote,
            validation
        }
    },
})

</script>
