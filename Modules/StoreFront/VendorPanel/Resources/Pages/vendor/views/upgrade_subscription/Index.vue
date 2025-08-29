<template>
    <Head :title="$t('core__vendor_subscription_upgrade')" />
    <ps-layout>
        <!-- breadcrumb start -->
        <ps-breadcrumb-2 :items="breadcrumb" class="mb-5 sm:mb-6 lg:mb-8" />

        <!-- expiry noti -->
        <div v-if="vendor.expire_status == 1" :class="['flex p-4 rounded-lg border gap-4 mb-6 border-yellow-400 bg-yellow-50']">
            <div class="w-6 h-6">
                <ps-icon theme="text-yellow-500" name="warning-triangle" w="24" h="24"/>
            </div>
            <ps-label textColor="font-semibold">{{$t('core_vendor__subscription_plan_warning_info',{'attribute': moment(vendor.expired_date).format($page.props.dateFormat)})}}</ps-label>
        </div>
        <div v-if="vendor.expire_status == 2" :class="['flex p-4 rounded-lg border gap-4 mb-6 border-red-400 bg-red-50']">
            <div class="w-6 h-6">
                <ps-icon theme="text-red-500" name="close-fill" w="24" h="24" viewBox="0 0 16 16"/>
            </div>
            <ps-label textColor="font-semibold">{{$t('core_vendor__subscription_plan_expired_info',{'attribute': moment(vendor.expired_date).format($page.props.dateFormat)})}}</ps-label>
        </div>



        <div class="grid grid-cols-3 gap-6">
            <div v-for="plan in vendorSubscriptionPlans" :key="plan.id" >
                <subscription-plan-horizontal-plan :plan="plan" @purchaseClick="purchaseClick"/>
            </div>
        </div>
        <payment-modal ref="payment_modal" :appInfo="appInfo" :vendor="vendor"/>
        <ps-success-dialog ref="ps_success_dialog" />
        <ps-error-dialog ref="ps_error_dialog" />
    </ps-layout>
</template>

<script>
import { ref, defineComponent, defineAsyncComponent,watch } from "vue";
import { Head } from "@inertiajs/vue3";
import { trans } from 'laravel-vue-i18n';
import moment from 'moment';

import PsVendorLayout from "@vendorPanel/vendor/components/layouts/container/PsVendorLayout.vue";
import PsBreadcrumb2 from "@/Components/Core/Breadcrumbs/PsBreadcrumb2.vue";
import PsLabelHeader6 from "@/Components/Core/Label/PsLabelHeader6.vue";
import PsButton from "@/Components/Core/Buttons/PsButton.vue";
import PsLabel from "@/Components/Core/Label/PsLabel.vue";
import PsIcon from "@/Components/Core/Icons/PsIcon.vue";
import SubscriptionPlanHorizontalPlan from './components/SubscriptionPlanHorizontalPlan.vue';
// import PaymentModal from './components/PaymentModal.vue';
import PsSuccessDialog from "@/Components/Core/Dialog/PsSuccessDialog.vue";
const PsErrorDialog = defineAsyncComponent(()=>import('@template1/vendor/components/core/dialog/PsErrorDialog.vue'));
const PaymentModal = defineAsyncComponent(()=>import('./components/PaymentModal.vue'));

    export default defineComponent({
        name: "Index",
        components: {
            Head,
            PsBreadcrumb2,
            PsLabelHeader6,
            PsButton,
            PsLabel,
            PsIcon,
            SubscriptionPlanHorizontalPlan,
            PaymentModal,
            PsSuccessDialog,PsErrorDialog,
        },
        layout: PsVendorLayout,
        props: {
            vendorSubscriptionPlans : Object,
            vendor: Object,
            appInfo: Object,
            status: Object,
        },
        setup(props){
            const payment_modal = ref();
            const ps_success_dialog = ref();
            const ps_error_dialog = ref();

            function purchaseClick(id, price, currency){
                payment_modal.value.openModal(id, price, currency);
                // ps_success_dialog.value.openModal(
                //     trans('core_vendor__update_vendor_success'),
                //     trans('core_vendor__update_vendor_success_message'),
                //     trans('core__vendor_got_it'),
                //     ()=>{},
                //     false);
            }

            function watchStatus(value){
                if(value.flag == 'success'){
                    ps_success_dialog.value.openModal(
                        trans('core_vendor__update_vendor_success'),
                        trans('vendor_subscription__transaction_success'),
                        trans('core__vendor_got_it'),
                        ()=>{
                            localStorage.paymentNonce = "";
                            location.reload();
                        },
                        false);
                }else{
                    ps_error_dialog.value.openModal(trans('core__vendor_btn_ok'), trans(value.msg));
                }
            }

            return {
                moment,
                purchaseClick,
                watchStatus,
                payment_modal,
                ps_success_dialog,
                ps_error_dialog,
            }
        },
        watch: {
            status(current, previous){
                this.watchStatus(current)
            }
        },
        computed: {
            breadcrumb() {
                return [
                    {
                        label: trans('core__vendor_my_store_module'),
                    },
                    {
                        label: trans('core__vendor_subscription_upgrade'),
                        color: "text-primary-500"
                    }
                ]
            },
        },
    })
</script>
