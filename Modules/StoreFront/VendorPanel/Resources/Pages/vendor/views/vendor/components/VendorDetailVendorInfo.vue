<template>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- vendor name -->
        <div class="" v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'name' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
            <ps-label class="text-base font-medium mb-2">{{ $t(coreField.label_name) }}<span class="text-red-800 font-medium ms-1">*</span></ps-label>
            <ps-input   type="text" v-model:value="form.name" :placeholder="$t(coreField.placeholder)" />
            <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ errors.name }}</ps-label-caption>
        </div>

        <!-- vendor phone -->
        <div class="" v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'phone' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
            <ps-label class="text-base font-medium mb-2">{{ $t(coreField.label_name) }}</ps-label>
            <div class="flex flex-row">
                <ps-dropdown @on-click="loadMore" horizontalAlign="left" class="me-0 "  >

                    <template #select>

                        <ps-dropdown-select h="h-10" rounded="rounded-s"  :showCenter="true" :selectedValue="countryCode" />

                    </template>
                    <template #list>
                        <div class="rounded-md shadow-xs w-56 ">
                            <div class="pt-2 z-30 ">
                                <div v-if="is_loading">
                                    <ps-label class='p-2 flex'>{{
                                        $t("item_entry__loading")
                                    }} </ps-label>
                                </div>
                                <div v-else>
                                    <div v-for="selectData in phoneList"
                                        :key="selectData.id"
                                        class="w-56 flex py-4 px-2 hover:bg-primary-000 dark:hover:bg-primary-900 cursor-pointer items-center"
                                        @click="phoneFilterClicked(selectData)">

                                        <ps-label class="ms-2"
                                            :class="selectData.country_code == countryCode ? ' font-bold' : ''">
                                            {{ selectData.country_code }} </ps-label>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                    <template #loadmore>

                        <div class="mb-2 w-56" @click="loadMore(true)" v-if="phone_loadmore_visible">

                            <ps-label class="flex mt-4 ms-4 mb-2 underline font-bold cursor-pointer"
                                >
                                    {{is_loading ? $t('core__be_loading') :$t('core__be_load_more') }} </ps-label>
                        </div>

                    </template>
                    <template #filter>
                        <div class="w-56">
                            <ps-input-with-right-icon class="rounded-xl flex-1 "
                                v-model:value="phoneSearch"
                                v-bind:placeholder="$t('phone_code_by_country')">
                                <template #icon>
                                    <ps-icon textColor="text-secondary-400 dark:text-secondaryDark-grey"
                                        name="search" class='cursor-pointer'
                                        />
                                </template>
                            </ps-input-with-right-icon>
                        </div>
                    </template>

                </ps-dropdown>

                <ps-input @keypress="checkPhone($event)" class="flex-grow" rounded="rounded-e" ref="phone" type="text" v-model:value="tempPhone" :placeholder="$t(coreField.placeholder)"  />

            </div>
            <ps-label-caption  textColor="text-red-500 " class="mt-2 block">{{ errors.phone }}</ps-label-caption>
        </div>

        <!-- vendor address -->
        <div class="" v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'address' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
            <ps-label class="text-base font-medium mb-2">{{ $t(coreField.label_name) }}</ps-label>
            <ps-textarea  rows="4" v-model:value="form.address" :placeholder="$t(coreField.placeholder)" />
            <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ errors.address }}</ps-label-caption>
        </div>

        <!-- vendor description -->
        <div class="" v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'description' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
            <ps-label class="text-base font-medium mb-2">{{ $t(coreField.label_name) }}</ps-label>
            <ps-textarea  rows="4" v-model:value="form.description" :placeholder="$t(coreField.placeholder)" />
            <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ errors.description }}</ps-label-caption>
        </div>

        <!-- vendor email -->
        <!-- <div class="" v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'email' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
            <ps-label class="text-base font-medium mb-2">{{ $t(coreField.label_name) }}<span class="text-red-800 font-medium ms-1">*</span></ps-label>
            <ps-input   type="text" v-model:value="form.email" :placeholder="$t(coreField.placeholder)" />
            <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ errors.email }}</ps-label-caption>
        </div> -->

        <!-- Website -->
        <div class="" v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'website' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
            <ps-label class="text-base font-medium mb-2">{{ $t(coreField.label_name) }}</ps-label>
            <ps-input   type="text"  v-model:value="form.website" :placeholder="$t(coreField.placeholder)" />
            <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ errors.website }}</ps-label-caption>
        </div>

        <!-- Facebook -->
        <div class="" v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'facebook' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
            <ps-label class="text-base font-medium mb-2">{{ $t(coreField.label_name) }}</ps-label>
            <ps-input   type="text"  v-model:value="form.facebook" :placeholder="$t(coreField.placeholder)" />
            <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ errors.facebook }}</ps-label-caption>
        </div>

        <!-- Instagram -->
        <div class="" v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'instagram' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
            <ps-label class="text-base font-medium mb-2">{{ $t(coreField.label_name) }}</ps-label>
            <ps-input   type="text"  v-model:value="form.instagram" :placeholder="$t(coreField.placeholder)" />
            <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ errors.instagram }}</ps-label-caption>
        </div>

        <!-- WhatsApp -->
        <!-- <div class="" v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'whatsApp' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
            <ps-label class="text-base font-medium mb-2">{{ $t(coreField.label_name) }}</ps-label>
            <ps-input   type="text"  v-model:value="form.whatsApp" :placeholder="$t(coreField.placeholder)" />
        </div> -->

        <!-- custom field start -->
        <div v-for="(custom_field_header) in customizeHeaders" :key="custom_field_header.id">
            <!-- dropdown-->
            <div class="md:me-6 sm:me-0" v-if="custom_field_header.ui_type_id === 'uit00001' && custom_field_header.enable === 1 && custom_field_header.is_delete === 0">
                <ps-label class="text-base">{{ $t(custom_field_header.name) }}<span class="text-red-800 font-medium ms-1" v-show="custom_field_header.mandatory == 1">*</span></ps-label>
                <ps-dropdown  align="left" class="lg:mt-2 mt-1 w-full">
                    <template #select>
                        <ps-dropdown-select  ref="detail" :placeholder="$t(custom_field_header.placeholder)" :showCenter="true"
                            :selectedValue="this.customizeDetails.filter((detail) =>detail.id ==
                            this.form.vendor_relation[custom_field_header.core_keys_id]).length > 0?
                            this.customizeDetails.filter((detail) => detail.id == this.form.vendor_relation[custom_field_header.core_keys_id])[0].name: ''" />
                    </template>
                    <template #list>
                        <div class="rounded-md shadow-xs w-full bg-background dark:bg-backgroundDark">
                            <div class="pt-2 z-30">
                                <div v-if="customizeDetails.filter((customizeDetail) => customizeDetail.core_keys_id === custom_field_header.core_keys_id).length === 0">
                                    <ps-label class="p-2 flex" @click="route('currency.index')">{{$t('core__be_create_new')}} {{ $t(custom_field_header.name)}}</ps-label>
                                </div>
                                <div v-else>
                                    <div v-for="detail in customizeDetails.filter((customizeDetail) => customizeDetail.core_keys_id === custom_field_header.core_keys_id)" :key="detail.id"
                                        class="w-96 flex py-4 px-2 hover:bg-primary-000 dark:hover:bg-secondary-700 cursor-pointer items-center"
                                        @click="[(form.vendor_relation[custom_field_header.core_keys_id] = detail.id)]">
                                        <ps-label class="ms-2" :class="detail.id == form.vendor_relation[custom_field_header.core_keys_id]? ' font-bold': ''">{{detail.name}}</ps-label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </ps-dropdown>
            </div>

            <!-- text-->
            <div class="" v-if="custom_field_header.ui_type_id === 'uit00002' && custom_field_header.enable === 1 && custom_field_header.is_delete === 0">
                <ps-label class="text-base font-medium mb-2">{{$t(custom_field_header.name)}}<span class="text-red-800 font-medium ms-1" v-show="custom_field_header.mandatory === 1">*</span></ps-label>
                <ps-input  type="text" class="w-full rounded" :placeholder="$t(custom_field_header.placeholder)" v-model:value="form.vendor_relation[custom_field_header.core_keys_id]" />
            </div>

            <!-- radio-->
            <div class="" v-if="custom_field_header.ui_type_id === 'uit00003' && custom_field_header.enable === 1 && custom_field_header.is_delete === 0 ">
                <ps-label class="text-base">{{ $t(custom_field_header.name) }}<span class="text-red-800 font-medium ms-1" v-show="custom_field_header.mandatory === 1">*</span></ps-label>
                <div class="flex flex-row">
                    <div class="me-2" v-for="detail in customizeDetails.filter((customizeDetail) => customizeDetail.core_keys_id === custom_field_header.core_keys_id )" :key="detail.id">
                        <ps-radio-value  color="text-purple-600 border-purple-300" v-model:value="form.vendor_relation[custom_field_header.core_keys_id]" :title="detail.name" />
                        <ps-label :for="detail.id">{{detail.attribute}}</ps-label>
                    </div>
                </div>
            </div>

            <!-- checkbox-->
            <div class="" v-if="custom_field_header.ui_type_id === 'uit00004' && custom_field_header.enable === 1 && custom_field_header.is_delete === 0">
                <ps-label class="text-base">{{$t(custom_field_header.name)}}</ps-label>
                <div class="flex flex-row">
                    <div class="me-2 flex">
                        <input  type="checkbox" class="rounded border mt-1.5" value="0" v-model="form.vendor_relation[custom_field_header.core_keys_id]" />
                        <ps-label class="ms-2">{{$t(custom_field_header.placeholder)}}</ps-label>
                    </div>
                </div>
            </div>

            <!-- datetime -->
            <div class="" v-if="custom_field_header.ui_type_id === 'uit00005' && custom_field_header.enable === 1 && custom_field_header.is_delete === 0">
                <ps-label class="text-base font-medium mb-2">{{ $t(custom_field_header.name) }}<span class="text-red-800 font-medium ms-1" v-show="custom_field_header.mandatory === 1">*</span></ps-label>
                <div>
                    <date-picker  v-model:value="form.vendor_relation[custom_field_header.core_keys_id]" :enableTimePicker="true" :inline="false" :autoApply="false" />
                </div>
            </div>

            <!-- textarea -->
            <div class="" v-if="custom_field_header.ui_type_id === 'uit00006' && custom_field_header.enable === 1 && custom_field_header.is_delete === 0">
                <ps-label class="text-base font-medium mb-2">{{ $t(custom_field_header.name) }}<span class="text-red-800 font-medium ms-1" v-show="custom_field_header.mandatory === 1">*</span></ps-label>
                <ps-textarea  rows="4" :placeholder="$t(custom_field_header.placeholder)" v-model:value="form.vendor_relation[custom_field_header.core_keys_id]" />
            </div>

            <!-- number-->
            <div class="" v-if="custom_field_header.ui_type_id === 'uit00007' && custom_field_header.enable === 1 && custom_field_header.is_delete === 0">
                <ps-label class="text-base font-medium mb-2">{{ $t(custom_field_header.name) }}<span class="text-red-800 font-medium ms-1" v-show="custom_field_header.mandatory === 1">*</span></ps-label>
                <ps-input  type="number" class="w-full rounded" :placeholder="$t(custom_field_header.placeholder)" v-model:value="form.vendor_relation[custom_field_header.core_keys_id]" />
            </div>

            <!-- multi Select-->
            <div class="" v-if="custom_field_header.ui_type_id === 'uit00008' && custom_field_header.enable === 1 && custom_field_header.is_delete === 0">
                <ps-label class="text-base font-medium mb-2">{{ $t(custom_field_header.name) }}<span class="text-red-800 font-medium ms-1" v-show="custom_field_header.mandatory === 1">*</span></ps-label>
                <div class="flex flex-row">
                    <CheckBox  :oldData="form.vendor_relation[custom_field_header.core_keys_id]" @toParent="handleMultiSelect" :customizeDetails="customizeDetails" :customizeHeader="custom_field_header" />
                </div>
            </div>

            <!-- Image-->
            <div class="" v-if="custom_field_header.ui_type_id === 'uit00009' && custom_field_header.enable === 1 && custom_field_header.is_delete === 0">
                <ps-label class="text-base font-medium mb-2">{{ $t(custom_field_header.name) }}<span class="text-red-800 font-medium ms-1" v-show="custom_field_header.mandatory ===1">*</span></ps-label>
                <ps-image-upload uploadType="image" v-model:imageFile="form.vendor_relation[custom_field_header.core_keys_id]" />
            </div>

            <!-- time Only -->
            <div class="" v-if="custom_field_header.ui_type_id === 'uit00010' && custom_field_header.enable === 1 && custom_field_header.is_delete === 0">
                <ps-label class="text-base font-medium mb-2">{{ $t(custom_field_header.name) }}<span class="text-red-800 font-medium ms-1" v-show="custom_field_header.mandatory === 1">*</span></ps-label>
                <input type="time" class="w-full rounded" v-model="form.vendor_relation[custom_field_header.core_keys_id]" />
            </div>

            <!-- date Only -->
            <div class="" v-if="custom_field_header.ui_type_id === 'uit00011' && custom_field_header.enable === 1 &&custom_field_header.is_delete === 0">
                <ps-label class="text-base font-medium mb-2">{{ $t(custom_field_header.name) }}<span class="text-red-800 font-medium ms-1" v-show="custom_field_header.mandatory === 1">*</span></ps-label>
                <div>
                    <date-picker v-model:value="form.vendor_relation[custom_field_header.core_keys_id]" :inline="false" :autoApply="false" />
                </div>
            </div>
        </div>
        <!-- /.custom field end -->
        <!-- logo -->
        <div class="">
            <ps-label class="text-base font-medium mb-1">{{ $t('core_be__vendor_logo') }}</ps-label>
            <ps-label class="text-xs mb-2" textColor="text-secondary-400">{{ $t('core_vendor__vendor_logo_recommended_size') }}</ps-label>
            <ps-image-upload-2 v-model:imageFile="form.logo" :title="form.logo" :placeholder="$t('core_be__or_drop_files')"/>
            <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ errors.logo }}</ps-label-caption>
        </div>

        <!-- banner 1 -->
        <div class="">
            <ps-label class="text-base font-medium mb-1">{{ $t('core_be__vendor_banner_1') }}</ps-label>
            <ps-label class="text-xs mb-2" textColor="text-secondary-400">{{ $t('core_vendor__vendor_banner_1_recommended_size') }}</ps-label>
            <ps-image-upload-2 v-model:imageFile="form.banner_1" :title="form.banner_1" :placeholder="$t('core_be__or_drop_files')"/>
            <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ errors.banner_1 }}</ps-label-caption>
        </div>

        <!-- banner 2 -->
        <div class="">
            <ps-label class="text-base font-medium mb-1">{{ $t('core_be__vendor_banner_2') }}</ps-label>
            <ps-label class="text-xs mb-2" textColor="text-secondary-400">{{ $t('core_vendore__vendor_banner_2_recommended_size') }}</ps-label>
            <ps-image-upload-2  v-model:imageFile="form.banner_2" :title="form.banner_2" :placeholder="$t('core_be__or_drop_files')"/>
            <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ errors.banner_2 }}</ps-label-caption>

        </div>
    </div>
