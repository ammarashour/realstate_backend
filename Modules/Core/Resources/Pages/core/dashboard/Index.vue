<template>
    <Head :title="$t('core__be_dashboard_label')" />

        <ps-label-header-3 class="pt-3 mb-6 font-semibold">{{ $t('core__be_dashboard_label') }}</ps-label-header-3>

        <!-- Filter -->
        <div class="flex flex-row mb-6 ">
            <form>
                <div class="flex flex-row">
                    <ps-dropdown :disabled="true" align="left" class='w-48 me-4'>
                        <template #select>
                            <ps-dropdown-select :disabled="true" placeholder="Last 7 Days"
                                :selectedValue="(form.filter == null ) ? '' : form.filter"/>
                        </template>
                        <template #list>
                            <div class="w-48 rounded-md shadow-xs ">
                                <div class="z-30 pt-2 ">
                                    <div v-for="filter in date_filters" :key="filter.id"
                                        @click="[form.filter=filter.name, form.date='', handleFilter()]"
                                        class="flex items-center w-48 px-2 py-4 cursor-pointer hover:bg-primary-000 dark:hover:bg-primary-900">
                                        <ps-label class="ms-2" :class="(form.filter !=null && filter.name == form.filter) ? ' font-bold' : ''">
                                            {{ filter.name }} </ps-label>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </ps-dropdown>
                    <date-picker @blur="handleFilter()" class="rounded shadow-sm pt-0.5 border border-secondary-200 dark:border-secondary-200 focus:outline-none focus:ring-1 focus:ring-primary-500" :class="(form.date == null || form.date == '') ? 'w-full' :'w-full'" v-model:value="form.date" :range="true" :inline="false" :autoApply="false" v-if="form.filter=='Custom'"/>
                </div>
            </form>
            <!-- <dropdown align="left" v-model:colFilterOptions="colFilterOptions">
                <template #select>
                    <div class="relative flex items-center justify-center w-10 h-10 rounded me-2 bg-primary-500">
                        <ps-icon name="eye-on" class='text-white cursor-pointer dark:text-secondaryDark-black' />
                    </div>
                </template>
            </dropdown> -->
            <ps-button @click="handleRefresh()" rounded="rounded" class="flex flex-wrap items-center ms-3 ">
                <ps-icon name="refresh" class="font-semibold me-2" />
                <ps-label textColor="text-white dark:text-secondary-800">{{ $t('core__be_refresh') }}</ps-label>
            </ps-button>
        </div>

        <!-- Cards hide-scroll-bar -->
        <div class="grid gap-6 mb-6 cxxl:grid-cols-3 lg:grid-cols-3 cmd:grid-cols-3 sm:grid-cols-2">
            <!-- Total Users -->
            <ps-card theme="w-full px-4 pt-4 me-6 flex flex-col bg-secondary-000 shadow dark:bg-secondary-900" rounded="rounded-md"
                v-show="colFilterOptions.filter((item) => item.key == 'total_user').length == 0 ? true : !colFilterOptions.filter((item) => item.key == 'total_user')[0]?.hidden">
                <div class="w-12 h-12 p-3 rounded-full bg-primary-500">
                    <ps-icon name="elements" class="text-white"/>
                </div>
                <div class="flex flex-row justify-between mt-2 mb-auto">
                    <div>
                        <ps-label-header-3 textColor="text-secondary-800 dark:text-secondary-100" class="mb-1 font-semibold">{{(totalUsers).toLocaleString('en-US')}}</ps-label-header-3>
                        <ps-label-caption-2 textColor="text-secondary-600 dark:text-secondary-300" class="font-medium">{{ $t('total_users') }}</ps-label-caption-2>
                    </div>
                    <div class="place-self-center">
                        <ps-label-caption-2 textColor="text-green-500 flex" v-if="userPercentage>=0"><ps-icon class="block rtl:hidden" name="arrowTopRight" /><ps-icon class="hidden rtl:block" name="arrowTopLeft" />{{ userPercentage }} % </ps-label-caption-2>
                        <ps-label-caption-2 textColor="text-red-500 flex" v-else><ps-icon class="block rtl:hidden" name="arrowButtonRight" /><ps-icon class="hidden rtl:block" name="arrowButtonLeft" /> {{ userPercentage }} %</ps-label-caption-2>
                        <ps-label-caption-2 textColor="text-secondary-600 dark:text-secondary-300">{{form.filter}}</ps-label-caption-2>
                        <!-- <ps-label-caption-2 v-if="filter.filter == 'Last 7 days'">{{form.filter}}</ps-label-caption-2>
                        <ps-label-caption-2 v-if="filter.filter == 'Last 14 days'">{{$t('core__be_in_last_14_days')}}</ps-label-caption-2>
                        <ps-label-caption-2 v-if="filter.filter == 'Last 30 days'">{{$t('core__be_in_last_30_days')}}</ps-label-caption-2>
                        <ps-label-caption-2 v-if="filter.filter == 'Last 60 days'">{{$t('core__be_in_last_60_days')}}</ps-label-caption-2> -->
                    </div>
                </div>
                <div class="flex justify-center w-full h-10">
                    <ps-text-button colors="text-primary-500" class="flex" @click="handleUserList()">{{ $t('core__be_see_more_label') }} <ps-icon class="block ms-2 place-self-center rtl:hidden" name="rightChervon"/> <ps-icon class="hidden ms-2 place-self-center rtl:block" name="leftChervon"/></ps-text-button>
                </div>
            </ps-card>

            <!-- Total Sellers -->
            <ps-card theme="w-full px-4 pt-4 me-6 flex flex-col bg-secondary-000 shadow dark:bg-secondary-900" rounded="rounded-md"
                v-show="colFilterOptions.filter((item) => item.key == 'total_seller').length == 0 ? true : !colFilterOptions.filter((item) => item.key == 'total_seller')[0]?.hidden">
                <div class="flex flex-row -space-x-4 rtl:space-x-reverse">
                    <div v-for="user in onlyFourSellerImages" :key="user.id">
                        <img  class="relative inline object-cover w-12 h-12 border-2 border-white rounded-full dark:border-secondaryDark-black"
                        v-lazy=" { src: $page.props.uploadUrl + '/' + user.img_path, loading: $page.props.sysImageUrl+'/loading_gif.gif', error: $page.props.sysImageUrl+'/default_profile.png' }"
                       />
                        <!-- <img v-else class="relative inline object-cover w-12 h-12 border-2 border-white rounded-full"
                        :src="$page.props.uploadUrl + '/default_profile.png'"/> -->
                    </div>
                    <ps-avatar v-if="(totalSellers)-4>0" class="relative inline object-cover dark:border-secondaryDark-black border-2 border-white rounded-full -mt-0.5" theme="dark:bg-secondary-800 bg-secondary-100" rounded="rounded-full" w="w-12" h="h-12">
                        <ps-label class="dark:text-secondary-400 text-secondary-500">+{{(totalSellers)-4>99?'+99':(totalSellers)-4}}</ps-label>
                    </ps-avatar>
                    <ps-avatar v-else-if="(totalSellers)==0" class="relative inline object-cover dark:border-secondaryDark-black border-2 border-white rounded-full -mt-0.5" theme="dark:bg-secondary-800 bg-secondary-100" rounded="rounded-full" w="w-12" h="h-12">
                        <ps-label class="dark:text-secondary-400 text-secondary-500"></ps-label>
                    </ps-avatar>
                </div>
                <div class="flex flex-row justify-between mt-2 mb-auto">
                    <div>
                        <ps-label-header-3 textColor="text-secondary-800 dark:text-secondary-100" class="mb-1 font-semibold">{{(totalSellers).toLocaleString('en-US')}}</ps-label-header-3>
                        <ps-label-caption-2 textColor="text-secondary-600 dark:text-secondary-300" class="font-medium">{{ $t('total_sellers') }}</ps-label-caption-2>
                    </div>
                    <div class="place-self-center">
                        <ps-label-caption-2 textColor="text-green-500 flex" v-if="sellerPercentage>=0"><ps-icon class="block rtl:hidden" name="arrowTopRight" /><ps-icon class="hidden rtl:block" name="arrowTopLeft" />{{ sellerPercentage }} % </ps-label-caption-2>
                        <ps-label-caption-2 textColor="text-red-500 flex" v-else><ps-icon class="block rtl:hidden" name="arrowButtonRight" /><ps-icon class="hidden rtl:block" name="arrowButtonLeft" /> {{ sellerPercentage }} %</ps-label-caption-2>
                        <ps-label-caption-2 textColor="text-secondary-600 dark:text-secondary-300">{{form.filter}}</ps-label-caption-2>
                        <!-- <ps-label-caption-2 v-if="filter.filter == 'Last 7 days'">{{form.filter}}</ps-label-caption-2>
                        <ps-label-caption-2 v-if="filter.filter == 'Last 14 days'">{{$t('core__be_in_last_14_days')}}</ps-label-caption-2>
                        <ps-label-caption-2 v-if="filter.filter == 'Last 30 days'">{{$t('core__be_in_last_30_days')}}</ps-label-caption-2>
                        <ps-label-caption-2 v-if="filter.filter == 'Last 60 days'">{{$t('core__be_in_last_60_days')}}</ps-label-caption-2> -->
                    </div>
                </div>
                <div class="flex justify-center w-full h-10">
                    <ps-text-button colors="text-primary-500" class="flex" @click="handleSellerList()">{{ $t('core__be_see_more_label') }} <ps-icon class="block ms-2 place-self-center rtl:hidden" name="rightChervon"/> <ps-icon class="hidden ms-2 place-self-center rtl:block" name="leftChervon"/></ps-text-button>
                </div>
            </ps-card>

            <!-- Total Buyers -->
            <ps-card theme="w-full px-4 pt-4 me-6 flex flex-col bg-secondary-000 shadow dark:bg-secondary-900" rounded="rounded-md"
                        v-show="colFilterOptions.filter((item) => item.key == 'total_buyer').length == 0 ? true : !colFilterOptions.filter((item) => item.key == 'total_buyer')[0]?.hidden">
                <div class="flex flex-row -space-x-4 rtl:space-x-reverse">
                    <div v-for="user in onlyFourBuyerImages" :key="user.id">
                        <img class="relative inline object-cover w-12 h-12 border-2 border-white rounded-full dark:border-secondaryDark-black"
                        v-lazy=" { src: $page.props.uploadUrl + '/' + user.img_path, loading: $page.props.sysImageUrl+'/loading_gif.gif', error: $page.props.sysImageUrl+'/default_profile.png' }"
                       />
                        <!-- <img v-else class="relative inline object-cover w-12 h-12 border-2 border-white rounded-full"
                        :src="$page.props.uploadUrl + '/default_profile.png'"/> -->
                    </div>
                    <ps-avatar v-if="(totalBuyers)-4>0" class="relative inline object-cover border-2 dark:border-secondaryDark-black border-white rounded-full -mt-0.5" theme="dark:bg-secondary-800 bg-secondary-100" rounded="rounded-full" w="w-12" h="h-12">
                        <ps-label class="dark:text-secondary-400 text-secondary-500">+{{(totalBuyers)-4>99?'+99':(totalBuyers)-4}}</ps-label>
                    </ps-avatar>
                    <ps-avatar v-else-if="(totalBuyers)==0" class="relative inline object-cover border-2 dark:border-secondaryDark-black border-white rounded-full -mt-0.5" theme="dark:bg-secondary-800 bg-secondary-100" rounded="rounded-full" w="w-12" h="h-12">
                        <ps-label class="dark:text-secondary-400 text-secondary-500"></ps-label>
                    </ps-avatar>
                </div>
                <div class="flex flex-row justify-between mt-2 mb-auto">
                    <div>
                        <ps-label-header-3 textColor="text-secondary-800 dark:text-secondary-100" class="mb-1 font-semibold">{{(totalBuyers).toLocaleString('en-US')}}</ps-label-header-3>
                        <ps-label-caption-2 textColor="text-secondary-600 dark:text-secondary-300" class="font-medium">{{ $t('total_buyers') }}</ps-label-caption-2>
                    </div>
                    <div class="place-self-center">
                        <ps-label-caption-2 textColor="text-green-500 flex" v-if="buyerPercentage>=0"><ps-icon class="block rtl:hidden" name="arrowTopRight" /><ps-icon class="hidden rtl:block" name="arrowTopLeft" />{{ buyerPercentage }} % </ps-label-caption-2>
                        <ps-label-caption-2 textColor="text-red-500 flex" v-else><ps-icon class="block rtl:hidden" name="arrowButtonRight" /><ps-icon class="hidden rtl:block" name="arrowButtonLeft" /> {{ buyerPercentage }} %</ps-label-caption-2>
                        <ps-label-caption-2 textColor="text-secondary-600 dark:text-secondary-300">{{form.filter}}</ps-label-caption-2>
                        <!-- <ps-label-caption-2 v-if="filter.filter == 'Last 7 days'">{{form.filter}}</ps-label-caption-2>
                        <ps-label-caption-2 v-if="filter.filter == 'Last 14 days'">{{$t('core__be_in_last_14_days')}}</ps-label-caption-2>
                        <ps-label-caption-2 v-if="filter.filter == 'Last 30 days'">{{$t('core__be_in_last_30_days')}}</ps-label-caption-2>
                        <ps-label-caption-2 v-if="filter.filter == 'Last 60 days'">{{$t('core__be_in_last_60_days')}}</ps-label-caption-2> -->
                    </div>
                </div>
                <div class="flex justify-center w-full h-10">
                    <ps-text-button colors="text-primary-500" class="flex" @click="handleBuyerList()">{{ $t('core__be_see_more_label') }} <ps-icon class="block ms-2 place-self-center rtl:hidden" name="rightChervon"/> <ps-icon class="hidden ms-2 place-self-center rtl:block" name="leftChervon"/></ps-text-button>
                </div>
            </ps-card>

            <!-- Successful Deal Count -->
            <ps-card theme="w-full px-4 pt-4 me-6 flex flex-col bg-secondary-000 shadow dark:bg-secondary-900" rounded="rounded-md"
                v-show="colFilterOptions.filter((item) => item.key == 'successful_deal_count').length == 0 ? true : !colFilterOptions.filter((item) => item.key == 'successful_deal_count')[0]?.hidden">
                <div class="w-12 h-12 p-3 bg-yellow-500 rounded-full">
                    <ps-icon name="wallet" class="text-white"/>
                </div>
                <div class="flex flex-row justify-between mt-2 mb-auto">
                    <div>
                        <ps-label-header-3 textColor="text-secondary-800 dark:text-secondary-100" class="mb-1 font-semibold">{{(successful_deal_count).toLocaleString('en-US')}}</ps-label-header-3>
                        <ps-label-caption-2 textColor="text-secondary-600 dark:text-secondary-300" class="font-medium">{{ $t('successful_deal_count') }}</ps-label-caption-2>
                    </div>
                    <div class="place-self-center">
                        <ps-label-caption-2 textColor="text-green-500 flex" v-if="successfulDealCountItemPercentage>=0"><ps-icon class="block rtl:hidden" name="arrowTopRight" /><ps-icon class="hidden rtl:block" name="arrowTopLeft" />{{ successfulDealCountItemPercentage }} % </ps-label-caption-2>
                        <ps-label-caption-2 textColor="text-red-500 flex" v-else><ps-icon class="block rtl:hidden" name="arrowButtonRight" /><ps-icon class="hidden rtl:block" name="arrowButtonLeft" /> {{ successfulDealCountItemPercentage }} %</ps-label-caption-2>
                        <ps-label-caption-2 textColor="text-secondary-600 dark:text-secondary-300">{{form.filter}}</ps-label-caption-2>
                        <!-- <ps-label-caption-2 v-if="filter.filter == 'Last 7 days'">{{form.filter}}</ps-label-caption-2>
                        <ps-label-caption-2 v-if="filter.filter == 'Last 14 days'">{{$t('core__be_in_last_14_days')}}</ps-label-caption-2>
                        <ps-label-caption-2 v-if="filter.filter == 'Last 30 days'">{{$t('core__be_in_last_30_days')}}</ps-label-caption-2>
                        <ps-label-caption-2 v-if="filter.filter == 'Last 60 days'">{{$t('core__be_in_last_60_days')}}</ps-label-caption-2> -->
                    </div>
                </div>
                <div class="flex justify-center w-full h-10">
                    <ps-text-button colors="text-primary-500" class="flex" @click="handleSuccessfulDealCountList()">{{ $t('core__be_see_more_label') }} <ps-icon class="block ms-2 place-self-center rtl:hidden" name="rightChervon"/> <ps-icon class="hidden ms-2 place-self-center rtl:block" name="leftChervon"/></ps-text-button>
                </div>
            </ps-card>

            <!-- Total Sold Out Items -->
            <ps-card theme="w-full px-4 pt-4 me-6 flex flex-col bg-secondary-000 shadow dark:bg-secondary-900" rounded="rounded-md"
                v-show="colFilterOptions.filter((item) => item.key == 'total_users').length == 0 ? true : !colFilterOptions.filter((item) => item.key == 'total_users')[0]?.hidden">
                <div class="w-12 h-12 p-3 rounded-full bg-primary-500">
                    <ps-icon name="shoppingCart" class="text-white"/>
                </div>
                <div class="flex flex-row justify-between mt-2 mb-auto">
                    <div>
                        <ps-label-header-3 textColor="text-secondary-800 dark:text-secondary-100" class="mb-1 font-semibold">{{(total_sold_out_items).toLocaleString('en-US')}}</ps-label-header-3>
                        <ps-label-caption-2 textColor="text-secondary-600 dark:text-secondary-300" class="font-medium">{{ $t('total_sold_out_items') }}</ps-label-caption-2>
                    </div>
                    <div class="place-self-center">
                        <ps-label-caption-2 textColor="text-green-500 flex" v-if="soldOutItemPercentage>=0"><ps-icon class="block rtl:hidden" name="arrowTopRight" /><ps-icon class="hidden rtl:block" name="arrowTopLeft" />{{ soldOutItemPercentage }} % </ps-label-caption-2>
                        <ps-label-caption-2 textColor="text-red-500 flex" v-else><ps-icon class="block rtl:hidden" name="arrowButtonRight" /><ps-icon class="hidden rtl:block" name="arrowButtonLeft" /> {{ soldOutItemPercentage }} %</ps-label-caption-2>
                        <ps-label-caption-2 textColor="text-secondary-600 dark:text-secondary-300">{{form.filter}}</ps-label-caption-2>
                        <!-- <ps-label-caption-2 v-if="filter.filter == 'Last 7 days'">{{form.filter}}</ps-label-caption-2>
                        <ps-label-caption-2 v-if="filter.filter == 'Last 14 days'">{{$t('core__be_in_last_14_days')}}</ps-label-caption-2>
                        <ps-label-caption-2 v-if="filter.filter == 'Last 30 days'">{{$t('core__be_in_last_30_days')}}</ps-label-caption-2>
                        <ps-label-caption-2 v-if="filter.filter == 'Last 60 days'">{{$t('core__be_in_last_60_days')}}</ps-label-caption-2> -->
                    </div>
                </div>
                <div class="flex justify-center w-full h-10">
                    <ps-text-button colors="text-primary-500" class="flex" @click="handleSoldOutItemList()">{{ $t('core__be_see_more_label') }} <ps-icon class="block ms-2 place-self-center rtl:hidden" name="rightChervon"/> <ps-icon class="hidden ms-2 place-self-center rtl:block" name="leftChervon"/></ps-text-button>
                </div>
            </ps-card>

            <!-- Total Slow Moving Item -->
            <ps-card theme="w-full px-4 pt-4 me-6 flex flex-col bg-secondary-000 shadow dark:bg-secondary-900" rounded="rounded-md"
                v-show="colFilterOptions.filter((item) => item.key == 'total_slow_moving_items').length == 0 ? true : !colFilterOptions.filter((item) => item.key == 'total_slow_moving_items')[0]?.hidden">
                <div class="w-12 h-12 p-3 bg-blue-500 rounded-full">
                    <ps-icon name="shoppingCart" class="text-white"/>
                </div>
                <div class="flex flex-row justify-between mt-2 mb-auto">
                    <div>
                        <ps-label-header-3 textColor="text-secondary-800 dark:text-secondary-100" class="mb-1 font-semibold">{{(total_slow_moving_items).toLocaleString('en-US')}}</ps-label-header-3>
                        <ps-label-caption-2 textColor="text-secondary-600 dark:text-secondary-300" class="font-medium">{{ $t('total_slow_moving_items') }}</ps-label-caption-2>
                    </div>
                    <div class="place-self-center">
                        <ps-label-caption-2 textColor="text-green-500 flex" v-if="slowMovingItemPercentage>=0"><ps-icon class="block rtl:hidden" name="arrowTopRight" /><ps-icon class="hidden rtl:block" name="arrowTopLeft" />{{ slowMovingItemPercentage }} % </ps-label-caption-2>
                        <ps-label-caption-2 textColor="text-red-500 flex" v-else><ps-icon class="block rtl:hidden" name="arrowButtonRight" /><ps-icon class="hidden rtl:block" name="arrowButtonLeft" /> {{ slowMovingItemPercentage }} %</ps-label-caption-2>
                        <ps-label-caption-2 textColor="text-secondary-600 dark:text-secondary-300">{{form.filter}}</ps-label-caption-2>
                        <!-- <ps-label-caption-2 v-if="filter.filter == 'Last 7 days'">{{form.filter}}</ps-label-caption-2>
                        <ps-label-caption-2 v-if="filter.filter == 'Last 14 days'">{{$t('core__be_in_last_14_days')}}</ps-label-caption-2>
                        <ps-label-caption-2 v-if="filter.filter == 'Last 30 days'">{{$t('core__be_in_last_30_days')}}</ps-label-caption-2>
                        <ps-label-caption-2 v-if="filter.filter == 'Last 60 days'">{{$t('core__be_in_last_60_days')}}</ps-label-caption-2> -->
                    </div>
                </div>
                <div class="flex justify-center w-full h-10">
                    <ps-text-button colors="text-primary-500" class="flex" @click="handleSlowMovingItemList()">{{ $t('core__be_see_more_label') }} <ps-icon class="block ms-2 place-self-center rtl:hidden" name="rightChervon"/> <ps-icon class="hidden ms-2 place-self-center rtl:block" name="leftChervon"/></ps-text-button>
                </div>
            </ps-card>
        </div>

        <!-- Charts -->
        <div class="grid gap-6 mb-6 cxxl:grid-cols-3 xxl:grid-cols-3 lg:grid-cols-2 cmd:grid-cols-2 sm:grid-cols-1">
            <!-- Most Engaging Products Chart -->
            <ps-card theme="px-4 py-3 bg-secondary-000 shadow flex flex-col dark:bg-secondary-900" rounded="rounded-md"
                v-show="colFilterOptions.filter((item) => item.key == 'most_engaging_products').length == 0 ? true : !colFilterOptions.filter((item) => item.key == 'most_engaging_products')[0]?.hidden">
                <div class="flex flex-row justify-between mb-4">
                    <ps-label-title textColor="text-secondary-800 dark:text-secondary-100" class="font-semibold">{{$t('core__be_most_engaging_products')}}</ps-label-title>
                    <ps-dropdown h="h-auto">
                        <template #select>
                            <ps-icon name="vertical-dot" theme="text-secondary-800 dark:text-secondary-100"  viewBox="0 0 24 24" w="24"/>
                        </template>
                        <template #list>
                            <div class="w-32 rounded-md shadow-xs ">
                                <div class="z-30 ">
                                    <div v-if="productChart!='Pie Chart'" @click="productChart = 'Pie Chart' "
                                        class="flex items-center w-32 px-2 py-4 cursor-pointer hover:bg-primary-000 dark:hover:bg-primary-900">
                                        <ps-label class="ms-2" :class="(productChart=='Pie Chart') ? '' : ' font-bold'"> Pie Chart </ps-label>
                                    </div>
                                    <div v-if="productChart!='Column Chart'" @click="productChart = 'Column Chart' "
                                        class="flex items-center w-32 px-2 py-4 cursor-pointer hover:bg-primary-000 dark:hover:bg-primary-900">
                                        <ps-label class="ms-2" :class="(productChart=='Column Chart') ? '' : ' font-bold'"> Column Chart </ps-label>
                                    </div>
                                    <div v-if="productChart!='Bar Chart'" @click="productChart = 'Bar Chart' "
                                        class="flex items-center w-32 px-2 py-4 cursor-pointer hover:bg-primary-000 dark:hover:bg-primary-900">
                                        <ps-label class="ms-2" :class="(productChart=='Bar Chart') ? '' : ' font-bold'"> Bar Chart </ps-label>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </ps-dropdown>
                </div>
                <div class="flex flex-row justify-between mt-2 mb-auto">
                    <doughnut-chart
                        v-if="productChart=='Pie Chart'"
                        :chartOptions="productDoughnutChartOptions"
                        :chartData="productDoughnutChartData"
                        :plugins="[productDoughnutChartPlugin]"
                    />
                    <bar-chart
                        v-if="productChart=='Bar Chart'"
                        :chartOptions="productBarChartOptions"
                        :chartData="productBarChartData"
                    />
                    <bar-chart
                        v-if="productChart=='Column Chart'"
                        :chartOptions="productColumnChartOptions"
                        :chartData="productColumnChartData"
                    />
                </div>
                <div class="flex justify-center w-full h-10">
                    <ps-text-button colors="text-primary-500" class="flex" @click="handleItemList()">{{ $t('core__be_see_more_label') }} <ps-icon class="block ms-2 place-self-center rtl:hidden" name="rightChervon"/> <ps-icon class="hidden ms-2 place-self-center rtl:block" name="leftChervon"/></ps-text-button>
                </div>
            </ps-card>

            <!-- Most Engaging Categories Chart -->
            <!-- <ps-card theme="px-4 py-3 bg-secondary-000 shadow dark:bg-secondary-900" rounded="rounded-md"
                v-show="colFilterOptions.filter((item) => item.key == 'most_engaging_categories').length == 0 ? true : !colFilterOptions.filter((item) => item.key == 'most_engaging_categories')[0]?.hidden">
                <div class="flex flex-row justify-between mb-4">
                    <ps-label-title textColor="text-secondary-800 dark:text-secondary-100" class="font-semibold">{{$t('core__be_most_engaging_categories')}}</ps-label-title>

                    <ps-dropdown h="h-auto">
                        <template #select>
                            <ps-icon name="vertical-dot"  theme="text-secondary-800 dark:text-secondary-100"  viewBox="0 0 24 24" w="24"/>
                        </template>
                        <template #list>
                            <div class="w-32 rounded-md shadow-xs ">
                                <div class="z-30 ">
                                    <div v-if="categoryChart!='Pie Chart'" @click="categoryChart = 'Pie Chart' "
                                         class="flex items-center w-32 px-2 py-4 cursor-pointer hover:bg-primary-000 dark:hover:bg-primary-900">
                                        <ps-label class="ms-2" :class="(categoryChart=='Pie Chart') ? '' : ' font-bold'"> Pie Chart </ps-label>
                                    </div>
                                    <div v-if="categoryChart!='Column Chart'" @click="categoryChart = 'Column Chart' "
                                        class="flex items-center w-32 px-2 py-4 cursor-pointer hover:bg-primary-000 dark:hover:bg-primary-900">
                                        <ps-label class="ms-2" :class="(categoryChart=='Column Chart') ? '' : ' font-bold'"> Column Chart </ps-label>
                                    </div>
                                    <div v-if="categoryChart!='Bar Chart'" @click="categoryChart = 'Bar Chart' "
                                        class="flex items-center w-32 px-2 py-4 cursor-pointer hover:bg-primary-000 dark:hover:bg-primary-900">
                                        <ps-label class="ms-2" :class="(categoryChart=='Bar Chart') ? '' : ' font-bold'"> Bar Chart </ps-label>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </ps-dropdown>
                </div>
                <div class="flex flex-row justify-between mt-2 mb-auto">
                    <doughnut-chart
                        v-if="categoryChart=='Pie Chart'"
                        :chartOptions="categoryDoughnutChartOptions"
                        :chartData="categoryDoughnutChartData"
                        :plugins="[categoryDoughnutChartPlugin]"
                    />
                    <bar-chart
                        v-if="categoryChart=='Bar Chart'"
                        :chartOptions="categoryBarChartOptions"
                        :chartData="categoryBarChartData"
                    />
                    <bar-chart
                        v-if="categoryChart=='Column Chart'"
                        :chartOptions="categoryColumnChartOptions"
                        :chartData="categoryColumnChartData"
                    />
                </div>

                <div class="flex justify-center w-full h-10">
                    <ps-text-button colors="text-primary-500" class="flex" @click="handleCategoryList()">{{ $t('core__be_see_more_label') }} <ps-icon class="block ms-2 place-self-center rtl:hidden" name="rightChervon"/> <ps-icon class="hidden ms-2 place-self-center rtl:block" name="leftChervon"/></ps-text-button>
                </div>
            </ps-card> -->
            <!-- Most Purchased Packages Chart -->
            <ps-card theme="px-4 py-3 bg-secondary-000 shadow dark:bg-secondary-900" rounded="rounded-md"
            v-show="colFilterOptions.filter((item) => item.key == 'most_purchased_packages').length == 0 ? true : !colFilterOptions.filter((item) => item.key == 'most_purchased_packages')[0]?.hidden">
                <div class="flex flex-row justify-between mb-4">
                    <ps-label-title textColor="text-secondary-800 dark:text-secondary-100" class="font-semibold">{{$t('core__be_most_purchased_packages')}}</ps-label-title>

                    <ps-dropdown h="h-auto">
                        <template #select>
                            <ps-icon name="vertical-dot" theme="text-secondary-800 dark:text-secondary-100"  viewBox="0 0 24 24" w="24"/>
                        </template>
                        <template #list>
                            <div class="w-32 rounded-md shadow-xs ">
                                <div class="z-30 ">
                                    <div v-if="packageChart!='Column Chart'" @click="packageChart = 'Column Chart' "
                                        class="flex items-center w-32 px-2 py-4 cursor-pointer hover:bg-primary-000 dark:hover:bg-primary-900">
                                        <ps-label class="ms-2" :class="(packageChart=='Column Chart') ? '' : ' font-bold'"> Column Chart </ps-label>
                                    </div>
                                    <div v-if="packageChart!='Bar Chart'" @click="packageChart = 'Bar Chart' "
                                        class="flex items-center w-32 px-2 py-4 cursor-pointer hover:bg-primary-000 dark:hover:bg-primary-900">
                                        <ps-label class="ms-2" :class="(packageChart=='Bar Chart') ? '' : ' font-bold'"> Bar Chart </ps-label>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </ps-dropdown>
                </div>
                <div class="flex flex-row justify-between mt-2 mb-auto">
                    <bar-chart
                        v-if="packageChart=='Column Chart'"
                        :chartOptions="packageColumnChartOptions"
                        :chartData="packageColumnChartData"
                    />
                    <bar-chart
                        v-if="packageChart=='Bar Chart'"
                        :chartOptions="packageBarChartOptions"
                        :chartData="packageBarChartData"
                    />
                </div>
            </ps-card>

            <!-- User Activities Chart -->
            <!-- <ps-card theme="px-4 py-3 bg-secondary-000 shadow dark:bg-secondary-900" rounded="rounded-md"
                v-show="colFilterOptions.filter((item) => item.key == 'user_activities').length == 0 ? true : !colFilterOptions.filter((item) => item.key == 'user_activities')[0]?.hidden">
                <div class="flex flex-row justify-between mb-4">
                    <ps-label-title textColor="text-secondary-800 dark:text-secondary-100" class="font-semibold">User Activities</ps-label-title>
                </div>
                <bar-chart
                    :chartOptions="activityVerticalChartOptions"
                    :chartData="activityVerticalChartData"
                />
            </ps-card> -->

            <!-- Revenue from Promotion Chart -->
            <ps-card theme="px-4 py-3 bg-secondary-000 shadow dark:bg-secondary-900" rounded="rounded-md"
                v-show="colFilterOptions.filter((item) => item.key == 'revenue_from_promotion').length == 0 ? true : !colFilterOptions.filter((item) => item.key == 'revenue_from_promotion')[0]?.hidden">
                <div class="flex flex-row justify-between mb-4">
                    <div class="flex flex-row">
                        <ps-label-title textColor="text-secondary-800 dark:text-secondary-100" class="font-semibold">{{$t('core__be_revenue_from_promotion')}}</ps-label-title><ps-label-title> ({{ form.filter }})</ps-label-title>
                    </div>
                    <ps-dropdown h="h-auto">
                        <template #select>
                            <ps-icon name="vertical-dot" theme="text-secondary-800 dark:text-secondary-100"  viewBox="0 0 24 24" w="24"/>
                        </template>
                        <template #list>
                            <div class="w-32 rounded-md shadow-xs ">
                                <div class="z-30 ">
                                    <div v-if="promotionChart!='Line Chart'" @click="promotionChart = 'Line Chart' "
                                        class="flex items-center w-32 px-2 py-4 cursor-pointer hover:bg-primary-000 dark:hover:bg-primary-900">
                                        <ps-label class="ms-2" :class="(promotionChart=='Line Chart') ? '' : ' font-bold'"> Line Chart </ps-label>
                                    </div>
                                    <div v-if="promotionChart!='Column Chart'" @click="promotionChart = 'Column Chart' "
                                        class="flex items-center w-32 px-2 py-4 cursor-pointer hover:bg-primary-000 dark:hover:bg-primary-900">
                                        <ps-label class="ms-2" :class="(promotionChart=='Column Chart') ? '' : ' font-bold'"> Column Chart </ps-label>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </ps-dropdown>
                </div>
                <div class="flex flex-row justify-between mt-2 mb-auto">
                    <line-chart
                        v-if="promotionChart=='Line Chart'"
                        :chartOptions="promotionLineChartOptions"
                        :chartData="promotionLineChartData"
                    />
                    <bar-chart
                        v-if="promotionChart=='Column Chart'"
                        :chartOptions="promotionColumnChartOptions"
                        :chartData="promotionColumnChartData"
                    />
                </div>
                <div class="flex justify-center w-full h-10">
                    <ps-text-button colors="text-primary-500" class="flex" @click="handlePromotionList()">{{ $t('core__be_see_more_label') }} <ps-icon class="block ms-2 place-self-center rtl:hidden" name="rightChervon"/> <ps-icon class="hidden ms-2 place-self-center rtl:block" name="leftChervon"/></ps-text-button>
                </div>
            </ps-card>

            <!-- Revenue from Packages Chart -->
            <ps-card theme="px-4 py-3 bg-secondary-000 shadow dark:bg-secondary-900" rounded="rounded-md"
                v-show="colFilterOptions.filter((item) => item.key == 'revenue_from_packages').length == 0 ? true : !colFilterOptions.filter((item) => item.key == 'revenue_from_packages')[0]?.hidden">
                <div class="flex flex-row justify-between mb-4">
                    <div class="flex flex-row">
                        <ps-label-title textColor="text-secondary-800 dark:text-secondary-100" class="font-semibold">{{$t('core__be_revenue_from_package')}}</ps-label-title><ps-label-title> ({{ form.filter }})</ps-label-title>
                    </div>
                    <ps-dropdown h="h-auto">
                        <template #select>
                            <ps-icon name="vertical-dot" theme="text-secondary-800 dark:text-secondary-100"  viewBox="0 0 24 24" w="24"/>
                        </template>
                        <template #list>
                            <div class="w-32 rounded-md shadow-xs ">
                                <div class="z-30 ">
                                    <div v-if="revenuePackageChart!='Line Chart'" @click="revenuePackageChart = 'Line Chart' "
                                        class="flex items-center w-32 px-2 py-4 cursor-pointer hover:bg-primary-000 dark:hover:bg-primary-900">
                                        <ps-label class="ms-2" :class="(revenuePackageChart=='Line Chart') ? '' : ' font-bold'"> Line Chart </ps-label>
                                    </div>
                                    <div v-if="revenuePackageChart!='Column Chart'" @click="revenuePackageChart = 'Column Chart' "
                                        class="flex items-center w-32 px-2 py-4 cursor-pointer hover:bg-primary-000 dark:hover:bg-primary-900">
                                        <ps-label class="ms-2" :class="(revenuePackageChart=='Column Chart') ? '' : ' font-bold'"> Column Chart </ps-label>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </ps-dropdown>
                </div>
                <div class="flex flex-row justify-between mt-2 mb-auto">
                    <line-chart
                        v-if="revenuePackageChart=='Line Chart'"
                        :chartOptions="revenuePackageLineChartOptions"
                        :chartData="revenuePackageLineChartData"
                    />
                    <bar-chart
                        v-if="revenuePackageChart=='Column Chart'"
                        :chartOptions="revenuePackageColumnChartOptions"
                        :chartData="revenuePackageColumnChartData"
                    />
                </div>
                <div class="flex justify-center w-full h-10">
                    <ps-text-button colors="text-primary-500" class="flex" @click="handlePackageList()">{{ $t('core__be_see_more_label') }} <ps-icon class="block ms-2 place-self-center rtl:hidden" name="rightChervon"/> <ps-icon class="hidden ms-2 place-self-center rtl:block" name="leftChervon"/></ps-text-button>
                </div>
            </ps-card>

        </div>
        <!-- Highest Rated Seller Table -->
        <div class="mb-6" v-show="colFilterOptions.filter((item) => item.key == 'highest_rated_seller').length == 0 ? true : !colFilterOptions.filter((item) => item.key == 'highest_rated_seller')[0]?.hidden">
            <ps-card theme="px-4 py-3 bg-secondary-000 shadow dark:bg-secondary-900" rounded="rounded-md">
                <ps-label-title textColor="text-secondary-800 dark:text-secondary-100" class="font-semibold">{{$t('core__be_highest_rated_seller')}} {{$t('core__be_lifetime')}}</ps-label-title>
                <ps-table2 :defaultMt="defaultMt" :sm="defaultMt" :lg="defaultMt" :row="row" :search="search" :object="this.highest_rated_seller" :colFilterOptions="colFilterOptions"
                           :columns="highestRatedSellerColumns" :sort_field="highest_rated_seller_sort_field" :sort_order="highest_rated_seller_sort_order"
                           @FilterOptionshandle="FilterOptionshandle" @handleSort="handleHighestRatedSellerSorting" @handleSearch="handleSearching"
                           @handleRow="handleRow" :searchable="showFilter" :defaultSearch="false" :eye_filter="false" :defaultRowFilter="false">

                    <template #tableRow="rowProps">
                        <div class="flex flex-row " v-if="rowProps.field == 'overall_rating'">
                            <ps-rating :grade="rowProps.row.overall_rating" :hasCounter="true" /> <ps-label class="text-sm font-light"> {{rowProps.row.overall_rating}} ({{rowProps.row.rating_count}}) </ps-label>
                        </div>
                        <div class="flex flex-row" v-if="rowProps.field == 'detail'">
                            <ps-text-button colors="text-primary-500" @click="handleSellerDetail(rowProps.row.id)" >{{ $t('core__be_btn_detail') }}</ps-text-button>
                        </div>

                    </template>
                </ps-table2>

                <div class="flex justify-center w-full h-10">
                    <ps-text-button colors="text-primary-500" class="flex" @click="handleSellerList()">{{ $t('core__be_see_more_label') }} <ps-icon class="block ms-2 place-self-center rtl:hidden" name="rightChervon"/> <ps-icon class="hidden ms-2 place-self-center rtl:block" name="leftChervon"/></ps-text-button>
                </div>
            </ps-card>
        </div>

        <!-- Hightest Rated Buyer Table -->
        <div class="mb-6" v-show="colFilterOptions.filter((item) => item.key == 'highest_rated_buyer').length == 0 ? true : !colFilterOptions.filter((item) => item.key == 'highest_rated_buyer')[0]?.hidden">
            <ps-card theme="px-4 py-3 bg-secondary-000 shadow dark:bg-secondary-900" rounded="rounded-md">
                <ps-label-title textColor="text-secondary-800 dark:text-secondary-100" class="font-semibold">{{$t('core__be_highest_rated_buyer')}} {{$t('core__be_lifetime')}}</ps-label-title>

                <ps-table2 :defaultMt="defaultMt" :sm="defaultMt" :lg="defaultMt" :row="row" :search="search" :object="this.highest_rated_buyer" :colFilterOptions="colFilterOptions"
                           :columns="highestRatedBuyerColumns" :sort_field="highest_rated_buyer_sort_field" :sort_order="highest_rated_buyer_sort_order"
                           @FilterOptionshandle="FilterOptionshandle" @handleSort="handleHighestRatedBuyerSorting" @handleSearch="handleSearching"
                           @handleRow="handleRow" :searchable="showFilter" :defaultSearch="false" :eye_filter="false" :defaultRowFilter="false">

                    <template #tableRow="rowProps">
                        <div class="flex flex-row" v-if="rowProps.field == 'overall_rating'">
                            <ps-rating :grade="rowProps.row.overall_rating" :hasCounter="true" /> <ps-label class="text-sm font-light"> {{rowProps.row.overall_rating}} ({{rowProps.row.rating_count}}) </ps-label>
                        </div>
                        <div class="flex flex-row" v-if="rowProps.field == 'detail'">
                            <ps-text-button colors="text-primary-500" @click="handleBuyerDetail(rowProps.row.id)" >{{ $t('core__be_btn_detail') }}</ps-text-button>
                        </div>

                    </template>

                </ps-table2>

