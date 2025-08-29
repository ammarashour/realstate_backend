<template>
    <Head :title="$t('core__edit_city')" />
    <ps-layout>
        <!-- breadcrumb start -->
        <ps-breadcrumb-2 :items="breadcrumb" class="mb-5 sm:mb-6 lg:mb-8" />
        <!-- breadcrumb end -->

        <ps-card class="w-full h-auto">
            <div class="rounded-xl">
                <!-- card header start -->
                <div class="bg-primary-50 dark:bg-primary-900 py-2.5 ps-4 rounded-t-xl">
                    <ps-label-header-6 textColor="text-secondary-800 dark:text-secondary-100">{{$t('core__city_info')}}</ps-label-header-6>
                </div>
                <!-- card header end -->

                <!-- card body start -->
                <div class="px-4 pt-6 dark:bg-backgroundDark">
                    <form @submit.prevent="handleSubmit(this.city.id)">
                    <div class="grid w-full sm:w-1/2 gap-6">
                        <div>
                            <ps-label class="text-base mb-2">{{$t('core__be_city_name_label')}}<span class="text-red-800 font-medium ms-1">*</span></ps-label>
                            <ps-input type="text" v-model:value="form.name" :placeholder="$t('core__be_city_name_placeholder')"
                                @keyup="validateLocationNameInput('name', form.name)"
                                @blur="validateLocationNameInput('name', form.name)"/>
                            <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ errors.name }}</ps-label-caption>
                        </div>

                        <div>
                            <ps-label class="text-base mb-2">{{$t('core__be_ordering_label')}}</ps-label>
                            <ps-input type="text" v-model:value="form.ordering" :placeholder="$t('core__be_ordering_placeholder')"
                                    @keypress="onlyNumber"/>
                            <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ errors.ordering }}</ps-label-caption>
                        </div>

                        <div>
                            <ps-label class="text-base mb-2">{{$t('core__be_description_label')}}</ps-label>
                            <ps-textarea rows="3" v-model:value="form.description" :placeholder="$t('core__be_description_placeholder')"
                                ></ps-textarea>
                            <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ errors.description }}</ps-label-caption>
                        </div>

                        <!-- <div>
                            <google-map-pin-picker :mapKey="$page.props.mapKey"  :lat="parseFloat(form.lat)" :lng="parseFloat(form.lng)" widthHeight="width: 458px; height: 500px"
                                :onChange="updateCoordinates" />
                        </div> -->
                        <!-- maps start -->
                        <div class="mb-6" v-if="backendSettings.is_google_map == 1">
                            <google-map-pin-picker :mapKey="$page.props.mapKey"  :lat="parseFloat(form.lat)" :lng="parseFloat(form.lng)"
                                widthHeight="width: 458px; height: 500px" :onChange="updateCoordinates" />
                        </div>
                        <div class="mb-6" v-else>
                            <open-street-map :dir="$page.props.dir" :onChange="setCoordinates" :lat="parseFloat(form.lat)" :lng="parseFloat(form.lng)" class="h-[500px]"></open-street-map>
                        </div>
                        <!-- maps end -->

                        <div>
                            <ps-label class="text-base mb-2">{{$t('core__be_latitude_label')}}</ps-label>
                            <ps-input type="text" v-model:value="form.lat" :placeholder="$t('core__be_latitude_placeholder')"
                                @keypress="onlyNumberWithCustom"
                                @keyup="validateLatitudeInput('lat', form.lat)"
                                @blur="validateLatitudeInput('lat', form.lat)"/>
                            <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ errors.lat }}</ps-label-caption>
                        </div>

                        <div>
                            <ps-label class="text-base mb-2">{{$t('core__be_longitude_label')}}</ps-label>
                            <ps-input type="text" v-model:value="form.lng" :placeholder="$t('core__be_longitude_placeholder')"
                                @keypress="onlyNumberWithCustom"
                                @keyup="validateLongitudeInput('lng', form.lng)"
                                @blur="validateLongitudeInput('lng', form.lng)"/>
                            <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ errors.lng }}</ps-label-caption>
                        </div>

                        <!-- custom field start -->
                        <div v-for="(custom_field_header) in customizeHeaders" :key="custom_field_header.id">
                            <!-- dropdown-->
                            <div class="mb-4" v-if="custom_field_header.ui_type_id === 'uit00001' && custom_field_header.enable === 1 && custom_field_header.is_delete === 0">

                                <ps-label class="text-base">
                                    {{ $t(custom_field_header.name) }}<span class="text-red-800 font-medium ms-1" v-show="custom_field_header.mandatory==1">*</span>
                                </ps-label>
                                <ps-dropdown align="left" class='lg:mt-2 mt-1  w-full'>
                                    <template #select>
                                        <ps-dropdown-select ref="currency" :placeholder="$t(custom_field_header.placeholder)" :showCenter="true" :selectedValue="this.customizeDetails.filter(currency=>currency.id == this.form.city_relation[custom_field_header.core_keys_id] ).length > 0 ? this.customizeDetails.filter(currency=>currency.id == this.form.city_relation[custom_field_header.core_keys_id] )[0].name : ''"
                                                            @change="handleCustomFieldError(custom_field_header)"
                                                            @blur="handleCustomFieldError(custom_field_header)" />
                                    </template>
                                    <template #list >
                                        <div
                                            class="rounded-md shadow-xs w-full " >
                                            <div class="pt-2  ">

                                                <div v-if="customizeDetails.filter(customizeDetail => customizeDetail.core_keys_id === custom_field_header.core_keys_id ).length === 0">
                                                    <ps-label class='p-2 flex' @click="route('currency.index')">Create new {{ $t(custom_field_header.name) }}</ps-label >
                                                </div>
                                                <div v-else>
                                                    <div v-for="currency in customizeDetails.filter(customizeDetail => customizeDetail.core_keys_id === custom_field_header.core_keys_id )"
                                                            :key="currency.id"
                                                            class="w-96 flex py-4 px-2 hover:bg-primary-000 dark:hover:bg-secondary-700 cursor-pointer items-center"
                                                            @click="[form.city_relation[custom_field_header.core_keys_id] = currency.id, validateEmptyInput('currency_id', form.city_relation[custom_field_header.core_keys_id])]" >
                                                        <ps-label class="ms-2" :class="currency.id == form.city_relation[custom_field_header.core_keys_id] ? ' font-bold' : ''"  > {{currency.name}} </ps-label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </ps-dropdown>
                                <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ city_relation_errors&&city_relation_errors[custom_field_header.core_keys_id] }}</ps-label-caption>
                            </div>

                            <!-- text-->
                            <div class="mb-4"
                                    v-if="custom_field_header.ui_type_id === 'uit00002' && custom_field_header.enable === 1 && custom_field_header.is_delete === 0">

                                <ps-label><ps-label class="text-red-800 font-medium me-1" v-show="custom_field_header.mandatory==1">*</ps-label>{{ $t(custom_field_header.name) }}</ps-label>
                                <ps-input type="text" class="w-full rounded" :placeholder="$t(custom_field_header.placeholder)"

                                            v-model:value="form.city_relation[custom_field_header.core_keys_id]"
                                            @keyup="handleCustomFieldError(custom_field_header)"
                                            @blur="handleCustomFieldError(custom_field_header)"
                                />
                                <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ city_relation_errors&&city_relation_errors[custom_field_header.core_keys_id] }}</ps-label-caption>
                            </div>

                            <!-- radio-->
                            <div class="mb-4"
                                    v-if="custom_field_header.ui_type_id === 'uit00003' && custom_field_header.enable === 1 && custom_field_header.is_delete === 0">

                                <ps-label class="text-base">
                                    {{ $t(custom_field_header.name) }}
                                    <span class="text-red-800 font-medium ms-1" v-show="custom_field_header.mandatory === 1">*</span>
                                </ps-label>
                                <div class="flex flex-row ">
                                    <div class="me-2"
                                            v-for="detail in customizeDetails.filter(customizeDetail => customizeDetail.core_keys_id === custom_field_header.core_keys_id )"
                                            :key="detail.id">

                                        <ps-radio-value
                                            color="text-purple-600 border-purple-300"
                                            v-model:value="form.city_relation[custom_field_header.core_keys_id]"
                                            :title="String(detail.id)"
                                            :title_label = "detail.name"
                                        />
                                        <ps-label :for="detail.id">{{ detail.attribute }}</ps-label>
                                    </div>
                                </div>
                                <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ city_relation_errors&&city_relation_errors[custom_field_header.core_keys_id] }}</ps-label-caption>
                            </div>

                            <!-- checkbox-->
                            <div class="mb-4"
                                    v-if="custom_field_header.ui_type_id === 'uit00004' && custom_field_header.enable === 1 && custom_field_header.is_delete === 0">
                                <div class="flex flex-row ">
                                    <div class="me-2 flex">
                                        <input type="checkbox" class="rounded border" value="0"
                                                v-model="form.city_relation[custom_field_header.core_keys_id]"
                                                @change="handleCustomFieldError(custom_field_header)"/>
                                        <ps-label class="ms-2">{{ $t(custom_field_header.name) }}</ps-label>
                                    </div>
                                </div>
                                <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ city_relation_errors&&city_relation_errors[custom_field_header.core_keys_id] }}</ps-label-caption>
                            </div>

                            <!-- datetime -->
                            <div class="mb-4"
                                    v-if="custom_field_header.ui_type_id === 'uit00005' && custom_field_header.enable === 1 && custom_field_header.is_delete === 0">
                                <ps-label class="text-base">
                                    {{ $t(custom_field_header.name) }}
                                    <span class="text-red-800 font-medium ms-1" v-show="custom_field_header.mandatory === 1">*</span>
                                </ps-label>
                                <div>
                                    <date-picker
                                        v-model:value="form.city_relation[custom_field_header.core_keys_id]"
                                        @keyup="handleCustomFieldError(custom_field_header)"
                                        @blur="handleCustomFieldError(custom_field_header)"
                                        :enableTimePicker="true"
                                        :inline="false"
                                        :autoApply="false"
                                    />
                                </div>
                                <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ city_relation_errors&&city_relation_errors[custom_field_header.core_keys_id] }}</ps-label-caption>
                            </div>

                            <!-- textarea -->
                            <div class="mb-4"
                                    v-if="custom_field_header.ui_type_id === 'uit00006' && custom_field_header.enable === 1 && custom_field_header.is_delete === 0">
                                <ps-label class="text-base">
                                    {{ $t(custom_field_header.name) }}
                                    <span class="text-red-800 font-medium ms-1" v-show="custom_field_header.mandatory === 1">*</span>
                                </ps-label>
                                <ps-textarea rows="4" :placeholder="$t(custom_field_header.placeholder)"
                                                v-model:value="form.city_relation[custom_field_header.core_keys_id]"
                                                @keyup="handleCustomFieldError(custom_field_header)"
                                                @blur="handleCustomFieldError(custom_field_header)"
                                />
                                <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ city_relation_errors&&city_relation_errors[custom_field_header.core_keys_id] }}</ps-label-caption>
                            </div>

                            <!-- number-->
                            <div class="mb-4"
                                    v-if="custom_field_header.ui_type_id === 'uit00007' && custom_field_header.enable === 1 && custom_field_header.is_delete === 0">
                                <ps-label class="text-base">
                                    {{ $t(custom_field_header.name) }}
                                    <span class="text-red-800 font-medium ms-1" v-show="custom_field_header.mandatory === 1">*</span>
                                </ps-label>
                                <ps-input type="number" class="w-full rounded" :placeholder="$t(custom_field_header.placeholder)"
                                            v-model:value="form.city_relation[custom_field_header.core_keys_id]"
                                            @keyup="handleCustomFieldError(custom_field_header)"
                                            @blur="handleCustomFieldError(custom_field_header)"
                                />
                                <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ city_relation_errors&&city_relation_errors[custom_field_header.core_keys_id] }}</ps-label-caption>
                            </div>

                            <!-- multi Select-->
                            <div class="mb-4"
                                    v-if="custom_field_header.ui_type_id === 'uit00008' && custom_field_header.enable === 1 && custom_field_header.is_delete === 0">
                                <ps-label class="text-base">{{ $t(custom_field_header.name) }}<ps-label class="text-red-800 font-medium me-1" v-show="custom_field_header.mandatory==1">*</ps-label>{{ $t(custom_field_header.name) }}</ps-label>
                                <div class="flex flex-row ">
                                    <CheckBox :oldData="form.city_relation[custom_field_header.core_keys_id]" @toParent="handleMultiSelect" :customizeDetails="customizeDetails"
                                                :customizeHeader="custom_field_header" />
                                </div>
                                <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ city_relation_errors&&city_relation_errors[custom_field_header.core_keys_id] }}</ps-label-caption>
                            </div>

                            <!-- Image-->
                            <div class="mb-4"
                                    v-if="custom_field_header.ui_type_id === 'uit00009' && custom_field_header.enable === 1 && custom_field_header.is_delete === 0">
                                <ps-label class="text-base">
                                    {{ $t(custom_field_header.name) }}
                                    <span class="text-red-800 font-medium ms-1" v-show="custom_field_header.mandatory === 1">*</span>
                                </ps-label>
                                <ps-image-upload
                                    uploadType="image"
                                    v-model:imageFile="form.city_relation[custom_field_header.core_keys_id]"
                                    :imagePath="
                                        $page.props.uploadUrl +
                                        '/' +
                                        form.city_relation[custom_field_header.core_keys_id]
                                    "/>
                                <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ city_relation_errors&&city_relation_errors[custom_field_header.core_keys_id] }}</ps-label-caption>

                            </div>

                            <!-- time Only -->
                            <div class="mb-4"
                                    v-if="custom_field_header.ui_type_id === 'uit00010' && custom_field_header.enable === 1 && custom_field_header.is_delete === 0">
                                <ps-label class="text-base">
                                    {{ $t(custom_field_header.name) }}
                                    <span class="text-red-800 font-medium ms-1" v-show="custom_field_header.mandatory === 1">*</span>
                                </ps-label>
                                <input type="time" class="w-full rounded" v-model="form.city_relation[custom_field_header.core_keys_id]">
                                <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ city_relation_errors&&city_relation_errors[custom_field_header.core_keys_id] }}</ps-label-caption>

                            </div>


                            <!-- date Only -->
                            <div class="mb-4"
                                    v-if="custom_field_header.ui_type_id === 'uit00011' && custom_field_header.enable === 1 && custom_field_header.is_delete === 0">

                                <ps-label class="text-base">
                                    {{ $t(custom_field_header.name) }}
                                    <span class="text-red-800 font-medium ms-1" v-show="custom_field_header.mandatory === 1">*</span>
                                </ps-label>
                                <div>
                                    <date-picker
                                        v-model:value="form.city_relation[custom_field_header.core_keys_id]"
                                        @keyup="handleCustomFieldError(custom_field_header)"
                                        @blur="handleCustomFieldError(custom_field_header)"
                                        :inline="false"
                                        :autoApply="false"
                                    />
                                </div>
                                <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ city_relation_errors&&city_relation_errors[custom_field_header.core_keys_id] }}</ps-label-caption>
                            </div>

                        </div>
                        <!-- /.custom field end -->

                            <div class="flex flex-row justify-end mb-2.5">
                                <ps-button @click="handleCancel()" textSize="text-sm " type="reset" class="me-4" colors="text-primary-500" focus="" hover="">{{ $t('core__be_btn_cancel') }}</ps-button>
                                <ps-button class="transition-all duration-300 min-w-3xs" padding="px-5 py-2" rounded="rounded" hover="" focus="" >
                                    <ps-loading v-if="loading" theme="border-2 border-t-2 border-text-8 border-t-primary-500"  loadingSize="h-5 w-5" />
                                    <ps-icon v-if="success" name="check" w="20" h="20" class="me-1.5 transition-all duration-300" />
                                    <span v-if="success" class="transition-all duration-300">{{ $t('core__be_btn_saved') }}</span>
                                    <span v-if="!loading && !success" class="" > {{ $t('core__be_btn_save') }} </span>
                                </ps-button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </ps-card>
    </ps-layout>
