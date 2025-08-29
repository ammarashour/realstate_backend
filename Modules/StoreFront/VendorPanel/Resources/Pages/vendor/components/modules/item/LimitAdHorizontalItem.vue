<template>

    <!-- Start Pscard -->
    <div class="sm:w-full md:w-full flex flex-col rounded-t-3xl bg-fePrimary-300 px-0" v-on:click="onClick != null ? onClick(buyad) : null">

        <!-- Package -->
        <ps-label textColor="text-base text-center font-medium text-feSecondary-800 mt-6">{{buyad ? buyad.package.paymentInfo.value : ''}} {{ $t("package__packages") }}</ps-label>

        <!-- Price -->
        <ps-label textColor="text-base text-center font-medium text-feSecondary-800">{{buyad ? buyad.package.paymentAttributes.currencySymbol : ''}}{{buyad ? buyad.package.paymentAttributes.price : ''}}</ps-label>

        <div :class="['bg-fePrimary-50 rounded-t-3xl mt-4 text-feSecondary-800 flex flex-col gap-4', padding]">
            <!-- Posts -->
            <ps-label class="flex justify-between items-center text-sm font-medium ">
                <span>{{ $t("package__total_post") }}</span>
                <span>{{buyad ? buyad.user.remainingPost : ''}} {{ $t("package__posts") }}</span>
            </ps-label>

            <!-- Purchased Date -->
            <ps-label class="flex justify-between items-center text-sm font-medium ">
                <span>{{ $t("package__purchased_date") }}</span>
                <span>{{ buyad ?  moment(buyad.addedDate.split(" ")[0]).format($page.props.dateFormat)  : ''}}</span>
            </ps-label>

            <!-- Purchased Time -->
            <ps-label class="flex justify-between items-center text-sm font-medium ">
                <span>{{ $t("package__purchased_time") }}</span>
                <span>{{buyad ? buyad.addedDate.split(" ")[1] : ''}}</span>
            </ps-label>

            <!-- Payment Type -->
            <ps-label class="flex justify-between items-center text-sm font-medium ">
                <span>{{ $t("package__payment_type") }}</span>
                <span>{{buyad ? buyad.paymentMethod : ''}}</span>
            </ps-label>

        </div>
    </div>
    <!-- END Pscard -->
</template>

<script lang="ts">

// Components
import PsLabel from '@template1/vendor/components/core/label/PsLabel.vue';

//language

// test

import { trans } from 'laravel-vue-i18n';
import PsUtils from '@templateCore/utils/PsUtils';

// Objects
import LimitAdTransaction from '@templateCore/object/LimitAdTransaction';

// Providers
import { usePsAppInfoStoreState } from '@templateCore/store/modules/appinfo/AppInfoStore';
import AppInfoParameterHolder from '@templateCore/object/holder/AppInfoParameterHolder';
import { PsValueStore } from "@templateCore/store/modules/core/PsValueStore";
import { usePsValueHolderState } from '@templateCore/object/core/PsValueHolder';
import moment from "moment";

export default {
    name : "LimitAdHorizontalItem",
    components : {
        PsLabel
    },
    props : {
        buyad : { type : LimitAdTransaction } ,
        onClick : Function,
        padding: {
            type: String,
            default: "py-10 px-10 lg:px-3"
        }
    },
    setup() {
        // Inject Provider
        PsValueStore.psValueStore = usePsValueHolderState();

        const psValueStore = PsValueStore();
        const loginUserId = psValueStore.getLoginUserId();
        const appInfoParameterHolder = new AppInfoParameterHolder();
        appInfoParameterHolder.userId = loginUserId;
        const appInfoStore = usePsAppInfoStoreState();

        function formatPrice(value) {
            if(value.toString() == '0') {
                return trans('item_price__free');
            }else {
                return PsUtils.formatPrice(appInfoStore, value);
            }
        }
        return {
            formatPrice,
            psValueStore,
            moment
        }
    }
}
</script>
