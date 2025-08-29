<template>
    <Head :title="$t('item_module')" />
    <ps-layout>
        <!-- breadcrumb start -->
        <ps-breadcrumb-2 :items="breadcrumb" class="mb-5 sm:mb-6 lg:mb-8" />
        <!-- breadcrumb end -->
        <!-- {{ categoryStore.itemList.data }} -->
        <!-- alert banner start -->
        <ps-banner-icon v-if="visible" :visible="visible"
            :theme="(status.flag) == 'danger' ? 'bg-red-500' : (status.flag) == 'warning' ? 'bg-yellow-500' : 'bg-green-500'"
            :iconName="(status.flag) == 'danger' ? 'close-circle' : (status.flag) == 'warning' ? 'alert-triangle' : 'rightalert'"
            class="text-white mb-5 sm:mb-6 lg:mb-8" iconColor="white">{{ status.msg }}</ps-banner-icon>
        <!-- alert banner end -->

        <!-- data table start -->
        <ps-table2 :eye_filter="false" :customizeDetails="customizeDetails" :row="row" :search="search" :object="this.items"
            :colFilterOptions="colFilterOptions" :columns="columns" :sort_field="sort_field" :sort_order="sort_order"
            :globalSearchPlaceholder="$t('core__be_search')" @FilterOptionshandle="FilterOptionshandle"
            @handleSort="handleSorting" @handleSearch="handleSearching" @handleRow="handleRow" :searchable="showFilter">
            <!-- <template #button>
                <ps-button v-if="can.createItem" @click="handleCreate()" rounded="rounded-lg" type="button"
                    class="flex flex-wrap items-center"> <ps-icon name="plus" class="me-1 font-semibold" /> {{
                        $t('core__be_add_item') }}</ps-button>
            </template>
            <template #responsive_button>
                <ps-button v-if="can.createItem" @click="handleCreate()" rounded="rounded-lg" type="button"
                    class="flex flex-wrap items-center"> <ps-icon name="plus" class="me-1 font-semibold" /> {{
                        $t('core__be_add_item') }}</ps-button>
            </template> -->
            <!-- category filter start -->
            <template #tags>
                <div class="mx-5 mt-6 mb-6">
                    <div class="w-full">
                        <splide class="px-4" :options="options" :has-track="false">
                            <splide-track >
                                <!-- <splide-slide >
                                    <ps-text-button :class="categoryId == '' ? 'bg-primary-100 rounded-t-md': ''" @click="clickCategory('')" class="w-full h-full p-4 cursor-pointer flex flex-row items-center justify-center">
                                        <ps-label>{{$t('general')}} </ps-label>
                                        <ps-label class="ms-1 text-xs px-1 py-0.5 rounded-xl bg-blue-500" textColor="text-white">{{generalCategroies}}</ps-label>
                                    </ps-text-button>
                                </splide-slide> -->
                                <splide-slide v-for="category in categoriesWithCount" :key="category.id">
                                    <ps-text-button :class="categoryId == category.id ? 'bg-primary-500 rounded-md': 'bg-gray-200/90 rounded-md'"  @click="clickCategory(category.id)"  class="flex flex-row items-center justify-center w-auto h-full p-4 mx-1 cursor-pointer">
                                        <ps-label :textColor="categoryId == category.id ? 'text-white': 'text-black'"> {{category.name}}</ps-label>
                                        <ps-label v-if="category?.count != '0'"  class="ms-1 text-xs px-1 py-0.5 bg-white rounded-full min-w-[20px]" textColor="text-black">{{category.count}}</ps-label>
                                    </ps-text-button>
                                </splide-slide>
                            </splide-track>
                            <div class="splide__arrows splide__arrows--ltr">
                                <button
                                    class="w-10 h-10 p-2 rounded shadow-sm bg-secondary-50 dark:bg-secondary-800 arrow splide__arrow--prev"
                                    type="button"
                                    aria-label="Previous slide"
                                    aria-controls="splide01-track"
                                >
                                    <ps-icon textColor="dark:text-secondary-200" name="doubleArrowRight"/>
                                </button>
                                <button
                                    class="w-10 h-10 p-2 rounded shadow-sm bg-secondary-50 dark:bg-secondary-800 arrow splide__arrow--next"
                                    type="button"
                                    aria-label="Next slide"
                                    aria-controls="splide01-track"
                                >
                                    <ps-icon textColor="dark:text-secondary-200" name="doubleArrowRight" />
                                </button>
                            </div>
                        </splide>
                    </div>
                </div>
                <!-- category filter end -->
            </template>
            <template #searchRight>
                <ps-text-button v-if="showFilter" @click="handleClearFilter()"
                    class="flex text-sm items-center text-red-400 dark:text-red-400" padding="py-1 px-4">
                    <ps-icon theme="dark:text-red-400" name="cross" class="me-3" />
                    {{ $t('core__be_clear_filter') }}
                </ps-text-button>

                <ps-icon-button :colors="!showFilter ? '' : 'bg-primary-500 text-white dark:text-secondary-800'" focus=""
                    padding="py-1 px-4" hover="hover:bg-primary-500 hover:text-white"
                    :border="!showFilter ? ' border border-secondary-200' : 'border border-primary-500'" leftIcon="filter"
                    @click="showFilter = !showFilter">{{ $t('core__be_filter') }}</ps-icon-button>

            </template>

            <template #Filter>
                <!-- category filter -->
                <ps-dropdown @on-click="loadCategory" align="" class=" h-10"
                    v-if="!colFilterOptions.filter(option => option.key == 'category_id@@name')[0]?.hidden">
                    <template #select>
                        <ps-dropdown-select :placeholder="$t('core__be_category')"
                            :border="(selected_cat !== '' && selected_cat !== 'all') ? 'border border-indigo-500/100' : 'border border-1 border-secondary-200'"
                            :selectedValue="(selected_cat == '' || selected_cat == 'all') ? '' : selectedCategory.name" />
                    </template>
                    <template #list>
                        <div class="rounded-md shadow-xs w-56 ">
                            <div class="pt-2 z-30  ">
                                <div v-if="categoryStore.itemList.data == null">
                                    <ps-label class='p-2 flex' @click="loadCategory"> {{ $t("list__loading") }} </ps-label>
                                </div>
                                <div v-else>
                                    <!-- <div class="w-56 flex py-2 px-2 hover:bg-primary-000 dark:hover:bg-primary-900 cursor-pointer items-center"
                                        @click="handleCategoryfilter('all')">
                                        <ps-label class="text-gray-200 ms-2 ">{{ $t('core__be_select_all') }}</ps-label>
                                    </div> -->
                                    <!-- {{ categoryStore.itemList.data }} -->
                                    <div v-for="category in categoryStore.itemList.data" :key="category.id"
                                        class="w-56 flex py-2 px-2 hover:bg-primary-000 dark:hover:bg-primary-900 cursor-pointer items-center"
                                        @click="handleCategoryfilter(category.catId)">
                                        <ps-label class="ms-2" :class="category.catId == selected_cat ? ' font-bold' : ''">
                                            {{ category.catName }} </ps-label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                    <template #loadmore>
                        <!-- <div @click="dropdownClick(true)" v-if="category_loadmore_visible"
                            class="w-56 flex py-2 px-2 hover:bg-primary-000 dark:hover:bg-primary-900 cursor-pointer items-center">
                            <div class="flex flex-row items-center justify-between">
                                <ps-label class="ms-2 ">
                                    {{ is_loading ? $t('core__be_loading') : $t('core__be_load_more') }}
                                </ps-label>
                                <ps-loading v-if="is_loading"
                                    theme="border-2 border-t-2 border-text-8 border-t-primary-500 justify-end"
                                    loadingSize="h-5 w-5" />
                            </div>
                        </div> -->
                        <div class="mb-2 w-56">
                            <div v-if="categoryStore.itemList.data != null
                                && categoryStore.loading.value == true" class='mt-4 ms-4 flex'>
                                <ps-label-caption>{{ $t("core__be_load_more") }} </ps-label-caption>
                            </div>
                            <ps-label class="flex mt-4 ms-4 mb-2 underline font-bold cursor-pointer"
                                @click="loadCategory()"> {{ $t("core__be_load_more") }} </ps-label>
                        </div>
                    </template>
                    <template #filter>
                        <!-- <ps-input type="text" :placeholder="$t('core__be_category')" v-model:value="catSearch" class=""/> -->
                        <div class="mt-1 mx-1">
                            <ps-input-with-right-icon class="w-full h-10" rounded="rounded-lg" v-model:value="catSearch"
                                :placeholder="$t('core__be_search')">
                                <template #icon>
                                    <ps-icon name="search" class='cursor-pointer' />
                                </template>
                            </ps-input-with-right-icon>
                        </div>

                    </template>
                </ps-dropdown>

                <!-- subcategory filter -->
                <ps-dropdown @on-click="subCategoryDropdownClick" class=" h-10"
                    v-if="$page.props.isSubCategoryOn == '1' && !colFilterOptions.filter(option => option.key == 'subcategory_id@@name')[0]?.hidden">
                    <template #select>
                        <ps-dropdown-select :placeholder="$t('core__be_subcategory')"
                            :border="(selected_sub_cat !== '' && selected_sub_cat !== 'all') ? 'border border-indigo-500/100' : 'border border-1 border-secondary-200'"
                            :selectedValue="(selected_sub_cat == '' || selected_sub_cat == 'all') ? '' : selectedSubcategory.name" />
                    </template>
                    <template #list>
                        <div class="rounded-md shadow-xs w-56 ">
                            <div class="pt-2 z-30  ">
                                <div class="w-56 flex py-2 px-2 hover:bg-primary-000 dark:hover:bg-primary-900 cursor-pointer items-center"
                                    @click="handleSubcategoryfilter('all')">
                                    <ps-label class="text-gray-200 ms-2">{{ $t('core__be_select_all') }}</ps-label>
                                </div>
                                <!-- <div v-if="selected_cat == 'all' || selected_cat==''"> -->
                                <div v-for="subcategory in subCategories" :key="subcategory.id"
                                    class="w-56 flex py-2 px-2 hover:bg-primary-000 dark:hover:bg-primary-900 cursor-pointer items-center"
                                    @click="handleSubcategoryfilter(subcategory.id)">
                                    <ps-label class="ms-2" :class="subcategory.id == selected_sub_cat ? ' font-bold' : ''">
                                        {{ subcategory.name }} </ps-label>
                                </div>
                                <!-- </div> -->
                                <!-- <div v-else>
                                    <div v-for="subcategory in subCategories.filter((subCat) => subCat.category_id == selected_cat)" :key="subcategory.id"
                                        class="w-56 flex py-2 px-2 hover:bg-primary-000 dark:hover:bg-primary-900 cursor-pointer items-center"
                                        @click="handleSubcategoryfilter(subcategory.id)">
                                        <ps-label class="ms-2"
                                            :class="subcategory.id == selected_sub_cat ? ' font-bold' : ''">
                                            {{ subcategory.name }} </ps-label>
                                    </div>
                                </div> -->
                            </div>
                        </div>
                    </template>
                    <template #loadmore>
                        <div @click="subCategoryDropdownClick(true)" v-if="subCategory_loadmore_visible"
                            class="w-56 flex py-2 px-2 hover:bg-primary-000 dark:hover:bg-primary-900 cursor-pointer items-center">
                            <!-- <ps-label v-if="is_loading"  class="ms-2 italic underline">
                                {{$t('core__be_loading')}}
                            </ps-label> -->
                            <div class="flex justify-between  rtl:space-x-reverse space-x-4">

                                <ps-label class="ms-2 ">
                                    {{ is_loading ? $t('core__be_loading') : $t('core__be_load_more') }}
                                </ps-label>

                                <ps-loading v-if="is_loading"
                                    theme="border-2 border-t-2 border-text-8 border-t-primary-500 justify-end"
                                    loadingSize="h-5 w-5" />

                            </div>
                        </div>
                    </template>
                    <template #filter>
                        <div class="mt-1 mx-1">
                            <ps-input-with-right-icon class="w-full h-10" rounded="rounded-lg" v-model:value="subCatSearch"
                                :placeholder="$t('core__be_search')">
                                <template #icon>
                                    <ps-icon name="search" class='cursor-pointer' />
                                </template>
                            </ps-input-with-right-icon>
                        </div>

                    </template>
                </ps-dropdown>

                <!-- location city filter -->
                <ps-dropdown @on-click="cityDropdownClick" align="" class=" h-10"
                    v-if="!colFilterOptions.filter(option => option.key == 'location_city_id@@name')[0]?.hidden">
                    <template #select>
                        <ps-dropdown-select :placeholder="$t('core__be_city')"
                            :border="(selected_city !== '' && selected_city !== 'all') ? 'border border-indigo-500/100' : 'border border-1 border-secondary-200'"
                            :selectedValue="(selected_city == '' || selected_city == 'all') ? '' : selectedCity.name" />
                    </template>
                    <template #list>
                        <div class="rounded-md shadow-xs w-56 ">
                            <div class="pt-2 z-30  ">
                                <div class="w-56 flex py-2 px-2 hover:bg-primary-000 dark:hover:bg-primary-900 cursor-pointer items-center"
                                    @click="handleCityfilter('all')">
                                    <ps-label class="text-gray-200 ms-2">{{ $t('core__be_select_all') }}</ps-label>
                                </div>
                                <div v-for="city in cities" :key="city.id"
                                    class="w-56 flex py-2 px-2 hover:bg-primary-000 dark:hover:bg-primary-900 cursor-pointer items-center"
                                    @click="handleCityfilter(city.id)">
                                    <ps-label class="ms-2" :class="city.id == selected_city ? ' font-bold' : ''">
                                        {{ city.name }} </ps-label>
                                </div>
                            </div>
                        </div>
                    </template>
                    <template #loadmore>
                        <div @click="cityDropdownClick(true)" v-if="city_loadmore_visible"
                            class="w-56 flex py-2 px-2 hover:bg-primary-000 dark:hover:bg-primary-900 cursor-pointer items-center">
                            <!-- <ps-label v-if="is_loading"  class="ms-2 italic underline">
                                {{$t('core__be_loading')}}
                            </ps-label> -->
                            <div class="flex flex-row items-center justify-between">
                                <ps-label class="ms-2 ">
                                    {{ is_loading ? $t('core__be_loading') : $t('core__be_load_more') }}
                                </ps-label>
                                <ps-loading v-if="is_loading"
                                    theme="border-2 border-t-2 border-text-8 border-t-primary-500 justify-end"
                                    loadingSize="h-5 w-5" />
                            </div>
                        </div>
                    </template>
                    <template #filter>
                        <div class="mt-1 mx-1">
                            <ps-input-with-right-icon class="w-full h-10" rounded="rounded-lg" v-model:value="citySearch"
                                :placeholder="$t('core__be_search')">
                                <template #icon>
                                    <ps-icon name="search" class='cursor-pointer' />
                                </template>
                            </ps-input-with-right-icon>
                        </div>
                    </template>
                </ps-dropdown>

                <!-- location township filter -->
                <ps-dropdown @on-click="townshipDropdownClick" class=" h-10"
                    v-if="!colFilterOptions.filter(option => option.key == 'location_township_id@@name')[0]?.hidden">
                    <template #select>
                        <ps-dropdown-select :placeholder="$t('core__be_township')"
                            :border="(selected_township !== '' && selected_township !== 'all') ? 'border border-indigo-500/100' : 'border border-1 border-secondary-200'"
                            :selectedValue="(selected_township == '' || selected_township == 'all') ? '' : selectedTownship.name" />
                    </template>
                    <template #list>
                        <div class="rounded-md shadow-xs w-56 ">
                            <div class="pt-2 z-30  ">
                                <div class="w-56 flex py-2 px-2 hover:bg-primary-000 dark:hover:bg-primary-900 cursor-pointer items-center"
                                    @click="handleTownshipfilter('all')">
                                    <ps-label class="text-gray-200 ms-2">{{ $t('core__be_select_all') }}</ps-label>
                                </div>
                                <div>
                                    <div v-for="township in townships" :key="township.id"
                                        class="w-56 flex py-2 px-2 hover:bg-primary-000 dark:hover:bg-primary-900 cursor-pointer items-center"
                                        @click="handleTownshipfilter(township.id)">
                                        <ps-label class="ms-2"
                                            :class="township.id == selected_township ? ' font-bold' : ''">
                                            {{ township.name }} </ps-label>
                                    </div>
                                </div>
                                <!-- <div v-else>
                                    <div v-for="township in townships.filter((t) => t.location_city_id == selected_city)" :key="township.id"
                                        class="w-56 flex py-2 px-2 hover:bg-primary-000 dark:hover:bg-primary-900 cursor-pointer items-center"
                                        @click="handleTownshipfilter(township.id)"
                                        >
                                        <ps-label class="ms-2"
                                            :class="township.id == selected_township ? ' font-bold' : ''">
                                            {{ township.name }} </ps-label>
                                    </div>
                                </div> -->
                            </div>
                        </div>
                    </template>
                    <template #loadmore>
                        <div @click="townshipDropdownClick(true)" v-if="townships_loadmore_visible"
                            class="w-56 flex py-2 px-2 hover:bg-primary-000 dark:hover:bg-primary-900 cursor-pointer items-center">
                            <!-- <ps-label v-if="is_loading"  class="ms-2 italic underline">
                                {{$t('core__be_loading')}}
                            </ps-label> -->
                            <div class="flex flex-row items-center justify-between">
                                <ps-label class="ms-2 ">
                                    {{ is_loading ? $t('core__be_loading') : $t('core__be_load_more') }}
                                </ps-label>
                                <ps-loading v-if="is_loading"
                                    theme="border-2 border-t-2 border-text-8 border-t-primary-500 justify-end"
                                    loadingSize="h-5 w-5" />
                            </div>
                        </div>
                    </template>
                    <template #filter>

                        <div class="mt-1 mx-1">
                            <ps-input-with-right-icon class="w-full h-10" rounded="rounded-lg"
                                v-model:value="townshipsSearch" :placeholder="$t('core__be_search')">
                                <template #icon>
                                    <ps-icon name="search" class='cursor-pointer' />
                                </template>
                            </ps-input-with-right-icon>
                        </div>
                    </template>
                </ps-dropdown>



                <!-- owner filter -->
                <ps-dropdown @on-click="ownersDropdownClick" align="" class=" h-10"
                    v-if="colFilterOptions.filter(option => option.key == 'added_user_id')[0] ? !colFilterOptions.filter(option => option.key == 'added_user_id')[0]?.hidden : false">
                    <template #select>
                        <ps-dropdown-select :placeholder="$t('core__be_posted_by')"
                            :border="(selected_owner !== '' && selected_owner !== 'all') ? 'border border-indigo-500/100' : 'border border-1 border-secondary-200'"
                            :selectedValue="(selected_owner == '' || selected_owner == 'all') ? '' : selectedOwner.name" />
                    </template>
                    <template #list>
                        <div class="rounded-md shadow-xs w-56 ">
                            <div class="pt-2 z-30  ">
                                <div class="w-56 flex py-2 px-2 hover:bg-primary-000 dark:hover:bg-primary-900 cursor-pointer items-center"
                                    @click="handleOwnerfilter('all')">
                                    <ps-label class="text-gray-200 ms-2">{{ $t('core__be_select_all') }}</ps-label>
                                </div>
                                <div v-for="owner in owners" :key="owner.id"
                                    class="w-56 flex py-2 px-2 hover:bg-primary-000 dark:hover:bg-primary-900 cursor-pointer items-center"
                                    @click="handleOwnerfilter(owner.id)">
                                    <ps-label class="ms-2" :class="owner.id == selected_owner ? ' font-bold' : ''">
                                        {{ owner.name }} </ps-label>
                                </div>
                            </div>
                        </div>
                    </template>
                    <template #loadmore>
                        <div @click="ownersDropdownClick(true)" v-if="owners_loadmore_visible"
                            class="w-56 flex py-2 px-2 hover:bg-primary-000 dark:hover:bg-primary-900 cursor-pointer items-center">
                            <!-- <ps-label v-if="is_loading"  class="ms-2 italic underline">
                                {{$t('core__be_loading')}}
                            </ps-label> -->
                            <div class="flex flex-row items-center justify-between">
                                <ps-label class="ms-2 ">
                                    {{ is_loading ? $t('core__be_loading') : $t('core__be_load_more') }}
                                </ps-label>
                                <ps-loading v-if="is_loading"
                                    theme="border-2 border-t-2 border-text-8 border-t-primary-500 justify-end"
                                    loadingSize="h-5 w-5" />
                            </div>
                        </div>
                    </template>
                    <template #filter>
                        <div class="mt-1 mx-1">
                            <ps-input-with-right-icon class="w-full h-10" rounded="rounded-lg" v-model:value="ownersSearch"
                                :placeholder="$t('core__be_search')">
                                <template #icon>
                                    <ps-icon name="search" class='cursor-pointer' />
                                </template>
                            </ps-input-with-right-icon>
                        </div>
                    </template>
                </ps-dropdown>
                <!-- <ps-dropdown align="" class=" h-10" >
                    <template #select>
                        <ps-dropdown-select :placeholder="$t('customfield')"
                            :selectedValue="(selected_customfield == '' || selected_customfield == 'all') ? '' : ps_itm00002.filter(option => option.id == selected_customfield)[0].name" />
                    </template>
                    <template #list>
                        <div class="rounded-md shadow-xs w-56 ">
                            <div class="pt-2 z-30  ">
                                <div class="w-56 flex py-2 px-2 hover:bg-primary-000 dark:hover:bg-primary-900 cursor-pointer items-center"
                                    @click="handleCustomfieldFilter('all')">
                                    <ps-label class="text-gray-200">{{$t('core__be_select_all')}}</ps-label>
                                </div>
                                <div v-for="item in ps_itm00002" :key="item.id"
                                    class="w-56 flex py-2 px-2 hover:bg-primary-000 dark:hover:bg-primary-900 cursor-pointer items-center"
                                    @click="handleCustomfieldFilter(item.id)">
                                    <ps-label class="ms-2" :class="item.id == selected_customfield ? ' font-bold' : ''">
                                        {{ item.name }} </ps-label>
                                </div>
                            </div>
                        </div>
                    </template>
                </ps-dropdown> -->
                <!-- customfields -->
                <!-- <div v-for="(custom_field_header) in this.customFieldsHeadersDropdown" :key="custom_field_header.id">
                  <ps-dropdown @on-click="customFieldDropdownClick(custom_field_header.core_keys_id)" align=""  class=" h-10">
                    <template #select>

                        <ps-dropdown-select :placeholder="$t(custom_field_header.placeholder)"
                            :selectedValue="(selected_available == '' || selected_available == 'all') ? '' : availables.filter(option => option.id == selected_available)[0].name" />
                    </template>
                    <template #list>
                        <div class="rounded-md shadow-xs w-56 ">
                            <div class="pt-2 z-30  ">
                                <div class="w-56 flex py-2 px-2 hover:bg-primary-000 dark:hover:bg-primary-900 cursor-pointer items-center"
                                    @click="handleCustomfieldFilter('all')">
                                    <ps-label class="text-gray-200">{{$t('core__be_select_all')}}</ps-label>
                                </div>
                                <div v-for="available in customFields[custom_field_header.core_keys_id]" :key="available.id"
                                    class="w-56 flex py-2 px-2 hover:bg-primary-000 dark:hover:bg-primary-900 cursor-pointer items-center"
                                    @click="handleAvilablefilter(available.id)">
                                    <ps-label class="ms-2" :class="available.id == selected_available ? ' font-bold' : ''">
                                        {{ available.name }} </ps-label>
                                </div>
                            </div>
                        </div>
                    </template>
                </ps-dropdown>
            </div> -->

                <!-- added date filter -->
                <date-picker
                    v-if="reRenderDate && colFilterOptions.filter(option => option.key == 'added_date')[0] ? !colFilterOptions.filter(option => option.key == 'added_date')[0]?.hidden : false"
                    :placeholder="$t('core__be_posted_date')" @datepick="handleAddedDatefilter"
                    class="rounded shadow-sm pt-0.5   focus:outline-none focus:ring-1 focus:ring-primary-500 "
                    :class="(selected_added_date == null || selected_added_date == '') ? 'w-full' : 'w-full'"
                    v-model:value="selected_added_date" :range="true" :inline="false" :autoApply="false" />

                <!-- updated date filter -->
                <date-picker
                    v-if="reRenderDate && colFilterOptions.filter(option => option.key == 'updated_date')[0] ? !colFilterOptions.filter(option => option.key == 'updated_date')[0]?.hidden : false"
                    :placeholder="$t('core__be_updated_date')" @datepick="handleUpdatedDatefilter"
                    class="rounded shadow-sm pt-0.5 focus:outline-none focus:ring-1 focus:ring-primary-500 "
                    :class="(selected_updated_date == null || selected_updated_date == '') ? 'w-full' : 'w-full'"
                    v-model:value="selected_updated_date" :range="true" :inline="false" :autoApply="false" />

                <!-- available filter -->
                <!-- <ps-dropdown  align="" class=" h-10" v-if="colFilterOptions.filter(option => option.key =='is_sold_out')[0] ? !colFilterOptions.filter(option => option.key =='is_sold_out')[0]?.hidden:''">
                    <template #select>
                        <ps-dropdown-select :placeholder="$t('core__be_item_available')"
                            :selectedValue="(selected_available == '' || selected_available == 'all') ? '' : availables.filter(option => option.id == selected_available)[0].name" />
                    </template>
                    <template #list>
                        <div class="rounded-md shadow-xs w-56 ">
                            <div class="pt-2 z-30  ">
                                <div class="w-56 flex py-2 px-2 hover:bg-primary-000 dark:hover:bg-primary-900 cursor-pointer items-center"
                                    @click="handleAvailablefilter('all')">
                                    <ps-label class="text-gray-200 ms-2">{{$t('core__be_select_all')}}</ps-label>
                                </div>
                                <div v-for="available in availables" :key="available.id"
                                    class="w-56 flex py-2 px-2 hover:bg-primary-000 dark:hover:bg-primary-900 cursor-pointer items-center"
                                    @click="handleAvilablefilter(available.id)">
                                    <ps-label class="ms-2" :class="available.id == selected_available ? ' font-bold' : ''">
                                        {{ available.name }} </ps-label>
                                </div>
                            </div>
                        </div>
                    </template>

                </ps-dropdown> -->

            </template>

            <template #tableActionRow="rowProps">
                <div class="flex flex-row" v-if="rowProps.field == 'action'">
                    <ps-button :disabled="!rowProps.row.vendorAuthorizations.update" @click="handleEdit(rowProps.row.id)"
                        class="me-2" rounded="rounded-lg" colors="bg-primary-400 text-white" padding="p-1.5"
                        hover="hover:outline-none hover:ring hover:ring-primary-100"
                        focus="focus:outline-none focus:ring focus:ring-primary-200">
                        <ps-icon theme="text-white dark:text-primary-900" name="eye-on" w="16" h="16" />
                    </ps-button>
                    <ps-button :disabled="!rowProps.row.vendorAuthorizations.delete"
                        @click="confirmDeleteClicked(rowProps.row.id)" rounded="rounded-lg" colors="bg-red-400 text-white"
                        padding="p-1.5" hover="hover:outline-none hover:ring hover:ring-red-100"
                        focus="focus:outline-none focus:ring focus:ring-red-200">
                        <ps-icon theme="text-white dark:text-primary-900" name="trash" w="16" h="16" />
                    </ps-button>
                    <ps-danger-dialog ref="ps_danger_dialog" />
                </div>
            </template>



            <template #tableRow="rowProps">

                <span v-if="rowProps.field == itmPurchasedOption + '@@name'">
                    <ps-badge v-if="rowProps.row[itmPurchasedOption + '@@name']">{{ rowProps.row[itmPurchasedOption +
                        '@@name'] }}</ps-badge>
                </span>

                <span v-if="rowProps.field == itmItemType + '@@name'">
                    <ps-badge theme="text-red-500 bg-red-100" v-if="rowProps.row[itmItemType + '@@name']">{{
                        rowProps.row[itmItemType + '@@name'] }}</ps-badge>
                </span>

                <span v-if="rowProps.field == itmConditionOfItem + '@@name'">
                    <ps-badge theme="text-red-500 bg-red-100" v-if="rowProps.row[itmConditionOfItem + '@@name']">{{
                        rowProps.row[itmConditionOfItem + '@@name'] }}</ps-badge>
                </span>

                <div v-if="rowProps.field == 'status' && reRenderToogle">
                    <ps-toggle v-if="reRenderToogle" :disabled="!rowProps.row.vendorAuthorizations.update"
                        :selectedValue="rowProps.row.status == 1 ? true : false"
                        @click="handlePublish(rowProps.row.id, rowProps.row.vendorAuthorizations.update)"></ps-toggle>
                    <!-- <ps-toggle @updateToggle="handlePublish(rowProps.row.id)" :disabled="!rowProps.row.vendorAuthorizations.update" v-if="rowProps.field == 'status'" :selectedValue="rowProps.row.status == 1 ? true : false"
                   ></ps-toggle> -->
                    <!-- <div v-if="rowProps.field == 'status'">
                {{rowProps.row.status}}
                </div> -->
                </div>

                <!-- <ps-text-button :disabled="!can.createItem" v-if="rowProps.field == 'duplicate'"
                    @click="handleDuplicate(rowProps.row.id)">
                    {{ $t('core__be_duplicate_label') }}
                </ps-text-button> -->

                <!-- <ps-text-button :disabled="!rowProps.row.vendorAuthorizations.update" v-if="rowProps.field == 'deeplink'"
                    @click="handleDeeplink(rowProps.row.id)">
                    {{ $t('core__be_generate_deeplink_label') }}
                </ps-text-button> -->

                <ps-label v-if="rowProps.field == 'is_sold_out'" class="flex">
                    <ps-label v-if="(rowProps.row.is_sold_out == 1)"
                        class="mb-2 px-1 py-0.5 text-xs font-semibold rounded whitespace-nowrap" textColor="text-red-500">
                        {{ $t('core__be_item_sold_out') }}
                    </ps-label>
                    <ps-label v-else class="mb-2 px-1 py-0.5 text-xs font-semibold rounded whitespace-nowrap"
                        textColor="text-green-500">
                        {{ $t('core__be_item_available') }}
                    </ps-label>
                </ps-label>
                <ps-label v-if="rowProps.field == 'original_price'">
                    {{ checkPriceFormat(rowProps.row['currency_id@@currency_symbol'], rowProps.row.original_price) }}

                </ps-label>
                <ps-label v-if="rowProps.field == 'price'">
                    {{ checkPriceFormat(rowProps.row['currency_id@@currency_symbol'], rowProps.row.price) }}
                </ps-label>
            </template>
        </ps-table2>
        <ps-confirm-dialog ref="ps_confirm_dialog" />
    <ps-error-dialog ref="ps_error_dialog" />
    <ps-loading-dialog ref="ps_loading_dialog" />

    </ps-layout>
