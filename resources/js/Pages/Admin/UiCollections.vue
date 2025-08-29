<template>
    <ps-layout>
            <ps-model-view ref="psmodal" />

            <ps-modal ref="psMapModal" :isClickOut="false">
                <template #title>
                    <ps-label-title>
                        {{ $t("ui_collection__select_location") }}
                    </ps-label-title>
                </template>
                <template #body>
                    <GoogleMap
                        id="map"
                        ref="mapRef"
                        :api-key="map.key"
                        :center="map.center"
                        :zoom="map.zoom"
                        style="width: 100%; height: 500px"
                    >
                        <!-- For circle -->
                        <Circle
                            id="circle"
                            :options="circleCenter"
                            ref="cirRef"
                        />
                    </GoogleMap>
                </template>
                <template #footer>
                    <div class="hidden sm:flex justify-end ">
                        <ps-button @click="psMapModal.toggle(false)">
                            {{ $t("ui_collection__ok") }}
                        </ps-button>
                    </div>
                </template>
            </ps-modal>

            <div>
                <!-- Table  -->
                {{selectedTableData}}
                <ps-table
                    v-model:selectedTableData="selectedTableData"
                    :rows="rows"
                    :columns="columns"
                    checkable="true"
                    searchable="true"
                    showLineNumber="true"
                    :globalSearchFields = "globalSearchFields"
                    :searchOptions = "searchOptions"
                >
                    <template #tableSearch>

                    </template>
                    <template #tableRow="rowProps">
                        <span v-if="rowProps.field == 'gender'">
                            <span v-if="rowProps.row[rowProps.field] == '1'"
                                >Male</span
                            >
                            <span v-else>Female</span>
                        </span>

                        <span v-if="rowProps.field == 'status'">
                            <span v-if="rowProps.row[rowProps.field] == '1'">
                                <ps-badge class="m-2" theme="badge-success"
                                    >{{ $t('btn_active') }}</ps-badge
                                >
                            </span>
                            <span v-else>
                                <ps-badge class="m-2" theme="badge-warning"
                                    >{{ $t('btn_inactive') }}</ps-badge
                                >
                            </span>
                        </span>

                        <span v-if="rowProps.field == 'action'">
                            <button @click="edit(rowProps.row)">Edit</button>
                        </span>
                    </template>
                </ps-table>
                <ps-line theme="my-5" />

                <!-- Text -->
                <ps-label-title class="mt-4">{{
                    $t("ui_collection__label_ui")
                }}</ps-label-title>
                <ps-label-header-1>{{
                    $t("ui_collection__label_h1")
                }}</ps-label-header-1>
                <ps-label-header-2>{{
                    $t("ui_collection__label_h2")
                }}</ps-label-header-2>
                <ps-label-header-3>{{
                    $t("ui_collection__label_h3")
                }}</ps-label-header-3>
                <ps-label-header-4>{{
                    $t("ui_collection__label_h4")
                }}</ps-label-header-4>
                <ps-label-header-5>{{
                    $t("ui_collection__label_h5")
                }}</ps-label-header-5>
                <ps-label-header-6
                    >{{ $t("ui_collection__label_h6") }}
                </ps-label-header-6>
                <ps-label-title>{{
                    $t("ui_collection__label_title1")
                }}</ps-label-title>
                <ps-label-title-2>{{
                    $t("ui_collection__label_title2")
                }}</ps-label-title-2>
                <ps-label-title-3>{{
                    $t("ui_collection__label_title3")
                }}</ps-label-title-3>
                <ps-label-title-4>{{
                    $t("ui_collection__label_title4")
                }}</ps-label-title-4>
                <ps-label>{{ $t("ui_collection__body") }}</ps-label>
                <ps-label-caption>{{
                    $t("ui_collection__label_caption")
                }}</ps-label-caption>
                <ps-label-caption-2>{{
                    $t("ui_collection__label_caption2")
                }}</ps-label-caption-2>
                <ps-label-caption-3>{{
                    $t("ui_collection__label_caption3")
                }}</ps-label-caption-3>

                <ps-line theme="my-5" />

                <!-- Select -->
                <!-- <ps-label-title class="mt-4">Select</ps-label-title>
                <v-select
                    dir="$store.state.dir"
                    multiple
                    v-model="selectedMultiple"
                    :options="options"
                    :reduce="(country) => country.code"
                    label="country"
                    placeholder="Choose..."
                    class="multiple-select input-primary rounded"
                />
                {{ selectedMultiple }} -->

                <ps-line theme="my-5" />

                <!-- Input -->
                <ps-label-title class="mt-4">
                    {{ $t("ui_collection__input_ui") }}
                </ps-label-title>

                <ps-input
                    v-model:value="input"
                    type="text"
                    placeholder="Placeholder ..."
                />
                {{ input }}

                <ps-input-with-right-icon
                    v-model:value="inputRightIcon"
                    :placeholder="
                        $t('core.ui_collection__what_are_you_looking_for')
                    "
                >
                    <template #icon>
                        <ps-icon name="search" class="cursor-pointer" />
                    </template>
                </ps-input-with-right-icon>
                {{ inputRightIcon }}

                <ps-input-with-left-icon
                    v-model:value="inputLeftIcon"
                    :placeholder="
                        $t('core.ui_collection__what_are_you_looking_for')
                    "
                >
                    <template #icon>
                        <ps-icon name="search" class="cursor-pointer" />
                    </template>
                </ps-input-with-left-icon>
                {{ inputLeftIcon }}

                <ps-line theme="my-5" />

                <!-- Button -->
                <ps-label-title class="mt-4">
                    {{ $t("ui_collection__button") }}
                </ps-label-title>
                <ps-button class="m-2">Default Button</ps-button>
                <ps-button class="m-2" btnSize="btn-sm">
                    Default Button
                </ps-button>
                <ps-button class="m-2">
                    Default Button
                </ps-button>
                <ps-button class="m-2" btnSize="btn-lg">
                    Default Button
                </ps-button>
                <ps-button class="m-2" disabled="true">
                    Disabled Button
                </ps-button>
                <br />
                <ps-button class="m-2" theme="btn-success">
                    Success Button
                </ps-button>
                <ps-button class="m-2" theme="btn-warning">
                    Warning Button
                </ps-button>
                <ps-button class="m-2" theme="btn-info">
                    Info Button
                </ps-button>
                <ps-button class="m-2" theme="btn-error">
                    Error Button
                </ps-button>
                <ps-button class="m-2" theme="btn-dark">
                    Dark Button
                </ps-button>
                <ps-button class="m-2" theme="btn-light">
                    Light Button
                </ps-button>

                <ps-line theme="my-5" />

                <!-- Badge -->
                <ps-label-title class="mt-4"> Badge </ps-label-title>
                <ps-badge class="m-2"> Default Badge </ps-badge>
                <ps-badge class="m-2" theme="badge-success">
                    Success Badge
                </ps-badge>
                <ps-badge class="m-2" theme="badge-warning">
                    Warning Badge
                </ps-badge>
                <ps-badge class="m-2" theme="badge-info"> Info Badge </ps-badge>
                <ps-badge class="m-2" theme="badge-error">
                    Error Badge
                </ps-badge>
                <ps-badge class="m-2" theme="badge-dark"> Dark Badge </ps-badge>
                <ps-badge class="m-2" theme="badge-light">
                    Light Badge
                </ps-badge>

                <ps-line theme="my-5" />
                <ps-label-title class="mt-4">Alert</ps-label-title>
                <ps-alert>
                    <span
                        >Lorem ipsum dolor sit amet consectetur adipisicing
                        elit. Distinctio nisi rerum qui quibusdam minus adipisci
                        expedita ad ipsam tempore officia. Eligendi possimus
                        neque omnis molestias, enim incidunt earum error
                        natus!</span
                    >
                </ps-alert>
                <ps-alert theme="alert-info"
                    ><svg
                        aria-hidden="true"
                        focusable="false"
                        data-prefix="fas"
                        data-icon="info-circle"
                        class="w-4 h-4 me-2 fill-current"
                        role="img"
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 512 512"
                    >
                        <path
                            fill="currentColor"
                            d="M256 8C119.043 8 8 119.083 8 256c0 136.997 111.043 248 248 248s248-111.003 248-248C504 119.083 392.957 8 256 8zm0 110c23.196 0 42 18.804 42 42s-18.804 42-42 42-42-18.804-42-42 18.804-42 42-42zm56 254c0 6.627-5.373 12-12 12h-88c-6.627 0-12-5.373-12-12v-24c0-6.627 5.373-12 12-12h12v-64h-12c-6.627 0-12-5.373-12-12v-24c0-6.627 5.373-12 12-12h64c6.627 0 12 5.373 12 12v100h12c6.627 0 12 5.373 12 12v24z"
                        ></path></svg
                    ><span>This is info alert</span></ps-alert
                >
                <ps-alert theme="alert-success"
                    ><svg
                        aria-hidden="true"
                        focusable="false"
                        data-prefix="fas"
                        data-icon="check-circle"
                        class="w-4 h-4 me-2 fill-current"
                        role="img"
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 512 512"
                    >
                        <path
                            fill="currentColor"
                            d="M504 256c0 136.967-111.033 248-248 248S8 392.967 8 256 119.033 8 256 8s248 111.033 248 248zM227.314 387.314l184-184c6.248-6.248 6.248-16.379 0-22.627l-22.627-22.627c-6.248-6.249-16.379-6.249-22.628 0L216 308.118l-70.059-70.059c-6.248-6.248-16.379-6.248-22.628 0l-22.627 22.627c-6.248 6.248-6.248 16.379 0 22.627l104 104c6.249 6.249 16.379 6.249 22.628.001z"
                        ></path></svg
                    ><span>This is success alert</span></ps-alert
                >
                <ps-alert theme="alert-error"
                    ><svg
                        aria-hidden="true"
                        focusable="false"
                        data-prefix="fas"
                        data-icon="times-circle"
                        class="w-4 h-4 me-2 fill-current"
                        role="img"
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 512 512"
                    >
                        <path
                            fill="currentColor"
                            d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm121.6 313.1c4.7 4.7 4.7 12.3 0 17L338 377.6c-4.7 4.7-12.3 4.7-17 0L256 312l-65.1 65.6c-4.7 4.7-12.3 4.7-17 0L134.4 338c-4.7-4.7-4.7-12.3 0-17l65.6-65-65.6-65.1c-4.7-4.7-4.7-12.3 0-17l39.6-39.6c4.7-4.7 12.3-4.7 17 0l65 65.7 65.1-65.6c4.7-4.7 12.3-4.7 17 0l39.6 39.6c4.7 4.7 4.7 12.3 0 17L312 256l65.6 65.1z"
                        ></path></svg
                    ><span>This is error alert</span></ps-alert
                >
                <ps-alert theme="alert-warning"
                    ><svg
                        aria-hidden="true"
                        focusable="false"
                        data-prefix="fas"
                        data-icon="exclamation-triangle"
                        class="w-4 h-4 me-2 fill-current"
                        role="img"
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 576 512"
                    >
                        <path
                            fill="currentColor"
                            d="M569.517 440.013C587.975 472.007 564.806 512 527.94 512H48.054c-36.937 0-59.999-40.055-41.577-71.987L246.423 23.985c18.467-32.009 64.72-31.951 83.154 0l239.94 416.028zM288 354c-25.405 0-46 20.595-46 46s20.595 46 46 46 46-20.595 46-46-20.595-46-46-46zm-43.673-165.346l7.418 136c.347 6.364 5.609 11.346 11.982 11.346h48.546c6.373 0 11.635-4.982 11.982-11.346l7.418-136c.375-6.874-5.098-12.654-11.982-12.654h-63.383c-6.884 0-12.356 5.78-11.981 12.654z"
                        ></path></svg
                    ><span>This is warning alert</span></ps-alert
                >
                <ps-alert theme="alert-dark"
                    ><span>This is dark alert</span></ps-alert
                >
                <ps-alert theme="alert-light"
                    ><span>This is light alert</span></ps-alert
                >

                <ps-line theme="my-5" />

                <!-- Textarea -->
                <ps-label-title class="mt-4">
                    {{ $t("ui_collection__textarea_ui") }}
                </ps-label-title>
                <div class="flex flex-col">
                    <ps-textarea
                        placeholder="Message..."
                        rows="5"
                        v-model:value="textarea"
                    />
                    {{ textarea }}
                </div>

                <ps-line theme="my-5" />

                <!-- Rating -->
                <ps-label-title class="mt-4">
                    {{ $t("ui_collection__rating_ui") }}
                </ps-label-title>
                <ps-rating :grade="2.5" :hasCounter="true" />
                <ps-rating-selected />

                <ps-line theme="my-5" />

                <!-- CheckBox -->
                <ps-label-title class="mt-4">
                    {{ $t("ui_collection__checkbox_fixed") }}
                </ps-label-title>
                <div class="flex items-center mt-4">
                    <ps-checkbox-value
                        v-model:value="checkedFruits.apple"
                        :title="$t('core.ui_collection__apple')"
                    />
                    <ps-checkbox-value
                        v-model:value="checkedFruits.orange"
                        :title="$t('core.ui_collection__orange')"
                    />
                    <ps-checkbox-value
                        v-model:value="checkedFruits.grape"
                        :title="$t('core.ui_collection__grape')"
                    />
                </div>

                <ps-label class="mt-4">{{ checkedFruits }}</ps-label>

                <ps-label-title class="mt-4 mb-4">
                    {{ $t("ui_collection__checkbox_dynamic") }}
                </ps-label-title>

                <ps-checkbox
                    v-for="cha in checkedDataList"
                    :key="selectData.id"
                    :value="selectData"
                    v-model:selectedValue="checkedSelectedList"
                    :title="selectData.name"
                >
                    {{ selectData.name }}
                </ps-checkbox>

                <ps-label class="mt-4">{{ checkedSelectedList }}</ps-label>

                <ps-line theme="my-5" />

                <!-- Radio -->
                <ps-label-title class="mt-8">
                    {{ $t("ui_collection__radio_fixed") }}
                </ps-label-title>
                <div class="flex items-center mt-4" id="r1">
                    <ps-radio-value
                        v-model:value="radioFruits"
                        :title="$t('core.ui_collection__apple')"
                    />
                    <ps-radio-value
                        v-model:value="radioFruits"
                        :title="$t('core.ui_collection__orange')"
                    />
                    <ps-radio-value
                        v-model:value="radioFruits"
                        :title="$t('core.ui_collection__grape')"
                    />
                    <ps-radio-value
                        v-model:value="radioFruits"
                        :title="$t('core.ui_collection__mango')"
                    />
                </div>
                <ps-label class="mt-4">{{ radioFruits }}</ps-label>

                <ps-label-title class="mt-4">
                    {{ $t("ui_collection__radio_dynamic") }}
                </ps-label-title>
                <div class="flex items-center mt-4" id="r1">
                    <ps-radio
                        v-for="selectData in radioDataList"
                        :key="selectData.id"
                        :value="selectData"
                        v-model:selectedValue="radioSelectedList"
                        :title="selectData.name"
                    ></ps-radio>
                </div>
                <ps-label class="mt-4">{{ radioSelectedList }}</ps-label>

                <ps-line theme="my-5" />

                <!-- Select -->
                <ps-label-title class="mt-8">
                    {{ $t("ui_collection__select_dynamic") }}
                </ps-label-title>

                <ps-select
                    v-model:value="selectedValue"
                    class="mt-4"
                    :dataList="selectDataList"
                />
                <ps-label class="mt-4">{{ selectedValue }}</ps-label>
                <br />
                <div class="flex">
                    <p>{{ $t("ui_collection__test") }}</p>

                    <ps-dropdown align="right">
                        <template #select>
                            <ps-dropdown-select
                                :selectedValue="
                                    'Transaction History ' + selectedIndex
                                "
                                w="w-96"
                            />
                        </template>
                        <template #list>
                            <div
                                class="rounded-md bg-white shadow-xs w-96"
                                role="menu"
                                aria-orientation="vertical"
                                aria-labelledby="options-menu"
                            >
                                <div class="pt-2">
                                    <div
                                        v-for="i in 5"
                                        :key="i"
                                        class="flex py-4 px-2 hover:bg-red-100 cursor-pointer items-center"
                                        @click="onItemClick(i)"
                                    >
                                        <img
                                            width="300px"
                                            height="200px"
                                            alt="Placeholder"
                                            src="https://s.svgbox.net/hero-outline.svg?ic=currency-rupee"
                                            class="h-6 w-6"
                                        />
                                        <span
                                            class="ms-2"
                                            :class="
                                                i == selectedIndex
                                                    ? 'text-red-500'
                                                    : 'text-primary-500'
                                            "
                                        >
                                            {{
                                                $t(
                                                    "core.ui_collection__transaction_history"
                                                )
                                            }}
                                            {{ i }}
                                        </span>
                                    </div>
                                </div>
                                <div class="border-t border-gray-100"></div>
                            </div>
                        </template>
                    </ps-dropdown>
                </div>

                <ps-line theme="my-5" />

                <!-- Icons -->
                <ps-label-title class="mt-8">
                    {{ $t("ui_collection__icons") }}
                </ps-label-title>
                <div class="flex mt-8">
                    <ps-icon name="downArrow" class="text-orange-500" />
                    <ps-icon name="heart" class="text-orange-500 ms-4" />
                    <ps-icon name="plusCircle" class="ms-4" />
                    <ps-icon name="dashboard" class="ms-4" />
                    <ps-icon name="apps" class="ms-4" />
                    <ps-icon name="rhombusMedium" class="ms-4" />
                    <ps-icon name="hexagonMultiple" class="ms-4" />
                    <ps-icon name="menu" class="ms-4" />
                    <ps-icon name="printer" class="ms-4" />
                    <ps-icon name="plus" class="ms-4" />
                    <ps-icon name="minus" class="ms-4" />
                    <ps-icon name="cogOutline" class="ms-4" />
                    <ps-icon name="search" class="ms-4" />
                </div>
                <ps-label class="mt-4">
                    {{ $t("ui_collection__icon_60_60") }}
                </ps-label>
                <ps-icon name="dashboard" class="mt-4" w="60" h="60" />
                <ps-icon
                    name="cogOutline"
                    class="mt-4 text-purple-500"
                    w="60"
                    h="60"
                />

                <ps-line theme="my-5" />

                <!-- Full Page Loading -->
                <ps-label-title class="mt-8">
                    {{ $t("ui_collection__full_page_loading") }}
                </ps-label-title>

                <ps-button class="mt-2" @click="loadMore">
                    {{ $t("ui_collection__full_page_loading") }}
                </ps-button>

                <ps-line theme="my-5" />

                <!-- Countdown -->
                <ps-label-title class="mt-8">
                    {{ $t("ui_collection__countdown") }}
                </ps-label-title>
                {{ countDown }}

                <ps-line theme="my-5" />

                <!-- Modal -->
                <ps-label-title class="mt-8"
                    >{{ $t("ui_collection__modal") }}
                </ps-label-title>
                <ps-button class="mt-2" @click="psmodal.openModal()">
                    {{ $t("ui_collection__open") }}
                </ps-button>
                <ps-button class="mt-2" @click="psMapModal.toggle(true)">
                    {{ $t("ui_collection__open_map_modal") }}
                </ps-button>

                <ps-line theme="my-5" />

                <!-- Skeletor -->
                <ps-label-title class="mt-8">
                    {{ $t("ui_collection__skeletor") }}
                </ps-label-title>
                <div class="w-96 my-8">
                    <div class="flex items-start">
                        <div>
                            <Skeletor circle size="48" />
                        </div>
                        <div class="w-52 rounded-md ms-4 mt-1">
                            <Skeletor height="15" class="rounded-md" />
                            <Skeletor height="20" class="rounded-md mt-2" />
                        </div>
                    </div>
                </div>

                <ps-line theme="my-5" />

                <!-- Speak -->
                <ps-label-title class="mt-8">
                    {{ $t("ui_collection__speak") }}
                </ps-label-title>

                <ps-button class="mt-2" @click="speak">
                    {{ $t("ui_collection__speak") }}
                </ps-button>

                <ps-line theme="my-5" />

                <!-- Card -->
                <ps-label-title class="mt-8">
                    {{ $t("ui_collection__card") }}
                </ps-label-title>
                <ps-card
                    class="flex flex-col my-3"
                    enabledHover="true"
                    cursorPointer="true"
                >
                    <div>Card</div>
                </ps-card>

                <ps-custom-card class="flex flex-col mt-3" enabledHover="true">
                    <template #card-title>
                        <div class="p-3 bg-primary-000 shadow-sm">
                            <ps-label-title>Card title</ps-label-title>
                        </div>
                    </template>
                    <template #card-body>
                        <div class="p-3">
                            <ps-label-title>Card body</ps-label-title>
                        </div>
                    </template>
                    <template #card-footer>
                        <div class="p-3 bg-gray-200 flex gap-3">
                            <ps-button>Save</ps-button>
                            <ps-button>Cancel</ps-button>
                        </div>
                    </template>
                </ps-custom-card>

                <ps-line theme="my-5" />
                <ps-label-title class="mt-8">
                    Datetime Picker UI
                </ps-label-title>
                <ps-date-picker class="mt-5" />

                <datepicker
                    v-model="date"
                    range
                    :enableTimePicker="false"
                    placeholder="Select Date"
                    class="mt-5"
                />
                {{ date }}

                <datepicker
                    v-model="time"
                    timePicker
                    :is24="false"
                    placeholder="Select Time"
                    class="mt-5"
                />
                {{ time }}
                <!-- <ps-time-picker/> -->
                <ps-line theme="my-5" />

                <!-- Chart  -->
                <ps-label-title class="mt-8"> Chart UI </ps-label-title>
                <bar-chart
                    :chartOptions="chartOptions"
                    :chartData="chartData"
                />
                <pie-chart
                    :chartOptions="pieChartOptions"
                    :chartData="pieChartData"
                />
                <bubble-chart
                    :chartOptions="bubbleChartOptions"
                    :chartData="bubbleChartData"
                />
                <doughnut-chart
                    :chartOptions="doughnutChartOptions"
                    :chartData="doughnutChartData"
                />
                <line-chart
                    :chartOptions="lineChartOptions"
                    :chartData="lineChartData"
                />
                <scatter-chart
                    :chartOptions="scatterChartOptions"
                    :chartData="scatterChartData"
                />
                <polar-area-chart
                    :chartOptions="polarChartOptions"
                    :chartData="polarChartData"
                />
                <radar-chart
                    :chartOptions="radarChartOptions"
                    :chartData="radarChartData"
                />

                <ps-line theme="my-5" />

                <!-- Progress Bar -->
                <ps-label-title class="mt-8"> Progresss Bar UI </ps-label-title>
                <base-progress :percentage="50" class="mx-2 mb-2 h-20" />

                <ps-line theme="my-5" />

                <!-- CKEditor -->
                <!-- <ps-label-title class="mt-8"> Editor UI </ps-label-title>
                <ps-editor />

                <ps-line theme="my-5"/> -->
            </div>
    </ps-layout>
