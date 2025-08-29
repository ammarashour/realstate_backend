<template>
    <ps-modal ref="psmodal"  :isClickOut='false' line="hidden">
        <template #title>
             <!-- Item entry title -->
            <div class=" items-center">
                <ps-label-header-6 > {{offlineProvider.offlinePayment.data?.message}} </ps-label-header-6>
            </div>
        </template>

        <template #body>
            <!-- End Item entry title -->
            <!-- Start Input Field for md .. -->
            <div class="flex justify-between container w-full p-4">
                <!-- Start Left Screen -->
                <div class="md:w-full h-72 bg-feAchromatic-50 dark:bg-feAchromatic-900 rounded-md  pt-2 overflow-auto">
                    <div v-for="offline in offlineProvider.offlinePayment.data" :key="offline.id">
                        <!-- bank -->
                        <div class="flex flex-auto px-4 py-10">
                            <div>
                                <img alt="Placeholder" width="300px" height="300px" class="w-18 mx-auto h-18"
                                v-lazy=" { src: $page.props.thumb2xUrl + '/' + offline.defaultIcon.imgPath, loading: $page.props.sysImageUrl+'/loading_gif.gif', error: $page.props.sysImageUrl+'/default_profile.png' }">
                            </div>
                            <div class="flex flex-col ms-4">
                                <ps-label-title-3> {{offline ? offline.title : ''}} </ps-label-title-3>
                                <ps-label> {{offline ? offline.description : ''}} </ps-label>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- End Left Screen -->
            </div>
            <!-- End Input Field -->
        </template>

        <template #footer>
            <div class="flex items-center justify-center mb-4">
                <ps-button class="text-center w-60 mx-auto  " @click="actionClicked('yes')" > {{ $t('offline_payment_modal__pay_offline') }} </ps-button>
                <ps-button class="text-center w-60 mx-auto ms-4" theme="btn-second" @click="actionClicked('no')" >  {{ $t('stripe_credit_card_modal__cancel') }} </ps-button>
            </div>
        </template>

    </ps-modal>

    <ps-loading-dialog ref="psloading"  :isClickOut='false'/>

</template>

<script lang='ts'>
import { defineComponent,ref } from 'vue';
import PsModal from '@template1/vendor/components/core/modals/PsModal.vue';
import PsLabel from '@template1/vendor/components/core/label/PsLabel.vue';
import PsLabelTitle3 from '@template1/vendor/components/core/label/PsLabelTitle3.vue';
import PsLabelHeader6 from '@template1/vendor/components/core/label/PsLabelHeader6.vue';
import PsButton from '@template1/vendor/components/core/buttons/PsButton.vue';
import PsLoadingDialog from '@template1/vendor/components/core/dialog/PsLoadingDialog.vue';
import { useOfflinePaymentStoreState } from '@templateCore/store/modules/offlinePayment/OfflinePaymentStore';

export default defineComponent({
    name : 'OfflinePaymentModal',
    components : {
        PsModal,
        PsLabel,
        PsButton,
        PsLabelTitle3,
        PsLoadingDialog,
        PsLabelHeader6
    },
    setup() {


        const psmodal = ref();
        const psloading = ref();
        const offlineProvider = useOfflinePaymentStoreState();

        let okClicked: Function;
        let cancelClicked: Function;

        async function openModal(okClickedAction: Function, cancelClickedAction: Function) {
            await offlineProvider.loadOfflinePaymentMethodList();
            okClicked = okClickedAction;
            cancelClicked= cancelClickedAction;
            psmodal.value.toggle(true);

        }

        function actionClicked(status) {
            if(status == 'yes') {
                okClicked();
            }else {
                cancelClicked();
            }
            psmodal.value.toggle(false);
        }

        return {
            psmodal,
            psloading,
            openModal,
            actionClicked,
            offlineProvider,
        }
    },
})
</script>