</template>

<script>
import { ref, defineComponent,defineAsyncComponent, watch } from "vue";
import PsVendorLayout from "@vendorPanel/vendor/components/layouts/container/PsVendorLayout.vue";
import { Head, usePage } from "@inertiajs/vue3";
import { router } from '@inertiajs/vue3';
import { Splide, SplideSlide, SplideTrack } from '@splidejs/vue-splide';
import '@splidejs/splide/dist/css/splide-core.min.css';

import PsButton from "@/Components/Core/Buttons/PsButton.vue";
import PsTextButton from "@/Components/Core/Buttons/PsTextButton.vue";
import PsBannerIcon from "@/Components/Core/Banners/PsBannerIcon.vue";
import PsBreadcrumb2 from "@/Components/Core/Breadcrumbs/PsBreadcrumb2.vue";
import PsDangerDialog from "@/Components/Core/Dialog/PsDangerDialog.vue";
import PsIcon from "@/Components/Core/Icons/PsIcon.vue";
import PsDropdown from "@/Components/Core/Dropdown/PsDropdown.vue";
import PsDropdownSelect from "@/Components/Core/Dropdown/PsDropdownSelect.vue";
import Dropdown from "@/Components/Core/DropdownModal/Dropdown.vue";
import PsIconButton from "@/Components/Core/Buttons/PsIconButton.vue";
import PsLabel from "@/Components/Core/Label/PsLabel.vue";
import PsLink1 from '@/Components/Core/Link/PsLink1.vue';
import PsToggle from '@/Components/Core/Toggle/PsToggle.vue';
import PsTable2 from "@/Components/Core/Table/PsTable2.vue";
import DatePicker from "@/Components/Core/DateTime/DatePicker.vue";
import PsBadge from "@/Components/Core/Badge/PsBadge.vue"
import { trans } from 'laravel-vue-i18n';
import PsInput from "@/Components/Core/Input/PsInput.vue";
import debounce from 'lodash/debounce';
import { getCategories, getSubCat, getCustomFields, getCities, getTownships, getUsers } from '@/Api/psApiService.js'
import PsInputWithRightIcon from '@/Components/Core/Input/PsInputWithRightIcon.vue';
import PsLoading from "@/Components/Core/Loading/PsLoading.vue";
import firebaseApp from 'firebase/app';
import PsApiService from '@templateCore/api/PsApiService';
import { useCategoryStoreState } from "@templateCore/store/modules/category/CategoryStore";
import { PsValueStore } from '@templateCore/store/modules/core/PsValueStore';
import PsConst from '@templateCore/object/constant/ps_constants';
    import { useProductStore } from '@templateCore/store/modules/item/ProductStore';