</template>

<script>
import { defineComponent, onUnmounted, onMounted, reactive, ref } from "vue";
// import { useLoading } from "vue3-loading-overlay";
// import "vue3-loading-overlay/dist/vue3-loading-overlay.css";

import PsLayout from "@/Components/PsLayout.vue";

import PsTable from "@/Components/Core/Table/PsTable.vue";

import PsLine from "@/Components/Core/Line/PsLine.vue";

import PsIcon from "@/Components/Core/Icons/PsIcon.vue";

import PsLabel from "@/Components/Core/Label/PsLabel.vue";
import PsLabelTitle from "@/Components/Core/Label/PsLabelTitle.vue";
import PsLabelTitle2 from "@/Components/Core/Label/PsLabelTitle2.vue";
import PsLabelTitle3 from "@/Components/Core/Label/PsLabelTitle3.vue";
import PsLabelTitle4 from "@/Components/Core/Label/PsLabelTitle4.vue";
import PsLabelHeader6 from "@/Components/Core/Label/PsLabelHeader6.vue";
import PsLabelHeader5 from "@/Components/Core/Label/PsLabelHeader5.vue";
import PsLabelHeader4 from "@/Components/Core/Label/PsLabelHeader4.vue";
import PsLabelHeader3 from "@/Components/Core/Label/PsLabelHeader3.vue";
import PsLabelHeader2 from "@/Components/Core/Label/PsLabelHeader2.vue";
import PsLabelHeader1 from "@/Components/Core/Label/PsLabelHeader1.vue";
import PsLabelCaption from "@/Components/Core/Label/PsLabelCaption.vue";
import PsLabelCaption2 from "@/Components/Core/Label/PsLabelCaption2.vue";
import PsLabelCaption3 from "@/Components/Core/Label/PsLabelCaption3.vue";

