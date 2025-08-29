<template>
    <div class=" w-full md:w-auto cursor-pointer my-2 ">
        <!-- Pscard -->
        <div class="flex flex-col my-2 bg-fePrimary-50 h-[196px] rounded-md dark:bg-feAchromatic-900" :enabledHover="true">
            <div class="px-4 mt-4">

                <div class="flex justify-between">
                    <rating-view :iconColor="'text-feWarning-500 dark:text-feWarning-accent'" :size="'md'" :rating="rate ? rate.rating:0" :maxStars="5" :hasCounter="true" />
                    <ps-label-title-3 class="mt-2"> {{rate ? moment(rate.addedDate).format($page.props.dateFormat):''}} </ps-label-title-3>
                </div>
                <ps-label class="mt-2 mb-2 text-xl"> {{rate ? rate.title:''}} </ps-label>
                <!-- <ps-label class="mt-2 mb-4"> {{rate ? rate.description:''}} </ps-label> -->
                <div class="h-[100px] overflow-y-auto">
                    <ps-label class="mt-2 mb-4"> {{rate ? rate.description:''}} </ps-label>
                </div>
            </div>
        </div>
        <!-- end Pscard -->
    </div>
</template>

<script lang="ts">

import PsLabel from '@template1/vendor/components/core/label/PsLabel.vue';
import PsLabelTitle3 from '@template1/vendor/components/core/label/PsLabelTitle3.vue';
import PsCard from '@template1/vendor/components/core/card/PsCard.vue';
import RatingView from '@template1/vendor/components/core/rating/RatingShow.vue';

import Rating from '@templateCore/object/Rating';
import moment from "moment";

export default {
    name : "RatingHorizontal",
    components : {
        PsLabel,
        PsCard,
        PsLabelTitle3,
        RatingView
    },
    props : {
        rate : { type : Rating } ,
        onClick : Function
    },
    setup() {
        // Inject Provider
        return {
            moment
        }
    }
}
</script>