</template>

<script>
import { defineComponent,defineAsyncComponent, ref } from 'vue'
import PsLayout from "@/Components/PsLayout.vue";
import { Head, useForm } from "@inertiajs/vue3";
import useValidators from '@/Validation/Validators'
import PsDropdown from "@/Components/Core/Dropdown/PsDropdown.vue";
import PsDropdownSelect from "@/Components/Core/Dropdown/PsDropdownSelect.vue";
import PsRadioValue from "@/Components/Core/Radio/PsRadioValue.vue";
import DatePicker from "@/Components/Core/DateTime/DatePicker.vue";
import PsImageUpload from "@/Components/Core/Upload/PsImageUpload.vue";


import PsInput from "@/Components/Core/Input/PsInput.vue";
import PsLabel from "@/Components/Core/Label/PsLabel.vue";
import PsButton from "@/Components/Core/Buttons/PsButton.vue";
import PsLabelHeader6 from "@/Components/Core/Label/PsLabelHeader6.vue";
import PsCard from "@/Components/Core/Card/PsCard.vue";
import PsIcon from "@/Components/Core/Icons/PsIcon.vue";
import PsTextarea from '@/Components/Core/Textarea/PsTextarea.vue';
import PsLoading from "@/Components/Core/Loading/PsLoading.vue";
import PsCheckboxValue from "@/Components/Core/Checkbox/PsCheckboxValue.vue";
import PsActionModal from '@/Components/Core/Modals/PsActionModal.vue';
import PsImageIconModal from '@/Components/Core/Modals/PsImageIconModal.vue';
import PsDangerDialog from "@/Components/Core/Dialog/PsDangerDialog.vue";
import PsBreadcrumb2 from "@/Components/Core/Breadcrumbs/PsBreadcrumb2.vue";
import PsLabelCaption from "@/Components/Core/Label/PsLabelCaption.vue";
import CheckBox from "../components/CheckBox.vue";
const GoogleMapPinPicker = defineAsyncComponent(() => import('@/Components/Core/Map/GoogleMapPinPicker.vue'));
const OpenStreetMap = defineAsyncComponent(() => import('@/Components/Core/Map/OpenStreetMapPinPicker.vue'));
import { trans } from 'laravel-vue-i18n';