import PsInput from "@/Components/Core/Input/PsInput.vue";
import PsInputWithRightIcon from "@/Components/Core/Input/PsInputWithRightIcon.vue";
import PsInputWithLeftIcon from "@/Components/Core/Input/PsInputWithLeftIcon.vue";

import PsTextarea from "@/Components/Core/Textarea/PsTextarea.vue";

import PsButton from "@/Components/Core/Buttons/PsButton.vue";
import PsBadge from "@/Components/Core/Badge/PsBadge.vue";
import PsAlert from "@/Components/Core/Alert/PsAlert.vue";

import PsRating from "@/Components/Core/Rating/PsRating.vue";
import PsRatingSelected from "@/Components/Core/Rating/PsRatingSelected.vue";

import PsCheckbox from "@/Components/Core/Checkbox/PsCheckbox.vue";
import PsCheckboxValue from "@/Components/Core/Checkbox/PsCheckboxValue.vue";

import PsRadio from "@/Components/Core/Radio/PsRadio.vue";
import PsRadioValue from "@/Components/Core/Radio/PsRadioValue.vue";

import PsSelect from "@/Components/Core/Select/PsSelect.vue";
import PsDropdown from "@/Components/Core/Dropdown/PsDropdown.vue";
import PsDropdownSelect from "@/Components/Core/Dropdown/PsDropdownSelect.vue";

