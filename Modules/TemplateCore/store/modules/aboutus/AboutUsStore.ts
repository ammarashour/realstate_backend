
import { reactive,ref } from 'vue';

import PsApiService from '@templateCore/api/PsApiService';
import AboutUs from '@templateCore/object/AboutUs';
import PsResource from '@templateCore/api/common/PsResource';
import { defineStore  } from 'pinia'

import makeSeparatedStore from '@templateCore/store/modules/core/PsSepetetedStore';


export const useAboutUsStoreState = makeSeparatedStore((key: string) =>
defineStore(`aboutUsStore/${key}`,
 () => {


    const aboutus = reactive<PsResource<AboutUs>>(new PsResource());
    const loading = reactive({
        value: false
    });

    async function loadAboutUs(loginUserId) {
       
        loading.value = true;

        const responseData = await PsApiService.getAboutUsDataList<AboutUs>(new AboutUs(),loginUserId);

        aboutus.code = responseData.code;
        aboutus.status = responseData.status;
        aboutus.message = responseData.message;
        aboutus.data = responseData.data;
        
        loading.value = false;

    }

    async function resetAboutUs() {

        loading.value = true;

        const result = await PsApiService.getAboutUsDataList<AboutUs>(new AboutUs(),'1');
        if(result.data != null && result.data.length > 0) {
            aboutus.data = result.data[0];
        }


        loading.value = false;

    }
    return{
        aboutus,
        loading,
        loadAboutUs,
        resetAboutUs

    }
}),
);