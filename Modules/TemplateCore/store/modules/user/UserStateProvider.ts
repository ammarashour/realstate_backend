
import { reactive, provide, inject, ref } from 'vue';

import PsApiService from '@templateCore/api/PsApiService';
import PsResource from '@templateCore/api/common/PsResource';
import UserState from '@templateCore/object/UserState';

export class UserStateListProvider{

    isNoMoreRecord = reactive({
        value: false
    })
    userStateList = reactive<PsResource<UserState[]>>(new PsResource());

    loading = reactive({
        value: false
    });

    limit: Number = 30;
    offset: Number = 0;

    private updateUserStateList(responseData: PsResource<UserState[]>) {

        if (this.userStateList != null
            && this.userStateList.data != null
            && this.userStateList.data.length > 0
            && this.offset != 0) {

            if (responseData.data != null && responseData.data.length > 0) {
                if(responseData.data?.length < this.limit){
                    this.isNoMoreRecord.value = true;
                } else {
                    this.isNoMoreRecord.value = false;
                }
                this.userStateList.data.push(...responseData.data);
            }else {
                this.isNoMoreRecord.value = true;
            }

            this.userStateList.code = responseData.code;
            this.userStateList.status = responseData.status;
            this.userStateList.message = responseData.message;

        } else {
            if(responseData.data?.length < this.limit || responseData.data == null){
                this.isNoMoreRecord.value = true;
            } else {
                this.isNoMoreRecord.value = false;
            }
            this.userStateList = responseData;
        }

        if (this.userStateList != null && this.userStateList.data != null) {
            this.offset = this.userStateList.data.length;
        }

    }

    async loadUserStateList() {

        this.loading.value = true;
        const responseData = await PsApiService.getUserStateList<UserState>(new UserState(), this.limit, this.offset);

        this.updateUserStateList(responseData);

        this.loading.value = false;

    }

    async resetUserStateList() {

        this.offset = 0;

        this.loading.value = true;

        const responseData = await PsApiService.getUserStateList<UserState>(new UserState(), this.limit, this.offset);

        this.updateUserStateList(responseData);

        this.loading.value = false;

    }

}

export const userStateListProviderSymbol = Symbol('UserStateListProvider')
export const createUserStateListProviderState = () => {
    return reactive(new UserStateListProvider())
}

export const useUserStateListProviderState = () => inject(userStateListProviderSymbol) as UserStateListProvider
export const provideUserStateListProviderState = () => provide(
   userStateListProviderSymbol,
    createUserStateListProviderState()
)
