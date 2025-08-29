<template>
    <ps-modal ref="psmodal" :isClickOut="false" line="hidden" maxWidth="552px" bodyHeight="h-[500px]" theme="rounded-lg px-8 py-6 bg-feAchromatic-50 dark:bg-feSecondary-800" >
        <template #title>
            <div class="flex justify-between items-center">
                <ps-label textColor="text-lg font-semibold text-feSecondary-800 dark:text-feSecondary-200">  {{ $t("core_fe__find_what_you_need") }} </ps-label>
                <ps-icon name="close" class="text-feAchromatic-400 hover:cursor-pointer" w="24" h="24" @click.prevent="psmodal.toggle(false)"></ps-icon>
            </div>
        </template>
        <template #body>
            <div class="relative">
                <div class="w-full h-full bg-feAchromatic-50 dark:bg-feSecondary-800 absolute z-20" v-if="isShow">
                    <div class="grid grid-cols-3">
                        <div class="col-span-1">
                            <ps-label textColor="text-sm font-medium text-fePrimary-500 px-2 py-1 cursor-pointer" @click="hideRecentSearch()">{{ $t("core_fe__back") }}</ps-label>
                        </div>
                        <div class="col-span-1 text-center">
                            <ps-label textColor="text-lg font-semibold text-feSecondary-800 dark:text-feSecondary-200">{{ $t(recentHistoryTitle) }}</ps-label>
                        </div>
                    </div>
                    <div class="border-b-2 pb-1 mt-6">
                        <ps-label textColor="text-xs font-medium text-feSecondary-500 px-2 py-1 cursor-pointer" @click="deleteHistory()">{{ $t("core_fe__clear_searches") }}</ps-label>
                    </div>
                    <div v-if="loading" class="mt-3 flex items-center justify-center h-96">
                        <ps-label textColor="text-sm font-normal text-feSecondary-800 dark:text-feSecondary-200">{{ $t("list__loading") }}</ps-label>
                    </div>
                    <div v-else class="mt-3 flex flex-col gap-4 h-96 overflow-auto">
                        <div v-for="history in currentHistoryList.searchHistoryList?.data" :key="history.id">
                            <ps-label textColor="text-sm font-normal text-feSecondary-800 dark:text-feSecondary-200">{{ history.keyword }}</ps-label>
                        </div>
                    </div>
                </div>

                <div class="flex gap-1 mt-4 dark:text-feAchromatic-50">
                    <div :class="['flex items-center gap-2 p-2 cursor-pointer border-b-4',activeTabId=='0'?'border-fePrimary-500':'border-feAchromatic-50 dark:border-feSecondary-800']" @click="setActiveTabId('0')">
                        <ps-label :textColor="activeTabId == '0' ? 'font-semibold' : ''">{{ $t("core_fe__all") }}</ps-label>
                    </div>
                    <div :class="['flex items-center gap-2 p-2 cursor-pointer border-b-4',activeTabId=='1'?'border-fePrimary-500':'border-feAchromatic-50 dark:border-feSecondary-800']" @click="setActiveTabId('1')">
                        <ps-icon name="item" w="24" h="24"/>
                        <ps-label :textColor="activeTabId == '1' ? 'font-semibold' : ''">{{ $t("core_fe__search_item") }}</ps-label>
                    </div>
                    <div :class="['flex items-center gap-2 p-2 cursor-pointer border-b-4',activeTabId=='2'?'border-fePrimary-500':'border-feAchromatic-50 dark:border-feSecondary-800']" @click="setActiveTabId('2')">
                        <ps-icon name="category" w="24" h="24"/>
                        <ps-label :textColor="activeTabId == '2' ? 'font-semibold' : ''">{{ $t("item_entry__category") }}</ps-label>
                    </div>
                    <div :class="['flex items-center gap-2 p-2 cursor-pointer border-b-4',activeTabId=='3'?'border-fePrimary-500':'border-feAchromatic-50 dark:border-feSecondary-800']" @click="setActiveTabId('3')">
                        <ps-icon name="user" w="24" h="24"/>
                        <ps-label :textColor="activeTabId == '3' ? 'font-semibold' : ''">{{ $t("core_fe__user") }}</ps-label>
                    </div>
                </div>
                <div class="mt-2">
                    <ps-input-with-left-icon rounded="rounded-lg" class="shadow-md"
                        theme="dark:bg-feSecondary-600 dark:text-feSecondary-200"
                        defaultBorder="border-none focus:outline-none focus:border-none focus:ring-2 focus:ring-fePrimary-300 ring-fePrimary-300"
                        :placeholder="$t('list__search')"
                        placeholderColor="placeholder-feSecondary-800 dark:placeholder-feSecondary-400"
                        v-on:keyup.enter="searchClicked" v-model:value="allSearchHolder.keyword">
                        <template #icon>
                            <ps-icon name="search" textColor="text-feSecondary-800 dark:text-feSecondary-400" w="24" h="24"/>
                        </template>
                    </ps-input-with-left-icon>
                </div>
                <div class="mt-6 ">
                    <!-- all -->
                    <div class="" v-if="activeTabId == '0' && allSearchStore.allSearchList.data == null">
                        <div v-if="loginUserId != PsConst.NO_LOGIN_USER">
                            <!-- All -->
                            <div v-if="allSearchHistroyStore.searchHistoryList?.data != null">
                                <div class="flex justify-between border-b pb-2">
                                    <ps-label textColor="text-base font-medium text-feSecondary-500">{{ $t("core_fe__recent_searches") }}</ps-label>
                                    <ps-label textColor="text-base font-medium text-feSecondary-500 cursor-pointer" @click="showRecentSearch('all')">{{ $t("core_fe__see_all") }}</ps-label>
                                </div>
                                <div class="mt-3 flex flex-col gap-4 max-h-80 overflow-auto">
                                    <div v-for="all in allSearchHistroyStore.searchHistoryList?.data" :key="all.id">
                                        <ps-label textColor="text-feSecondary-800 dark:text-feSecondary-200 cursor-pointer" @click="historyClick(all.keyword)">{{ all.keyword }}</ps-label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="w-full h-96 flex items-center justify-center" v-else>
                            <ps-label textColor="text-feSecondary-500">{{ $t("core_fe__no_recent_searches") }}</ps-label>
                        </div>
                    </div>
                    <div class="me-2" v-if="activeTabId == '0' && allSearchStore.allSearchList.data != null">
                        <div v-if="loading" class="w-full h-96 flex items-center justify-center">
                            <ps-label textColor="text-feSecondary-500" >{{ $t("list__loading") }}</ps-label>
                        </div>
                        <div v-else>
                            <div v-if="allSearchStore.allSearchList.data?.item == '' && allSearchStore.allSearchList.data?.category == '' && allSearchStore.allSearchList.data?.user == ''">
                                <div class="w-full h-96 flex items-center justify-center">
                                    <ps-label textColor="text-feSecondary-500">{{ $t("core_fe__no_suggestion") }}</ps-label>
                                </div>
                            </div>
                            <div v-else>
                                <ps-label textColor="text-feSecondary-500">{{ $t("core_fe__suggestion") }}</ps-label>
                                <div class="max-h-80 overflow-auto mt-6 pe-2">
                                    <!-- item -->
                                    <div class="mb-6" v-if="allSearchStore.allSearchList.data?.item != ''">
                                        <div class="flex justify-between items-center border-b pb-2">
                                            <div class="flex items-center gap-2">
                                                <ps-icon textColor="text-feSecondary-500" name="item" w="20" h="20" viewBox="-2 -2 24 24"/>
                                                <ps-label textColor="text-feSecondary-500">{{ $t("core_fe__search_item") }}</ps-label>
                                            </div>
                                        </div>
                                        <div class="mt-3 flex flex-col gap-4" v-for="item in allSearchStore.allSearchList.data.item.slice(0,3)" :key="item.id">
                                            <ps-route-link :to="{ name: 'fe_item_detail', query: { item_id: item.id } }" @click.prevent="psmodal.toggle(false)">
                                                <ps-label textColor="text-feSecondary-800 dark:text-feSecondary-200 cursor-pointer">{{ item.title }}</ps-label>
                                            </ps-route-link>
                                        </div>
                                    </div>
                                    <!-- category -->
                                    <div class="mb-6" v-if="allSearchStore.allSearchList.data?.category != ''">
                                        <div class="flex justify-between items-center border-b pb-2">
                                            <div class="flex items-center gap-2">
                                                <ps-icon textColor="text-feSecondary-500" name="category" w="20" h="20" viewBox="-2 -2 24 24"/>
                                                <ps-label textColor="text-feSecondary-500">{{ $t("item_entry__category") }}</ps-label>
                                            </div>
                                        </div>
                                        <div class="mt-3 flex flex-col gap-4" v-for="category in allSearchStore.allSearchList.data.category.slice(0,3)" :key="category.id">
                                            <ps-route-link :to="{name: $page.props.mobileSetting.is_show_subcategory == '1' ? 'fe_sub_category' : 'fe_item_list',
                                                query: { cat_id: category.catId, cat_name: category.catName } }" @click.prevent="psmodal.toggle(false)">
                                                <ps-label textColor="text-feSecondary-800 dark:text-feSecondary-200 cursor-pointer">{{ category.catName }}</ps-label>
                                            </ps-route-link>
                                        </div>
                                    </div>
                                    <!-- user -->
                                    <div class="" v-if="allSearchStore.allSearchList.data?.user != ''">
                                        <div class="flex justify-between items-center border-b pb-2">
                                            <div class="flex items-center gap-2">
                                                <ps-icon textColor="text-feSecondary-500" name="user" w="20" h="20" viewBox="-2 -2 24 24"/>
                                                <ps-label textColor="text-feSecondary-500">{{ $t("core_fe__user") }}</ps-label>
                                            </div>
                                        </div>
                                        <div class="mt-3 flex flex-col gap-4" v-for="user in allSearchStore.allSearchList.data.user.slice(0,3)" :key="user.id">
                                            <ps-route-link :to="{ name: 'fe_other_profile', query: { userId: user.userId } }" @click.prevent="psmodal.toggle(false)">
                                                <ps-label textColor="text-feSecondary-800 dark:text-feSecondary-200 cursor-pointer">{{ user.userName }}</ps-label>
                                            </ps-route-link>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- item -->
                    <div class="" v-if="activeTabId == '1'">
                        <div class="" v-if="loginUserId != PsConst.NO_LOGIN_USER">
                            <div v-if="loading" class="w-full h-96 flex items-center justify-center">
                                <ps-label textColor="text-feSecondary-500" >{{ $t("list__loading") }}</ps-label>
                            </div>
                            <div v-else>
                                <div v-if="allSearchStore.allSearchList.data != null">
                                    <div v-if="allSearchStore.allSearchList.data?.item != null && allSearchStore.allSearchList.data?.item.length != 0">
                                        <ps-label textColor="text-feSecondary-500">{{ $t("core_fe__suggestion") }}</ps-label>
                                        <div class="mt-3 flex flex-col gap-4" v-for="item in allSearchStore.allSearchList.data?.item" :key="item.id">
                                            <ps-route-link :to="{ name: 'fe_item_detail', query: { item_id: item.id } }" @click.prevent="psmodal.toggle(false)">
                                                <ps-label textColor="text-feSecondary-800 dark:text-feSecondary-200 cursor-pointer">{{ item.title }}</ps-label>
                                            </ps-route-link>
                                        </div>
                                    </div>
                                    <div class="w-full h-96 flex items-center justify-center" v-else>
                                        <ps-label textColor="text-feSecondary-500">{{ $t("core_fe__no_suggestion") }}</ps-label>
                                    </div>
                                </div>
                                <div v-else>
                                    <div v-if="itemSearchHistroyStore.searchHistoryList?.data != null">
                                        <div class="flex justify-between border-b pb-2">
                                            <ps-label textColor="text-base font-medium text-feSecondary-500">{{ $t("core_fe__recent_searches") }}</ps-label>
                                            <ps-label textColor="text-base font-medium text-feSecondary-500 cursor-pointer" @click="showRecentSearch('item')">{{ $t("core_fe__see_all") }}</ps-label>
                                        </div>
                                        <div class="mt-3 flex flex-col gap-4 max-h-80 overflow-auto">
                                            <div v-for="item in itemSearchHistroyStore.searchHistoryList?.data" :key="item.id">
                                                <ps-label textColor="text-feSecondary-800 dark:text-feSecondary-200 cursor-pointer" @click="historyClick(item.keyword)">{{ item.keyword }}</ps-label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="w-full h-96 flex items-center justify-center" v-else>
                                        <ps-label textColor="text-feSecondary-500">{{ $t("core_fe__no_recent_searches") }}</ps-label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div v-else>
                            <div v-if="allSearchStore.allSearchList.data != null">
                                <div v-if="loading" class="w-full h-96 flex items-center justify-center">
                                    <ps-label textColor="text-feSecondary-500" >{{ $t("list__loading") }}</ps-label>
                                </div>
                                <div v-else>
                                    <div v-if="allSearchStore.allSearchList.data?.item != null && allSearchStore.allSearchList.data?.item.length != 0">
                                        <ps-label textColor="text-feSecondary-500">{{ $t("core_fe__suggestion") }}</ps-label>
                                        <div class="mt-3 flex flex-col gap-4" v-for="item in allSearchStore.allSearchList.data?.item" :key="item.id">
                                            <ps-route-link :to="{ name: 'fe_item_detail', query: { item_id: item.id } }" @click.prevent="psmodal.toggle(false)">
                                                <ps-label textColor="text-feSecondary-800 dark:text-feSecondary-200 cursor-pointer">{{ item.title }}</ps-label>
                                            </ps-route-link>
                                        </div>
                                    </div>
                                    <div class="w-full h-96 flex items-center justify-center" v-else>
                                        <ps-label textColor="text-feSecondary-500">{{ $t("core_fe__no_suggestion") }}</ps-label>
                                    </div>
                                </div>
                            </div>
                            <div class="w-full h-96 flex items-center justify-center" v-else>
                                <ps-label textColor="text-feSecondary-500">{{ $t("core_fe__no_recent_searches") }}</ps-label>
                            </div>
                        </div>
                    </div>

                    <!-- category -->
                    <div class="" v-if="activeTabId == '2'">
                        <div class="" v-if="loginUserId != PsConst.NO_LOGIN_USER">
                            <div v-if="loading" class="w-full h-96 flex items-center justify-center">
                                <ps-label textColor="text-feSecondary-500" >{{ $t("list__loading") }}</ps-label>
                            </div>
                            <div v-else>
                                <div v-if="allSearchStore.allSearchList.data != null">
                                    <div v-if="allSearchStore.allSearchList.data?.category != null && allSearchStore.allSearchList.data?.category.length != 0">
                                        <ps-label textColor="text-feSecondary-500">{{ $t("core_fe__suggestion") }}</ps-label>
                                        <div class="mt-3 flex flex-col gap-4" v-for="category in allSearchStore.allSearchList.data?.category" :key="category.id">
                                            <ps-route-link :to="{name: $page.props.mobileSetting.is_show_subcategory == '1' ? 'fe_sub_category' : 'fe_item_list',
                                                query: {
                                                    cat_id: category.catId,
                                                    cat_name: category.catName
                                                } }" @click.prevent="psmodal.toggle(false)">
                                                <ps-label textColor="text-feSecondary-800 dark:text-feSecondary-200 cursor-pointer">{{ category.catName }}</ps-label>
                                            </ps-route-link>
                                        </div>
                                    </div>
                                    <div class="w-full h-96 flex items-center justify-center" v-else>
                                        <ps-label textColor="text-feSecondary-500">{{ $t("core_fe__no_suggestion") }}</ps-label>
                                    </div>
                                </div>
                                <div v-else>
                                    <div v-if="categorySearchHistroyStore.searchHistoryList?.data != null">
                                        <div class="flex justify-between border-b pb-2">
                                            <ps-label textColor="text-base font-medium text-feSecondary-500">{{ $t("core_fe__recent_searches") }}</ps-label>
                                            <ps-label textColor="text-base font-medium text-feSecondary-500 cursor-pointer" @click="showRecentSearch('category')">{{ $t("core_fe__see_all") }}</ps-label>
                                        </div>
                                        <div class="mt-3 flex flex-col gap-4 max-h-80 overflow-auto">
                                            <div v-for="category in categorySearchHistroyStore.searchHistoryList?.data" :key="category.id">
                                                <ps-label textColor="text-feSecondary-800 dark:text-feSecondary-200 cursor-pointer" @click="historyClick(category.keyword)">{{ category.keyword }}</ps-label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="w-full h-96 flex items-center justify-center" v-else>
                                        <ps-label textColor="text-feSecondary-500">{{ $t("core_fe__no_recent_searches") }}</ps-label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div v-else>
                            <div v-if="allSearchStore.allSearchList.data != null">
                                <div v-if="loading" class="w-full h-96 flex items-center justify-center">
                                    <ps-label textColor="text-feSecondary-500" >{{ $t("list__loading") }}</ps-label>
                                </div>
                                <div v-else>
                                    <div v-if="allSearchStore.allSearchList.data?.category != null && allSearchStore.allSearchList.data?.category.length != 0">
                                        <ps-label textColor="text-feSecondary-500">{{ $t("core_fe__suggestion") }}</ps-label>
                                        <div class="mt-3 flex flex-col gap-4" v-for="category in allSearchStore.allSearchList.data?.category" :key="category.id">
                                            <ps-route-link :to="{ name: 'fe_category.index' }" @click.prevent="psmodal.toggle(false)">
                                                <ps-label textColor="text-feSecondary-800 dark:text-feSecondary-200 cursor-pointer">{{ category.catName }}</ps-label>
                                            </ps-route-link>
                                        </div>
                                    </div>
                                    <div class="w-full h-96 flex items-center justify-center" v-else>
                                        <ps-label textColor="text-feSecondary-500">{{ $t("core_fe__no_suggestion") }}</ps-label>
                                    </div>
                                </div>
                            </div>
                            <div class="w-full h-96 flex items-center justify-center" v-else>
                                <ps-label textColor="text-feSecondary-500">{{ $t("core_fe__no_recent_searches") }}</ps-label>
                            </div>
                        </div>
                    </div>

                    <!-- user -->
                    <div class="" v-if="activeTabId == '3'">
                        <div class="" v-if="loginUserId != PsConst.NO_LOGIN_USER">
                            <div v-if="loading" class="w-full h-96 flex items-center justify-center">
                                <ps-label textColor="text-feSecondary-500" >{{ $t("list__loading") }}</ps-label>
                            </div>
                            <div v-else>
                                <div v-if="allSearchStore.allSearchList.data != null">
                                    <div v-if="allSearchStore.allSearchList.data?.user != null && allSearchStore.allSearchList.data?.user.length != 0">
                                        <ps-label textColor="text-feSecondary-500">{{ $t("core_fe__suggestion") }}</ps-label>
                                        <div class="mt-3 flex flex-col gap-4" v-for="user in allSearchStore.allSearchList.data?.user" :key="user.id">
                                            <ps-route-link :to="{ name: 'fe_other_profile', query: { userId: user.userId } }" @click.prevent="psmodal.toggle(false)">
                                                <ps-label textColor="text-feSecondary-800 dark:text-feSecondary-200 cursor-pointer">{{ user.userName }}</ps-label>
                                            </ps-route-link>
                                        </div>
                                    </div>
                                    <div class="w-full h-96 flex items-center justify-center" v-else>
                                        <ps-label textColor="text-feSecondary-500">{{ $t("core_fe__no_suggestion") }}</ps-label>
                                    </div>
                                </div>
                                <div v-else>
                                    <div v-if="userSearchHistroyStore.searchHistoryList?.data != null">
                                        <div class="flex justify-between border-b pb-2">
                                            <ps-label textColor="text-base font-medium text-feSecondary-500">{{ $t("core_fe__recent_searches") }}</ps-label>
                                            <ps-label textColor="text-base font-medium text-feSecondary-500 cursor-pointer" @click="showRecentSearch('user')">{{ $t("core_fe__see_all") }}</ps-label>
                                        </div>
                                        <div class="mt-3 flex flex-col gap-4 max-h-80 overflow-auto">
                                            <div v-for="user in userSearchHistroyStore.searchHistoryList?.data" :key="user.id">
                                                <ps-label textColor="text-feSecondary-800 dark:text-feSecondary-200 cursor-pointer" @click="historyClick(user.keyword)">{{ user.keyword }}</ps-label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="w-full h-96 flex items-center justify-center" v-else>
                                        <ps-label textColor="text-feSecondary-500">{{ $t("core_fe__no_recent_searches") }}</ps-label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div v-else>
                            <div v-if="allSearchStore.allSearchList.data != null">
                                <div v-if="loading" class="w-full h-96 flex items-center justify-center">
                                    <ps-label textColor="text-feSecondary-500" >{{ $t("list__loading") }}</ps-label>
                                </div>
                                <div v-else>
                                    <div v-if="allSearchStore.allSearchList.data?.user != null && allSearchStore.allSearchList.data?.user.length != 0">
                                        <ps-label textColor="text-feSecondary-500">{{ $t("core_fe__suggestion") }}</ps-label>
                                        <div class="mt-3 flex flex-col gap-4" v-for="user in allSearchStore.allSearchList.data?.user" :key="user.id">
                                            <ps-route-link :to="{ name: 'fe_other_profile', query: { userId: user.userId } }" @click.prevent="psmodal.toggle(false)">
                                                <ps-label textColor="text-feSecondary-800 dark:text-feSecondary-200 cursor-pointer">{{ user.userName }}</ps-label>
                                            </ps-route-link>
                                        </div>
                                    </div>
                                    <div class="w-full h-96 flex items-center justify-center" v-else>
                                        <ps-label textColor="text-feSecondary-500">{{ $t("core_fe__no_suggestion") }}</ps-label>
                                    </div>
                                </div>
                            </div>
                            <div class="w-full h-96 flex items-center justify-center" v-else>
                                <ps-label textColor="text-feSecondary-500">{{ $t("core_fe__no_recent_searches") }}</ps-label>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </template>
    </ps-modal>
    <ps-success-dialog ref="success" />