import { Skeletor } from "vue-skeletor";
import "vue-skeletor/dist/vue-skeletor.css";

import PsModal from "@/Components/Core/Modals/PsModal.vue";
import PsModelView from "@/Components/Widgets/PsModalView.vue";
import { GoogleMap, Circle } from "vue3-google-map";
import PsUtils from '@templateCore/utils/PsUtils';

import PsCard from "@/Components/Core/Card/PsCard.vue";

import PsDatePicker from "@/Components/Core/Picker/PsDatePicker.vue";

import Datepicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";

import BarChart from "@/Components/Core/Charts/BarChart.vue";
import PieChart from "@/Components/Core/Charts/PieChart.vue";
import BubbleChart from "@/Components/Core/Charts/BubbleChart.vue";
import DoughnutChart from "@/Components/Core/Charts/DoughnutChart.vue";
import LineChart from "@/Components/Core/Charts/LineChart.vue";
import ScatterChart from "@/Components/Core/Charts/ScatterChart.vue";
import PolarAreaChart from "@/Components/Core/Charts/PolarAreaChart.vue";
import RadarChart from "@/Components/Core/Charts/RadarChart.vue";

import BaseProgress from "@/Components/Core/Progressbar/BaseProgress.vue";

import PsEditor from "@/Components/Core/CKEditor/PsEditor.vue";