<!--                <ps-data-table :rows="this.highest_rated_buyer" :columns="highestRatedBuyerColumns" :searchHide="true" :perPageOptions="perPageOptions">-->
<!--                    <template #tableRow="rowProps">-->
<!--                        <div class="flex flex-row mb-2" v-if="rowProps.field == 'overall_rating'">-->
<!--                            <ps-rating :grade="rowProps.row.overall_rating" :hasCounter="true" /> {{rowProps.row.overall_rating}}-->
<!--                        </div>-->
<!--                        <div class="flex flex-row mb-2" v-if="rowProps.field == 'detail'">-->
<!--                            <ps-text-button colors="text-primary-500" @click="handleBuyerDetail(rowProps.row.id)" >{{ $t('core__be_btn_detail') }}</ps-text-button>-->
<!--                        </div>-->
<!--                    </template>-->
<!--                </ps-data-table>-->

                <div class="flex justify-center w-full h-10">
                    <ps-text-button colors="text-primary-500" class="flex" @click="handleBuyerList()">{{ $t('core__be_see_more_label') }} <ps-icon class="block ms-2 place-self-center rtl:hidden" name="rightChervon"/> <ps-icon class="hidden ms-2 place-self-center rtl:block" name="leftChervon"/></ps-text-button>
                </div>
            </ps-card>
        </div>

        <!-- Recent Registered Users Table -->
        <div class="mb-6" v-show="colFilterOptions.filter((item) => item.key == 'recent_registered_users').length == 0 ? true : !colFilterOptions.filter((item) => item.key == 'recent_registered_users')[0]?.hidden">
            <ps-card theme="px-4 py-3 bg-secondary-000 shadow dark:bg-secondary-900" rounded="rounded-md">
                <ps-label-title textColor="text-secondary-800 dark:text-secondary-100 dark:bg-secondary-900" class="font-semibold">{{$t('core__be_recent_registered_users')}}</ps-label-title>
                <ps-table2 :defaultMt="defaultMt" :sm="defaultMt" :lg="defaultMt" :row="row" :search="search" :object="this.recent_registered_users" :colFilterOptions="colFilterOptions"
                           :columns="recentRegisteredUserColumns" :sort_field="recent_registered_user_sort_field" :sort_order="recent_registered_user_sort_order"
                           @FilterOptionshandle="FilterOptionshandle" @handleSort="handleRecentRegisteredUserSorting" @handleSearch="handleSearching"
                           @handleRow="handleRow" :searchable="showFilter" :defaultSearch="false" :eye_filter="false" :defaultRowFilter="false">

                    <template #tableRow="rowProps">
                        <div class="flex flex-row" v-if="rowProps.field == 'detail'">
                            <ps-text-button colors="text-primary-500" @click="handleUserDetail(rowProps.row.id)" >{{ $t('core__be_btn_detail') }}</ps-text-button>
                        </div>

                    </template>


                </ps-table2>

                <div class="flex justify-center w-full h-10">
                    <ps-text-button colors="text-primary-500" class="flex" @click="handleUserList()">{{ $t('core__be_see_more_label') }} <ps-icon class="block ms-2 place-self-center rtl:hidden" name="rightChervon"/> <ps-icon class="hidden ms-2 place-self-center rtl:block" name="leftChervon"/></ps-text-button>
                </div>
            </ps-card>
        </div>
        <ps-success-dialog ref="ps_success_dialog" />
        <ps-confirm-dialog-with-icon ref="ps_confirm_dialog_with_icon" />
        <ps-license-activate-modal :isCountDownShow=true :hasError="hasError" :logMessages="logMessages" :status="status" :purchased_code="purchased_code" :project="project" :errors="errors" :systemCode2="systemCode2" ref="ps_license_activate_modal" />
        <ps-loading-circle-dialog ref="ps_loading_circle_dialog" />
        <ps-sync-setup-modal ref="ps_sync_setup_modal" :project="project" />
            <ps-error-dialog ref="ps_error_dialog"/>


