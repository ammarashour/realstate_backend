import { reactive,ref } from 'vue';

import AllSearchParameterHolder from '@templateCore/object/holder/AllSearchParameterHolder';
import PsApiService from '@templateCore/api/PsApiService';
import AllSearch from '@templateCore/object/AllSearch';
import PsResource from '@templateCore/api/common/PsResource';
import { defineStore } from 'pinia'
import makeSeparatedStore from '@templateCore/store/modules/core/PsSepetetedStore';

export const useAllSearchStoreState = makeSeparatedStore((key: string) =>
    defineStore(`allSearchStore/${key}`,
        () => {

            const allSearchList = reactive<PsResource<AllSearch[]>>(new PsResource());
            const loading = reactive({
                value: false
            });

            let limit = ref(10);
            let offset: Number = 0;
            const isNoMoreRecord = reactive({
                value: false
            })

            function updateAllSearchList(responseData: PsResource<AllSearch[]>) {

                if (allSearchList != null
                    && allSearchList.data != null
                    && allSearchList.data.length > 0
                    && offset != 0) {

                    if (responseData.data != null && responseData.data.length > 0) {
                        if(responseData.data?.length < limit.value){
                            isNoMoreRecord.value = true;
                        } else {
                            isNoMoreRecord.value = false;
                        }
                        allSearchList.data.push(...responseData.data);
                    }else {
                        isNoMoreRecord.value = true;
                    }

                    allSearchList.code = responseData.code;
                    allSearchList.status = responseData.status;
                    allSearchList.message = responseData.message;

                } else {
                    if(responseData.data?.length < limit.value || responseData.data == null){
                        isNoMoreRecord.value = true;
                    } else {
                        isNoMoreRecord.value = false;
                    }
                    allSearchList.data = responseData.data;
                    allSearchList.code = responseData.code;
                    allSearchList.status = responseData.status;
                    allSearchList.message = responseData.message;

                }

                if (allSearchList != null && allSearchList.data != null) {
                    offset = allSearchList.data.length;
                }

            }

            async function getSearchedDataList(loginUserId: string, holder: AllSearchParameterHolder) {

                offset = 0;

                loading.value = true;

                const responseData = await PsApiService.getSearchedDataList<AllSearch>(new AllSearch(), loginUserId, limit.value, offset, holder.toMap());

                updateAllSearchList(responseData);

                loading.value = false;

            }



            return {
                allSearchList,
                loading,
                limit,
                offset,
                isNoMoreRecord,
                getSearchedDataList,
            }

        }),
);
