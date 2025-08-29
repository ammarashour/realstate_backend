<template>
    <div class="h-auto text-100 bg-feSecondary-900 flex flex-col justify-between ">
        <div class="lg:w-large xl:w-feLarge flex flex-col lg:justify-between xl:justify-center xl:gap-20 sm:flex-row md:flex-col lg:flex-row mx-auto mt-6">
            <div class="flex sm:flex-col md:flex-row gap-10">
                <div class="flex flex-col sm:grid sm:grid-cols-3 gap-10 sm:hidden md:grid md:grid-cols-2">
                    <div class="flex flex-col gap-6">
                        <ps-label textColor="text-feAchromatic-50" class="font-semibold text-xl sm:text-2xl"> {{ $t("footer__shop") }} </ps-label>
                        <div class="flex flex-col gap-2 sm:gap-4">
                            <ps-label class="font-normal text-base cursor-pointer" >
                                <ps-route-link
                                    :to="{name: 'fe_category.index' }"
                                    textColor="text-feAchromatic-50" textSize="text-base font-normal">
                                        {{ $t("footer__categories") }}
                                </ps-route-link>
                            </ps-label>

                            <ps-label class="font-normal text-base cursor-pointer" @click="viewAllRecentItemList" textColor="text-feAchromatic-50" textSize="font-normal text-base">
                                    {{ $t("footer__latest_posts") }}
                            </ps-label>

                            <ps-label class="font-normal text-base cursor-pointer" @click="viewAllPopularItemList" textColor="text-feAchromatic-50" textSize="font-normal text-base">
                                    {{ $t("footer__popular_posts") }}
                            </ps-label>

                            <ps-route-link :to="{name: 'fe_user_list' }">
                                <ps-label class="font-normal text-base cursor-pointer" textColor="text-feAchromatic-50" textSize="font-normal text-base">
                                    {{ $t("footer__user_list") }}
                                </ps-label>
                            </ps-route-link>

                        </div>
                    </div>
                    <div class="flex flex-col gap-6">
                        <ps-label textColor="text-feAchromatic-50" class="font-semibold text-xl sm:text-2xl"> {{ $t("footer__support") }} </ps-label>
                        <div class="flex flex-col gap-2 sm:gap-4 cursor-pointer">
                            <ps-label class="font-normal text-base" >
                                <ps-route-link
                                    :to="{name: 'fe_contact_us' }" textColor="text-feAchromatic-50" textSize="font-normal text-base">
                                    {{ $t("footer__contact_us") }}
                                </ps-route-link>
                            </ps-label>

                            <ps-label class="font-normal text-base cursor-pointer" >
                                <ps-route-link
                                    :to="{name: 'fe_safety_tips' }" textColor="text-feAchromatic-50" textSize="font-normal text-base">
                                        {{ $t("footer__safety_tips") }}
                                </ps-route-link>
                            </ps-label>

                            <ps-label class="font-normal text-base cursor-pointer" >
                                <ps-route-link
                                    :to="{name: 'fe_privacy' }" textColor="text-feAchromatic-50" textSize="font-normal text-base">
                                        {{ $t("footer__privacy_policy") }}
                                </ps-route-link>
                            </ps-label>
                            <ps-label class="font-normal text-base cursor-pointer hyphens-none ..." >
                                <ps-route-link
                                    :to="{name: 'fe_terms_and_conditions' }" textColor="text-feAchromatic-50" textSize="font-normal text-base">
                                        {{ $t("term_and_condition__term_and_condition") }}
                                </ps-route-link>
                            </ps-label>
                            <ps-label class="font-normal text-base cursor-pointer" >
                                <ps-route-link
                                    :to="{name: 'fe_faq' }" textColor="text-feAchromatic-50" textSize="font-normal text-base">
                                        {{ $t("faq_page__faq_page") }}
                                </ps-route-link>
                            </ps-label>
                        </div>
                    </div>
                </div>
                <div class="grid sm:grid-cols-3 md:grid-cols-1 gap-8 md:mx-auto">
                    <div class="flex flex-col hidden sm:block md:hidden">
                        <ps-label textColor="text-feAchromatic-50" class="font-semibold text-xl sm:text-2xl"> {{ $t("footer__shop") }} </ps-label>
                        <div class="flex flex-col gap-2 sm:gap-4 mt-6">
                            <ps-label class="font-normal text-base cursor-pointer" >
                                <ps-route-link
                                    :to="{name: 'fe_category.index' }"
                                    textColor="text-feAchromatic-50" textSize="text-base font-normal">
                                        {{ $t("footer__categories") }}
                                </ps-route-link>
                            </ps-label>

                            <ps-label class="font-normal text-base cursor-pointer" @click="viewAllRecentItemList" textColor="text-feAchromatic-50" textSize="font-normal text-base">
                                    {{ $t("footer__latest_posts") }}
                            </ps-label>

                            <ps-label class="font-normal text-base cursor-pointer" @click="viewAllPopularItemList" textColor="text-feAchromatic-50" textSize="font-normal text-base">
                                    {{ $t("footer__popular_posts") }}
                            </ps-label>
                        </div>
                    </div>
                    <div class="flex flex-col col-span-2 hidden sm:block md:hidden">
                        <ps-label textColor="text-feAchromatic-50" class="font-semibold text-xl sm:text-2xl"> {{ $t("footer__support") }} </ps-label>
                        <div class="flex flex-col gap-2 sm:gap-4 mt-6">
                            <ps-label class="font-normal text-base cursor-pointer" >
                                <ps-route-link
                                    :to="{name: 'fe_contact_us' }" textColor="text-feAchromatic-50" textSize="font-normal text-base">
                                    {{ $t("footer__contact_us") }}
                                </ps-route-link>
                            </ps-label>

                            <ps-label class="font-normal text-base cursor-pointer" >
                                <ps-route-link
                                    :to="{name: 'fe_safety_tips' }" textColor="text-feAchromatic-50" textSize="font-normal text-base">
                                        {{ $t("footer__safety_tips") }}
                                </ps-route-link>
                            </ps-label>

                            <ps-label class="font-normal text-base cursor-pointer" >
                                <ps-route-link
                                    :to="{name: 'fe_privacy' }" textColor="text-feAchromatic-50" textSize="font-normal text-base">
                                        {{ $t("footer__privacy_policy") }}
                                </ps-route-link>
                            </ps-label>
                            <ps-label class="font-normal text-base cursor-pointer hyphens-none ..." >
                                <ps-route-link
                                    :to="{name: 'fe_terms_and_conditions' }" textColor="text-feAchromatic-50" textSize="font-normal text-base">
                                        {{ $t("term_and_condition__term_and_condition") }}
                                </ps-route-link>
                            </ps-label>
                            <ps-label class="font-normal text-base cursor-pointer" >
                                <ps-route-link
                                    :to="{name: 'fe_faq' }" textColor="text-feAchromatic-50" textSize="font-normal text-base">
                                        {{ $t("faq_page__faq_page") }}
                                </ps-route-link>
                            </ps-label>
                        </div>
                    </div>
                    <div class="flex flex-col gap-6">
                        <ps-label textColor="text-feAchromatic-50" class="font-semibold text-xl sm:text-2xl"> {{ $t("footer__company") }} </ps-label>
                        <div class="flex flex-col gap-2 sm:gap-4">
                            <ps-label class="font-normal text-base cursor-pointer" >
                                <ps-route-link
                                    :to="{name: 'fe_about_us' }" textColor="text-feAchromatic-50" textSize="font-normal text-base">
                                        {{ $t("footer__about_us") }}
                                </ps-route-link>
                            </ps-label>
                            <ps-label class="font-normal text-base cursor-pointer" >
                                <ps-route-link
                                    :to="{name: 'fe_blog' }" textColor="text-feAchromatic-50" textSize="font-normal text-base">
                                        {{ $t("footer__blogs") }}
                                </ps-route-link>
                            </ps-label>
                        </div>
                    </div>
                    <div class="col-span-2 hidden sm:block md:hidden">
                        <div class="flex flex-col items-center sm:items-start mt-8 sm:mt-0">
                            <ps-label textColor="text-feAchromatic-50" class="font-semibold text-xl sm:text-2xl"> {{ $t("footer__follow_us") }} </ps-label>
                            <div class="flex items-center gap-6 mt-2.5">
                                <ps-route-link :to="{name: 'dashboard' }">
                                    <ps-icon class="cursor-pointer" textColor="text-feAchromatic-50" name="facebook" h="20" w="20" viewBox="0 0 20 20"/>
                                </ps-route-link>
                                <ps-route-link :to="{name: 'dashboard' }">
                                    <ps-icon class="cursor-pointer" textColor="text-feAchromatic-50" name="linkedIn" h="20" w="20" viewBox="0 0 20 20"/>
                                </ps-route-link>
                                <ps-route-link :to="{name: 'dashboard' }">
                                    <ps-icon class="cursor-pointer" textColor="text-feAchromatic-50" name="twitter" h="20" w="20" viewBox="0 0 20 20"/>
                                </ps-route-link>
                                <ps-route-link :to="{name: 'dashboard' }">
                                    <ps-icon class="cursor-pointer" textColor="text-feAchromatic-50" name="instagram" h="20" w="20" viewBox="0 0 18 18"/>
                                </ps-route-link>
                                <ps-route-link :to="{name: 'dashboard' }">
                                    <ps-icon class="cursor-pointer" textColor="text-feAchromatic-50" name="pinterest" h="20" w="20" viewBox="0 0 20 20"/>
                                </ps-route-link>
                                <ps-route-link :to="{name: 'dashboard' }">
                                    <ps-icon class="cursor-pointer" textColor="text-feAchromatic-50" name="youtube" h="20" w="20" viewBox="0 -3 20 20"/>
                                </ps-route-link>
                            </div>
                            <div class="flex justify-center sm:justify-start gap-6 h-8 sm:h-10 mt-8 sm:mt-6">
                                <div class="" v-if="appInfoStore?.appInfo?.data?.frontendConfigSetting?.googleSetting == '1'" >
                                    <ps-link class="h-10" textColor="text-feAchromatic-50" :url="appInfoStore.appInfo.data.frontendConfigSetting.googlePlayUrl">
                                        <img class="w-full h-full cursor-pointer object-cover" alt="goole play store" width="50px" height="50px"
                                            v-lazy=" { src: $page.props.sysImageUrl + '/get_on_google_play_white.png', loading: $page.props.sysImageUrl+'/loading_gif.gif', error: $page.props.sysImageUrl+'/default_photo.png' }" />
                                    </ps-link>
                                </div>
                                <div class="" v-if="appInfoStore?.appInfo?.data?.frontendConfigSetting?.appStoreSetting == '1'">
                                    <ps-link class="h-10" textColor="text-feAchromatic-50" :url="appInfoStore.appInfo.data.frontendConfigSetting.appStoreUrl">
                                        <img class="w-full h-full cursor-pointer object-cover" alt="goole play store" width="50px" height="50px"
                                            v-lazy=" { src: $page.props.sysImageUrl + '/get_on_apple_store_white.png', loading: $page.props.sysImageUrl+'/loading_gif.gif', error: $page.props.sysImageUrl+'/default_photo.png' }" />
                                    </ps-link>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex flex-col items-center sm:items-start mt-8 sm:mt-0 md:mt-8 block sm:hidden md:flex md:flex-row md:gap-8 lg:flex-col lg:mt-0 lg:gap-10">
                <div class="">
                    <ps-label textColor="text-feAchromatic-50" class="font-semibold text-xl sm:text-2xl"> {{ $t("footer__follow_us") }} </ps-label>
                    <div class="flex items-center gap-6 mt-2.5 lg:mt-4">
                        <ps-route-link :to="{name: 'dashboard' }">
                            <ps-icon class="cursor-pointer" textColor="text-feAchromatic-50" name="facebook" h="20" w="20" viewBox="0 0 20 20"/>
                        </ps-route-link>
                        <ps-route-link :to="{name: 'dashboard' }">
                            <ps-icon class="cursor-pointer" textColor="text-feAchromatic-50" name="linkedIn" h="20" w="20" viewBox="0 0 20 20"/>
                        </ps-route-link>
                        <ps-route-link :to="{name: 'dashboard' }">
                            <ps-icon class="cursor-pointer" textColor="text-feAchromatic-50" name="twitter" h="20" w="20" viewBox="0 0 20 20"/>
                        </ps-route-link>
                        <ps-route-link :to="{name: 'dashboard' }">
                            <ps-icon class="cursor-pointer" textColor="text-feAchromatic-50" name="instagram" h="20" w="20" viewBox="0 0 18 18"/>
                        </ps-route-link>
                        <ps-route-link :to="{name: 'dashboard' }">
                            <ps-icon class="cursor-pointer" textColor="text-feAchromatic-50" name="pinterest" h="20" w="20" viewBox="0 0 20 20"/>
                        </ps-route-link>
                        <ps-route-link :to="{name: 'dashboard' }">
                            <ps-icon class="cursor-pointer" textColor="text-feAchromatic-50" name="youtube" h="20" w="20" viewBox="0 -3 20 20"/>
                        </ps-route-link>
                    </div>
                </div>
                <div class="flex justify-center sm:justify-start gap-6 h-8 sm:h-10 mt-8 sm:mt-6 lg:mt-0">
                    <div class="" v-if="appInfoStore?.appInfo?.data?.frontendConfigSetting?.googleSetting == '1'" >
                        <ps-link class="h-10" textColor="text-feAchromatic-50" :url="appInfoStore.appInfo.data.frontendConfigSetting.googlePlayUrl">
                            <img class=" w-32 h-full cursor-pointer object-contain" alt="goole play store" width="50px" height="50px"
                                v-lazy=" { src: $page.props.sysImageUrl + '/get_on_google_play_white.png', loading: $page.props.sysImageUrl+'/loading_gif.gif', error: $page.props.sysImageUrl+'/default_photo.png' }" />
                        </ps-link>
                    </div>
                    <div class="" v-if="appInfoStore?.appInfo?.data?.frontendConfigSetting?.appStoreSetting == '1'">
                        <ps-link class="h-10" textColor="text-feAchromatic-50" :url="appInfoStore.appInfo.data.frontendConfigSetting.appStoreUrl">
                            <img class="w-auto h-full cursor-pointer object-contain" alt="goole play store" width="50px" height="50px"
                                v-lazy=" { src: $page.props.sysImageUrl + '/get_on_apple_store_white.png', loading: $page.props.sysImageUrl+'/loading_gif.gif', error: $page.props.sysImageUrl+'/default_photo.png' }" />
                        </ps-link>
                    </div>
                </div>
            </div>
        </div>

        <div class='mb-6 flex justify-center items-center mt-8 sm:mt-6'>
            <ps-label textColor="text-feAchromatic-50" class="font-medium text-sm"> {{appInfoStore?.appInfo?.data?.frontendConfigSetting?.copyRight}}  </ps-label>
        </div>

    </div>
