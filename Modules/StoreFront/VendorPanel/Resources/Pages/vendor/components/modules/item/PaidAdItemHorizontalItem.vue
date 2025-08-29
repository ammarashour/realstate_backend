<template>

    <div class="cursor-pointer sm:w-full md:w-full" v-on:click="onClick != null ? onClick(paiditem) : null">
        <!-- Pscard -->

        <ps-card class="lg:rounded-lg rounded-lg shadow-sm relative z-0" :enabledHover="true">

            <!-- Favourite -->
            <div class="absolute z-10 bg-feAchromatic-50 dark:bg-feSecondary-800 rounded w-10 h-10 flex justify-center items-center right-2 top-2" @click="favouriteClicked(paiditem.item)">
                <ps-icon textColor="text-fePrimary-500 dark:text-feAchromatic-50" :name="paiditem.item.isFavourited == '1' ? 'heart' : 'heart-outline'" h="24" w="24" />
            </div>

            <ps-route-link :to="{ name: 'fe_item_detail', query: { item_id: paiditem.item.id }}">

                <div class="relative">
                    <!-- Image -->
                    <div class=" overflow-hidden lg:h-56 h-36 w-full" >
                        <img alt="Placeholder" class="overflow-hidden transform transition duration-500 hover:scale-110 block object-cover w-screen lg:h-56 h-36 " width="640px" height="360px"
                        v-lazy=" { src: $page.props.thumb2xUrl + '/' + paiditem.item.defaultPhoto.imgPath, loading: $page.props.sysImageUrl+'/loading_gif.gif', error: $page.props.sysImageUrl+'/default_photo.png' }" />
                    </div>
                    <!-- Paid Item Status -->
                    <paid-ad-item-paid-status :status="paiditem ? paiditem.paidStatus : ''"></paid-ad-item-paid-status>
                </div>

                <div :class="showProfile ? 'pb-0' : 'pb-2'" class="pt-2 px-2 flex flex-col gap-2">

                    <!-- Item Title -->
                    <ps-label textColor="text-feSecondary-600 dark:text-feSecondary-200 text-sm">{{ paiditem.item.title }}</ps-label>

                    <!-- Item Price -->
                    <ps-label v-if="appInfoStore.appInfo.data?.psAppSetting?.SelectedPriceType != PsConst.NO_PRICE" textColor="font-semibold text-fePrimary-500 text-base">
                        {{ paiditem && appInfoStore.appInfo.data?.psAppSetting?.SelectedPriceType == "NORMAL_PRICE"  ? paiditem.item.itemCurrency.currencySymbol : '' }}
                        {{ formatPrice(paiditem ? paiditem.item.price : '') }}
                    </ps-label>

                    <!-- Location -->
                    <div class="flex items-center gap-2">
                        <ps-icon name="location" w="14" h="14" viewBox="0 0 14 14"/>
                        <ps-label textColor="text-feSecondary-600 dark:text-feSecondary-200 text-sm">{{ paiditem.item.itemLocation.name }}</ps-label>
                    </div>

                    <!-- Start Date -->
                    <ps-label textColor="flex items-center text-feSecondary-600 dark:text-feSecondary-200 text-sm sm:text-xs md:text-sm">
                        {{ $t('paid_item_horizontal_item__start') }}
                        <span class="grow ms-1">: {{ paiditem ? moment(paiditem.startDate.split(' ')[0]).format($page.props.dateFormat) : ''}}
                            <span class="float-end">{{ paiditem ? paiditem.startDate.split(' ')[1] : ''}}</span>
                        </span>
                    </ps-label>

                    <!-- End Date -->
                    <ps-label textColor="flex items-center text-feSecondary-600 dark:text-feSecondary-200 text-sm sm:text-xs md:text-sm">
                        {{ $t('paid_item_horizontal_item__end') }}
                        <span class="grow ms-3">: {{ paiditem ? moment(paiditem.endDate.split(' ')[0]).format($page.props.dateFormat)  : ''}}
                            <span class="float-end">{{ paiditem ? paiditem.endDate.split(' ')[1] : ''}}</span>
                        </span>
                    </ps-label>

                    <!-- Total Spending -->
                    <ps-label textColor="flex items-center text-feSecondary-600 dark:text-feSecondary-200 text-sm sm:text-xs md:text-sm">
                        {{ $t('paid_item_horizontal_item__total_spending') }}
                        <span class="grow ms-1">: {{ paiditem ? paiditem.item.itemCurrency.currencySymbol : ''}} {{paiditem ? paiditem.amount : ''}} </span>
                    </ps-label>
                </div>
            </ps-route-link>

            <!-- Profile -->
            <ps-route-link :to="{ name: 'fe_other_profile', params: { userId: paiditem.item?.user?.userId }}" >
                <div v-if="showProfile" class="pt-1 pb-2 flex flex-row items-center no-underline justify-between text-feAchromatic-900 leading-none px-2 rounded-b-xl lg:rounded-b-2xl">
                    <div class="relative">

                        <!-- Profile Image -->
                        <img alt="Placeholder" class="rounded-full bg-transparent lg:w-8 lg:h-8 w-6 h-6 flex items-center justify-center object-cover" width="50px" height="50px"
                         v-lazy=" { src: $page.props.thumb1xUrl + '/' + paiditem.item?.user?.userCoverPhoto, loading: $page.props.sysImageUrl+'/loading_gif.gif', error: $page.props.sysImageUrl+'/default_photo.png' }">

                        <!-- Blue Mark -->
                        <div v-if="paiditem.item.user.isVerifybluemark == '1'" class="w-3 h-3 bg-feInfo-500 rounded-full flex justify-center items-center absolute bottom-0 right-0">
                            <ps-icon name="bluemark" textColor="text-feSecondary-50" w="8" h="8" />
                        </div>
                    </div>

                    <ps-label class="ms-2 mt-1 text-sm flex-grow">
                        <!-- Profile Name -->
                        <span class="flex truncate text-xs text-feSecondary-800 dark:text-feSecondary-300" >
                            {{ paiditem.item.user.userName.length > 12 ? paiditem.item.user.userName.slice(0,12)+' ...' : paiditem.item.user.userName }}
                        </span>

                        <!-- Date -->
                        <span class="font-light text-xs text-feSecondary-500 dark:text-feSecondary-500">
                            {{ paiditem.item ? moment(paiditem.item.addedDate).format($page.props.dateFormat) : '' }}
                        </span>
                    </ps-label>
                </div>

            </ps-route-link>
        </ps-card>
    </div>
    <!-- END Pscard -->
    <ps-loading-dialog ref="ps_loading_dialog"/>
