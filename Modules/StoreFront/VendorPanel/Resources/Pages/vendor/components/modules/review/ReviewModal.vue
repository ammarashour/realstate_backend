<template>
       <ps-modal ref="psmodal" line="hidden" maxWidth="405px" bodyHeight="488px" :isClickOut='false' theme="p-6 rounded-md">
        <template #title>
        </template>
        <template #body>
            <div class=" w-full p-2">
                <div class="w-full flex flex-col">

                    <div class="flex justify-between">
                        <ps-label class="font-semibold text-xl">  {{ $t("review_entry__leave_a_review") }} </ps-label>
                        <ps-icon name="close" class="cursor-pointer text-feAchromatic-400" @click.prevent="cancel"></ps-icon>
                    </div>

                    <div class="mt-5">
                        <ps-label class="font-semibold text-center text-lg">{{ $t("review_entry__your_experience") }}</ps-label>
                        <rating-selected ref="rating" class="mt-3 flex justify-center" :grade="0" :maxStars="5" :hasCounter="true" colors="text-feWarning-500"/>
                    </div>

                    <ps-label class="mt-4">  {{ $t("review_entry__title") }} </ps-label>
                    <ps-input class="mt-2" type="text" v-bind:placeholder="$t('review_enter__title')" v-model:value="title"></ps-input>

                    <ps-label class="mt-4">  {{ $t("review_entry__description") }} </ps-label>
                    <ps-textarea class=" mt-2" v-bind:placeholder="$t('review_enter__description')" :rows="5" v-model:value="description"></ps-textarea>
                </div>
            </div>
        </template>
        <template #footer>
            <div class="grid grid-cols-4 gap-6">
                <ps-button v-if="ratingProvider.loading.value == false"  @click="clicked" textSize="text-xxs lg:text-sm" class="col-span-2"  > {{ $t("review_entry__submit") }}</ps-button>
                <ps-button :disabled="true" v-else textSize="text-xxs lg:text-sm" class="col-span-2"  > {{ $t("review_entry__loading") }}</ps-button>
                <ps-button @click="cancel()" textSize="text-xxs lg:text-sm" class="col-span-2" colors="bg-feAchromatic-50 dark:bg-feAchromatic-800 dark:text-feAchromatic-200 hover:text-feAchromatic-50" border="border border-feAchromatic-300 dark:border-feAchromatic-500"> {{ $t("review_entry__cancel") }} </ps-button>
            </div>

        </template>
    </ps-modal>
</template>


<script lang="ts">
//Libs
import {ref} from 'vue';
import PsModal from '@template1/vendor/components/core/modals/PsModal.vue';
import PsLabel from '@template1/vendor/components/core/label/PsLabel.vue'
import PsButton from '@template1/vendor/components/core/buttons/PsButton.vue';
import PsInput from '@template1/vendor/components/core/input/PsInput.vue'
import RatingSelected from '@template1/vendor/components/core/rating/RatingSelected.vue';
import PsTextarea from '@template1/vendor/components/core/textarea/PsTextarea.vue';
import PsStatus from '@templateCore/api/common/PsStatus';
import PsIcon from '@template1/vendor/components/core/icons/PsIcon.vue';

// Models
import { PsValueStore } from "@templateCore/store/modules/core/PsValueStore";
import { useRatingStoreState } from "@templateCore/store/modules/rating/RatingStore";

// Params Holders
import RatingHolder from '@templateCore/object/holder/RatingHolder';


export default {
    name : "ReviewModal",
    components : {
        PsLabel,
        PsButton,
        PsModal,
        PsInput,
        PsTextarea,
        RatingSelected,
        PsIcon
    },
    setup() {
        const psmodal = ref();
        const psValueStore = PsValueStore();
        const holder = new RatingHolder();
        const ratingProvider = useRatingStoreState();
        const loginUserId = psValueStore.getLoginUserId();

        const toUserId = ref('');
        let okClicked: Function;
        let cancelClicked: Function;

        const rating = ref();
        const title = ref();
        const description = ref();

        function cancel(){
            psmodal.value.toggle(false);
        }

        function openModal(
                userId,
                cancelClickedAction: Function,
                okClickedAction: Function,
                ) {
            okClicked = okClickedAction;
            cancelClicked= cancelClickedAction;
            toUserId.value = userId;
            psmodal.value.toggle(true);
        }

        async function clicked() {
            holder.fromUserId = loginUserId;
            holder.toUserId = toUserId.value;
            holder.rating = rating.value.getRating();
            holder.title = title.value;
            holder.description = description.value;
            holder.type = "user";
            const status = await ratingProvider.postRating(holder, loginUserId);
            if(status.status == PsStatus.SUCCESS){
                okClicked();
            }else{
                cancelClicked();
            }
            psmodal.value.toggle(false);
        }

        return{
            ratingProvider,
            openModal,
            clicked,
            cancel,
            description,
            rating,
            title,
            loginUserId,
            psmodal
        }
    }
}
</script>