</template>

<script>

// Libs
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import PsLabel from '@template1/vendor/components/core/label/PsLabel.vue';
 import PsLink from '@template1/vendor/components/core/link/PsLink.vue'
import PsRouteLink from '@template1/vendor/components/core/link/PsRouteLink.vue'
import PsLabelCaption from '@template1/vendor/components/core/label/PsLabelCaption.vue'
import PsIcon from '@template1/vendor/components/core/icons/PsIcon.vue';
// Holders
import ProductParameterHolder from '@templateCore/object/holder/ProductParameterHolder';
import { PsValueStore } from "@templateCore/store/modules/core/PsValueStore";
// import PsConfig from "@template1/config/PsConfig";
import { usePsAppInfoStoreState } from '@templateCore/store/modules/appinfo/AppInfoStore';
// import AppInfoParameterHolder from '@templateCore/object/holder/AppInfoParameterHolder';
export default {
    components : {
        PsLabel,
        PsIcon,
        PsLink,
        PsRouteLink,
        PsLabelCaption
    },
    setup() {
        // Inject Item Provider
        const psValueStore = PsValueStore();
        const locationId = ref(psValueStore.locationId);
        const versionNo = '1.0.1';


        // Inject Item Provider
        const loginUserId = psValueStore.getLoginUserId();
        const appInfoStore = usePsAppInfoStoreState();
        // const appInfoParameterHolder = new AppInfoParameterHolder();
        // appInfoParameterHolder.userId = loginUserId;
        function gotToHome() {
            router.get(route('dashboard'));
        }
        // appInfoStore.loadAppInfo(appInfoParameterHolder);

        function viewAllRecentItemList() {
            const holder = new ProductParameterHolder().getLatestParameterHolder();
            const params = holder.getUrlParamsAndQuery();
            
            router.get(route('fe_item_list',params.query));

        }

        function viewAllPopularItemList() {
            const holder = new ProductParameterHolder().getPopularParameterHolder();
            const params = holder.getUrlParamsAndQuery();

            // Redirect
            router.get(route('fe_item_list', params.query));


        }

        return  {
            gotToHome,
            viewAllPopularItemList,
            viewAllRecentItemList,
            versionNo,
            // PsConfig,
            appInfoStore
        }
    }
}
</script>

