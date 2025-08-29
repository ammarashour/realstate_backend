
import { reactive,ref } from 'vue';

import PsApiService from '@templateCore/api/PsApiService';
import PsApi from '@templateCore/api/common/PsApi';
import PsResource from '@templateCore/api/common/PsResource';
import User from '@templateCore/object/User';
import UserParameterHolder from '@templateCore/object/holder/UserParameterHolder';
import UserListParameterHolder from '@templateCore/object/holder/UserListParameterHolder';
import { defineStore  } from 'pinia'

import makeSeparatedStore from '@templateCore/store/modules/core/PsSepetetedStore';

export const useUserListStoreState = makeSeparatedStore((key: string) =>
defineStore(`userListProvider/${key}`,
 () => {

    const isNoMoreRecord = reactive({
        value: false
    })
    const userList = reactive<PsResource<User[]>>(new PsResource());

    const loading = reactive({
        value: false
    });

    let limit = ref(6);
    let offset: Number = 0;

    let id : string = '';
    let paramHolder = reactive<UserParameterHolder>(new UserParameterHolder());
    let userparamHolder = reactive<UserListParameterHolder>(new UserListParameterHolder());

    function hasData() {
        return userList?.data != null && userList!.data!.length > 0;
    }

    function updateUserList(responseData: PsResource<User[]>) {

        if (userList != null
            && userList.data != null
            && userList.data.length > 0
            && offset != 0) {

            if (responseData.data != null && responseData.data.length > 0) {
                if(responseData.data?.length < limit.value){
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
            if(responseData.data?.length < limit.value || responseData.data == null){
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

    async function loadUserList(loginUserId: string, holder: UserParameterHolder) {

        loading.value = true;

        const responseData = await PsApiService.getUserList<User>(new User(), loginUserId, limit.value, offset, holder.toMap());
        updateUserList(responseData);

        loading.value = false;

    }

    async function loadUserSearchList(loginUserId :string,holder: UserListParameterHolder) {

        offset = 0;

        loading.value = true;

        const responseData = await PsApiService.getUserSearchList<User>(new User(), limit.value, offset, loginUserId, holder.toMap());

        updateUserList(responseData);

        loading.value = false;

    }

    async function resetTopRatedSellerList(loginUserId :string) {

        offset = 0;

        loading.value = true;

        const responseData = await PsApiService.getTopRatedSellerList<User>(new User(), loginUserId, limit.value, offset);

        updateUserList(responseData);

        loading.value = false;

    }

    async function resetTopRatedSellerListProps(users: PsResource<User[]>) {

        offset = 0;

        loading.value = true;

        const responseData = await PsApi.wrapDataFromProps(new User(), users);

        updateUserList(responseData);

        loading.value = false;

    }


    async function loadTopRatedSellerList(loginUserId :string) {

        loading.value = true;

        const responseData = await PsApiService.getTopRatedSellerList<User>(new User(), loginUserId, limit.value, offset);

        updateUserList(responseData);

        loading.value = false;

    }

    async function refleshTopRatedSellerList(loginUserId :string) {

        loading.value = true;
        const tempOffset = 0;
        const templimit = userList.data.length;
        const responseData = await PsApiService.getTopRatedSellerList<User>(new User(), loginUserId, templimit, tempOffset);
        userList.data = responseData.data;
        userList.code = responseData.code;
        userList.status = responseData.status;
        userList.message = responseData.message;
        loading.value = false;
    }

    async function refleshUserSearchList(loginUserId :string, holder: UserListParameterHolder) {

        loading.value = true;
        const tempOffset = 0;
        const templimit = userList.data.length;
        const responseData = await PsApiService.getUserSearchList<User>(new User(), templimit, tempOffset,loginUserId,holder.toMap());
        userList.data = responseData.data;
        userList.code = responseData.code;
        userList.status = responseData.status;
        userList.message = responseData.message;
        loading.value = false;
    }

    async function resetUserSearchList(loginUserId :string, holder: UserListParameterHolder) {

        offset = 0;
        loading.value = true;

        const responseData = await PsApiService.getUserSearchList<User>(new User(), limit.value, offset,loginUserId, holder.toMap());
        updateUserList(responseData);
        loading.value = false;
    }

    async function resetUserList(loginUserId: string, holder:UserParameterHolder) {

        offset = 0;

        loading.value = true;

        const responseData = await PsApiService.getUserList<User>(new User(),loginUserId, limit.value, offset, holder.toMap());

        updateUserList(responseData);

        loading.value = false;

    }

    async function refleshUserFollowerFollowingList(loginUserId :string, holder: UserListParameterHolder) {

        loading.value = true;
        const tempOffset = 0;
        const templimit = userList.data.length;
        const responseData = await PsApiService.getUserList<User>(new User(),loginUserId, templimit, tempOffset, holder.toMap());
        userList.data = responseData.data;
        userList.code = responseData.code;
        userList.status = responseData.status;
        userList.message = responseData.message;
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
        resetUserList,
        resetTopRatedSellerList,
        loadTopRatedSellerList,
        refleshUserFollowerFollowingList,
        refleshTopRatedSellerList,
        hasData,
        resetTopRatedSellerListProps
    }

}),
);
