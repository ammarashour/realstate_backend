import { reactive,ref } from 'vue';

import VendorSearchParameterHolder from '@templateCore/object/holder/VendorSearchParameterHolder';
import PsApiService from '@templateCore/api/PsApiService';
import PsApi from '@templateCore/api/common/PsApi';
import Vendor from '@templateCore/object/Vendor';
import PsResource from '@templateCore/api/common/PsResource';
import { defineStore } from 'pinia'
import makeSeparatedStore from '@templateCore/store/modules/core/PsSepetetedStore';

export const useVendorSearchStoreState = makeSeparatedStore((key: string) =>
    defineStore(`vendorSearchStore/${key}`,
        () => {

            const vendorSearchList = reactive<PsResource<Vendor[]>>(new PsResource());
            let paramHolder = reactive<VendorSearchParameterHolder>(new VendorSearchParameterHolder().getAllVendorParameterHolder());
            const loading = reactive({
                value: false
            });
            const showPopUpFilter = ref(false);
            const sortByValue = ref('');
            const ViewByValue = ref('');

            let limit = ref(10);
            let offset: Number = 0;
            const isNoMoreRecord = reactive({
                value: false
            })

            function updateVendorSearchList(responseData: PsResource<Vendor[]>) {

                if (vendorSearchList != null
                    && vendorSearchList.data != null
                    && vendorSearchList.data.length > 0
                    && offset != 0) {

                    if (responseData.data != null && responseData.data.length > 0) {
                        if(responseData.data?.length < limit.value){
                            isNoMoreRecord.value = true;
                        } else {
                            isNoMoreRecord.value = false;
                        }
                        vendorSearchList.data.push(...responseData.data);
                    }else {
                        isNoMoreRecord.value = true;
                    }

                    vendorSearchList.code = responseData.code;
                    vendorSearchList.status = responseData.status;
                    vendorSearchList.message = responseData.message;

                } else {
                    if(responseData.data?.length < limit.value || responseData.data == null){
                        isNoMoreRecord.value = true;
                    } else {
                        isNoMoreRecord.value = false;
                    }
                    vendorSearchList.data = responseData.data;
                    vendorSearchList.code = responseData.code;
                    vendorSearchList.status = responseData.status;
                    vendorSearchList.message = responseData.message;

                }

                if (vendorSearchList != null && vendorSearchList.data != null) {
                    offset = vendorSearchList.data.length;
                }

            }

            async function getVendorSearchList(loginUserId: string, holder: VendorSearchParameterHolder) {

                offset = 0;

                loading.value = true;

                const responseData = await PsApiService.postVendorSearchList<Vendor>(new Vendor(), loginUserId, limit.value, offset, holder.toMap());

                updateVendorSearchList(responseData);

                loading.value = false;

            }

            async function loadMoreVendorSearchList(loginUserId: string, holder: VendorSearchParameterHolder) {
                offset = vendorSearchList.data.length ?? 0;

                loading.value = true;

                const responseData = await PsApiService.postVendorSearchList<Vendor>(new Vendor(), loginUserId, limit.value, offset, holder.toMap());

                updateVendorSearchList(responseData);

                loading.value = false;
            }

            async function getVendorSearchListProps(vendors: PsResource<Vendor[]>) {

                offset = 0;

                loading.value = true;

                const responseData = await PsApi.wrapDataFromProps(new Vendor(), vendors);


                updateVendorSearchList(responseData);

                loading.value = false;

            }

            return {
                vendorSearchList,
                paramHolder,
                loading,
                sortByValue,
                ViewByValue,
                limit,
                offset,
                isNoMoreRecord,
                getVendorSearchList,
                showPopUpFilter,
                getVendorSearchListProps,
                loadMoreVendorSearchList
            }

        }),
);
