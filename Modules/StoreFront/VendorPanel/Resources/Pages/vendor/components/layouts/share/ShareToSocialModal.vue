<template>
    <ps-modal ref="psmodal" :isClickOut="false" maxWidth="472px" theme="rounded-lg p-6 bg-feAchromatic-50 dark:bg-feSecondary-800" >
        <template #title>
            <ps-label textColor="text-xl font-semibold text-feSecondary-800 dark:text-feSecondary-200">  {{ $t("share_with_social_modal__share_with") }} </ps-label>
        </template>
        <template #body>

            <ps-link :url="facebookURL">
                <div class="flex items-center gap-4 mb-6">
                    <ps-icon textColor="text-feSecondary-800 dark:text-feSecondary-200" name="share-facebook" w="32" h="32" viewBox="0 0 32 32"/>
                    <!-- <ps-label> {{ $t("share_with_social_modal__facebook") }} </ps-label> -->
                    <ps-label textColor="text-lg font-medium text-feSecondary-800 dark:text-feSecondary-200"> {{ $t("Facebook") }} </ps-label>
                </div>
            </ps-link>


            <ps-link :url="twitterURL">
                <div class="flex items-center gap-4 mb-6">
                    <ps-icon textColor="text-feSecondary-800 dark:text-feSecondary-200" name="twitter" w="32" h="32" viewBox="0 0 20 20"/>
                    <ps-label textColor="text-lg font-medium text-feSecondary-800 dark:text-feSecondary-200"> {{ $t("Twitter") }} </ps-label>
                </div>
            </ps-link>


            <ps-link :url="linkedinURL">
                <div class="flex items-center gap-4 mb-6">
                    <ps-icon textColor="text-feSecondary-800 dark:text-feSecondary-200" name="linkedIn" w="32" h="32" viewBox="0 0 20 20"/>
                    <ps-label textColor="text-lg font-medium text-feSecondary-800 dark:text-feSecondary-200"> {{ $t("LinkedIn") }} </ps-label>
                </div>
            </ps-link>

            <div @click="copy" class="cursor-pointer">
                <div class="flex items-center gap-4">
                    <ps-icon textColor="text-feSecondary-800 dark:text-feSecondary-200" name="copy-link-outline" w="32" h="32" viewBox="0 0 32 32"/>
                    <ps-label textColor="text-lg font-medium text-feSecondary-800 dark:text-feSecondary-200"> {{ $t("item_detail__copy_link") }} </ps-label>
                </div>
            </div>
        </template>
        <template #footer>
            <div class="flex justify-end">
                <ps-button @click="psmodal.toggle(false)" colors="bg-feAchromatic-50 text-feSecondary-800 dark:bg-feSecondary-800 dark:text-feSecondary-200" hover="dark:hover:bg-feSecondary-700" border="border"> {{ $t("share_with_social_modal__cancel") }} </ps-button>
            </div>
        </template>
    </ps-modal>
    <ps-success-dialog ref="success" />
</template>

<script lang='ts'>
import { defineComponent, ref } from 'vue';
import PsModal from '@template1/vendor/components/core/modals/PsModal.vue';
import PsLabelTitle from '@template1/vendor/components/core/label/PsLabelTitle.vue';
import PsLabel from '@template1/vendor/components/core/label/PsLabel.vue';
import PsButton from '@template1/vendor/components/core/buttons/PsButton.vue';
import PsLink from '@template1/vendor/components/core/link/PsLink.vue'
import PsSuccessDialog from '@template1/vendor/components/core/dialog/PsSuccessDialog.vue';
import PsIcon from '@template1/vendor/components/core/icons/PsIcon.vue';
import { trans } from 'laravel-vue-i18n'

export default defineComponent({
    name : "ShareToSocialModal",
    components: {
        PsModal,
        PsIcon,
        PsLabelTitle,
        PsLabel,
        PsButton,
        PsLink,
        PsSuccessDialog
    },
    setup() {
        const psmodal = ref();
        const url = ref();
        const facebookURL = ref();
        const twitterURL = ref();
        const linkedinURL = ref();
        const success = ref();

        function openModal(link, id, title){

            url.value = link;
            facebookURL.value=  'http://www.facebook.com/sharer/sharer.php?u='+ link +'&title='+title;
            twitterURL.value =  'https://twitter.com/intent/tweet?text=' + title + '&url=' + link;
            linkedinURL.value = 'http://www.linkedin.com/shareArticle?mini=true&url=' + link + '&title=' + title;
            psmodal.value.toggle(true);
        }
        async function copy() {
            await navigator.clipboard.writeText(url.value);
            psmodal.value.toggle(false);
            success.value.openModal(
                trans('item_detail__copy_success'),
                trans('item_detail__link_copied_to_clipboard')
            );
        }

        return {
            psmodal,
            openModal,
            facebookURL,
            twitterURL,
            linkedinURL,
            copy,
            success
         }
    },
})
</script>
