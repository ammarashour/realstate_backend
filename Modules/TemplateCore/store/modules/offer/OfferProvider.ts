
import { reactive, provide, inject, ref } from 'vue';

import PsApiService from '@templateCore/api/PsApiService';
import PsResource from '@templateCore/api/common/PsResource';
import Offer from '@templateCore/object/Offer';
import ApiStatus from '@templateCore/object/ApiStatus';
import MakeOfferParameterHolder from '@templateCore/object/holder/MakeOfferParameterHolder';
import IsUserBoughtParameterHolder from '@templateCore/object/holder/IsUserBoughtParameterHolder';
import OfferParameterHolder from '@templateCore/object/holder/OfferParameterHolder';
import MarkSoldOutItemParameterHolder from '@templateCore/object/holder/MarkSoldOutItemParameterHolder'

export class OfferProvider{

    offerList = reactive<PsResource<Offer[]>>(new PsResource());
    loading = reactive({
        value: false
    });

    limit: Number = 30;
    offset: Number = 0;
    isNoMoreRecord = reactive({
        value: false
    })

    async makeOffer (loginUserId, holder: MakeOfferParameterHolder){
        this.loading.value = true;


        const returnData = await PsApiService.makeOffer<Offer>(new Offer(),loginUserId, holder.toMap());

        this.loading.value = false;
        return returnData;
    }
    async acceptOffer (loginUserId, holder: MakeOfferParameterHolder){
        this.loading.value = true;


        const returnData = await PsApiService.acceptedOffer<Offer>(new Offer(), holder.toMap(), loginUserId);

        this.loading.value = false;
        return returnData;
    }
    async isUserBought (loginUserId, holder: IsUserBoughtParameterHolder){
        this.loading.value = true;

        const returnData = await PsApiService.isUsetBought<ApiStatus>(new ApiStatus(),loginUserId, holder.toMap());

        this.loading.value = false;
        return returnData;
    }
    async markAsSold (loginUserId, holder: IsUserBoughtParameterHolder){
        this.loading.value = true;

        const returnData = await PsApiService.makeMarkAsSold<ApiStatus>(new ApiStatus(),loginUserId, holder.toMap());

        this.loading.value = false;
        return returnData;
    }
    async markAsSoldFromDetail (loginUserId, holder: MarkSoldOutItemParameterHolder){

        this.loading.value = true;

        const returnData = await PsApiService.markSoldOutItem<ApiStatus>(new ApiStatus(),loginUserId, holder.toMap());

        this.loading.value = false;
        return returnData;
    }

    private updateOfferList(responseData: PsResource<Offer[]>) {

        if (this.offerList != null
            && this.offerList.data != null
            && this.offerList.data.length > 0
            && this.offset != 0) {

            if (responseData.data != null && responseData.data.length > 0) {
                if(responseData.data?.length < this.limit){
                    this.isNoMoreRecord.value = true;
                } else {
                    this.isNoMoreRecord.value = false;
                }
                this.offerList.data.push(...responseData.data);
            }else {
                this.isNoMoreRecord.value = true;
            }

            this.offerList.code = responseData.code;
            this.offerList.status = responseData.status;
            this.offerList.message = responseData.message;

        } else {
            if(responseData.data?.length < this.limit || responseData.data == null){
                this.isNoMoreRecord.value = true;
            } else {
                this.isNoMoreRecord.value = false;
            }
            this.offerList = responseData;


        }

        if (this.offerList != null && this.offerList.data != null) {
            this.offset = this.offerList.data.length;
        }

    }

    async loadOfferList(holder: OfferParameterHolder) {

        this.loading.value = true;

        const responseData = await PsApiService.getOfferList<Offer>(new Offer(), holder.toMap());

        this.updateOfferList(responseData);

        this.loading.value = false;

    }

    async resetOfferList(holder: OfferParameterHolder) {

        this.offset = 0;

        this.loading.value = true;

        const responseData = await PsApiService.getOfferList<Offer>(new Offer(), holder.toMap());

        this.updateOfferList(responseData);

        this.loading.value = false;

    }

}

export const offerProviderSymbol = Symbol('OfferProvider')
export const createOfferProviderState = () => {
    return reactive(new OfferProvider())
}

export const useOfferProviderState = () => inject(offerProviderSymbol) as OfferProvider
export const provideOfferProviderState = () => provide(
    offerProviderSymbol,
    createOfferProviderState()
)