</template>

<script>
import { defineComponent,ref,reactive,computed, onMounted } from 'vue'
import {Head, useForm} from '@inertiajs/vue3';
import PsLayout from "@/Components/PsLayout.vue";
import PsCard from "@/Components/Core/Card/PsCard.vue";
import PsLabel from "@/Components/Core/Label/PsLabel.vue";
import PsLabelHeader3 from "@/Components/Core/Label/PsLabelHeader3.vue";
import PsLabelCaption2 from "@/Components/Core/Label/PsLabelCaption2.vue";
import PsLabelTitle from "@/Components/Core/Label/PsLabelTitle.vue";
import PsIcon from "@/Components/Core/Icons/PsIcon.vue";
import PsTextButton from "@/Components/Core/Buttons/PsTextButton.vue";
import PsButton from "@/Components/Core/Buttons/PsButton.vue";
import PsDataTable from "@/Components/Core/Table/PsDataTable.vue";
import PsDropdown from "@/Components/Core/Dropdown/PsDropdown.vue";
import PsDropdownSelect from "@/Components/Core/Dropdown/PsDropdownSelect.vue";
import PsRating from "@/Components/Core/Rating/PsRating.vue";
import DatePicker from "@/Components/Core/DateTime/DatePicker.vue";
import Dropdown from "@/Components/Core/DropdownModal/Dropdown.vue";
import PsAvatar from '@/Components/Core/Avatar/PsAvatar.vue';
import BarChart from '@/Components/Core/Charts/BarChart.vue';
import PieChart from "@/Components/Core/Charts/PieChart.vue";
import BubbleChart from "@/Components/Core/Charts/BubbleChart.vue";
import DoughnutChart from "@/Components/Core/Charts/DoughnutChart.vue";
import LineChart from "@/Components/Core/Charts/LineChart.vue";
import RadarChart from "@/Components/Core/Charts/RadarChart.vue";
import moment from 'moment';
import PsLicenseActivateModal from '@/Components/Core/Modals/PsLicenseActivateModal.vue';
import PsSuccessDialog from '@/Components/Core/Dialog/PsSuccessDialog.vue';
import PsTable2 from "@/Components/Core/Table/PsTable2.vue";
import PsErrorDialog from '@/Components/Core/Dialog/PsErrorDialog.vue';

