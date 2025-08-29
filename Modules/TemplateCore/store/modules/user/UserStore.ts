
import { reactive,ref } from 'vue';

import PsApiService from '@templateCore/api/PsApiService';
import User from '@templateCore/object/User';
import PsResource from '@templateCore/api/common/PsResource';
import { defineStore  } from 'pinia'
import PsStatus from '@templateCore/api/common/PsStatus';
import DefaultPhoto from '@templateCore/object/DefaultPhoto';
import UserParameterHolder from '@templateCore/object/holder/UserParameterHolder';
import makeSeparatedStore from '@templateCore/store/modules/core/PsSepetetedStore';
import UserListParameterHolder from '@templateCore/object/holder/UserListParameterHolder';
import UserFollowHolder from '@templateCore/object/holder/UserFollowHolder';
import UserBlockParameterHolder from '@templateCore/object/holder/UserBlockParameterHolder';
import UserBlueMarkParameterHolder from '@templateCore/object/holder/UserBlueMarkParameterHolder';
import ApiStatus from '@templateCore/object/ApiStatus';
import ChangePasswordParameterHolder from '@templateCore/object/holder/ChangePasswordParameterHolder';
import UserDeleteItemParameterHolder from '@templateCore/object/holder/UserDeleteItemParameterHolder';
import PsApi from '@templateCore/api/common/PsApi';


export const useUserStore = makeSeparatedStore((key: string) =>
defineStore(`userStore/${key}`,
 () => {


    const user = reactive<PsResource<User>>(new PsResource());
    const isNoMoreRecord = reactive({
        value: false
    })
    const userList = reactive<PsResource<User[]>>(new PsResource());

    const loading = reactive({
        value: false
    });

    let limit = ref(10);
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


    async function loadUser(loginUserId: string) {

        loading.value = true;

        const result = await PsApiService.getUser<User>(new User(), loginUserId);

        user.data = result.data;
        user.code = result.code;
        user.message = result.message;
        user.status = result.status;

        loading.value = false;

        return result;
    }

    async function loadUserProps(userObj: PsResource<User>) {

        loading.value = true;

        const result = await PsApi.wrapDataFromProps(new User(), userObj);

        user.data = result.data;
        user.code = result.code;
        user.message = result.message;
        loading.value = false;

        return user;

    }

    async function loadOtherUser(loginUserId : string, holder: UserParameterHolder) {

        loading.value = true;

        const result = await PsApiService.getOtherUser<User>(new User(),loginUserId, holder.toMap());

        user.data = result.data;
        user.code = result.code;
        user.message = result.message;
        user.status = result.status;


        loading.value = false;

        return result;
    }
    async function postProfileUpdate(holder: any,loginUserId : string) {

        loading.value = true;
        const returnData = await PsApiService.postProfileUpdate<User>(new User(), holder.toMap(),loginUserId);

        if(returnData.status == PsStatus.SUCCESS){
            user.data = returnData.data;
            user.code = returnData.code;
            user.message = returnData.message;
            user.status = returnData.status;
            // PsValueStore.psValueStore.replaceLoginUserName(returnData.data?.userName ?? '');
        }
        loading.value = false;

        return returnData;

    }

    async function postImageUpload(
        userId: string,
        platformName: string,
        imageFile: File,
        imgId: string
    ) {
        loading.value = true;

        await PsApiService.postUserImageUpload<DefaultPhoto>(new DefaultPhoto(), userId, platformName, imageFile, imgId);

        loading.value = false;
    }

    async function resetUserSearchList(loginUserId :string, holder: UserListParameterHolder) {

        offset = 0;
        loading.value = true;

        const responseData = await PsApiService.getUserSearchList<User>(new User(), limit.value, offset,loginUserId, holder.toMap());
        updateUserList(responseData);
        loading.value = false;
    }

    async function loadUserSearchList(loginUserId :string,holder: UserListParameterHolder) {

        loading.value = true;

        const responseData = await PsApiService.getUserSearchList<User>(new User(), limit.value, offset, loginUserId, holder.toMap());

        updateUserList(responseData);

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

    async function postUserFollow(holder: UserFollowHolder) {

        this.loading.value = true;

        const result = await PsApiService.postUserFollow<User>(new User(), holder.toMap(),holder.userId);

        this.loading.value = false;
        return result;

    }

    async function blockUser(holder: UserBlockParameterHolder,loginUserId : String) {

        this.loading.value = true;

        const status = await PsApiService.blockUser<ApiStatus>( new ApiStatus(), loginUserId,holder.toMap());

        this.loading.value = false;

        return status;

    }

    async function blueMarkUser(loginId, holder: UserBlueMarkParameterHolder) {

        this.loading.value = true;

        const status = await PsApiService.blueMarkUser<ApiStatus>( new ApiStatus(), loginId, holder.toMap());

        this.loading.value = false;

        return status;

    }

    async function postChangePassword(holder: ChangePasswordParameterHolder,loginUserId : String) {

        loading.value = true;

        const status = await PsApiService.postChangePassword<ApiStatus>( new ApiStatus(), holder.toMap(),loginUserId);

        loading.value = false;

        return status;


    }

    async function postDeleteUser(holder: UserDeleteItemParameterHolder) {

        loading.value = true;

        const status = await PsApiService.postDeleteUser<ApiStatus>( new ApiStatus(), holder.toMap());


        loading.value = false;

        return status;

    }

    function verifybluemark(verifyNo : String){
        return user.data?.isVerifybluemark == verifyNo;
    }



    return{
        blockUser,
        postUserFollow,
        loadUserSearchList,
        resetUserSearchList,
        userList,
        limit,
        isNoMoreRecord,
        offset,
        id,
        user,
        loading,
        loadUser,
        postProfileUpdate,
        postImageUpload,
        loadOtherUser,
        paramHolder,
        userparamHolder,
        refleshUserSearchList,
        blueMarkUser,
        postChangePassword,
        postDeleteUser,
        verifybluemark,
        loadUserProps
    }
}),
);
