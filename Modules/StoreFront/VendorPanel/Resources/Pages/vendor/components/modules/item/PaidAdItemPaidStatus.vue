<template>
<div class="">
    <div v-if="status == PsConst.paidItemProgressStatus" >
    <ps-label-caption>
        <div class="absolute py-2 px-4 left-2 top-2 bg-feInfo-500 text-feAchromatic-50   text-center leading-4 rounded-sm">
            {{ $t("core__fe_paidad_item__progress") }}
        </div>
    </ps-label-caption>
    </div>

    <div v-else-if="status == PsConst.paidItemCompletedStatus">
    <ps-label-caption>
        <div class="absolute py-2 px-4 left-2 top-2 bg-feSuccess-500 text-feAchromatic-50   text-center leading-4 rounded-sm">
            {{ $t("core__fe_paidad_item__completed") }}
        </div>
    </ps-label-caption>
    </div>

    <div v-else-if="status == PsConst.paidItemNotYetStartStatus">
    <ps-label-caption>
        <div class="absolute py-2 px-4 left-2 top-2 bg-feAchromatic-500 text-feAchromatic-50   text-center leading-4 rounded-sm">
            {{ $t("core__fe_paidad_item__not_reached") }}
        </div>
    </ps-label-caption>
    </div>

    <div v-else-if="status == PsConst.paidItemNotAvailable">
    <ps-label-caption>
        <div class="absolute py-2 px-4 left-2 top-2 bg-feAchromatic-500 text-feAchromatic-50   text-center leading-4 rounded-sm">
            {{ $t("paidad_item__no_action") }}
        </div>
    </ps-label-caption>
    </div>

    <div v-else-if="status == PsConst.paidItemWaitingForApproval">
    <ps-label-caption>
        <div class="absolute py-2 px-4 left-2 top-2 bg-feWarning-500 text-feAchromatic-50   text-center rounded-sm">
            {{ $t("core__fe_paidad_item__wait_approval") }}
        </div>
    </ps-label-caption>
    </div>

    <div v-else-if="status == PsConst.paidItemRejected">
    <ps-label-caption>
        <div class="absolute py-2 px-4 left-2 top-2 bg-feError-600 text-feAchromatic-50   text-center leading-4 rounded-sm">
            {{ $t("core__fe_paidad_item__wait_rejected") }}
        </div>
    </ps-label-caption>
    </div>
</div>
</template>

<script lang="ts">

import PsLabelCaption from '@template1/vendor/components/core/label/PsLabelCaption.vue';
import PsConst from '@templateCore/object/constant/ps_constants';

export default {
    name : "PaidAdItemPaidStatus",
    components : {
        PsLabelCaption

    },
    props : {
        status : { type : String } ,
        onClick : Function
    },
    setup(){
        return {
            PsConst
        }
    }
}
</script>
