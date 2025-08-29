<template>
    <div :class="'border-feSecondary-400'" class="bg-feAchromatic-50 dark:bg-feSecondary-800 border-2 rounded-lg shadow-sm px-4 sm:px-4 lg:px-5 xl:px-6 py-4 sm:py-1 md:py-2 lg:py-2">
        <div>
            <ps-label textColor="text-xl md:text-2xl font-semibold text-feSecondary-800 dark:text-feSecondary-200">{{packageList.paymentInfo.value}}</ps-label>
        </div>
        <div class="flex gap-1 mt-1">
            <ps-icon class="text-feSecondary-800 dark:text-feSecondary-200" name="user-fill" w="20" h="20" viewBox="0 0 20 20"/>
            <ps-label textColor="text-sm font-medium text-feSecondary-800 dark:text-feSecondary-200">{{packageList.purchasedCount}}</ps-label>
        </div>
        <div class="mt-1 lg:mt-2">
            <ps-label textColor="text-sm md:text-base font-medium text-feSecondary-800 dark:text-feSecondary-200">{{ packageList.paymentAttributes.count }} posts</ps-label>
        </div>
        <div class="flex justify-end mt-1 md:mt-3 lg:mt-4">
            <ps-label textColor="text-lg sm:text-xl font-semibold text-feSecondary-800 dark:text-feSecondary-200">{{ packageList.paymentAttributes.currencySymbol }} {{ formatPrice(packageList.paymentAttributes.price) }}</ps-label>
        </div>
    </div>
</template>


<script>
import PsUtils from '@templateCore/utils/PsUtils';
import { trans } from 'laravel-vue-i18n';
import { usePsAppInfoStoreState } from '@templateCore/store/modules/appinfo/AppInfoStore';
import PsLabel from '@template1/vendor/components/core/label/PsLabel.vue';
import PsIcon from '@template1/vendor/components/core/icons/PsIcon.vue';

    export default {
        name: "PackageHorizontalPackage",
        components: {
            PsLabel,
            PsIcon
        },
        props: {
            packageList: Object,
        },
        setup(){
            const appInfoStore = usePsAppInfoStoreState();
            function formatPrice(value) {
                if(value.toString() == '0') {
                    return trans('item_price__free');
                }else {
                    return PsUtils.formatPrice(appInfoStore, value);
                }
            }

            return{
                formatPrice
            }
        }
    }
</script>