</template>

<script lang="ts">

// Components
import PsLabel from '@template1/vendor/components/core/label/PsLabel.vue';
import PaidAdItemPaidStatus from './PaidAdItemPaidStatus.vue';
import PsCard from '@template1/vendor/components/core/card/PsCard.vue';
import PsIcon from '@template1/vendor/components/core/icons/PsIcon.vue';
import PsRouteLink from '@template1/vendor/components/core/link/PsRouteLink.vue';
import PsLoadingDialog from '@template1/vendor/components/core/dialog/PsLoadingDialog.vue';

//language
import { trans } from 'laravel-vue-i18n';
import PsUtils from '@templateCore/utils/PsUtils';
import PsConst from '@templateCore/object/constant/ps_constants';

// Objects
import PaidAdItem from '@templateCore/object/PaidAdItem';
import { ref } from 'vue';

// Providers
import { usePaidAdItemStoreState } from "@templateCore/store/modules/item/PaidAdItemStore";
import { PsValueStore } from "@templateCore/store/modules/core/PsValueStore";
import { usePsValueHolderState } from '@templateCore/object/core/PsValueHolder';
import { usePsAppInfoStoreState } from '@templateCore/store/modules/appinfo/AppInfoStore';
import { useFavouriteItemStoreState } from "@templateCore/store/modules/item/FavouriteItemStore";
import AppInfoParameterHolder from '@templateCore/object/holder/AppInfoParameterHolder';
import moment from "moment";
import { router } from '@inertiajs/vue3';

export default {
    name : "PaidAdItemHorizontalItem",
    components : {
        PsLabel,
        PsCard,
        PsRouteLink,
        PsIcon,
        PaidAdItemPaidStatus,
        PsLoadingDialog
    },
    props : {
        paiditem : { type : PaidAdItem } ,
        onClick : Function
    },
    setup() {
        // Inject Provider
        PsValueStore.psValueStore = usePsValueHolderState();
        const itempaidProvider = usePaidAdItemStoreState();
        const psValueStore = PsValueStore();
        const FavouriteItemProvider = useFavouriteItemStoreState();
        const loginUserId = psValueStore.getLoginUserId();
        const appInfoParameterHolder = new AppInfoParameterHolder();
        appInfoParameterHolder.userId = loginUserId;
        const appInfoStore = usePsAppInfoStoreState();
        const showProfile = ref(false);
        if(appInfoStore?.appInfo?.data?.mobileSetting?.is_show_owner_info == '1'){
            showProfile.value = true;
        }
        const ps_loading_dialog = ref();
        // appInfoStore.loadAppInfo(appInfoParameterHolder);
        function formatPrice(value) {
            if(value.toString() == '0') {
                return trans('item_price__free');
            }else {
                if (appInfoStore.appInfo.data?.psAppSetting?.SelectedPriceType ==  PsConst.PRICE_RANGE) {
                    const floatValue = parseFloat(value);
                    const intValue = parseInt(floatValue);
                    if (intValue > 5) {
                        return '$'.repeat(5);
                    }
                    if (intValue < 1) {
                        return '$'.repeat(1);
                    }
                    return '$'.repeat(intValue);
                } else {
                    return PsUtils.formatPrice(appInfoStore, value);
                }
            }
        }
        async function favouriteClicked(paidItem){
            if(loginUserId && loginUserId != PsConst.NO_LOGIN_USER){
                ps_loading_dialog.value.closeModal();
                if( paidItem.isFavourited == '1' ){
                    ps_loading_dialog.value.message = trans('item_detail__removing_item_from_favourite');
                }else{
                    ps_loading_dialog.value.message = trans('item_detail__adding_item_to_favourite');
                }
                await FavouriteItemProvider.postFavourite(paidItem.id, loginUserId);
                await refleshData();
                ps_loading_dialog.value.closeModal();
            }
            else{
                router.get(route('login'));
            }
        }
        async function refleshData(){
            await itempaidProvider.refleshPaidAdItemList(loginUserId);
        }
        return {
            itempaidProvider,
            formatPrice,
            psValueStore,
            PsConst,
            appInfoStore,
            favouriteClicked,
            ps_loading_dialog,
            showProfile,
            moment
        }
    }
}
</script>
