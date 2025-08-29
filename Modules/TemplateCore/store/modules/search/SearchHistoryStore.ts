import { reactive,ref } from 'vue';

import SearchHistoryHolder from '@templateCore/object/holder/SearchHistoryHolder';
import DeleteSearchHistory from '@templateCore/object/holder/DeleteSearchHistory';
import PsApiService from '@templateCore/api/PsApiService';
import SearchHistory from '@templateCore/object/SearchHistory';
import PsResource from '@templateCore/api/common/PsResource';
import { defineStore } from 'pinia'
import makeSeparatedStore from '@templateCore/store/modules/core/PsSepetetedStore';
import ApiStatus from '@templateCore/object/ApiStatus';

export const useSearchHistoryStoreState = makeSeparatedStore((key: string) =>
    defineStore(`searchHistoryStore/${key}`,
        () => {

            const searchHistoryList = reactive<PsResource<SearchHistory[]>>(new PsResource());
            const loading = reactive({
                value: false
            });

            let limit = ref(10);
            let offset: Number = 0;
            const isNoMoreRecord = reactive({
                value: false
            })

            function updateSearchHistoryList(responseData: PsResource<SearchHistory[]>) {

                if (searchHistoryList != null
                    && searchHistoryList.data != null
                    && searchHistoryList.data.length > 0
                    && offset != 0) {

                    if (responseData.data != null && responseData.data.length > 0) {
                        if(responseData.data?.length < limit.value){
                            isNoMoreRecord.value = true;
                        } else {
                            isNoMoreRecord.value = false;
                        }
                        searchHistoryList.data.push(...responseData.data);
                    }else {
                        isNoMoreRecord.value = true;
                    }

                    searchHistoryList.code = responseData.code;
                    searchHistoryList.status = responseData.status;
                    searchHistoryList.message = responseData.message;

                } else {
                    if(responseData.data?.length < limit.value || responseData.data == null){
                        isNoMoreRecord.value = true;
                    } else {
                        isNoMoreRecord.value = false;
                    }
                    searchHistoryList.data = responseData.data;
                    searchHistoryList.code = responseData.code;
                    searchHistoryList.status = responseData.status;
                    searchHistoryList.message = responseData.message;

                }

                if (searchHistoryList != null && searchHistoryList.data != null) {
                    offset = searchHistoryList.data.length;
                }

            }

            async function resetSearchHistoryList(loginUserId: string, holder: SearchHistoryHolder) {
                offset = 0;

                loading.value = true;

                const responseData = await PsApiService.getSearchHistoryList<SearchHistory>(new SearchHistory(), loginUserId, limit.value, offset, holder.toMap());

                updateSearchHistoryList(responseData);

                loading.value = false;
            }

            async function deleteSearchHistroy(loginUserId: string, holder: DeleteSearchHistory){
                console.log(holder);
                loading.value = true;

                const status = await PsApiService.postDeleteSearchHistory<ApiStatus>(new ApiStatus(), loginUserId, holder.toMap());
                console.log(status);
                loading.value = false;

                return status;
            }

            return {
                searchHistoryList,
                loading,
                limit,
                offset,
                isNoMoreRecord,
                resetSearchHistoryList,
                deleteSearchHistroy,
            }

        }),
);
