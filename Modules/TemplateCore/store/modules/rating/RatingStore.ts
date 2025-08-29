import { reactive, ref } from 'vue';

import PsApiService from '@templateCore/api/PsApiService';
import PsResource from '@templateCore/api/common/PsResource';
import ApiStatus from '@templateCore/object/ApiStatus';
import Rating from '@templateCore/object/Rating';
import RatingHolder from '@templateCore/object/holder/RatingHolder';
import { defineStore, acceptHMRUpdate  } from 'pinia'
import { store } from '@templateCore/store/modules/core/PsStore';
import makeSeparatedStore from '@templateCore/store/modules/core/PsSepetetedStore';


export const useRatingStoreState = makeSeparatedStore((key: string) =>
defineStore(`ratingStore/${key}`,
 () => {

    const rating = reactive<PsResource<Rating>>(new PsResource());

    const loading = reactive({
        value: false
    });

    let limit = ref(10);
    let offset: Number = 0;

    let id: string = "";



    async function postRating(holder:RatingHolder, loginUserId: string): Promise<PsResource<ApiStatus>> {
        loading.value = true;

        const status = await PsApiService.postRating<ApiStatus>(new ApiStatus(), loginUserId, holder.toMap());

        loading.value = false;
        return status;


    }

    return {
        postRating,
        loading,
        limit,
        offset,
        rating,
    }

}),
);

