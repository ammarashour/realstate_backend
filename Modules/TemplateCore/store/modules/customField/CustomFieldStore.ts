import { reactive,ref } from 'vue';

import PsApiService from '@templateCore/api/PsApiService';
import CustomField from '@templateCore/object/CustomField';
import PsResource from '@templateCore/api/common/PsResource';
import { defineStore  } from 'pinia'
import { store } from '@templateCore/store/modules/core/PsStore';

import makeSeparatedStore from '@templateCore/store/modules/core/PsSepetetedStore';


export const useCustomFieldStoreState = makeSeparatedStore((key: string) =>
defineStore(`customFieldStore/${key}`,
 () => {

    const loading = reactive({
        value: false
    });

    const customField = reactive<PsResource<CustomField>>(new PsResource());

    let id: string = "";


    async function loadCustomFieldList(loginUserId:string , categoryId : string) {

        loading.value = true;
        const responseData = await PsApiService.getCustomFieldList<CustomField>(new CustomField(), loginUserId , categoryId);
        customField.code = responseData.code;
        customField.status = responseData.status;
        customField.message = responseData.message;
        customField.data = responseData.data;
        loading.value = false;
        return customField;

    }

    async function loadUserCustomFieldList(loginUserId:string) {

        loading.value = true;
        const responseData = await PsApiService.getUserCustomFieldList<CustomField>(new CustomField(), loginUserId);
        customField.code = responseData.code;
        customField.status = responseData.status;
        customField.message = responseData.message;
        customField.data = responseData.data;
        loading.value = false;
        return customField;

    }
    async function loadFeSettingCustomFieldList(loginUserId:string) {

        loading.value = true;
        const responseData = await PsApiService.getFeSettingCustomFieldList<CustomField>(new CustomField(), loginUserId);
        customField.code = responseData.code;
        customField.status = responseData.status;
        customField.message = responseData.message;
        customField.data = responseData.data;
        loading.value = false;
        return customField;

    }


    return{
        loadUserCustomFieldList,
        customField,
        loading,
        id,
        loadCustomFieldList,
        loadFeSettingCustomFieldList,

    }

}),
);

// if (import.meta.hot) {
//     import.meta.hot.accept(acceptHMRUpdate(useCustomFieldStoreState, import.meta.hot))
//   }

// Need to be used outside the setup
export function useCustomFieldStoreStateWithOut() {
    return useCustomFieldStoreState(store);
  }