import "firebase/auth";
import PsLoadingDialog from '@template1/vendor/components/core/dialog/PsLoadingDialog.vue';
    const PsConfirmDialog = defineAsyncComponent(() => import('@template1/vendor/components/core/dialog/PsConfirmDialog.vue'));
    const PsErrorDialog = defineAsyncComponent(() => import('@template1/vendor/components/core/dialog/PsErrorDialog.vue'));

export default defineComponent({
    name: "Index",
    components: {
        PsLoadingDialog,
        PsConfirmDialog,
        PsErrorDialog,
        Splide, SplideSlide, SplideTrack,
        Head,
        PsButton,
        PsTextButton,
        PsBannerIcon,
        PsBreadcrumb2,
        PsDangerDialog,
        PsIcon,
        PsDropdown,
        PsDropdownSelect,
        Dropdown,
        PsIconButton,
        PsLabel,
        PsLink1,
        PsToggle,
        PsTable2,
        DatePicker,
        PsBadge,
        PsInput,
        getCategories,
        getSubCat,
        getCustomFields,
        debounce,
        PsInputWithRightIcon,
        PsLoading
    },
    layout: PsVendorLayout,
    props: {
        isSubCategoryOn: String,
        can: Object,
        status: Object,
        items: Object,
        categoryId: String,
        // generalCategroies: String,
        categoriesWithCount: Object,
        // categories: Object,
        // subcategories: Object,
        customizeHeaders: Object,
        customizeDetails: Object,
        // cities: Object,
        // townships: Object,
        prices: Object,
        // owners: Object,
        availables: Object,
        hideShowFieldForFilterArr: Object,
        showCoreAndCustomFieldArr: Object,
        selectedCategory: { type: String, default: '' },
        selectedSubcategory: { type: String, default: '' },
        selectedCity: { type: String, default: '' },
        selectedTownship: { type: String, default: '' },
        selectedPrice: { type: String, default: '' },
        selectedAvailable: { type: String, default: '' },
        selectedOwner: { type: String, default: '' },
        selectedAddedDate: { type: String, default: '' },
        selectedUpdatedDate: { type: String, default: '' },
        selectedCustomfield: { type: String, default: '' },
        authUser: Object,
        uis: Object,
        sort_field: {
            type: String,
            default: "",
        },
        sort_order: {
            type: String,
            default: 'desc',
        },
        search: String,
        itmPurchasedOption: String,
        itmDealOption: String,
        itmConditionOfItem: String,
        itmItemType: String,
        ps_itm00002: Object,
        customizeHeader: Object,
        customizeDetails: Object,
        selected_price_type: String,
        defaultCategoryId: String,
    },
    data() {
        return {
            location_city: "",
            category: "",
        };
    },
    setup(props) {

        const options = {
                rewind: true,
                gap   : '0.1rem',
                perPage: 7,
                focus  : 0,
                omitEnd: true,
                pagination: false,
                autoWidth : true,
                breakpoints:{
                    1800:{
                        perPage: 7,
                    },
                    1536:{
                        perPage: 6,
                    },
                    1280:{
                        perPage:5,
                    },
                    1024:{
                        perPage:4,
                    },
                    768:{
                        perPage:3,
                    },
                    640:{
                        perPage:2
                    },
                    200:{
                        perPage:1
                    }
                }
            };
            const categoryId = ref();
            const defaultCategoryId = ref(props.defaultCategoryId);

        const psValueStore = PsValueStore();

        const showFilter =  props.selectedSubcategory || props.selectedCity ||
            props.selectedTownship || props.selectedPrice || props.selectedAvailable || props.selectedOwner ||
            props.selectedAddedDate || props.selectedUpdatedDate ? ref(true) : ref(false);
        const clearFilter = ref(false);

        const categoryStore = useCategoryStoreState('123123');

        const ps_confirm_dialog = ref();
        const ps_error_dialog = ref();
        const ps_loading_dialog = ref();
        const productStore = useProductStore('vendor-list');



        let visible = ref(false)
        const reRenderDate = ref(true);
        const reRenderToogle = ref(true);

        let search = props.search ? ref(props.search) : ref('');
        let sort_field = props.sort_field ? ref(props.sort_field) : ref('');
        let sort_order = props.sort_order ? ref(props.sort_order) : ref('desc');
        let selected_cat = props.selectedCategory ? ref(props.selectedCategory.id) : ref('');
        let selected_sub_cat = props.selectedSubcategory ? ref(props.selectedSubcategory.id) : ref('');
        let selected_city = props.selectedCity ? ref(props.selectedCity.id) : ref('');
        let selected_township = props.selectedTownship ? ref(props.selectedTownship.id) : ref('');
        let selected_price = props.selectedPrice ? ref(props.selectedPrice) : ref('');
        let selected_owner = props.selectedOwner ? ref(props.selectedOwner.id) : ref('');
        let selected_added_date = props.selectedAddedDate ? ref(props.selectedAddedDate) : ref('');
        let selected_updated_date = props.selectedUpdatedDate ? ref(props.selectedUpdatedDate) : ref('');
        let selected_available = props.selectedAvailable ? ref(props.selectedAvailable) : ref('');
        let selected_customfield = props.selectedCustomfield ? ref(props.selectedCustomfield) : ref('');

        let categories = ref([]);
        let category_loadmore_visible = ref(false);
        let catSearch = ref();
        let is_loading = ref(false);

        let subCategories = ref([]);
        let subCategory_loadmore_visible = ref(false);
        let subCatSearch = ref();

        let cities = ref([]);
        let city_loadmore_visible = ref(false);
        let citySearch = ref();

        let townships = ref([]);
        let townships_loadmore_visible = ref(false);
        let townshipsSearch = ref();

        let owners = ref([]);
        let owners_loadmore_visible = ref(false);
        let ownersSearch = ref();

        let customFields = ref({});
        let core_key = ref([]);



        const colFilterOptions = ref();
        let columns = [];
        if (props.isSubCategoryOn == '1') {
            columns = [
                {
                    label: trans('core__be_items'),
                    field: "title",
                    type: 'String',
                    action: 'Action'
                },
                {
                    label: trans('core__be_item_categories'),
                    field: "category_id@@name",
                    type: 'Integer',
                    action: 'Action'
                },
                {
                    label: trans('core__be_item_subcategories'),
                    field: "subcategory_id@@name",
                    type: 'Integer',
                    action: 'Action'
                },
                { "action": "Action", "field": "location_city_id@@name", "label": "City", "sort": 1, "type": "Integer" },
                { "action": "Action", "field": "location_township_id@@name", "label": "Township", "sort": 1, "type": "Integer" },
                { "action": "Action", "field": "price", "label": "Sales Price", "sort": 1, "type": "Float" },
                { "action": "Action", "field": "added_user_id@@name", "label": "Posted By", "sort": 1, "type": "Integer" },
                { "action": "Action", "field": "is_sold_out", "label": "Available", "sort": 1, "type": "Boolean" },
                { "action": "Action", "field": "added_date", "label": "Posted Date", "sort": 1, "type": "Timestamp" },
                {
                    label: trans('core__be_action_label'),
                    field: "action",
                    type: 'Action',
                    sort: false
                },
            ]
        } else {
            columns = [
                {
                    label: trans('core__be_items'),
                    field: "title",
                    type: 'String',
                    action: 'Action'
                },
                {
                    label: trans('core__be_item_categories'),
                    field: "category_id@@name",
                    type: 'Integer',
                    action: 'Action'
                },
                { "action": "Action", "field": "location_city_id@@name", "label": "City", "sort": 1, "type": "Integer" },
                { "action": "Action", "field": "location_township_id@@name", "label": "Township", "sort": 1, "type": "Integer" },
                { "action": "Action", "field": "price", "label": "Sales Price", "sort": 1, "type": "Float" },
                { "action": "Action", "field": "added_user_id@@name", "label": "Posted By", "sort": 1, "type": "Integer" },
                { "action": "Action", "field": "is_sold_out", "label": "Available", "sort": 1, "type": "Boolean" },
                { "action": "Action", "field": "added_date", "label": "Posted Date", "sort": 1, "type": "Timestamp" },
                {
                    label: trans('core__be_action_label'),
                    field: "action",
                    type: 'Action',
                    sort: false
                },
            ]

        }

        if (usePage().props.selected_price_type == "NO_PRICE") {
            columns = columns.filter(column => column.field !== "original_price" && column.field !== "price");
        }

        const ps_danger_dialog = ref();

        function checkPriceFormat(currency, data) {
            // alert(data);
            if (usePage().props.selected_price_type == PsConst.PRICE_RANGE) {

                const floatValue = parseFloat(data);
                const intValue = parseInt(floatValue);
                if (intValue > 5) {
                    return '$'.repeat(5);
                }
                if (intValue < 1) {
                    return '$'.repeat(1);
                }
                return '$'.repeat(intValue);
            }
            if (usePage().props.selected_price_type == PsConst.NORMAL_PRICE) {
                return currency + data;
            }
        }

        function confirmDeleteClicked(id) {
            ps_danger_dialog.value.openModal(
                trans('core__be_delete_item'),
                trans('core__be_delete_item_info'),
                trans('core__be_btn_confirm'),
                trans('core__be_btn_cancel'),
                () => {
                    router.delete(route("vendor_item.destroy", id), {
                        onSuccess: () => {
                            visible.value = true;
                            setTimeout(() => {
                                visible.value = false;
                            }, 3000);
                        },
                        onError: () => {
                            visible.value = true;
                            setTimeout(() => {
                                visible.value = false;
                            }, 3000);
                        },
                    })
                },
                () => { }
            );
        }

        function handleSorting(value) {
            sort_field.value = value.field
            sort_order.value = value.sort_order
            handleSearchingSorting()
        }

        function handleClearFilter() {
            selected_cat.value = defaultCategoryId.value;
            selected_sub_cat.value = 'all';
            selected_city.value = 'all';
            selected_township.value = 'all';
            selected_price.value = 'all';
            selected_owner.value = 'all';
            selected_available.value = 'all';
            selected_added_date.value = '';
            selected_updated_date.value = '';
            search.value = '';
            handleSearchingSorting();

            reRenderDate.value = false;
            setTimeout(() => {
                reRenderDate.value = true;
            }, 100);
        }
        function loadCategory() {
            categoryStore.loadItemList(psValueStore.getLoginUserId(), categoryStore.paramHolder);
        }

        function handleCategoryfilter(value) {
            // alert(value)
            // loadCategory();
            selected_cat.value = value
            selected_sub_cat.value = "all"
            // selected_sub_cat.value = selected_sub_cat.value ?(props.subcategories.filter((subCat) => subCat.category_id == value).filter((subCategory) => subCategory.id == selected_sub_cat.value)[0]?selected_sub_cat.value:''):''
            let page = 1;
            subCategories.value = [];

            handleSearchingSorting(page);
        }

        function handleSubcategoryfilter(value) {
            selected_sub_cat.value = value
            let page = 1;
            handleSearchingSorting(page);
        }

        function handleCityfilter(value) {
            selected_city.value = value
            selected_township.value = "all"
            townships.value = []
            // selected_township.value = selected_township.value ?(props.cities.filter((city) => city.category_id == value).filter((city) => city.id == selected_township.value)[0]?selected_township.value:''):''
            let page = 1;
            handleSearchingSorting(page);
        }

        function handleTownshipfilter(value) {
            selected_township.value = value
            let page = 1;
            handleSearchingSorting(page);
        }

        function handleAddedDatefilter(value) {
            selected_added_date.value = value
            let page = 1;
            handleSearchingSorting(page);
        }

        function handleUpdatedDatefilter(value) {
            selected_updated_date.value = value
            let page = 1;
            handleSearchingSorting(page);
        }

        function handleOwnerfilter(value) {
            selected_owner.value = value
            let page = 1;
            handleSearchingSorting(page);
        }

        function handlePricefilter(value) {
            selected_price.value = value
            let page = 1;
            handleSearchingSorting(page);
        }

        function handleAvailablefilter(value) {
            selected_available.value = value
            let page = 1;
            handleSearchingSorting(page);
        }

        // function handleCustomfieldFilter(value) {
        //     selected_customfield.value = value
        //     let page = 1;
        //     handleSearchingSorting(page);
        // }


        function handleSearching(value) {
            search.value = value;
            let page = 1;
            handleSearchingSorting(page);
        }

        function handleRow(value) {
            handleSearchingSorting(1, value);
        }

        function handlePublish(id, hasPermission) {
            if (hasPermission) {
                router.put(route('vendor_item.statusChange', id));

                setTimeout(() => {
                    reRenderToogle.value = false;
                    setTimeout(() => {
                        reRenderToogle.value = true;
                    }, 200);
                }, 2000);
            }


        }

        // Category data
        async function getCategoriesData(offset) {
            category_loadmore_visible.value = true;
            is_loading.value = true
            let loginUserId = '1';

            getCategories(offset, catSearch.value, props.authUser.id).then(response => {

                if (!response.data.length) {
                    category_loadmore_visible.value = false;
                }
                else {
                    response.data.forEach(element => {
                        categories.value.push(element);
                    });
                }
                is_loading.value = false;
            });
        }

        function dropdownClick(loadMore = null) {

            let offset = categories.value ? categories.value.length : 0;
            if (offset == 0 || loadMore == true) {

                getCategoriesData(offset);
            }
        }
        watch(catSearch, _.debounce((current, previous) => {
            let offset = 0;
            categories.value = [];
            getCategoriesData(offset);

        }, 500))

        // Sub Category
        function getSubCategoriesData(offset) {

            subCategory_loadmore_visible.value = true;
            is_loading.value = true
            getSubCat(offset, subCatSearch.value, props.authUser.id, selected_cat.value).then(response => {

                if (!response.data.length) {
                    subCategory_loadmore_visible.value = false;
                }
                else {
                    response.data.forEach(element => {
                        subCategories.value.push(element);
                    });
                }
                is_loading.value = false;
            });
        }

        function subCategoryDropdownClick(loadMore = null) {


            let offset = subCategories.value ? subCategories.value.length : 0;
            if (offset == 0 || loadMore == true) {
                getSubCategoriesData(offset);
            }
        }
        watch(subCatSearch, _.debounce((current, previous) => {
            let offset = 0;
            subCategories.value = [];
            getSubCategoriesData(offset);

        }, 500))

        // cities dropdown

        function getCitiesData(offset) {

            city_loadmore_visible.value = true;
            is_loading.value = true
            getCities(offset, citySearch.value, props.authUser.id).then(response => {

                if (!response.data.length) {
                    city_loadmore_visible.value = false;
                }
                else {
                    response.data.forEach(element => {
                        cities.value.push(element);
                    });
                }
                is_loading.value = false;
            });
        }

        function cityDropdownClick(loadMore = null) {
            let offset = cities.value ? cities.value.length : 0;
            if (offset == 0 || loadMore == true) {
                getCitiesData(offset);
            }
        }
        watch(citySearch, _.debounce((current, previous) => {
            let offset = 0;
            cities.value = [];
            getCitiesData(offset);

        }, 500))

        // Township
        function getTownshipData(offset) {

            townships_loadmore_visible.value = true;
            is_loading.value = true
            getTownships(offset, townshipsSearch.value, props.authUser.id, selected_city.value).then(response => {

                if (!response.data.length) {
                    townships_loadmore_visible.value = false;
                }
                else {
                    response.data.forEach(element => {
                        townships.value.push(element);
                    });
                }
                is_loading.value = false;

            });
        }

        function townshipDropdownClick(loadMore = null) {
            let offset = townships.value ? townships.value.length : 0;
            if (offset == 0 || loadMore == true) {
                getTownshipData(offset);
            }
        }
        watch(townshipsSearch, _.debounce((current, previous) => {
            let offset = 0;
            townships.value = [];
            getTownshipData(offset);

        }, 500))

        // Owners

        function getOwnersData(offset) {

            owners_loadmore_visible.value = true;
            is_loading.value = true
            getUsers(offset, ownersSearch.value, props.authUser.id).then(response => {

                if (!response.data.length) {
                    owners_loadmore_visible.value = false;
                }
                else {
                    response.data.forEach(element => {
                        owners.value.push(element);
                    });
                }
                is_loading.value = false;
            });
        }

        function ownersDropdownClick(loadMore = null) {
            let offset = owners.value ? owners.value.length : 0;
            if (offset == 0 || loadMore == true) {
                getOwnersData(offset);
            }
        }
        watch(ownersSearch, _.debounce((current, previous) => {
            let offset = 0;
            owners.value = [];
            getOwnersData(offset);

        }, 500))

        function handleSearchingSorting(page = null, row = null) {
            router.get(route('vendor_item.index'),
                {
                    sort_field: sort_field.value,
                    sort_order: sort_order.value,
                    page: page ?? props.items.meta.current_page,
                    row: row ?? props.items.meta.per_page,
                    search: search.value,
                    category_filter: selected_cat.value,
                    sub_category_filter: selected_sub_cat.value,
                    city_filter: selected_city.value,
                    township_filter: selected_township.value,
                    added_date_filter: selected_added_date.value,
                    updated_date_filter: selected_updated_date.value,
                    price_filter: selected_price.value,
                    available_filter: selected_available.value,
                    owner_filter: selected_owner.value,
                    ps_itm00002: selected_customfield.value,
                },
                {
                    preserveScroll: true,
                    preserveState: true,
                })
        }

        function clickCategory(id){
            categoryId.value = id;
            selected_cat.value = id;
            handleSearchingSorting(1);
        }

        function customFieldDropdownClick(core_key_id, loadMore = null) {
            // core_key_id='itm111'
            // alert(core_key_id in customFields.value)

            let offset = core_key_id in customFields.value ? customFields.value[core_key_id].length : 0;
            loadMore = offset > 0 ? true : false;
            // let offset = 0;
            // alert(offset);
            if (offset == 0 || loadMore == true) {
                category_loadmore_visible.value = true;
                getCustomFields(offset, catSearch.value, 1, core_key_id).then(response => {


                    // customFields.value.push({[core_key_id]});
                    // core_key.value.push(core_key_id);
                    //  customFields.value = customField.value.concat(response.data).unique();
                    if (core_key_id in customFields.value) {
                        response.data.forEach(element => {
                            customFields.value[core_key_id].push(element);
                        })
                    }
                    else {
                        customFields.value[core_key_id] = response.data
                        //     response.data.forEach(element =>{
                        //         customFields.value[core_key_id].push(element);
                        //    })
                    }

                    // response.data.forEach(element =>{
                    //      customFields.value.push({[core_key_id]:element});
                    // });

                    //     core_key.value  = customFields.value ;
                    // console.log(customFields.value[core_key_id]);
                    if (!response.data.length) {
                        category_loadmore_visible.value = false;
                    }
                });
            }
        }

        function loadEyeFilter(value) {
            colFilterOptions.value = value;
        }

        function deleteClicked(id) {
            ps_confirm_dialog.value.openModal(
                trans('item_detail__delete_this_item'),
                trans('item_detail__confirm_to_delete_item'),
                trans('item_detail__delete'),
                trans('item_detail__cancel'),
                () => { doDelete(id) },
                () => { PsUtils.log("Cancel"); }
            );
        }

        async function doDelete(id) {

            const item = await productStore.userDeleteItem('1', id);
            if (item.status == '1') {
                // router.get(route("dashboard"));
                handleSearchingSorting();
            }
            else {
                ps_error_dialog.value.openModal(item.message);
            }

        }

        return {
            ps_confirm_dialog,
                ps_error_dialog,
                ps_loading_dialog,
            deleteClicked,
            loadEyeFilter,
            reRenderDate,
            showFilter,
            clearFilter,
            columns,
            confirmDeleteClicked,
            ps_danger_dialog,
            colFilterOptions,
            visible,
            handleSorting,
            handleSearchingSorting,
            handleCategoryfilter,
            handleSubcategoryfilter,
            handleCityfilter,
            handleTownshipfilter,
            handlePricefilter,
            handleOwnerfilter,
            handleAddedDatefilter,
            handleUpdatedDatefilter,
            handleAvailablefilter,
            handleClearFilter,
            handleRow,
            handleSearching,
            selected_cat,
            selected_sub_cat,
            selected_city,
            selected_township,
            selected_price,
            selected_owner,
            selected_added_date,
            selected_updated_date,
            selected_available,
            handlePublish,
            reRenderToogle,
            // handleCustomfieldFilter,
            selected_customfield,
            dropdownClick,
            categories,
            category_loadmore_visible,
            catSearch,
            subCategoryDropdownClick,
            subCategories,
            subCategory_loadmore_visible,
            subCatSearch,
            customFieldDropdownClick,
            checkPriceFormat,
            customFields,
            core_key,
            is_loading,
            cityDropdownClick,
            cities,
            city_loadmore_visible,
            citySearch,
            townshipDropdownClick,
            townships,
            townships_loadmore_visible,
            townshipsSearch,
            ownersDropdownClick,
            owners,
            owners_loadmore_visible,
            ownersSearch,
            loadCategory,
            categoryStore,
            options,
            clickCategory,
        };
    },
    watch: {
        hideShowFieldForFilterArr(current, previous){
            this.loadEyeFilter(current);
        },
        // showCoreAndCustomFieldArr(v,u){
        //     this.columns = v.map(column => {
        //         return {
        //             action: column.action,
        //             field: column.field,
        //             label: trans(column.label),
        //             sort: column.sort,
        //             type: column.type,
        //         };
        //     });
        // }
    },
    computed: {
        breadcrumb() {
            return [
                {
                    label: trans('core__vendor_my_store_module'),
                    url: route('vendor_info.index')
                },
                {
                    label: trans('item_module'),
                    color: "text-primary-500"
                }
            ]
        },
        customFieldsHeadersDropdown() {
            return this.customizeHeaders.filter(function (u) {
                return u.ui_type_id === 'uit00001'
            })
        }



    },
    created(props) {
        // this.columns = this.showCoreAndCustomFieldArr.map(column => {
        //     return {
        //         action: column.action,
        //         field: column.field,
        //         label: trans(column.label),
        //         sort: column.sort,
        //         type: column.type,
        //     };
        // });


        // if (usePage().props.selected_price_type == PsConst.NO_PRICE) {
        //     this.columns = this.columns.filter(column => column.field !== "original_price" && column.field !== "price");
        // }

        this.colFilterOptions = this.hideShowFieldForFilterArr.map(columnFilterOption => {
            return {
                hidden: columnFilterOption.hidden,
                id: columnFilterOption.id,
                key: trans(columnFilterOption.key),
                key_id: columnFilterOption.key_id,
                label: trans(columnFilterOption.label),
                module_name: columnFilterOption.module_name
            };
        });

    },
    methods: {
        handleCreate() {
            this.$inertia.get(route("vendor_item.create"));
        },
        handleCustomize() {
            this.$inertia.get(route("vendor_item.customization"));
        },
        handleDelete(id) {
            this.$inertia.delete(route("vendor_item.destroy", id));
        },
        handleEdit(id) {
            this.$inertia.get(route("vendor_item.edit", id));
        },
        handleDuplicate(id) {
            this.$inertia.put(route("vendor_item.duplicate", id));
        },
        handleDeeplink(id) {
            this.$inertia.put(route("vendor_item.deeplink", id));
        },
        // handlePublish(id){
        //     // alert("hereing")
        // this.$inertia.put(route('vendor_item.statusChange',id));
        // },
        FilterOptionshandle(value) {
            router.put(route('vendor_item.screenDisplayUiSetting.store'),
                {
                    value,
                    sort_field: this.sort_field,
                    sort_order: this.sort_order,
                    row: this.items.per_page,
                    search: this.search.value,
                    current_page: this.items.current_page
                },
                {
                    preserveScroll: true,
                    preserveState: true,
                });
        },
    },
});
</script>