</template>

<script lang='ts'>
import { defineComponent, ref } from 'vue';
import PsModal from '@template1/vendor/components/core/modals/PsModal.vue';
import PsLabelTitle from '@template1/vendor/components/core/label/PsLabelTitle.vue';
import PsLabel from '@template1/vendor/components/core/label/PsLabel.vue';
import PsButton from '@template1/vendor/components/core/buttons/PsButton.vue';
import PsLink from '@template1/vendor/components/core/link/PsLink.vue'
import PsSuccessDialog from '@template1/vendor/components/core/dialog/PsSuccessDialog.vue';
import PsIcon from '@template1/vendor/components/core/icons/PsIcon.vue';
import PsInputWithLeftIcon from '@template1/vendor/components/core/input/PsInputWithLeftIcon.vue';
import PsRouteLink from '@template1/vendor/components/core/link/PsRouteLink.vue';
import { trans } from 'laravel-vue-i18n'
import PsConst from '@templateCore/object/constant/ps_constants';

import { useAllSearchStoreState } from "@templateCore/store/modules/search/AllSearchStore";
import { useSearchHistoryStoreState } from "@templateCore/store/modules/search/SearchHistoryStore";
import { PsValueStore } from "@templateCore/store/modules/core/PsValueStore";

import AllSearchParameterHolder from '@templateCore/object/holder/AllSearchParameterHolder';
import SearchHistoryHolder from '@templateCore/object/holder/SearchHistoryHolder';
import DeleteSearchHistoryHolder from '@templateCore/object/holder/DeleteSearchHistoryHolder';