</template>

<script>
import { ref, defineComponent, watch } from "vue";
import { Head, useForm, usePage } from "@inertiajs/vue3";
import { trans } from 'laravel-vue-i18n';
import PsLabel from "@/Components/Core/Label/PsLabel.vue";
import PsInput from "@/Components/Core/Input/PsInput.vue";
import PsDropdown from "@/Components/Core/Dropdown/PsDropdown.vue";
import PsDropdownSelect from "@/Components/Core/Dropdown/PsDropdownSelect.vue";
import PsTextarea from '@/Components/Core/Textarea/PsTextarea.vue';
import PsImageUpload2 from "@/Components/Core/Upload/PsImageUpload2.vue";
import DatePicker from "@/Components/Core/DateTime/DatePicker.vue";
import CheckBox from "../../components/CheckBox.vue";
import PsRadioValue from "@/Components/Core/Radio/PsRadioValue.vue";
import PsLabelCaption from "@/Components/Core/Label/PsLabelCaption.vue";
import { getPhoneCountryCodes } from '@/Api/psApiService.js'
import PsInputWithRightIcon from "@/Components/Core/Input/PsInputWithRightIcon.vue";

    export default defineComponent({
        name: "VendorDetailVendorInfo",
        components: {
            PsLabel,
            PsInput,
            PsDropdown,
            PsDropdownSelect,
            PsTextarea,
            PsImageUpload2,
            DatePicker,
            CheckBox,
            PsRadioValue,
            PsLabelCaption,
            PsInputWithRightIcon
        },
        props: {
            errors: Object,
            form: Object,
            coreFieldFilterSettings: Object,
            customizeHeaders: Object,
            customizeDetails: Object,
            validation: Object,
            authUser: Object,
        },
        setup(props) {
            const tempPhone = ref("");
            const phone = ref("");
            const phoneSearch = ref('');

            const countryCode = ref('+1');
            const phone_loadmore_visible = ref(false);
            const is_loading = ref(false);
            let phoneList = ref([]);
            if(props.form.phone != null && props.form.phone != ''){
                const phoneArray = props.form.phone.split("-");
                if(phoneArray.length > 1){
                    countryCode.value = phoneArray[0];
                    tempPhone.value = phoneArray[1];
                }else{
                    tempPhone.value = phoneArray[0];
                }
            }

            phone.value = countryCode.value+"-"+tempPhone.value;

            function loadPhone(offset){

                is_loading.value = true
                getPhoneCountryCodes(offset,phoneSearch.value,props.authUser.id).then(response => {

                    if(response.data.length > 0){
                        response.data.forEach(element =>{
                            phoneList.value.push(element);
                        });
                        phone_loadmore_visible.value = true;
                    }else{
                        phone_loadmore_visible.value = false;
                    }
                        is_loading.value=false;
                });
            }

            watch(phoneSearch,_.debounce((current,previous)=>{
                    let offset= 0;
                    phoneList.value = [];
                    loadPhone(offset);

                },500))

            function loadMore(loadmore = null){

                let offset = phoneList.value ? phoneList.value.length : 0 ;
                if(offset == 0 || loadmore == true){

                    loadPhone(offset);
                }
            }

            function phoneFilterClicked(selectedCountryCode) {
                countryCode.value = selectedCountryCode.country_code

                phone.value = countryCode.value + "-" +tempPhone.value;
                props.form.phone = phone.value;
            }


            function checkPhone(e) {
                const char = String.fromCharCode(e.keyCode); // Get the character
                if(tempPhone.value == ''){
                    phone.value = '';
                }else{
                    phone.value = countryCode.value+"-"+tempPhone.value+char;
                }

                props.form.phone = phone.value;

                if (/^[0-9]+$/.test(char)) return true; // Match with regex
                else e.preventDefault(); // If not match, don't add to input text
            }

            return {
                checkPhone,
                loadPhone,
                loadMore,
                phoneFilterClicked,
                phoneSearch,
                countryCode,
                phone_loadmore_visible,
                is_loading,
                phoneList,
                tempPhone
            }
        }
    })
</script>
