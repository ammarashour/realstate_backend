import { reactive } from 'vue';

import PsApiService from '@templateCore/api/PsApiService';
import PsResource from '@templateCore/api/common/PsResource';
import ApiStatus from '@templateCore/object/ApiStatus';
import { defineStore  } from 'pinia'

import makeSeparatedStore from '@templateCore/store/modules/core/PsSepetetedStore';
import VendorPlanBoughtParameterHolder from '../../../object/holder/VendorPlanBoughtParameterHolder';

export const useVendorPlanBoughtStoreState = makeSeparatedStore((key: string) =>
defineStore(`vendorPlanBoughtStore/${key}`,
 () => {

    const loading = reactive({
        value: false
    });

    async function postSubscriptionPlanBought(loginUserId: string, holder:VendorPlanBoughtParameterHolder) : Promise<PsResource<ApiStatus>>  {

        loading.value = true;

        const subscriptionPlanItem = await PsApiService.postSubscriptionPlanBought<ApiStatus>(new ApiStatus(), loginUserId, holder.toMap());

        loading.value = false;

        return subscriptionPlanItem;

    }

    return {
        loading,
        postSubscriptionPlanBought
    }

}),
)

