import { reactive, ref } from 'vue';

import PsApiService from '@templateCore/api/PsApiService';
import ItemLocationTownship from '@templateCore/object/ItemLocationTownship';
import LocationTownshipParameterHolder from '@templateCore/object/holder/LocationTownshipParameterHolder';
import PsResource from '@templateCore/api/common/PsResource';

import { defineStore } from 'pinia'

import makeSeparatedStore from '@templateCore/store/modules/core/PsSepetetedStore';

export const useItemLocationTownshipStoreState = makeSeparatedStore((key: string) =>
defineStore(`ItemLocationTownshipStore/${key}`,
 () => {
    const locationTownshipList = reactive<PsResource<ItemLocationTownship[]>>(new PsResource());
    const cityId = ref('');
    const filterKeyword = ref('');
    const loading = reactive({
        value : false
    });

    let limit = ref(30);
    let offset: Number = 0;
    const isNoMoreRecord = reactive({
        value: false
    })

    function updateItemLocationTownshipList(responseData : PsResource<ItemLocationTownship[]>) {

        if(locationTownshipList != null
            && locationTownshipList.data != null
            && locationTownshipList.data.length > 0
            && offset != 0) {

            if(responseData.data != null && responseData.data.length > 0) {
                if(responseData.data?.length < limit.value){
                    isNoMoreRecord.value = true;
                } else {
                    isNoMoreRecord.value = false;
                }
                locationTownshipList.data.push(...responseData.data);
            }else {
                isNoMoreRecord.value = true;
            }

            locationTownshipList.code = responseData.code;
            locationTownshipList.status = responseData.status;
            locationTownshipList.message = responseData.message;

        }else {
            if(responseData.data?.length < limit.value || responseData.data == null){
                isNoMoreRecord.value = true;
            } else {
                isNoMoreRecord.value = false;
            }
            locationTownshipList.code = responseData.code;
            locationTownshipList.status = responseData.status;
            locationTownshipList.message = responseData.message;
            locationTownshipList.data = responseData.data;

        }

        if(locationTownshipList != null && locationTownshipList.data != null ) {
            offset = locationTownshipList.data.length;
        }

    }

    function filtersubLocationUpdate(loginUserId: string, holder: LocationTownshipParameterHolder) {
        
       
        // resetLocationTownshipList(loginUserId, holder);
        resetItemLocationTownshipList(holder.locationId,holder.keyword)
    }

    async function resetLocationTownshipList(loginUserId: string, holder: LocationTownshipParameterHolder) {

        offset = 0;

        loading.value = true;
       
        

        const responseData = await PsApiService.getLocationTownshipList<ItemLocationTownship>(new ItemLocationTownship(), limit.value, offset, loginUserId, holder.toMap());
            
        updateItemLocationTownshipList(responseData);

        loading.value = false;

    }

    async function loadItemLocationTownshipList(locationId: string) {

        if(cityId.value != locationId ) {
            const responseData = reactive<PsResource<ItemLocationTownship[]>>(new PsResource());
            locationTownshipList.code = responseData.code;
            locationTownshipList.status = responseData.status;
            locationTownshipList.message = responseData.message;
            locationTownshipList.data = responseData.data;
            offset = 0;
            isNoMoreRecord.value = false;

            // locationId = cityId.value;

            cityId.value = locationId;
        }

        locationId = locationId.toString();
        let holder = new LocationTownshipParameterHolder();
        holder.locationId = locationId;
       
      
        loading.value = true;

        const responseData = await PsApiService.getLocationTownshipList<ItemLocationTownship>(new ItemLocationTownship(),  limit.value, offset, holder.toMap());

        loading.value = false;

        updateItemLocationTownshipList(responseData);

    }

    async function resetItemLocationTownshipList(locationId: string,keyword:string) {

        if(cityId.value != locationId) {
            const responseData = new PsResource();
            locationTownshipList.code = responseData.code;
            locationTownshipList.status = responseData.status;
            locationTownshipList.message = responseData.message;
            locationTownshipList.data = responseData.data;
        }

        offset = 0;

        locationId = locationId.toString();
        let holder = new LocationTownshipParameterHolder();
        holder.locationId = locationId;
        holder.keyword = keyword;

        loading.value = true;

        const responseData = await PsApiService.getLocationTownshipList<ItemLocationTownship>(new ItemLocationTownship(), limit.value, offset, holder.toMap());

        updateItemLocationTownshipList(responseData);

        loading.value = false;

    }

    return{
        locationTownshipList,
        cityId,
        loading,
        limit,
        offset,
        isNoMoreRecord,
        updateItemLocationTownshipList,
        filtersubLocationUpdate,
        resetLocationTownshipList,
        loadItemLocationTownshipList,
        resetItemLocationTownshipList,
        filterKeyword

    }
}),
);
