<template>
    <Head :title="$t('core__be_add_township')" />
    <ps-layout>

        <!-- breadcrumb start -->
        <ps-breadcrumb-2 :items="breadcrumb" class="mb-5 sm:mb-6 lg:mb-8" />
        <!-- breadcrumb end -->

        <ps-card class="w-full h-auto">
            <div class="rounded-xl">
                <!-- card header start -->
                <div class="bg-primary-50 dark:bg-primary-900 py-2.5 ps-4 rounded-t-xl">
                    <ps-label-header-6 textColor="text-secondary-800 dark:text-secondary-100"> {{$t('core__be_township_info')}}</ps-label-header-6>
                </div>
                <!-- card header end -->

                <!-- card body start -->
                <div class="px-4 pt-6 dark:bg-backgroundDark">
                    <form @submit.prevent="handleSubmit">
                        <div class="grid w-full sm:w-1/2 gap-6">

                            <!-- for city dropdown -->
                            <div>
                                <ps-label class="text-base mb-2">{{$t('core__be_select_city')}}<span class="text-red-800 font-medium ms-1">*</span>
                                </ps-label>
                                <ps-dropdown align="left" class='lg:mt-2 mt-1  w-full'>
                                    <template #select>
                                        <ps-dropdown-select :placeholder="$t('core__be_select_city')"
                                            :selectedValue="(form.location_city_id == '') ? '' : cities.filter(city => city.id == form.location_city_id)[0].name"
                                            @change="validateEmptyInput('city_id', form.city_id)"
                                            @blur="validateEmptyInput('city_id', form.city_id)" />
                                    </template>
                                    <template #list>
                                        <div class="rounded-md shadow-xs w-56 ">
                                            <div class="pt-2 z-30 ">
                                                <div v-if="cities.length == null">
                                                    <ps-label class='p-2 flex' @click="route('city.index')">
                                                        {{$t('core__be_create_new_city')}}</ps-label>
                                                </div>
                                                <div v-else>
                                                    <!-- <div class="w-56 flex py-4 px-2 hover:bg-primary-000 dark:hover:bg-secondary-700 cursor-pointer items-center"
                                                        @click="[form.location_city_id = '', validateEmptyInput('city_id', form.location_city_id)]">
                                                        <ps-label class="text-gray-200">{{$t('core__be_select_city')}}</ps-label>
                                                    </div> -->
                                                    <div v-for="city in cities" :key="city.id"
                                                        class="w-56 flex py-4 px-2 hover:bg-primary-000 dark:hover:bg-secondary-700 cursor-pointer items-center"
                                                        @click="[form.location_city_id = city.id, validateEmptyInput('city_id', form.location_city_id)]">
                                                        <ps-label class="ms-2"
                                                            :class="city.id == form.location_city_id ? ' font-bold' : ''">
                                                            {{ city.name }} </ps-label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </ps-dropdown>
                                <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ errors.location_city_id }}</ps-label-caption>
                            </div>
                            <!-- end city -->

                            <div>
                                <ps-label class="text-base mb-2">{{$t('core__be_township_name_label')}}<span class="text-red-800 font-medium ms-1">*</span></ps-label>
                                <ps-input type="text" v-model:value="form.name" :placeholder="$t('core__be_township_name_placeholder')"
                                    @keyup="validateTownshipNameInput('name', form.name)"
                                    @blur="validateTownshipNameInput('name', form.name)" />
                                <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ errors.name }}</ps-label-caption>
                            </div>

                            <div>
                                <ps-label class="text-base mb-2">{{$t('core__be_ordering_label')}}</ps-label>
                                <ps-input type="text" v-model:value="form.ordering" :placeholder="$t('core__be_ordering_placeholder')"
                                    @keypress="onlyNumber" />
                                <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ errors.ordering }}</ps-label-caption>
                            </div>

                            <div>
                                <ps-label class="text-base mb-2">{{$t('core__be_description_label')}}</ps-label>
                                <ps-textarea rows="3" v-model:value="form.description" :placeholder="$t('core__be_description_placeholder')">
                                </ps-textarea>
                                <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ errors.description }}</ps-label-caption>
                            </div>

                            <!-- <div>
                                <google-map-pin-picker :lat="parseFloat(form.lat)" :lng="parseFloat(form.lng)" widthHeight="width: 458px; height: 500px"
                                    :onChange="updateCoordinates" />
                            </div> -->
                            <!-- maps start -->
                            <div class="mb-6" v-if="backendSettings.is_google_map == 1">
                                <google-map-pin-picker :lat="parseFloat(form.lat)" :lng="parseFloat(form.lng)"
                                    widthHeight="width: 458px; height: 500px" :onChange="updateCoordinates" />
                            </div>
                            <div class="mb-6" v-else>
                                <open-street-map-pin-picker :dir="$page.props.dir" :lat="16.7967129" :lng="96.1609916" :onChange="setCoordinates" class="h-[500px]" />
                            </div>
                            <!-- maps end -->

                            <div>
                                <ps-label class="text-base mb-2">{{$t('core__be_latitude_label')}}<span class="text-red-800 font-medium ms-1">*</span></ps-label>
                                <ps-input type="text" v-model:value="form.lat" :placeholder="$t('core__be_latitude_placeholder')"
                                    @keypress="onlyNumberWithCustom" @keyup="validateLatitudeInput('lat', form.lat)"
                                    @blur="validateLatitudeInput('lat', form.lat)" />
                                <ps-label-caption v-if="lat == ''" textColor="text-red-500 " class="mt-2 block">{{ errors.lat }}</ps-label-caption>
                            </div>

                            <div>
                                <ps-label>{{$t('core__be_longitude_label')}}<span class="text-red-800 font-medium ms-1">*</span></ps-label>
                                <ps-input type="text" v-model:value="form.lng" :placeholder="$t('core__be_longitude_placeholder')"
                                    @keypress="onlyNumberWithCustom"
                                    @keyup="validateLongitudeInput('lng', form.lng)"
                                    @blur="validateLongitudeInput('lng', form.lng)" />
                                <ps-label-caption v-if="lng == ''" textColor="text-red-500 " class="mt-2 block">{{ errors.lng }}</ps-label-caption>
                            </div>

                            <div class="mb-2.5 flex flex-row justify-end">
                                <ps-button @click="handleCancel()" textSize="text-base" type="reset" class="me-4"
                                    colors="text-primary-500" focus="" hover="">{{ $t('core__be_btn_cancel') }}</ps-button>
                                <ps-button class="transition-all duration-300 min-w-3xs" padding="px-5 py-2"
                                    rounded="rounded" hover="" focus="">
                                    <ps-loading v-if="loading"
                                        theme="border-2 border-t-2 border-text-8 border-t-primary-500"
                                        loadingSize="h-5 w-5" />
                                    <ps-icon v-if="success" name="check" viewBox="0 0 18 14" w="14" h="10"
                                        class="me-1.5 transition-all duration-300" />
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
import { defineComponent, defineAsyncComponent, ref } from 'vue'
import PsLayout from "@/Components/PsLayout.vue";
import { Head, useForm } from "@inertiajs/vue3";
import useValidators from '@/Validation/Validators'
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
import PsDropdown from "@/Components/Core/Dropdown/PsDropdown.vue";
import PsDropdownSelect from "@/Components/Core/Dropdown/PsDropdownSelect.vue";
import PsBreadcrumb2 from "@/Components/Core/Breadcrumbs/PsBreadcrumb2.vue";
import PsLabelCaption from "@/Components/Core/Label/PsLabelCaption.vue";
const GoogleMapPinPicker = defineAsyncComponent(() => import('@/Components/Core/Map/GoogleMapPinPicker.vue'));
const OpenStreetMapPinPicker = defineAsyncComponent(() => import('@/Components/Core/Map/OpenStreetMapPinPicker.vue'));
import { trans } from 'laravel-vue-i18n';

