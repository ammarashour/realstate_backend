import { reactive,ref } from 'vue';

import PsApiService from '@templateCore/api/PsApiService';
import PsResource from '@templateCore/api/common/PsResource';
import User from '@templateCore/object/User';
import UserParameterHolder from '@templateCore/object/holder/UserParameterHolder';
import UserFollowHolder from '@templateCore/object/holder/UserFollowHolder';
import ApiStatus from '@templateCore/object/ApiStatus';
import UserBlockParameterHolder from '@templateCore/object/holder/UserBlockParameterHolder';

import { defineStore  } from 'pinia'

import makeSeparatedStore from '@templateCore/store/modules/core/PsSepetetedStore';

export const useOtherUserStoreState = makeSeparatedStore((key: string) =>
defineStore(`otherUserStore/${key}`,
 () => {

    const user = reactive<PsResource<User>>(new PsResource());

    const loading = reactive({
        value: false
    });

    let limit = ref(30);
    let offset: Number = 0;
    let id : string = '';

    let paramHolder = reactive<UserParameterHolder>(new UserParameterHolder());

    function updateUser(responseData: PsResource<User>) {

        const user = responseData;

    }

    async function loadUser(holder: UserParameterHolder) {


        loading.value = true;

        const responseData = await PsApiService.getOtherUser<User>(new User(), limit.value, offset,holder.toMap());

        updateUser(responseData);

        loading.value = false;

    }

    async function postUserFollow(holder: UserFollowHolder) {

        loading.value = true;

        const user = await PsApiService.postUserFollow<User>(new User(), holder.toMap(),holder.userId);

        loading.value = false;

    }

    async function resetUser(holder:UserParameterHolder) {

        offset = 0;

        loading.value = true;

        const responseData = await PsApiService.getOtherUser<User>(new User(), limit.value, offset,holder.toMap());

        updateUser(responseData);

        loading.value = false;

    }
    async function blockUser(holder: UserBlockParameterHolder) {

        loading.value = true;

        const status = await PsApiService.blockUser<ApiStatus>( new ApiStatus(), holder.toMap());

        loading.value = false;

        return status;

    }
    async function postUnBlockUser(loginUserId: String, holder: UserBlockParameterHolder) {

        loading.value = true;

        const status = await PsApiService.postUnBlockUser<ApiStatus>( new ApiStatus(),loginUserId, holder.toMap());

        loading.value = false;

        return status;
    }

    return{
        user,
        loading,
        limit,
        offset,
        id,
        paramHolder,
        loadUser,
        postUserFollow,
        resetUser,
        blockUser,
        postUnBlockUser
    }

}),
);
