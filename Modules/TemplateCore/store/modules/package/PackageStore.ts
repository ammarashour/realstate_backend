import { reactive,ref } from 'vue';

import PsApiService from '@templateCore/api/PsApiService';
import PsApi from '@templateCore/api/common/PsApi';
import PsResource from '@templateCore/api/common/PsResource';
import Package from '@templateCore/object/Package';
import PackageWithPurchasedCount from '@templateCore/object/PackageWithPurchasedCount';
import ItemLimitParameterHolder from '@templateCore/object/holder/ItemLimitParameterHolder';
import ApiStatus from '@templateCore/object/ApiStatus';
import { defineStore  } from 'pinia'

import makeSeparatedStore from '@templateCore/store/modules/core/PsSepetetedStore';

export const usePackageStoreState = makeSeparatedStore((key: string) =>
defineStore(`packageStore/${key}`,
 () => {

    const packageList = reactive<PsResource<Package[]>>(new PsResource());
    const limitItem = reactive<PsResource<ApiStatus>>(new PsResource());

    const loading = reactive({
        value: false
    });

    let limit = ref(30);
    let offset: Number = 0;
    const isNoMoreRecord = reactive({
        value: false
    })

    function hasData() {
        return packageList?.data != null && packageList!.data!.length > 0;
    }

    function updatePackageList(responseData: PsResource<Package[]>) {

        if (packageList != null
            && packageList.data != null
            && packageList.data.length > 0
            && offset != 0) {

            if (responseData.data != null && responseData.data.length > 0) {

                if(responseData.data?.length < limit.value){
                    isNoMoreRecord.value = true;
                } else {
                    isNoMoreRecord.value = false;
                }
                packageList.data.push(...responseData.data);
            }else {
                isNoMoreRecord.value = true;
            }

            packageList.code = responseData.code;
            packageList.status = responseData.status;
            packageList.message = responseData.message;

        } else {
            if(responseData.data?.length < limit.value || responseData.data == null){
                isNoMoreRecord.value = true;
            } else {
                isNoMoreRecord.value = false;
            }
            packageList.data = responseData.data;
            packageList.code = responseData.code;
            packageList.status = responseData.status;
            packageList.message = responseData.message;

        }

        if (packageList != null && packageList.data != null) {
            offset = packageList.data.length;
        }

    }
    async function resetPackageList(loginUserId: string) {

        offset = 0;
        loading.value = true;

        const responseData = await PsApiService.getPackageList<Package>(new Package(), loginUserId);
        updatePackageList(responseData);

        loading.value = false;

    }

    async function loadPackageList(loginUserId: string) {

        loading.value = true;

        const responseData = await PsApiService.getPackageList<Package>(new Package(), loginUserId);
        updatePackageList(responseData);

        loading.value = false;

    }

    async function packageListWithPurchasedCount(loginUserId: string) {

        loading.value = true;

        const responseData = await PsApiService.getPackageListWithPurchasedCount<PackageWithPurchasedCount>(new PackageWithPurchasedCount(), loginUserId);
        packageList.data = responseData.data;
        packageList.code = responseData.code;
        packageList.status = responseData.status;
        packageList.message = responseData.message;

        loading.value = false;

        return packageList;
    }

    async function packageListWithPurchasedCountProps(packageWithPurchasedCount: PsResource<PackageWithPurchasedCount[]>) {

        loading.value = true;

        const responseData = await PsApi.wrapDataFromProps(new PackageWithPurchasedCount(), packageWithPurchasedCount);

        packageList.data = responseData.data;
        packageList.code = responseData.code;
        packageList.status = responseData.status;
        packageList.message = responseData.message;

        loading.value = false;

        return packageList;
    }

    async function postPackageBought(loginUserId: string, holder:ItemLimitParameterHolder) : Promise<PsResource<ApiStatus>>  {

        loading.value = true;

        const limitItem = await PsApiService.postPackageBought<ApiStatus>(new ApiStatus(), loginUserId, holder.toMap());

        loading.value = false;

        return limitItem;

    }

    return {
        isNoMoreRecord,
        loading,
        limit,
        offset,
        packageList,
        limitItem,
        resetPackageList,
        loadPackageList,
        postPackageBought,
        packageListWithPurchasedCount,
        hasData,
        packageListWithPurchasedCountProps
    }

}),
)