import { trans } from 'laravel-vue-i18n';
import { router } from '@inertiajs/vue3';
import PsConfirmDialogWithIcon from '@/Components/Core/Dialog/PsConfirmDialogWithIcon.vue';
import PsLoadingCircleDialog from '@/Components/Core/Dialog/PsLoadingCircleDialog.vue';
import PsSyncSetupModal from '@/Components/Core/Modals/PsSyncSetupModal.vue';
import { useThemeStore } from '../../../../../../resources/js/store/Utilities/ThemeStore';


export default defineComponent({
    name:'Index',
    props: [
        'endDate',
        'dateFormat',
        'most_engaging_products',
        'most_engaging_categories',
        'revenue_from_promotion',
        'revenue_from_packages',
        'most_purchased_packages',
        'user_activities',
        'highest_rated_seller',
        'highest_rated_buyer',
        'complaint_items',
        'recent_registered_users',
        'successful_deal_count',
        'total_sold_out_items',
        'total_slow_moving_items',
        'filter',
        'systemCode2',
        'buyerPercentage',
        'sellerPercentage',
        'userPercentage',
        'soldOutItemPercentage',
        'slowMovingItemPercentage',
        'successfulDealCountItemPercentage',
        'project',
        'errors',
        'purchased_code',
        'status',
        'logMessages',
        'hasError',
        'zipFileName',
        'activatedFileName',
        'highest_rated_buyer_sort_field',
        'highest_rated_buyer_sort_order',
        'highest_rated_seller_sort_field',
        'highest_rated_seller_sort_order',
        'recent_registered_user_sort_field',
        'recent_registered_user_sort_order',
        'totalUsers',
        'totalBuyers',
        'totalSellers',
        'packages',
        'onlyFourSellerImages',
        'onlyFourBuyerImages',
        'latest_refreshed_date_range',
        'downloadStatus',
        'activateStatus',
        'autoSyncStatus',
        'autoSyncMessage'
    ],
    components: {
        PsLicenseActivateModal,
        Head,
        PsCard,
        PsLabel,
        PsLabelHeader3,
        PsLabelCaption2,
        PsLabelTitle,
        PsIcon,
        PsTextButton,
        PsButton,
        PsDataTable,
        PsDropdown,
        PsDropdownSelect,
        PsRating,
        DatePicker,
        Dropdown,
        PsAvatar,

        BarChart,
        PieChart,
        BubbleChart,
        LineChart,
        DoughnutChart,
        RadarChart,
        PsSuccessDialog,
        PsTable2,
        PsConfirmDialogWithIcon,
        PsLoadingCircleDialog,
        PsSyncSetupModal,
        PsErrorDialog
    },
    data() {
        return {
            moment: moment,
            defaultMt: 'mt-1',
        }
    },
    layout: PsLayout,
    setup(props){

        let highest_rated_buyer_sort_field = props.highest_rated_buyer_sort_field ? ref(props.highest_rated_buyer_sort_field) : ref('');
        let highest_rated_buyer_sort_order = props.highest_rated_buyer_sort_order ? ref(props.highest_rated_buyer_sort_order) : ref('desc');

        let highest_rated_seller_sort_field = props.highest_rated_seller_sort_field ? ref(props.highest_rated_seller_sort_field) : ref('');
        let highest_rated_seller_sort_order = props.highest_rated_seller_sort_order ? ref(props.highest_rated_seller_sort_order) : ref('desc');

        let recent_registered_user_sort_field = props.recent_registered_user_sort_field ? ref(props.recent_registered_user_sort_field) : ref('');
        let recent_registered_user_sort_order = props.recent_registered_user_sort_order ? ref(props.recent_registered_user_sort_order) : ref('desc');
        
        const ps_error_dialog = ref();


        let form = reactive(
            {
                filter:'Last 7 Days',
                date:''
            }
        )
        const perPageOptions = ['5'];
        const ps_license_activate_modal = ref();
        const ps_success_dialog = ref();
        const ps_confirm_dialog_with_icon = ref();
        const ps_loading_circle_dialog = ref();
        const ps_sync_setup_modal = ref();
        //for darMode chart grid
        let darkGrid =  "#e5e7eb";
        const themeStore = useThemeStore();
        if(themeStore.isDarkMode.value){
            darkGrid = "white";
        } else{
            darkGrid = "#e5e7eb";
        }


        function openSuccessDialog(){
            ps_success_dialog.value.openModal(trans('license_activation_success'), trans('license_activation_success_finish_msg'), trans('setup_project'),
                ()=>{
                    let form = useForm({
                        'zipFileName' : props.zipFileName
                    })
                    this.$inertia.get(route('table.index'), form);
                },
                false);
        }

        // haven't imported at least once
        function remindToImportDialog(fileName){
            ps_confirm_dialog_with_icon.value.openModal(trans('pls_setup_project'),
                trans('remind_to_import_msg'),
                trans('setup'),
                trans('cancel'),
                true,
                ()=>{
                    let form = useForm({
                        'zipFileName' : fileName
                    })
                    this.$inertia.get(route('table.index'), form);
                },
                ()=>{});
            // ps_success_dialog.value.openModal('Please Import','You should import to use this project with your data. File that you made license activation has already exists. You can make import by clicking below button','Import',
            //     ()=>{
            //         let form = useForm({
            //             'zipFileName' : fileName
            //         })
            //         this.$inertia.get(route('table.index'), form);
            //     },
            //     false);
        }

        function openLicenseActivateModal(){
            ps_license_activate_modal.value.openModal('pls_activate_your_app','You have successfully imported the file.','Back',
                ()=>{
                    // router.get(route('language_string.updateAllLanguageStrings'), {
                    //     onBefore: () => {
                    //         ps_loading_circle_dialog.value.openModal('Importing','We’re processing your file at the moment. Please wait while we import the file for you.');
                    //     },
                    //     onSuccess: () => {
                    //         ps_loading_circle_dialog.value.closeModal();
                    //         //
                    //     },
                    //     onError: () => {
                    //         ps_loading_circle_dialog.value.closeModal();
                    //     },
                    // });

                },
                false);
        }

        function openSyncSetupModal(v = null){
            ps_sync_setup_modal.value.openModal('setup_connection_with_builder__title','You have successfully imported the file.','Back',
                ()=>{
                    // router.get(route('language_string.updateAllLanguageStrings'), {
                    //     onBefore: () => {
                    //         ps_loading_circle_dialog.value.openModal('Importing','We’re processing your file at the moment. Please wait while we import the file for you.');
                    //     },
                    //     onSuccess: () => {
                    //         ps_loading_circle_dialog.value.closeModal();
                    //         //
                    //     },
                    //     onError: () => {
                    //         ps_loading_circle_dialog.value.closeModal();
                    //     },
                    // });

                },
                false,
                v);
        }


        let date_filters = reactive([

            { 'id': 1, 'name':'Today' },
            { 'id': 2, 'name':'Yesterday' },
            { 'id': 3, 'name':'Last 7 days' },
            { 'id': 4, 'name':'Last 14 days' },
            { 'id': 5, 'name':'Last 30 days' },
            { 'id': 6, 'name':'Last 60 days' },
            { 'id': 7, 'name':'Custom' },
        ])

        const colFilterOptions = reactive([
            {
                label: "Total Users",
                key: "total_users",
                hidden: false
            },
            {
                label: "Total Buyer",
                key: "total_buyer",
                hidden: false
            },
            {
                label: "Total Seller",
                key: "total_seller",
                hidden: false
            },
            {
                label: "Successful Deal Count",
                key: "successful_deal_count",
                hidden: false
            },
            {
                label: "Total Sold Out Items",
                key: "total_sold_out_items",
                hidden: false
            },
            {
                label: "Total Slow Moving Items",
                key: "total_slow_moving_items",
                hidden: false
            },
            {
                label: "Most Engaging Products",
                key: "most_engaging_products",
                hidden: false
            },
            {
                label: "Most Engaging Categories",
                key: "most_engaging_categories",
                hidden: false
            },
            {
                label: "Most Purchased Packages",
                key: "most_purchased_packages",
                hidden: false
            },
            {
                label: "User Activities",
                key: "user_activities",
                hidden: false
            },
            {
                label: "Revenue from Promotion",
                key: "revenue_from_promotion",
                hidden: false
            },
            {
                label: "Revenue from Packages",
                key: "revenue_from_packages",
                hidden: false
            },
            {
                label: "Highest Rated Seller",
                key: "highest_rated_seller",
                hidden: false
            },
            {
                label: "Highest Rated Buyer",
                key: "highest_rated_buyer",
                hidden: false
            },
            {
                label: "Recent Registered Users",
                key: "recent_registered_users",
                hidden: false
            },
        ]);

        function getData(data, colName){
            if(data.length>0){
                let arr = [];
                for (let i = 0; i < data.length; i++) {
                    arr[i] = data[i][colName];
                }
                return arr;
            }else{
                // if data not existed, return demo data
                return [80, 30, 50, 70, 20];
            }
        }

        function getLabel(data, colName){
            if(data.length>0){
                let arr = [];
                for (let i = 0; i < data.length; i++) {
                    arr[i] = data[i][colName];
                }
                return arr;
            }else{
                // if data not existed, return demo data
                return ['label1', 'label2', 'label3', 'label4', 'label5'];
            }
        }

        function getDataForDate(data, colName, dateCol, last){
            let arr = new Array(last);
            if(data.length > 0){
                const newString = props.endDate.replace(' ', 'T');
                var currentDate = moment(new Date(newString), 'YYYY-MM-DD');
                for(var i = 0; i < data.length; i++) {
                    arr[last - ((currentDate.diff(data[i][dateCol], 'days')) + 1)] = data[i][colName]
                }
                return arr;
            }else{
                // if data not existed, return demo data
                return [80, 60, 50, 70, 20, 40, 60];
            }
        }

        function getDataForPackage(data, colName){
            if(data.length>0)          {
                let arr = new Array(props.packages.length);
                var packages = props.packages
                var packagesId = []
                packagesId = getData(props.packages, 'id')
                for (let i = 0; i < packages.length; i++) {
                    for (let j = 0; j < data.length; j++) {
                        if(packages[i].id == data[j].package_id){
                            var index = packagesId.findIndex(pkg => pkg == data[j].package_id)
                            if(index>=0){
                                arr[index] = data[j][colName]
                            }
                        }
                    }
                }
                return arr;
            }else{
                let count = props.packages.length
                let arr = new Array(count)
                if(count >0){
                    for(let i=0;i<count;i++){
                        arr[i] =  Math.floor(Math.random()*100)
                    }
                    return arr
                }else{
                    return [100, 500, 300,200,700]
                }
            }

        }

        // Most Engaging Products
        const productChart = ref('Pie Chart');
        const productDoughnutChartData = {
            labels: getLabel(props.most_engaging_products, 'title'),
            datasets: [
                {
                    data: getData(props.most_engaging_products, 'item_touch_count'),
                    backgroundColor: ["#6366F1","#EF4444","#FBBF24","#10B981", "#EB4898"],
                }
            ],
        };
        const productDoughnutChartOptions = {
            responsive: true,
            cutout: screen.width < 800 && screen.width > 640 ? 40 : 60,
            plugins: {
                legend: {
                    display: true,
                    position: 'right',
                    labels: {
                        boxWidth: 13,
                        padding: 15,
                        // color: '#1F2937',
                    },

                },

            },
            maintainAspectRatio: false,
        };
        const productDoughnutChartPlugin = {
            id: "idOfPlugin-doughnut-chart",
            beforeDraw: function (chart) {
                var width = chart.width,
                height = chart.height,
                ctx = chart.ctx;
                ctx.restore();
                var fontSize = (height / 150).toFixed(2);
                // ctx.font = `${fontSize}em`
                ctx.font = fontSize + "em Inter";
                ctx.fillStyle = "#4771FA";
                ctx.textBaseline = "middle";

                var text = "",
                textX = Math.round((width - ctx.measureText(text).width) / 1.9),
                textY = height / 2.4;

                ctx.fillText(text, textX, textY);
                ctx.save();
            },
        };
        const productColumnChartOptions = {
            responsive: true,
            scales: {
                y: {
                    grid: {
                        color: darkGrid,
                        borderColor: darkGrid
                    }
                },
                x: {
                    grid: {
                        color: darkGrid,
                        borderColor: darkGrid
                    }
                }
            },
            plugins: {
                legend: {
                    display: false

                }
            }
        };
        const productColumnChartData = {
            labels: getLabel(props.most_engaging_products, 'title'),
            datasets: [
                {
                    backgroundColor: ["#6366F1","#EF4444","#FBBF24","#10B981", "#EB4898"],
                    data: getData(props.most_engaging_products, 'item_touch_count'),
                    barThickness: 20,
                    maxBarThickness: 20,
                }
            ]
        };
        const productBarChartOptions = {
            responsive: true,
            indexAxis: 'y', //appear horizontal bar
            scales: {
                yAxes: {
                    display: true
                },
            },
            plugins: {
                legend: {

                    display: false
                }
            }
        };
        const productBarChartData = {
            labels: getLabel(props.most_engaging_products, 'title'),
            datasets: [{
                axis: 'y',
                data: getData(props.most_engaging_products, 'item_touch_count'),
                fill: false,
                backgroundColor: ["#6366F1","#EF4444","#FBBF24","#10B981", "#EB4898"],
                borderWidth: 1,
                barThickness: 20,
                maxBarThickness: 20,
            }]
        };

        // Most Engaging Catgories
        const categoryChart = ref('Column Chart');
        const categoryDoughnutChartData = {
            labels: getLabel(props.most_engaging_categories, 'name'),
            datasets: [
                {
                    data: getData(props.most_engaging_categories, 'category_touch_count'),
                    backgroundColor: ["#6366F1","#EF4444","#FBBF24","#10B981", "#EB4898"],
                }
            ],
        };
        const categoryDoughnutChartOptions = {
            responsive: true,
            cutout: screen.width < 800 && screen.width > 640 ? 40 : 60,
            plugins: {
                legend: {
                    display: true,
                    position: 'right',

                    labels: {
                        boxWidth: 13,
                        padding: 15,
                        color: '#1F2937'
                    },
                },

            },
            maintainAspectRatio: false,
        };
        const categoryDoughnutChartPlugin = {
            id: "idOfPlugin-doughnut-chart",
            beforeDraw: function (chart) {
                var width = chart.width,
                    height = chart.height,
                    ctx = chart.ctx;
                ctx.restore();
                var fontSize = (height / 150).toFixed(2);
                // ctx.font = `${fontSize}em`
                ctx.font = fontSize + "em Inter";
                ctx.fillStyle = "#4771FA";
                ctx.textBaseline = "middle";

                var text = "",
                    textX = Math.round((width - ctx.measureText(text).width) / 1.9),
                    textY = height / 2.4;

                ctx.fillText(text, textX, textY);
                ctx.save();
            },
        };
        const categoryColumnChartOptions = {
            responsive: true,
            scales: {
                y: {
                    grid: {
                        color: darkGrid,
                        borderColor: darkGrid
                    }
                },
                x: {
                    grid: {
                        color: darkGrid,
                        borderColor: darkGrid
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        };
        const categoryColumnChartData = {
            labels: getLabel(props.most_engaging_categories, 'name'),
            datasets: [
                {
                    backgroundColor: ["#6366F1","#EF4444","#FBBF24","#10B981", "#EB4898"],
                    fill: false,
                    data: getData(props.most_engaging_categories, 'category_touch_count'),
                    barThickness: 20,
                    maxBarThickness: 20,
                }
            ]
        };
        const categoryBarChartOptions = {
            responsive: true,
            indexAxis: 'y', //appear horizontal bar
            scales: {
                yAxes: {
                    display: true
                },
            },
            plugins: {
                legend: {
                    display: false
                },
            }
        };
        const categoryBarChartData = {
            labels: getLabel(props.most_engaging_categories, 'name'),
            datasets: [{
                axis: 'y',
                data: getData(props.most_engaging_categories, 'category_touch_count'),
                fill: false,
                backgroundColor: ["#6366F1","#EF4444","#FBBF24","#10B981", "#EB4898"],
                borderWidth: 1,
                barThickness: 20,
                maxBarThickness: 20,
            }]
        };

        // Most Purchased Packages
        const packageChart = ref('Column Chart');
        const packageColumnChartOptions = {
            responsive: true,
            xAxis: {
                color: 'red'
            },
            scales: {
                y: {
                    grid: {
                        color: darkGrid
                    }
                },
                x: {
                    grid: {
                        color: darkGrid
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        };
        const packageBarChartOptions = {
            responsive: true,
            scales: {
                xAxes: [{
                    ticks: {
                        beginAtZero: true,

                    }
                }]
            },
            indexAxis: 'y', //appear horizontal bar
            scales: {
                yAxes: {
                    display: true
                },
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        };


        const packageBarChartData = {
            labels: getLabel(props.packages, 'value'),
            datasets: [
                {
                    axis: 'y',
                    data: getDataForPackage(props.most_purchased_packages, 'total'),
                    fill: false,
                    backgroundColor: ["#0263FF","#FF7723","#8E30FF"],
                    borderWidth: 1,
                    barThickness: 20,
                    maxBarThickness: 20
                }
            ]
        };

        const packageColumnChartData = {
            labels: getLabel(props.packages, 'value'),
            datasets: [
                {
                    backgroundColor: ["#0263FF","#FF7723","#8E30FF"],
                    data: getDataForPackage(props.most_purchased_packages, 'total'),
                    barThickness: 20,
                    maxBarThickness: 20
                }
            ]
        };

        // User Activities (Vertical Bar Chart)
        const activityVerticalChartData = {

            labels: ["1", "2", "3", "4", "5","6", "7", "8", "9", "10","11","12"],
            datasets: [
                {
                label: "Hide label",
                backgroundColor: "#F59E0B",
                data: [12,10,8,11,10,8,11,10,8,11,5,12],
                barThickness: 5,
                maxBarThickness: 20,
                barPercentage: 0.5,
                },
            ]
        };
        const activityVerticalChartOptions = {
            responsive: true,
            title: {
                display: true,
                text: 'Projected Cumulative Savings',
                fontSize: 18
            },
            scales: {
                yAxes: {
                    display: false,
                },
                x: {
                    grid: {
                        display: false,//hide x grid line
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }

        // Revenue from Promotion
        const promotionChart = ref('Line Chart');
        const promotionLineChartData = {
            labels: getDate(7, 'DD MMM', 'days'),
            datasets: [
                {
                    data: getDataForDate(props.revenue_from_promotion, 'total', 'date', 7),
                    backgroundColor: "rgba(99,102,241,0.2)",
                    borderColor: "#6366F1",
                    pointBackgroundColor: "#fff",
                    fill: true
                },
            ],
        };
        const promotionLineChartOptions = {
            responsive: true,
            scales: {
                y: {
                    grid: {
                        color: darkGrid
                    }
                },
                x: {
                    grid: {
                        color: darkGrid
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            },
            maintainAspectRatio: false,
        };
        const promotionColumnChartOptions = {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            }
        };
        const promotionColumnChartData = {
            labels: getDate(7, 'DD MMM', 'days'),
            datasets: [
                {
                    backgroundColor: ['#6366F1'],
                    data: getDataForDate(props.revenue_from_promotion, 'total', 'date', 7),
                    barThickness: 20,
                    maxBarThickness: 20,
                }
            ]
        };

        // Revenue from Package
        const revenuePackageChart = ref('Line Chart');
        const revenuePackageLineChartData = {
            labels: getDate(7, 'DD MMM', 'days'),
            datasets: [
                {
                    data: getDataForDate(props.revenue_from_packages, 'total', 'date', 7),
                    backgroundColor: "rgba(99,102,241,0.2)",
                    borderColor: "#6366F1",
                    pointBackgroundColor: "#fff",
                    fill: true
                },
            ],
        };

        function getDate(last, format, calculation){
            var dateArray = [];
            if(props.latest_refreshed_date_range.length < 0){
                var currentDays = moment(new Date());
                var lastDays = moment(new Date()).subtract(last-1, calculation);

                while (lastDays <= currentDays) {
                    dateArray.push( moment(lastDays).format(format) )
                    lastDays = moment(lastDays).add(1, calculation);
                }
            } else {
                dateArray = props.latest_refreshed_date_range;
            }
            return dateArray
        }

        const revenuePackageLineChartOptions = {
            responsive: true,
            scales: {
                y: {
                    grid: {
                        color: darkGrid
                    }
                },
                x: {
                    grid: {
                        color: darkGrid
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            },
            maintainAspectRatio: false,
        };
        const revenuePackageColumnChartOptions = {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            }
        };
        const revenuePackageColumnChartData = {
            labels: getDate(7, 'DD MMM', 'days'),
            datasets: [
                {
                    backgroundColor: ['#6366F1'],
                    data:  getDataForDate(props.revenue_from_packages, 'total', 'date', 7),
                    barThickness: 20,
                    maxBarThickness: 20,
                }
            ]
        };

        const recentRegisteredUserColumns = reactive([
            {
                label: trans('core__be_user_name'),
                field: "name",
                type: 'String',
                action: 'Action',
                sort: false
            },
            {
                label: trans('core__be_user_email'),
                field: "email",
                type: 'String',
                action: 'Action',
                sort: false
            },
            {
                label: trans('core__be_user_phone'),
                field: "user_phone",
                type: 'String',
                action: 'Action',
                sort: false
            },
            {
                label: trans('core__be_registered_date'),
                field: "added_date",
                type:'Timestamp',
                action: 'Action',
                sort: false
            },
            {
                label: trans('core__be_detail_label'),
                field: "detail",
                type: 'Action',
                sort: false
            },
        ]);

        const highestRatedSellerColumns = reactive([
            {
                label: trans('core__be_user_name'),
                field: "name",
                type: 'String',
                action: 'Action',
                sort: false
            },
            {
                label: trans('core__be_user_email'),
                field: "email",
                type: 'String',
                action: 'Action',
                sort: false
            },
            {
                label: trans('core__be_user_phone'),
                field: "user_phone",
                type: 'String',
                action: 'Action',
                sort: false
            },
            {
                label: trans('core__be_user_rating'),
                field: "overall_rating",
                type:'Integer',
                action: 'Action',
                sort: false
            },
            {
                label: trans('core__be_detail_label'),
                field: "detail",
                type: 'Action',
                sort: false
            },
        ]);

        const highestRatedBuyerColumns = reactive([
            {
                label: trans('core__be_user_name'),
                field: "name",
                type: 'String',
                action: 'Action',
                sort: false
            },
            {
                label: trans('core__be_user_email'),
                field: "email",
                type: 'String',
                action: 'Action',
                sort: false
            },
            {
                label: trans('core__be_user_phone'),
                field: "user_phone",
                type: 'String',
                action: 'Action',
                sort: false
            },
            {
                label: trans('core__be_user_rating'),
                field: "overall_rating",
                type:'Integer',
                action: 'Action',
                sort: false
            },
            {
                label: trans('core__be_detail_label'),
                field: "detail",
                type: 'Action',
                sort: false
            },
        ]);

        function handleHighestRatedBuyerSearchingSorting(page = null, row = null) {
            router.get(route('admin.dashboard.search'),
                {
                    highest_rated_buyer_sort_field: highest_rated_buyer_sort_field.value,
                    highest_rated_buyer_sort_order: highest_rated_buyer_sort_order.value,
                    page: page ?? props.highest_rated_buyer.meta.current_page,
                    row: row ?? props.highest_rated_buyer.meta.per_page,
                    form: form
                },
                {
                    preserveScroll: true,
                    preserveState: true,
                })
        }

        function handleHighestRatedBuyerSorting(value) {
            highest_rated_buyer_sort_field.value = value.field
            highest_rated_buyer_sort_order.value = value.sort_order
            handleHighestRatedBuyerSearchingSorting()
        }

        function handleHighestRatedSellerSearchingSorting(page = null, row = null) {
            router.get(route('admin.dashboard.search'),
                {
                    highest_rated_seller_sort_field: highest_rated_seller_sort_field.value,
                    highest_rated_seller_sort_order: highest_rated_seller_sort_order.value,
                    page: page ?? props.highest_rated_seller.meta.current_page,
                    row: row ?? props.highest_rated_seller.meta.per_page,
                    form: form
                },
                {
                    preserveScroll: true,
                    preserveState: true,
                })
        }

        function handleHighestRatedSellerSorting(value) {
            highest_rated_seller_sort_field.value = value.field
            highest_rated_seller_sort_order.value = value.sort_order
            handleHighestRatedSellerSearchingSorting()
        }

        function handleRecentRegisteredUserSearchingSorting(page = null, row = null) {
            router.get(route('admin.dashboard.search'),
                {
                    recent_registered_user_sort_field: recent_registered_user_sort_field.value,
                    recent_registered_user_sort_order: recent_registered_user_sort_order.value,
                    page: page ?? props.recent_registered_users.meta.current_page,
                    row: row ?? props.recent_registered_users.meta.per_page,
                    form: form
                },
                {
                    preserveScroll: true,
                    preserveState: true,
                })
        }

        function handleRecentRegisteredUserSorting(value) {
            recent_registered_user_sort_field.value = value.field
            recent_registered_user_sort_order.value = value.sort_order
            handleRecentRegisteredUserSearchingSorting()
        }

        function handleRefresh($data){
            this.$inertia.get(route('admin.refresh'), $data,{
                onBefore: () => {
                    ps_loading_circle_dialog.value.openModal(trans('refreshing_for_dashboard_data'), trans('pls_wait_when_we_are_pregetting'));
                },
                onSuccess: (res) => {
                    ps_loading_circle_dialog.value.closeModal();
                    isClickOut.value = true;
                    // if (props.hasError === 0){
                    //     close();
                    // }
                    //
                },
                onError: () => {
                    ps_loading_circle_dialog.value.closeModal();

                },
            });
        }

        function openDownloadModel(){
            ps_success_dialog.value.openModal(
                trans('core__download_project_title'),
                trans('core__download_project_dialog'),
                trans('core__download_project_button'),
                () => {
                    ps_loading_circle_dialog.value.openModal(trans('core__downloading_title'), trans('core__downloading_message'));
                    router.post(route('admin.dashboard.downloadAndImport'),{
                        onBefore:() => {
                            ps_loading_circle_dialog.value.openModal(trans('core__downloading_title'), trans('core__downloading_message'));
                        },
                        onSuccess:() => {
                            ps_loading_circle_dialog.value.closeModal();

                        },
                        onError:() => {
                             ps_loading_circle_dialog.value.closeModal();
                        }
                    });
                },
                true
            );
        }

        function successDialogModal(){
             ps_loading_circle_dialog.value.closeModal();
             ps_success_dialog.value.openModal(
                            trans('core__success_import_title'),
                            trans('core__success_import_dialog'),
                            trans('core__be_btn_reload'),
                            () => {
                                window.location.reload();
                            },
                                true
                            );
        }

        function errorDialogModal(){
             ps_loading_circle_dialog.value.closeModal();
             ps_error_dialog.value.openModal(
                                trans('core__error_download_title'),
                                trans('core__error_download_dialog'),
                                trans('core__success_upgrade_confirm'),
                                () => {
                                    router.get(route('admin.index'));
                                }
                            )
        }

        function successAutoSync(){
            ps_success_dialog.value.openModal(
                            trans('core__success_upgrade_title'),
                            trans('core__success_upgrade_dialog'),
                            trans('core__success_upgrade_confirm'),
                            () => {
                                // router.get(route('admin.index'));
                            },
                            true
                        );
        }

        return {
            ps_error_dialog,
            successAutoSync,
            successDialogModal,
            errorDialogModal,
            handleRefresh,
            handleHighestRatedBuyerSearchingSorting,
            handleHighestRatedBuyerSorting,
            handleHighestRatedSellerSorting,
            handleRecentRegisteredUserSorting,
            colFilterOptions,
            perPageOptions, date_filters,
            productChart, productDoughnutChartPlugin, productDoughnutChartData, productDoughnutChartOptions, productColumnChartOptions, productColumnChartData, productBarChartOptions, productBarChartData,
            categoryChart, categoryDoughnutChartPlugin, categoryDoughnutChartData, categoryDoughnutChartOptions, categoryColumnChartOptions, categoryColumnChartData, categoryBarChartOptions, categoryBarChartData,
            packageChart, packageColumnChartOptions, packageBarChartOptions, packageBarChartData, packageColumnChartData,
            activityVerticalChartData, activityVerticalChartOptions,
            promotionChart, promotionLineChartData, promotionLineChartOptions, promotionColumnChartData, promotionColumnChartOptions,
            revenuePackageChart, revenuePackageLineChartData, revenuePackageLineChartOptions, revenuePackageColumnChartData, revenuePackageColumnChartOptions,
            recentRegisteredUserColumns,
            highestRatedSellerColumns,
            highestRatedBuyerColumns,
            ps_license_activate_modal,
            openLicenseActivateModal,
            ps_success_dialog,
            openSuccessDialog,
            remindToImportDialog,
            ps_confirm_dialog_with_icon,
            form,
            ps_loading_circle_dialog,
            getDate,
            openSyncSetupModal,
            ps_sync_setup_modal,
            openDownloadModel
        }
    },
    methods: {
        handleFilter(){
            this.$inertia.get(route('admin.dashboard.search'), this.form);
        },
        handleBuyerList() {
            this.$inertia.get(route('buyer_report.index'));
        },
        handleBuyerDetail(id) {
            this.$inertia.get(route('buyer_report.show', id));
        },

        handleSellerList() {
            this.$inertia.get(route('seller_report.index'));
        },
        handleSellerDetail(id) {
            this.$inertia.get(route('seller_report.show', id));
        },

        handleUserList() {
            this.$inertia.get(route('user_report.index'));
        },
        handleUserDetail(id) {
            this.$inertia.get(route('user_report.show', id));
        },

        handleDailyActiveUserList() {
            this.$inertia.get(route('daily_active_user_report.index'));
        },
        handleItemList() {
            this.$inertia.get(route('item_report.index'));
        },
        handleSuccessfulDealCountList() {
            this.$inertia.get(route('successful_deal_count_report.index'));
        },
        handleSlowMovingItemList() {
            this.$inertia.get(route('slow_moving_item_report.index'));
        },
        handleSoldOutItemList() {
            this.$inertia.get(route('sold_out_item_report.index'));
        },
        handleCategoryList() {
            this.$inertia.get(route('category_report.index'));
        },
        handlePromotionList() {
            this.$inertia.get(route('paid_item.index'));
        },
        handlePackageList() {
            this.$inertia.get(route('package_report.index'));
        },
    },
    created() {
        this.form.filter = this.filter.filter
        this.form.date = this.filter.date
    },
    mounted() {
        window.onerror = function(e){
            // console.log(e);

                // document.location.reload()
                // return true;


        }
        // console.log(this.getDate(7, 'DD MMM', 'days'));
        if(!this.project.ps_license_code  && this.hasError !== 0){
        //     localStorage.firstTimeInstall = 0;
            this.openLicenseActivateModal()
        }
        if(this.activateStatus == 'false'  && this.hasError !== 0){
        //     localStorage.firstTimeInstall = 0;
            this.openLicenseActivateModal()
        }
        if(this.activateStatus == 'true' && this.hasError === 0){
            this.openDownloadModel()
        }
        // if (this.activatedFileName && this.hasError !== 0){
        //     if (!this.activatedFileName.is_imported){
        //         this.remindToImportDialog(this.activatedFileName.file_name)
        //     }
        // }

        if(this.downloadStatus !=  null && this.downloadStatus.status == 'success'){
            this.successDialogModal();
        }
        if(this.downloadStatus !=  null && this.downloadStatus.status == 'error'){
            this.errorDialogModal();
        }


        if(this.activatedFileName && this.activatedFileName.is_imported && this.project.ps_license_code != "" && this.project.first_time_sync == 0){
            this.openSyncSetupModal();
        }

        if(this.autoSyncStatus == "error"){
            this.openSyncSetupModal(this.autoSyncMessage);
        }

        if(this.autoSyncStatus == "success"){
            this.successAutoSync();
        }


    },
    beforeUpdate() {
        if(!this.project.ps_license_code && this.hasError !== 0){
            this.openLicenseActivateModal()
        }
        if(this.activateStatus == 'false'  && this.hasError !== 0){
        //     localStorage.firstTimeInstall = 0;
            this.openLicenseActivateModal()
        }
        if(this.activateStatus == 'true' && this.hasError === 0){
            this.openDownloadModel()
        }
        if (this.activatedFileName){
            if (!this.activatedFileName.is_imported){
                this.openLicenseActivateModal()
            }
        }
        if(this.downloadStatus !==  null && this.downloadStatus.status == 'success'){
            this.successDialogModal();
        }
        if(this.downloadStatus !==  null && this.downloadStatus.status == 'error'){
            this.errorDialogModal();
        }
        if(this.autoSyncStatus == "error"){
            this.openSyncSetupModal(this.autoSyncMessage);
        }

        if(this.autoSyncStatus == "success"){
            this.successAutoSync();
        }


        if(this.activatedFileName && this.activatedFileName.is_imported && this.project.ps_license_code != "" && this.project.first_time_sync == 0){
            this.openSyncSetupModal();
        }
    }
});
</script>

<style scoped>


</style>
