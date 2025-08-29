import { reactive,ref } from 'vue';
import { defineStore  } from 'pinia';

import makeSeparatedStore from '@templateCore/store/modules/core/PsSepetetedStore';
import PsApiService from '@templateCore/api/PsApiService';
import PsResource from '@templateCore/api/common/PsResource';
import PsStatus from '@templateCore/api/common/PsStatus';
import ApiStatus from '@templateCore/object/ApiStatus';
import Vendor from '@templateCore/object/Vendor';
import VendorBranches from '@templateCore/object/VendorBranches';
import VendorBranchesParameterHolder from '@templateCore/object/holder/VendorBranchesParameterHolder';
import { route } from 'ziggy-js';
import { router } from '@inertiajs/vue3';

export const useVendorStore = makeSeparatedStore((key: string) =>
    defineStore(`vendorStore/${key}`, () => {

        const vendorList = reactive<PsResource<Vendor[]>>(new PsResource());
        const vendor = reactive<PsResource<Vendor>>(new PsResource());
        const vendorBranches = reactive<PsResource<VendorBranches>>(new PsResource());

        const loading = reactive({
            value: false
        });

        let limit = ref(10);
        let offset: Number = 0;
        const isNoMoreRecord = reactive({
            value: false
        })

        function updateVendorList(responseData: PsResource<Vendor[]>) {

            if (vendorList != null
                && vendorList.data != null
                && vendorList.data.length > 0
                && offset != 0) {


                if (responseData.data != null && responseData.data.length > 0) {
                    if(responseData.data?.length < limit.value){
                        isNoMoreRecord.value = true;
                    } else {
                        isNoMoreRecord.value = false;
                    }
                    vendorList.data.push(...responseData.data);
                } else {
                    isNoMoreRecord.value = true;
                }
                vendorList.code = responseData.code;
                vendorList.status = responseData.status;
                vendorList.message = responseData.message;

            } else {
                if(responseData.data?.length < limit.value || responseData.data == null){
                    isNoMoreRecord.value = true;
                } else {
                    isNoMoreRecord.value = false;
                }
                vendorList.data = responseData.data;
                vendorList.code = responseData.code;
                vendorList.status = responseData.status;
                vendorList.message = responseData.message;


            }

            if (vendorList != null && vendorList.data != null) {
                offset = vendorList.data.length;
            }

        }

        function updateVendorBranchesList(responseData: PsResource<VendorBranches[]>) {

            if (vendorBranches != null
                && vendorBranches.data != null
                && vendorBranches.data.length > 0
                && offset != 0) {


                if (responseData.data != null && responseData.data.length > 0) {
                    if(responseData.data?.length < limit.value){
                        isNoMoreRecord.value = true;
                    } else {
                        isNoMoreRecord.value = false;
                    }
                    vendorBranches.data.push(...responseData.data);
                } else {
                    isNoMoreRecord.value = true;
                }
                vendorBranches.code = responseData.code;
                vendorBranches.status = responseData.status;
                vendorBranches.message = responseData.message;

            } else {
                if(responseData.data?.length < limit.value || responseData.data == null){
                    isNoMoreRecord.value = true;
                } else {
                    isNoMoreRecord.value = false;
                }
                vendorBranches.data = responseData.data;
                vendorBranches.code = responseData.code;
                vendorBranches.status = responseData.status;
                vendorBranches.message = responseData.message;


            }

            if (vendorBranches != null && vendorBranches.data != null) {
                offset = vendorBranches.data.length;
            }

        }

        async function resetVendorList(loginUserId: string, ownerUserId: string, status: string = null) {

            offset = 0;
            loading.value = true;

            const responseData = await PsApiService.getVendorListByOwnerId<Vendor>(new Vendor(), loginUserId, ownerUserId, limit.value, offset, status);

            updateVendorList(responseData);
            loading.value = false;
        }

        async function loadVendorList(loginUserId: string, ownerUserId: string) {

            loading.value = true;

            const responseData = await PsApiService.getVendorListByOwnerId<Vendor>(new Vendor(), loginUserId, ownerUserId, limit.value, offset);

            updateVendorList(responseData);
            loading.value = false;
        }

        async function loadVendor(vendorId: string, loginUserId: string, ownerUserId: string) {

            loading.value = true;

            const responseData = await PsApiService.getVendorDetail<Vendor>(new Vendor(), vendorId, loginUserId, ownerUserId);
            vendor.data = responseData.data;
            vendor.code = responseData.code;
            vendor.status = responseData.status;
            vendor.message = responseData.message;
            loading.value = false;

            return vendor;

        }

        async function loadVendorBranches(loginUserId: string, holder: VendorBranchesParameterHolder) {

            loading.value = true;

            const responseData = await PsApiService.postVendorBranches<VendorBranches>(new VendorBranches(), loginUserId, limit.value, offset, holder.toMap());

            updateVendorBranchesList(responseData);
            loading.value = false;
        }

        async function refreshVendorList(loginUserId :string, ownerUserId: String) {

            loading.value = true;
            const tempOffset = 0;
            const templimit = vendorList.data.length;
            const responseData = await PsApiService.getVendorListByOwnerId<Vendor>(new Vendor(), loginUserId, ownerUserId, templimit, tempOffset);
            vendorList.data = responseData.data;
            vendorList.code = responseData.code;
            vendorList.status = responseData.status;
            vendorList.message = responseData.message;
            loading.value = false;
        }

        async function toggleIsUnlimitedInertia(id, callback?: (status, message) => void) {
            if(id) {
                router.put(route('vendor.isUnlimitedChange', id),
                {}, {
                    onBefore: () => callback?.('before', ''),
                    onSuccess: (data) =>{
                        const status = (data.props as any).status;
                        callback?.(status === 'danger' ? 'error' : 'success', status.msg);
                    },
                    onError: () => callback?.('error', '')
                })
            }
        }

        return {
            vendorList,
            vendor,
            vendorBranches,
            loading,
            limit,
            offset,
            isNoMoreRecord,
            updateVendorList,
            updateVendorBranchesList,
            resetVendorList,
            loadVendorList,
            loadVendor,
            loadVendorBranches,
            refreshVendorList,
            toggleIsUnlimitedInertia
        }
    }),
);
