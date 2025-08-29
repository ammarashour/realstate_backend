import { reactive, ref } from 'vue';

import PsApiService from '@templateCore/api/PsApiService';
import CustomizeUiDetail from '@templateCore/object/CustomizeUiDetail';
import PsResource from '@templateCore/api/common/PsResource';
import { defineStore } from 'pinia'
import { store } from '@templateCore/store/modules/core/PsStore';

import makeSeparatedStore from '@templateCore/store/modules/core/PsSepetetedStore';


export const useCustomizeUiStoreState = makeSeparatedStore((key: string) =>
    defineStore(`customizeUi/${key}`,
        () => {

            const isNoMoreRecord = reactive({
                value: false
            })
            const customizeUiList = reactive<PsResource<CustomizeUiDetail[]>>(new PsResource());
            const loading = reactive({
                value: false
            });

            let limit = ref(100);
            let offset: Number = 0;

            let id: string = "";

            function updateCustomFieldList(responseData: PsResource<CustomizeUiDetail[]>) {

                if (customizeUiList != null
                    && customizeUiList.data != null
                    && customizeUiList.data.length > 0
                    && offset != 0) {

                    if (responseData.data != null && responseData.data.length > 0) {

                        if (responseData.data?.length < limit.value) {
                            isNoMoreRecord.value = true;
                        } else {
                            isNoMoreRecord.value = false;
                        }

                        customizeUiList.data.push(...responseData.data);
                    } else {
                        isNoMoreRecord.value = true;
                    }
                    customizeUiList.code = responseData.code;
                    customizeUiList.status = responseData.status;
                    customizeUiList.message = responseData.message;

                } else {

                    if (responseData.data?.length < limit.value || responseData.data == null) {
                        isNoMoreRecord.value = true;
                    } else {
                        isNoMoreRecord.value = false;
                    }

                    customizeUiList.code = responseData.code;
                    customizeUiList.status = responseData.status;
                    customizeUiList.message = responseData.message;
                    customizeUiList.data = responseData.data;

                }

                if (customizeUiList != null && customizeUiList.data != null) {
                    offset = customizeUiList.data.length;
                }

            }

            async function loadCustomFieldList(loginUserId: string, customizeHeader: string) {

                loading.value = true;
                const responseData = await PsApiService.getCustomizeUiList<CustomizeUiDetail>(new CustomizeUiDetail(), limit.value, offset, loginUserId, customizeHeader);
                updateCustomFieldList(responseData);

                loading.value = false;

            }

            async function resetCustomFieldList(loginUserId: string, customizeHeader: string) {

                offset = 0;

                loading.value = true;
                const responseData = await PsApiService.getCustomizeUiList<CustomizeUiDetail>(new CustomizeUiDetail(), limit.value, offset, loginUserId, customizeHeader);
                updateCustomFieldList(responseData);

                loading.value = false;

            }

            return {
                isNoMoreRecord,
                customizeUiList,
                loading,
                limit,
                offset,
                id,
                updateCustomFieldList,
                loadCustomFieldList,
                resetCustomFieldList

            }

        }),
);

// if (import.meta.hot) {
//     import.meta.hot.accept(acceptHMRUpdate(useCustomizeUiStoreState, import.meta.hot))
//   }

// Need to be used outside the setup
export function useCustomizeUiStoreStateWithOut() {
    return useCustomizeUiStoreState(store);
}