export default defineComponent({
    name: "Create",
    components: {
        Head,
        GoogleMapPinPicker,
        OpenStreetMapPinPicker,
        PsInput,
        PsLabel,
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
        PsDropdown,
        PsDropdownSelect,
        PsBreadcrumb2,
        PsLabelCaption
    },
    layout: PsLayout,
    props: ['errors', 'cities', 'backendSettings'],
    setup(props) {
        const loading = ref(false);
        const success = ref(false);
        const name = ref();
        const location_city_id = ref();
        const lat = ref('');
        const lng = ref('');

        // Client Side Validation
        const { isEmpty, minLength, isLatitude, isLongitude } = useValidators();

        const validateTownshipNameInput = (fieldName, fieldValue) => {
            props.errors[fieldName] = !fieldValue ? isEmpty(fieldName, fieldValue,null,"TownShip") : minLength(fieldName, fieldValue, 3,null,'TownShip');
            if(fieldName == 'name'){
                name.value.isError = (!Boolean(props.errors.name)) ? false: true;
            }
        }

        const validateCityNameInput = (fieldName, fieldValue) => {
            props.errors[fieldName] = !fieldValue ? isEmpty(fieldName, fieldValue) : '';
            if(fieldName == 'location_city_id'){
                location_city_id.value.isError = (!Boolean(props.errors.location_city_id)) ? false: true;
            }
        }

        const validateLatitudeInput = (fieldName, fieldValue) => {
            props.errors[fieldName] = !fieldValue ? isEmpty(fieldName, fieldValue,'','Latitude') : isLatitude(fieldName, fieldValue);
            if(fieldName == 'lat'){
                lat.value.isError = (!Boolean(props.errors.lat)) ? false: true;
            }
        }

        const validateLongitudeInput = (fieldName, fieldValue) => {
            props.errors[fieldName] = !fieldValue ? isEmpty(fieldName, fieldValue,'','Longitude') : isLongitude(fieldName, fieldValue);
            if(fieldName == 'lng'){
                lng.value.isError = (!Boolean(props.errors.lng)) ? false: true;
            }
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
                name: "",
                location_city_id: "",
                lat: "",
                lng: "",
                ordering: "",
                description: "",
                cover: "",
                icon: "",
                video: ""
            }
        )
        function handleSubmit() {
            this.$inertia.post(route("township.store"), form, {
                forceFormData: true,
                onBefore: () => {
                    loading.value = true;
                },
                onSuccess: () => {
                    loading.value = false;
                    success.value = true;
                    setTimeout(() => {
                        success.value = false;
                    }, 2000);
                },
                onError: () => {
                    loading.value = false;
                    name.value.isError = (!Boolean(props.errors.name)) ? false: true;
                    lat.value.isError = (!Boolean(props.errors.lat)) ? false: true;
                    lng.value.isError = (!Boolean(props.errors.lng)) ? false: true;
                    location_city_id.value.isError = (!Boolean(props.errors.location_city_id)) ? false: true;

                },
            });
        }
        function updateCoordinates(location) {
            lat.value = location.latLng.lat();
            lng.value = location.latLng.lng();
            form.lat = lat.value;
            form.lng = lng.value;
        }

        function setCoordinates(location){
            lat.value = location.lat;
            lng.value = location.lng;
            form.lat = lat.value;
            form.lng = lng.value;
        }

        return { validateTownshipNameInput, validateCityNameInput, validateLatitudeInput, validateLongitudeInput, onlyNumber, onlyNumberWithCustom, form, handleSubmit, updateCoordinates, setCoordinates, loading, success, name, location_city_id, lat, lng }
    },
    computed: {
        breadcrumb() {
            return [
                {
                    label: trans('core__be_dashboard_label'),
                    url: route('admin.index')
                },
                {
                    label: trans('township_module'),
                    url: route('township.index'),
                },
                {
                    label: trans('core__be_add_township'),
                    color: "text-primary-500"
                }
            ]
        }
    },
    methods: {
        handleCancel() {
            this.$inertia.get(route('township.index'));
        },
    },
})
</script>