// import vSelect from "vue-select";
// import "vue-select/dist/vue-select.css";

export default {
    components: {
        PsLine,
        PsIcon,

        PsTable,
        PsLabel,
        PsLabelTitle,
        PsLabelTitle2,
        PsLabelTitle3,
        PsLabelTitle4,
        PsLabelHeader6,
        PsLabelHeader5,
        PsLabelHeader4,
        PsLabelHeader3,
        PsLabelHeader2,
        PsLabelHeader1,
        PsLabelCaption,
        PsLabelCaption2,
        PsLabelCaption3,

        PsInput,
        PsInputWithRightIcon,
        PsInputWithLeftIcon,

        PsTextarea,

        PsButton,
        PsBadge,
        PsAlert,

        PsRating,
        PsRatingSelected,

        PsCheckbox,
        PsCheckboxValue,

        PsRadio,
        PsRadioValue,

        PsSelect,
        PsDropdown,
        PsDropdownSelect,

        PsModal,
        PsModelView,
        GoogleMap,
        Circle,

        Skeletor,

        PsCard,

        PsDatePicker,
        Datepicker,

        BarChart,
        PieChart,
        BubbleChart,
        DoughnutChart,
        LineChart,
        ScatterChart,
        PolarAreaChart,
        RadarChart,

        BaseProgress,

        PsEditor,

        // vSelect,
    },
    layout: PsLayout,
    data() {
        return {
            input: "",
            textarea: "",
            inputLeftIcon: "",
            inputRightIcon: "",
            selectedMultiple: [],
            options: [
                { code: "ca", country: "Canada" },
                { code: "fr", country: "French" },
                { code: "en", country: "English" },
            ],
            selectedTableData: [],
        };
    },
    methods: {
        edit(item) {
            alert(item.id + ' ' + item.name);
        },
    },
    setup() {
        // For Checkbox
        const checkedFruits = reactive({
            apple: false,
            orange: false,
            grape: false,
        });
        const checkedDataList = reactive([
            {
                id: 1,
                name: "Apple",
            },
            {
                id: 2,
                name: "Orange",
            },
            {
                id: 3,
                name: "Grape",
            },
        ]);
        const checkedSelectedList = reactive([]);

        // For Radio
        const radioDataList = reactive([
            {
                id: 1,
                name: "Apple",
            },
            {
                id: 2,
                name: "Orange",
            },
            {
                id: 3,
                name: "Grape",
            },
        ]);
        const radioSelectedList = reactive({});
        const radioFruits = ref();

        // For Select
        const selectDataList = reactive([
            {
                id: 1,
                name: "$1,000",
            },
            {
                id: 2,
                name: "$2,000",
            },
            {
                id: 3,
                name: "$3,000",
            },
            {
                id: 4,
                name: "$4,000",
            },
            {
                id: 5,
                name: "$5,000",
            },
        ]);
        const selectedValue = ref("5");
        const selectedIndex = ref(3);
        function onItemClick(i) {
            selectedIndex.value = i;
        }

        // For Full Page Loading
        const fullPage = ref(true);
        const formContainer = ref(null);
        function onCancel() {}
        function loadMore() {
            // const loader = useLoading();
            // loader.show({
            //     // Optional parameters
            //     container: fullPage.value ? null : formContainer.value,
            //     canCancel: true,
            //     onCancel: onCancel,
            // });

            // // simulate AJAX
            // setTimeout(() => {
            //     loader.hide();
            // }, 2000);
        }

        // For countdown
        const countDown = ref("0");
        const toHHMMSS = (seconds) => {
            seconds = Number(seconds);
            const d = Math.floor(seconds / (3600 * 24));
            const h = Math.floor((seconds % (3600 * 24)) / 3600);
            const m = Math.floor((seconds % 3600) / 60);
            const s = Math.floor(seconds % 60);

            const dDisplay = d > 0 ? d + (d == 1 ? " day, " : " days, ") : "";
            const hDisplay = h > 0 ? h + (h == 1 ? " hour, " : " hours, ") : "";
            const mDisplay =
                m > 0 ? m + (m == 1 ? " minute, " : " minutes, ") : "";
            const sDisplay = s > 0 ? s + (s == 1 ? " second" : " seconds") : "";
            return dDisplay + hDisplay + mDisplay + sDisplay;
        };
        // Interval
        const timer = setInterval(() => {
            const now = new Date();
            const end = new Date(2023, 1, 22, 19, 40, 1);
            const distance = end.getTime() - now.getTime();

            if (distance < 0) {
                clearInterval(timer);
                return;
            }
            countDown.value = toHHMMSS(distance / 1000);
        }, 1000);
        onUnmounted(() => {
            clearInterval(timer);
        });

        // For modal
        const psmodal = ref();
        const psMapModal = ref();

        //MAP
        const mcenter = ref({
            position: {
                lat: 38.735086,
                lng: -9.141247,
            },
            draggable: false,
        });
        function updateCoordinates(location) {
            PsUtils.log(location.latLng.lat());
            PsUtils.log(location.latLng.lng());
        }
        // Radius * 2 = meters
        const circleCenter = {
            editable: true,
            center: mcenter.value.position,
            radius: 300,
            strokeColor: "#FF0000",
            strokeOpacity: 0.8,
            strokeWeight: 2,

            draggable: false,
            crossOnDrag: false,
        };
        const map = {
            key: "000",
            center: mcenter.value.position,
            zoom: 15,
        };

        // For speak
        const greetingSpeech = new window.SpeechSynthesisUtterance();
        const voices = window.speechSynthesis.getVoices();
        greetingSpeech.voice = voices[17];

        function speak() {
            greetingSpeech.text =
                "Hello! Welcome from PanaceaSoft. Your bidding is going to finish. 5, 4, 3, 2, 1. bidding completed. Thanks for using our service. Ended.";
            window.speechSynthesis.speak(greetingSpeech);
        }

        // For table
        const headers = [
            { text: "#", width: "w-1/5", align: "text-left" },
            {
                text: "Category Name",
                width: "w-2/5",
                align: "text-center",
                theme: "uppercase",
            },
            {
                text: "Action",
                width: "w-2/5",
                align: "text-center",
                theme: "uppercase",
            },
        ];
        const data = [
            { id: 1, category_name: "apple" },
            { id: 2, category_name: "orange" },
            { id: 3, category_name: "grape" },
            { id: 4, category_name: "banana" },
            { id: 5, category_name: "pineapple" },
        ];

        // For date picker
        const date = ref();
        // For demo purposes assign range from the current date
        onMounted(() => {
            const startDate = new Date();
            const endDate = new Date(
                new Date().setDate(startDate.getDate() + 7)
            );
            date.value = [startDate, endDate];
        });
        const time = ref();

        // For bar chart
        const chartData = {
            labels: ["January", "February", "March"],
            datasets: [
                {
                    label: "Monthly Dataset",
                    backgroundColor: [
                        "rgba(255, 99, 132, 0.2)",
                        "rgba(255, 159, 64, 0.2)",
                        "rgba(255, 205, 86, 0.2)",
                    ],
                    borderColor: [
                        "rgb(255, 99, 132)",
                        "rgb(255, 159, 64)",
                        "rgb(255, 205, 86)",
                    ],
                    borderWidth: 1,
                    color: "#666",
                    data: [30, 20, 10],
                },
            ],
        };
        const chartOptions = {
            responsive: true,
        };

        //For Pie Chart
        const pieChartData = {
            labels: ["VueJs", "EmberJs", "ReactJs", "AngularJs"],
            datasets: [
                {
                    backgroundColor: [
                        "#41B883",
                        "#E46651",
                        "#00D8FF",
                        "#DD1B16",
                    ],
                    data: [40, 20, 80, 10],
                },
            ],
        };
        const pieChartOptions = {
            responsive: true,
            maintainAspectRatio: false,
        };

        // For bubble chart
        const bubbleChartData = {
            datasets: [
                {
                    label: "Data One",
                    backgroundColor: "#f87979",
                    data: [
                        { x: 20, y: 25, r: 5 },
                        { x: 40, y: 10, r: 10 },
                        { x: 30, y: 22, r: 30 },
                    ],
                },
                {
                    label: "Data Two",
                    backgroundColor: "#7C8CF8",
                    data: [
                        { x: 10, y: 30, r: 15 },
                        { x: 20, y: 20, r: 10 },
                        { x: 15, y: 8, r: 30 },
                    ],
                },
            ],
        };

        const bubbleChartOptions = {
            responsive: true,
            maintainAspectRatio: false,
        };

        // For doughnut chart
        const doughnutChartData = {
            labels: ["VueJs", "EmberJs", "ReactJs", "AngularJs"],
            datasets: [
                {
                    backgroundColor: [
                        "#41B883",
                        "#E46651",
                        "#00D8FF",
                        "#DD1B16",
                    ],
                    data: [40, 20, 80, 10],
                },
            ],
        };

        const doughnutChartOptions = {
            responsive: true,
            maintainAspectRatio: false,
        };

        // For line chart
        const lineChartData = {
            labels: [
                "January",
                "February",
                "March",
                "April",
                "May",
                "June",
                "July",
            ],
            datasets: [
                {
                    label: "Data One",
                    backgroundColor: "#f87979",
                    data: [40, 39, 10, 40, 39, 80, 40],
                },
            ],
        };

        const lineChartOptions = {
            responsive: true,
            maintainAspectRatio: false,
        };

        // For scatter chart
        const scatterChartData = {
            datasets: [
                {
                    label: "Scatter Dataset 1",
                    fill: false,
                    borderColor: "#f87979",
                    backgroundColor: "#f87979",
                    data: [
                        { x: -2, y: 4 },
                        { x: -1, y: 1 },
                        { x: 0, y: 0 },
                        { x: 1, y: 1 },
                        { x: 2, y: 4 },
                    ],
                },
                {
                    label: "Scatter Dataset 2",
                    fill: false,
                    borderColor: "#7acbf9",
                    backgroundColor: "#7acbf9",
                    data: [
                        { x: -2, y: -4 },
                        { x: -1, y: -1 },
                        { x: 0, y: 1 },
                        { x: 1, y: -1 },
                        { x: 2, y: -4 },
                    ],
                },
            ],
        };

        const scatterChartOptions = {
            responsive: true,
            maintainAspectRatio: false,
        };
        // For polar chart
        const polarChartData = {
            labels: [
                "Eating",
                "Drinking",
                "Sleeping",
                "Designing",
                "Coding",
                "Cycling",
                "Running",
            ],
            datasets: [
                {
                    label: "My First dataset",
                    backgroundColor: "rgba(179,181,198,0.2)",
                    pointBackgroundColor: "rgba(179,181,198,1)",
                    pointBorderColor: "#fff",
                    pointHoverBackgroundColor: "#fff",
                    pointHoverBorderColor: "rgba(179,181,198,1)",
                    data: [65, 59, 90, 81, 56, 55, 40],
                },
                {
                    label: "My Second dataset",
                    backgroundColor: "rgba(255,99,132,0.2)",
                    pointBackgroundColor: "rgba(255,99,132,1)",
                    pointBorderColor: "#fff",
                    pointHoverBackgroundColor: "#fff",
                    pointHoverBorderColor: "rgba(255,99,132,1)",
                    data: [28, 48, 40, 19, 96, 27, 100],
                },
            ],
        };

        const polarChartOptions = {
            responsive: true,
            maintainAspectRatio: false,
        };
        // For radar chart
        const radarChartData = {
            labels: [
                "Eating",
                "Drinking",
                "Sleeping",
                "Designing",
                "Coding",
                "Cycling",
                "Running",
            ],
            datasets: [
                {
                    label: "My First dataset",
                    backgroundColor: "rgba(179,181,198,0.2)",
                    borderColor: "rgba(179,181,198,1)",
                    pointBackgroundColor: "rgba(179,181,198,1)",
                    pointBorderColor: "#fff",
                    pointHoverBackgroundColor: "#fff",
                    pointHoverBorderColor: "rgba(179,181,198,1)",
                    data: [65, 59, 90, 81, 56, 55, 40],
                },
                {
                    label: "My Second dataset",
                    backgroundColor: "rgba(255,99,132,0.2)",
                    borderColor: "rgba(255,99,132,1)",
                    pointBackgroundColor: "rgba(255,99,132,1)",
                    pointBorderColor: "#fff",
                    pointHoverBackgroundColor: "#fff",
                    pointHoverBorderColor: "rgba(255,99,132,1)",
                    data: [28, 48, 40, 19, 96, 27, 100],
                },
            ],
        };

        const radarChartOptions = {
            responsive: true,
            maintainAspectRatio: false,
        };

        // For ckeditor
        const desc = reactive({
            description: "",
        });

        // For data table
        const columns = [
            {
                label: "Name",
                field: "name",
                width: "w-1/3",
                align: "text-left",
                sortable: true,
                type: 'String'
            },
            {
                label: "Age",
                field: "age",
                width: "w-auto",
                align: "text-right",
                sortable: true,
                type:'Integer',
            },
            {
                label: "Gender",
                field: "gender",
                width: "w-auto",
                align: "text-center",
                type:'Integer',
            },
            {
                label: "Status",
                field: "status",
                width: "w-auto",
                align: "text-center",
                type:'Integer',
            },
            {
                label: "Added Date",
                field: "created_date",
                width: "w-auto",
                align: "text-center",
                sortable: true,
                type:'Date',
            },
            {
                label: "Action",
                field: "action",
                width: "w-auto",
                align: "text-center",
                type:'Action',
            },
        ];
        const rows = [
            {
                id: "1",
                name: "Mg  Mg",
                age: "18",
                gender: 1,
                status: "1",
                created_date: "2022-05-21",
            },
            {
                id: "2",
                name: "Aung Aung",
                age: "20",
                gender: 1,
                status: "1",
                created_date: "2022-05-22",
            },
            {
                id: "3",
                name: "Hla Hla",
                age: "30",
                gender: 2,
                status: "0",
                created_date: "2022-05-21",
            },
            {
                id: "4",
                name: "Mya Mya",
                age: "10",
                gender: 2,
                status: "1",
                created_date: "2022-05-20",
            },
            {
                id: "5",
                name: "Tun Tun",
                age: "15",
                gender: 2,
                status: "0",
                created_date: "2022-05-21",
            },
            {
                id: "6",
                name: "Tun Tun",
                age: "15",
                gender: 1,
                status: "0",
                created_date: "2022-05-22",
            },
            {
                id: "7",
                name: "Tun Tun",
                age: "15",
                gender: 2,
                status: "0",
                created_date: "2022-05-23",
            },
            {
                id: "8",
                name: "Tun Tun",
                age: "15",
                gender: 1,
                status: "0",
                created_date: "2022-05-24",
            },
            {
                id: "9",
                name: "Tun Tun",
                age: "15",
                gender: 2,
                status: "0",
                created_date: "2022-05-25",
            },
            {
                id: "10",
                name: "Tun Tun",
                age: "15",
                gender: 2,
                status: "1",
                created_date: "2022-05-26",
            },
        ];

        const selectedDataList = [
            {
                id: 0,
                name: "Choose...",
            },
            {
                id: 1,
                name: "male",
            },
            {
                id: 2,
                name: "female",
            },
        ];

        const globalSearchFields = ['name', 'age']

        const gender = [
            {
                id: '',
                name: "Choose...",
            },
            {
                id: '1',
                name: "male",
            },
            {
                id: '2',
                name: "female",
            },
        ];
        const status = [
            {
                id: '',
                name: "Choose...",
            },
            {
                id: '1',
                name: "active",
            },
            {
                id: '0',
                name: "unactive",
            },
        ];
        const searchOptions = [
                {search_type: 'dropdown', value:'', dropdown_items:gender, field:'gender'},
                {search_type: 'dropdown', value:'', dropdown_items:status, field:'status'},
                {search_type: 'dateRange', value:'', field:'created_date'},
            ]

        return {
            checkedFruits,
            checkedDataList,
            checkedSelectedList,

            radioDataList,
            radioSelectedList,
            radioFruits,

            selectDataList,
            selectedValue,
            selectedIndex,
            onItemClick,

            loadMore,

            countDown,

            psmodal,
            psMapModal,
            mcenter,
            circleCenter,
            map,
            updateCoordinates,

            speak,

            headers,
            data,

            date,
            time,

            chartData,
            chartOptions,

            pieChartData,
            pieChartOptions,

            bubbleChartData,
            bubbleChartOptions,

            doughnutChartData,
            doughnutChartOptions,

            lineChartData,
            lineChartOptions,

            scatterChartData,
            scatterChartOptions,

            polarChartData,
            polarChartOptions,

            radarChartData,
            radarChartOptions,

            rows,
            columns,
            selectedDataList,
            globalSearchFields,
            searchOptions,
        };
    },
};
</script>
