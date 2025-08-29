<template>
    <div v-if="expiredStatus != 0" :class="['flex p-4 rounded-lg border gap-4 mb-8',expiredStatus == 1?'border-yellow-400 bg-yellow-50':'border-red-400 bg-red-50']">
        <div class="w-6 h-6">
            <ps-icon v-if="expiredStatus == 1" theme="text-yellow-500" name="warning-triangle" w="24" h="24"/>
            <ps-icon v-if="expiredStatus == 2" theme="text-red-500" name="close-fill" w="24" h="24" viewBox="0 0 16 16"/>
        </div>
        <div>
            <ps-label v-if="expiredStatus == 1" textColor="font-semibold">{{$t('core_vendor__subscription_plan_warning_info',{'attribute': moment(expiredDate).format($page.props.dateFormat)})}}</ps-label>
            <ps-label v-if="expiredStatus == 2" textColor="font-semibold">{{$t('core_vendor__subscription_plan_expired_info',{'attribute': moment(expiredDate).format($page.props.dateFormat)})}}</ps-label>
            <ps-button @click="upgradeClick()" class="mt-4" rounded="rounded" colors="bg-red-500 text-white dark:text-secondaryDark-black">{{$t('core_vendor__purchase_now')}}</ps-button>
        </div>
    </div>
</template>

<script>
import { ref, defineComponent, watch } from "vue";
import { Head, useForm, usePage } from "@inertiajs/vue3";
import { trans } from 'laravel-vue-i18n';
import PsLabel from "@/Components/Core/Label/PsLabel.vue";
import PsIcon from "@/Components/Core/Icons/PsIcon.vue";
import PsButton from "@/Components/Core/Buttons/PsButton.vue";
import moment from 'moment';
import { router } from '@inertiajs/vue3';

    export default defineComponent({
        name: "VendorDetailSubscriptionNotification",
        components: {
            PsLabel,
            PsIcon,
            PsButton,
        },
        props: {
            expiredStatus:  Number,
            expiredDate: String,
        },
        setup(props) {
            function upgradeClick(){
                router.get(route('upgrade_subscription.index'));
            }
            return {
                moment,
                upgradeClick,
            }
        }
    })
</script>