export default defineComponent({
    name : "SearchModal",
    components: {
        PsModal,
        PsIcon,
        PsLabelTitle,
        PsInputWithLeftIcon,
        PsLabel,
        PsButton,
        PsRouteLink,
        PsLink,
        PsSuccessDialog
    },
    setup() {
        const psmodal = ref();
        const activeTabId = ref("0");
        const psValueStore = PsValueStore();
        const allSearchStore = useAllSearchStoreState();
        const allSearchHistroyStore = useSearchHistoryStoreState('all');
        const itemSearchHistroyStore = useSearchHistoryStoreState('item');
        const categorySearchHistroyStore = useSearchHistoryStoreState('category');
        const userSearchHistroyStore = useSearchHistoryStoreState('user');
        const searchHistroyStore = useSearchHistoryStoreState();

        const allSearchHolder = new AllSearchParameterHolder();
        const searchHistoryHolder = new SearchHistoryHolder();
        const allSearchHistoryHolder = new SearchHistoryHolder().getAllSearchHistoryList();
        const itemSearchHistoryHolder = new SearchHistoryHolder().getItemSearchHistoryList();
        const categorySearchHistoryHolder = new SearchHistoryHolder().getCategorySearchHistoryList();
        const userSearchHistoryHolder = new SearchHistoryHolder().getUserSearchHistoryList();
        const deleteSearchHistoryHolder = new DeleteSearchHistoryHolder();
        const loginUserId = psValueStore.getLoginUserId();

        searchHistoryHolder.userId = loginUserId;
        allSearchHistoryHolder.userId = loginUserId;
        itemSearchHistoryHolder.userId = loginUserId;
        categorySearchHistoryHolder.userId = loginUserId;
        userSearchHistoryHolder.userId = loginUserId;

        allSearchStore.limit = 5;

        const loading = ref(false);
        const isShow = ref(false);
        const currentHistoryList = ref();
        const historyIdsArr = ref([]);
        const recentHistoryTitle = ref();

        async function loadSearchHistoryList(){
            await allSearchHistroyStore.resetSearchHistoryList(loginUserId,allSearchHistoryHolder);
            await itemSearchHistroyStore.resetSearchHistoryList(loginUserId,itemSearchHistoryHolder);
            await categorySearchHistroyStore.resetSearchHistoryList(loginUserId,categorySearchHistoryHolder);
            await userSearchHistroyStore.resetSearchHistoryList(loginUserId,userSearchHistoryHolder);
        }

        function openModal(){
            psmodal.value.toggle(true);
            loadSearchHistoryList();
        }

        function setActiveTabId(id){
            activeTabId.value = id;
        }

        async function searchClicked(){
            if(allSearchHolder.keyword == ''){
                allSearchStore.allSearchList.data = null;
                loadSearchHistoryList();
            }else{
                if(activeTabId.value == '0'){
                    allSearchHolder.type = 'all';
                }
                if(activeTabId.value == '1'){
                    allSearchHolder.type = 'item';
                }
                if(activeTabId.value == '2'){
                    allSearchHolder.type = 'category';
                }
                if(activeTabId.value == '3'){
                    allSearchHolder.type = 'user';
                }
                loading.value = true;
                await allSearchStore.getSearchedDataList(loginUserId, allSearchHolder);
                loading.value = false;
            }
        }

        function showRecentSearch(type){
            isShow.value = true;
            if(type == 'all'){
                recentHistoryTitle.value = 'core_fe__all_recents';
                currentHistoryList.value = allSearchHistroyStore;
                allSearchHistroyStore.searchHistoryList.data.forEach((e)=>{
                    historyIdsArr.value.push(e.id);
                })
            }else if(type == 'item'){
                recentHistoryTitle.value = 'core_fe__item_recents';
                currentHistoryList.value = itemSearchHistroyStore;
                itemSearchHistroyStore.searchHistoryList.data.forEach((e)=>{
                    historyIdsArr.value.push(e.id);
                })
            }else if(type == 'category'){
                recentHistoryTitle.value = 'core_fe__category_recents';
                currentHistoryList.value = categorySearchHistroyStore;
                categorySearchHistroyStore.searchHistoryList.data.forEach((e)=>{
                    historyIdsArr.value.push(e.id);
                })
            }else if(type == 'user'){
                recentHistoryTitle.value = 'core_fe__user_recents';
                currentHistoryList.value = userSearchHistroyStore;
                userSearchHistroyStore.searchHistoryList.data.forEach((e)=>{
                    historyIdsArr.value.push(e.id);
                })
            }
            deleteSearchHistoryHolder.ids = historyIdsArr.value;
        }

        function historyClick(keyword){
            allSearchHolder.keyword = keyword;
            searchClicked();
        }

        function hideRecentSearch(){
            isShow.value = false;
            historyIdsArr.value = [];
        }

        async function deleteHistory(){
            loading.value = true;
            await searchHistroyStore.deleteSearchHistroy(loginUserId, deleteSearchHistoryHolder);
            await loadSearchHistoryList();
            loading.value = false;
        }

        return {
            psmodal,
            activeTabId,
            allSearchStore,
            allSearchHistroyStore,
            itemSearchHistroyStore,
            categorySearchHistroyStore,
            userSearchHistroyStore,
            searchHistroyStore,
            allSearchHolder,
            deleteSearchHistoryHolder,
            loginUserId,
            PsConst,
            loading,
            isShow,
            currentHistoryList,
            historyIdsArr,
            recentHistoryTitle,
            openModal,
            setActiveTabId,
            searchClicked,
            showRecentSearch,
            historyClick,
            deleteHistory,
            hideRecentSearch,
         }
    },
})
</script>
