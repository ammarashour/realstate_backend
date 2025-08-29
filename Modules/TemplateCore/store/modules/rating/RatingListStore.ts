
import { reactive,ref } from 'vue';

import PsApiService from '@templateCore/api/PsApiService';
import PsResource from '@templateCore/api/common/PsResource';
import Rating from '@templateCore/object/Rating';
import RatingListHolder from '@templateCore/object/holder/RatingListHolder';
import { defineStore, acceptHMRUpdate  } from 'pinia'
import { store } from '@templateCore/store/modules/core/PsStore';

import makeSeparatedStore from '@templateCore/store/modules/core/PsSepetetedStore';


export const useRatingListStoreState = makeSeparatedStore((key: string) =>
defineStore(`ratingListStore/${key}`,
 () => {


    const ratingList = reactive<PsResource<Rating[]>>(new PsResource());

    const loading = reactive({
        value: false
    });

    let limit = ref(30);
    let offset: Number = 0;
    const isNoMoreRecord = reactive({
        value: false
    })

    function updateRatingList(responseData: PsResource<Rating[]>) {

        if (ratingList != null
            && ratingList.data != null
            && ratingList.data.length > 0
            && offset != 0) {

            if (responseData.data != null && responseData.data.length > 0) {
                if(responseData.data?.length < limit.value){
                    isNoMoreRecord.value = true;
                } else {
                    isNoMoreRecord.value = false;
                }
                ratingList.data.push(...responseData.data);
            }else {
                isNoMoreRecord.value = true;
            }

            ratingList.code = responseData.code;
            ratingList.status = responseData.status;
            ratingList.message = responseData.message;

        } else {
            if(responseData.data?.length < limit.value || responseData.data == null){
                isNoMoreRecord.value = true;
            } else {
                isNoMoreRecord.value = false;
            }
            ratingList.data = responseData.data;
            ratingList.code = responseData.code;
            ratingList.status = responseData.status;
            ratingList.message = responseData.message;


        }

        if (ratingList != null && ratingList.data != null) {
            offset = ratingList.data.length;
        }

    }

    async function loadRatingList(loginUserId, holder:RatingListHolder) {

        loading.value = true;

        const responseData = await PsApiService.getRatingList<Rating>(new Rating(), loginUserId, limit.value, offset,holder.toMap());
        updateRatingList(responseData);

        loading.value = false;

    }

    async function resetRatingList(loginUserId, holder:RatingListHolder) {

        offset = 0;

        loading.value = true;

        const responseData = await PsApiService.getRatingList<Rating>(new Rating(), loginUserId, limit.value, offset,holder.toMap());
        updateRatingList(responseData);

        loading.value = false;

    }

    return{
        ratingList,
        loading,
        limit,
        offset,
        isNoMoreRecord,
        loadRatingList,
        resetRatingList

    }

}),
);