<style scoped>

.splide__arrows--rtl .splide__arrow--prev{left:auto;right:1em}
.splide__arrows--rtl .splide__arrow--prev svg{transform:scaleX(1)}
.splide__arrows--rtl .splide__arrow--next{left:1em;right:auto}
.splide__arrows--rtl .splide__arrow--next svg{transform:scaleX(-1)}
.splide__arrows--ttb .splide__arrow{left:50%;transform:translate(-50%)}
.splide__arrows--ttb .splide__arrow--prev{top:1em}
.splide__arrows--ttb .splide__arrow--prev svg{transform:rotate(-90deg)}
.splide__arrows--ttb .splide__arrow--next{bottom:1em;top:auto}
.splide__arrows--ttb .splide__arrow--next svg{transform:rotate(90deg)}
.splide{z-index: 0;}
.splide__arrow{
    -ms-flex-align:center;
    align-items:center;
    background:#ffffff;
    border:0;
    border-radius: 4px;
    cursor:pointer;
    display:-ms-flexbox;
    display:flex;
    height:2.5rem;
    -ms-flex-pack:center;
    justify-content:center;
    opacity:1;
    padding:.5rem;
    position:absolute;
    top:50%;
    transform:translateY(-50%);
    width:2.5rem;
    z-index:1;
    border: 0.5px solid #E5E7EB;
    box-shadow: 0px 1px 2px rgba(6, 25, 56, 0.05);
}
.splide__arrow svg{fill:#000;height:1.2em;width:1.2em}
.splide__arrow:hover:not(:disabled){opacity:1}
.splide__arrow:disabled{opacity:.3}
/* .splide__arrow:focus-visible{outline:3px solid #0bf;outline-offset:3px} */
.splide__arrow--prev{left:-.5rem}
.splide__arrow--prev svg{transform:scaleX(-1)}
.splide__arrow--next{right:-.5rem}
/* .splide.is-focus-in .splide__arrow:focus{outline:3px solid #0bf;outline-offset:3px} */
.splide__arrow--prev{
    position:absolute;
    top:50%;
    transform:translateY(-50%);
}
.splide__arrow--next{
    position:absolute;
    top:50%;
    transform:translateY(-50%);
}


    .splide__arrow--prev{
        left: -1.5rem;
    }
    .splide__arrow--next{
        right: -1.5rem;
    }

</style>