export default defineComponent({
    name: "Edit",
    components: {
        Head,
        GoogleMapPinPicker,
        OpenStreetMap,
        PsInput,
        PsLabel,
        CheckBox,
        PsDropdown,
        PsDropdownSelect,
        PsRadioValue,
        DatePicker,
        PsImageUpload,
        PsButton,
        PsLabelHeader6,
        PsCard,
        PsTextarea,
        PsIcon,
        PsLoading,
        PsCheckboxValue,
        PsActionModal,
        PsImageIconModal,
        PsDangerDialog,
        PsBreadcrumb2,
        PsLabelCaption
    },
    layout: PsLayout,
    props: ['errors', 'city', 'customizeHeaders', 'customizeDetails', 'coreFieldFilterSettings', 'backendSettings'],
    setup(props) {
        const loading = ref(false);
        const success = ref(false);
        const name = ref();
        const lat = ref();
        const lng = ref();

        // Client Side Validation
        const { isEmpty, minLength, isLatitude, isLongitude, isNum} = useValidators();

        const validateLocationNameInput = (fieldName, fieldValue) => {
            props.errors[fieldName] = !fieldValue ? isEmpty(fieldName, fieldValue) : minLength(fieldName, fieldValue, 3);
            if(fieldName == 'name'){
                name.value.isError = (!Boolean(props.errors.name)) ? false: true;
            }
        }

        const validateLatitudeInput = (fieldName, fieldValue) => {
            props.errors[fieldName] = !fieldValue ? isEmpty(fieldName, fieldValue) : isLatitude(fieldName, fieldValue);
            if(fieldName == 'lat'){
                lat.value.isError = (!Boolean(props.errors.lat)) ? false: true;
            }
        }

        const validateLongitudeInput = (fieldName, fieldValue) => {
            props.errors[fieldName] = !fieldValue ? isEmpty(fieldName, fieldValue) : isLongitude(fieldName, fieldValue);
            if(fieldName == 'lng'){
                lng.value.isError = (!Boolean(props.errors.lng)) ? false: true;
            }
        }

        const validateEmptyInput = (fieldName, fieldValue, errorMessage = '') => {
            props.errors[fieldName] = !fieldValue ? isEmpty(fieldName, fieldValue, errorMessage) : '';
        }

        const validateNumberInput = (fieldName, fieldValue, errorMessage1 = '', errorMessage2 = '') => {
            props.errors[fieldName] = !fieldValue ? isEmpty(fieldName, fieldValue, errorMessage1) : isNum(fieldName, fieldValue, errorMessage2);
        }

        // for only number input validate at keypress
        const onlyNumber = ($e) => {
            let keyCode = ($e.keyCode ? $e.keyCode : $e.which);
            if (keyCode < 48 || keyCode > 57) {
                $e.preventDefault();
            }
        }

        // for custom number input validate at keypress
        const onlyNumberWithCustom = ($e) => {
            let keyCode = ($e.keyCode ? $e.keyCode : $e.which);
            if ((keyCode < 48 || keyCode > 57) && keyCode !== 46 && keyCode !== 45) { // 46 is dot, 45 is dash
                $e.preventDefault();
            }
        }

        let form = useForm(
                {
                    id: props.city.id,
                    name: props.city.name,
                    ordering: props.city.ordering,
                    lat: props.city.lat,
                    lng: props.city.lng,
                    description: props.city.description,
                    ordering: props.city.ordering,
                    city_relation: {},
                    "_method": "put"
                }
            )

        function handleSubmit(id) {
            this.$inertia.post(route("city.update", id), form, {
                forceFormData: true,
                onBefore: () => {
                    loading.value = true;
                },
                onSuccess: () => {
                    loading.value = false;
                    success.value = true;
                    setTimeout(()=>{
                        this.$inertia.get(route('city.index'));
                    },500)
                },
                onError: () => {
                    loading.value = false;
                    name.value.isError = (!Boolean(props.errors.name)) ? false: true;
                    lat.value.isError = (!Boolean(props.errors.lat)) ? false: true;
                    lng.value.isError = (!Boolean(props.errors.lng)) ? false: true;
                },
            });
        }
        function updateCoordinates(location) {
            form.lat = location.latLng.lat();
            form.lng = location.latLng.lng();
        }

        function setCoordinates(e){
            form.lat = e['lat'];
            form.lng = e['lng'];
        }

        return { validateLocationNameInput, setCoordinates, validateLatitudeInput, validateLongitudeInput, form,loading, success, handleSubmit, updateCoordinates, validateEmptyInput, validateNumberInput, onlyNumber, onlyNumberWithCustom, name, lat, lng }
    },
    created() {
        this.customizeHeaders.map((customizeHeader, index) => {
            this.city.city_relation.map((value) => {
                if  (customizeHeader.core_keys_id === value.core_keys_id){
                    let data = value.value;
                    this.form.city_relation[customizeHeader.core_keys_id] = data === '1' ? true : (data === '0' ? false : data);

                }
            });
        })
    },
    computed: {
        breadcrumb() {
            return [
                {
                    label: trans('core__be_dashboard_label'),
                    url: route('admin.index')
                },
                {
                    label: trans('city_module'),
                    url: route('city.index'),
                },
                {
                    label: trans('core__edit_city'),
                    color: "text-primary-500"
                }
            ]
        }
    },
    methods: {
        handleCancel() {
            this.$inertia.get(route('city.index'));
        },
        handleMultiSelect({ data, id }) {
            this.form.city_relation[id] = data.toString();
        },
    },
})
</script>
