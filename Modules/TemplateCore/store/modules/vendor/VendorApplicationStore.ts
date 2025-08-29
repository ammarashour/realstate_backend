import { reactive,ref } from 'vue';
import { defineStore  } from 'pinia';

import makeSeparatedStore from '@templateCore/store/modules/core/PsSepetetedStore';
import VendorApplicationParameterHolder from '@templateCore/object/holder/VendorApplicationParameterHolder';
import PsApiService from '@templateCore/api/PsApiService';
import PsResource from '@templateCore/api/common/PsResource';
import PsStatus from '@templateCore/api/common/PsStatus';
import ApiStatus from '@templateCore/object/ApiStatus';
import Vendor from '../../../object/Vendor';

export const useVendorApplicationStore = makeSeparatedStore((key: string) =>
    defineStore(`vendorApplicationStore/${key}`, () => {

        const loading = reactive({
            value: false
        });

        let paramHolder = reactive<VendorApplicationParameterHolder>(new VendorApplicationParameterHolder());

        async function submitVendorApplication(loginUserId :string, id: String, email: String, storeName: String, coverLetter: String, documentFile: File, currencyId: String) {

            loading.value = true;

            const vendor = await PsApiService.postSubmitVendorApplication<Vendor>(new Vendor(), loginUserId, id, email, storeName, coverLetter, documentFile, currencyId);

            loading.value = false;

            return vendor;
        }

        return {
            loading,
            submitVendorApplication
        }
    }),
);
