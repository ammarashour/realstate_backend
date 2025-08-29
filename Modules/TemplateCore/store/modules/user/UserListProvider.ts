
import { reactive,ref } from 'vue';

import PsApiService from '@templateCore/api/PsApiService';
import PsResource from '@templateCore/api/common/PsResource';
import User from '@templateCore/object/User';
import UserParameterHolder from '@templateCore/object/holder/UserParameterHolder';
import UserListParameterHolder from '@templateCore/object/holder/UserListParameterHolder';
import { defineStore  } from 'pinia'

import makeSeparatedStore from '@templateCore/store/modules/core/PsSepetetedStore';
import { off } from 'process';

export const useUserListProviderState = makeSeparatedStore((key: string) =>
defineStore(`userListProvider/${key}`,
 () => {

    const isNoMoreRecord = reactive({
        value: false
    })
    const userList = reactive<PsResource<User[]>>(new PsResource());

    const loading = reactive({
        value: false
    });

    let limit = ref(4);
    let offset: Number = 0;

    let id : string = '';
    let paramHolder = reactive<UserParameterHolder>(new UserParameterHolder());
    let userparamHolder = reactive<UserListParameterHolder>(new UserListParameterHolder());

    function updateUserList(responseData: PsResource<User[]>) {

        if (userList != null
            && userList.data != null
            && userList.data.length > 0
            && offset != 0) {

            if (responseData.data != null && responseData.data.length > 0) {
                if(responseData.data?.length < limit){
                    isNoMoreRecord.value = true;
                } else {
                    isNoMoreRecord.value = false;
                }
                userList.data.push(...responseData.data);
            }else {
                isNoMoreRecord.value = true;
            }

            userList.code = responseData.code;
            userList.status = responseData.status;
            userList.message = responseData.message;

        } else {
            if(responseData.data?.length < limit || responseData.data == null){
                isNoMoreRecord.value = true;
            } else {
                isNoMoreRecord.value = false;
            }
            userList.data = responseData.data;
            userList.code = responseData.code;
            userList.status = responseData.status;
            userList.message = responseData.message;


        }

        if (userList != null && userList.data != null) {
            offset = userList.data.length;
        }

    }

    async function loadUserList(holder: UserParameterHolder) {

        loading.value = true;

        const responseData = await PsApiService.getUserList<User>(new User(), limit, offset, holder.toMap());
        updateUserList(responseData);

        loading.value = false;

    }

    async function loadUserSearchList(loginUserId :string,holder: UserListParameterHolder) {

        offset = 0;

        loading.value = true;

        const responseData = await PsApiService.getUserSearchList<User>(new User(), limit, offset, loginUserId, holder.toMap());

        updateUserList(responseData);

        loading.value = false;

    }
    async function refleshUserSearchList(loginUserId :string, holder: UserListParameterHolder) {

        loading.value = true;
        const tempOffset = 0;
        const tempLimit = userList.data.length;
        const responseData = await PsApiService.getUserSearchList<User>(new User(), tempLimit, tempOffset,loginUserId,holder.toMap());
        userList.data = responseData.data;
        userList.code = responseData.code;
        userList.status = responseData.status;
        userList.message = responseData.message;
        loading.value = false;
    }
    async function resetUserSearchList(loginUserId :string, holder: UserListParameterHolder) {

        offset = 0;
        loading.value = true;

        const responseData = await PsApiService.getUserSearchList<User>(new User(), limit, offset,loginUserId, holder.toMap());
        updateUserList(responseData);
        loading.value = false;
    }

    async function resetUserList( holder:UserParameterHolder) {

        offset = 0;

        loading.value = true;

        const responseData = await PsApiService.getUserList<User>(new User(), limit, offset, holder.toMap());

        updateUserList(responseData);

        loading.value = false;

    }

    return{
        isNoMoreRecord,
        userList,
        loading,
        limit,
        offset,
        id,
        paramHolder,
        userparamHolder,
        loadUserList,
        loadUserSearchList,
        refleshUserSearchList,
        resetUserSearchList,
        resetUserList

    }

}),
);
