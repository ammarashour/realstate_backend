<template>
    <div>
        <!-- user info -->
        <ps-label>{{ $t('core_be__vendor_user_info') }}</ps-label>
        <!-- user name -->
        <div class="mt-4">
            <ps-label class="text-base font-medium mb-2">{{ $t('core__be_vendor_user_name') }}</ps-label>
            <ps-input  :disabled="true" type="text"  v-model:value="vendor.owner.name" />
        </div>

        <!-- user email -->
        <div class="mt-4">
            <ps-label class="text-base font-medium mb-2">{{ $t('core__be_vendor_user_email') }}</ps-label>
            <ps-input  :disabled="true" type="email"  v-model:value="vendor.owner.email" :placeholder="$t('core__be_vendor_email')" />
        </div>

        <!-- user phone -->
        <div class="mt-4">
            <ps-label class="text-base font-medium mb-2">{{ $t('core__be_vendor_phone') }}</ps-label>
            <div class="grid grid-cols-4">
                <ps-dropdown disabled align="left" class="col-span-1 w-full">
                    <template #select>
                        <ps-dropdown-select ref="status" disabled :placeholder="$t('core__be_vendor_phone')"
                            :showCenter="true"
                            :selectedValue="country_code" />
                    </template>
                </ps-dropdown>
                <ps-input class="col-span-3" :disabled="true" type="text"  v-model:value="user_phone" :placeholder="$t('core__be_vendor_phone')" />
            </div>
        </div>

        <!-- vendor status -->
        <!-- <div class="mt-4" v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'status' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
            <ps-label class="text-base">{{ $t(coreField.label_name) }}</ps-label>
            <ps-dropdown disabled align="left" class="lg:mt-2 mt-1 w-full">
                <template #select>
                    <ps-dropdown-select ref="status" disabled :placeholder="$t(coreField.placeholder)"
                        :showCenter="true"
                        :selectedValue="vendorStatus.filter((status) => status.status == vendor.status)[0].label" />
                </template>
            </ps-dropdown>
        </div> -->
        <!-- vendor info -->
        <ps-label class="mt-8">{{ $t('core_be__vendor_info') }}</ps-label>
        <!-- vendor name -->
        <div class="mt-4" v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'name' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
            <ps-label class="text-base font-medium mb-2">{{ $t(coreField.label_name) }}</ps-label>
            <ps-input  :disabled="true" type="text" v-model:value="vendor.name" :placeholder="$t(coreField.placeholder)" />
        </div>

        <!-- vendor phone -->
        <div class="mt-4" v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'phone' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
            <ps-label class="text-base font-medium mb-2">{{ $t(coreField.label_name) }}</ps-label>
            <div class="grid grid-cols-4">
                <ps-dropdown disabled align="left" class="col-span-1 w-full">
                    <template #select>
                        <ps-dropdown-select ref="status" disabled :placeholder="$t(coreField.placeholder)"
                            :showCenter="true"
                            :selectedValue="vendor.phone?.split('-')[0] ? vendor.phone.split('-')[0]:'+93'" />
                    </template>
                </ps-dropdown>
                <ps-input class="col-span-3" :disabled="true" type="text"  v-model:value="vendor.phone.split('-')[1]" :placeholder="$t(coreField.placeholder)" />
            </div>
        </div>

        <!-- vendor address -->
        <div class="mt-4" v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'address' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
            <ps-label class="text-base font-medium mb-2">{{ $t(coreField.label_name) }}</ps-label>
            <ps-textarea :disabled="true" rows="4" v-model:value="vendor.address" :placeholder="$t(coreField.placeholder)" />
        </div>

        <!-- vendor description -->
        <div class="mt-4" v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'description' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
            <ps-label class="text-base font-medium mb-2">{{ $t(coreField.label_name) }}</ps-label>
            <ps-textarea :disabled="true" rows="4" v-model:value="vendor.description" :placeholder="$t(coreField.placeholder)" />
        </div>

        <!-- Website -->
        <div class="mt-4" v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'website' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
            <ps-label class="text-base font-medium mb-2">{{ $t(coreField.label_name) }}</ps-label>
            <ps-input  :disabled="true" type="text"  v-model:value="vendor.website" :placeholder="$t(coreField.placeholder)" />
        </div>

        <!-- Facebook -->
        <div class="mt-4" v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'facebook' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
            <ps-label class="text-base font-medium mb-2">{{ $t(coreField.label_name) }}</ps-label>
            <ps-input  :disabled="true" type="text"  v-model:value="vendor.facebook" :placeholder="$t(coreField.placeholder)" />
        </div>

        <!-- Instagram -->
        <div class="mt-4" v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'instagram' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
            <ps-label class="text-base font-medium mb-2">{{ $t(coreField.label_name) }}</ps-label>
            <ps-input  :disabled="true" type="text"  v-model:value="vendor.instagram" :placeholder="$t(coreField.placeholder)" />
        </div>

        <!-- WhatsApp -->
        <div class="mt-4" v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'whatsApp' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
            <ps-label class="text-base font-medium mb-2">{{ $t(coreField.label_name) }}</ps-label>
            <ps-input  :disabled="true" type="text"  v-model:value="vendor.whatsApp" :placeholder="$t(coreField.placeholder)" />
        </div>

        <!-- custom field start -->
        <div v-for="(custom_field_header) in customizeHeaders" :key="custom_field_header.id">
            <!-- dropdown-->
            <div class="md:me-6 sm:me-0" v-if="custom_field_header.ui_type_id === 'uit00001' && custom_field_header.enable === 1 && custom_field_header.is_delete === 0">
                <ps-label class="text-base">{{ $t(custom_field_header.name) }}<span class="text-red-800 font-medium ms-1" v-show="custom_field_header.mandatory == 1">*</span></ps-label>
                <ps-dropdown :disabled="true" align="left" class="lg:mt-2 mt-1 w-full">
                    <template #select>
                        <ps-dropdown-select :disabled="true" ref="detail" :placeholder="$t(custom_field_header.placeholder)" :showCenter="true"
                            :selectedValue="this.customizeDetails.filter((detail) =>detail.id ==
                            this.form.product_relation[custom_field_header.core_keys_id]).length > 0?
                            this.customizeDetails.filter((detail) => detail.id == this.form.product_relation[custom_field_header.core_keys_id])[0].name: ''" />
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
                                        @click="[(form.product_relation[custom_field_header.core_keys_id] = detail.id)]">
                                        <ps-label class="ms-2" :class="detail.id == form.product_relation[custom_field_header.core_keys_id]? ' font-bold': ''">{{detail.name}}</ps-label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </ps-dropdown>
            </div>

            <!-- text-->
            <div class="mt-4" v-if="custom_field_header.ui_type_id === 'uit00002' && custom_field_header.enable === 1 && custom_field_header.is_delete === 0">
                <ps-label class="text-base font-medium mb-2">{{$t(custom_field_header.name)}}<span class="text-red-800 font-medium ms-1" v-show="custom_field_header.mandatory === 1">*</span></ps-label>
                <ps-input :disabled="true" type="text" class="w-full rounded" :placeholder="$t(custom_field_header.placeholder)" v-model:value="form.product_relation[custom_field_header.core_keys_id]" />
            </div>

            <!-- radio-->
            <div class="mt-4" v-if="custom_field_header.ui_type_id === 'uit00003' && custom_field_header.enable === 1 && custom_field_header.is_delete === 0 ">
                <ps-label class="text-base">{{ $t(custom_field_header.name) }}<span class="text-red-800 font-medium ms-1" v-show="custom_field_header.mandatory === 1">*</span></ps-label>
                <div class="flex flex-row">
                    <div class="me-2" v-for="detail in customizeDetails.filter((customizeDetail) => customizeDetail.core_keys_id === custom_field_header.core_keys_id )" :key="detail.id">
                        <ps-radio-value :disabled="true" color="text-purple-600 border-purple-300" v-model:value="form.product_relation[custom_field_header.core_keys_id]" :title="detail.name" />
                        <ps-label :for="detail.id">{{detail.attribute}}</ps-label>
                    </div>
                </div>
            </div>

            <!-- checkbox-->
            <div class="mt-4" v-if="custom_field_header.ui_type_id === 'uit00004' && custom_field_header.enable === 1 && custom_field_header.is_delete === 0">
                <ps-label class="text-base">{{$t(custom_field_header.name)}}</ps-label>
                <div class="flex flex-row">
                    <div class="me-2 flex">
                        <input :disabled="true" type="checkbox" class="rounded border mt-1.5" value="0" v-model="form.product_relation[custom_field_header.core_keys_id]" />
                        <ps-label class="ms-2">{{$t(custom_field_header.placeholder)}}</ps-label>
                    </div>
                </div>
            </div>

            <!-- datetime -->
            <div class="mt-4" v-if="custom_field_header.ui_type_id === 'uit00005' && custom_field_header.enable === 1 && custom_field_header.is_delete === 0">
                <ps-label class="text-base font-medium mb-2">{{ $t(custom_field_header.name) }}<span class="text-red-800 font-medium ms-1" v-show="custom_field_header.mandatory === 1">*</span></ps-label>
                <div>
                    <date-picker :disabled="true" v-model:value="form.product_relation[custom_field_header.core_keys_id]" :enableTimePicker="true" :inline="false" :autoApply="false" />
                </div>
            </div>

            <!-- textarea -->
            <div class="mt-4" v-if="custom_field_header.ui_type_id === 'uit00006' && custom_field_header.enable === 1 && custom_field_header.is_delete === 0">
                <ps-label class="text-base font-medium mb-2">{{ $t(custom_field_header.name) }}<span class="text-red-800 font-medium ms-1" v-show="custom_field_header.mandatory === 1">*</span></ps-label>
                <ps-textarea :disabled="true" rows="4" :placeholder="$t(custom_field_header.placeholder)" v-model:value="form.product_relation[custom_field_header.core_keys_id]" />
            </div>

            <!-- number-->
            <div class="mt-4" v-if="custom_field_header.ui_type_id === 'uit00007' && custom_field_header.enable === 1 && custom_field_header.is_delete === 0">
                <ps-label class="text-base font-medium mb-2">{{ $t(custom_field_header.name) }}<span class="text-red-800 font-medium ms-1" v-show="custom_field_header.mandatory === 1">*</span></ps-label>
                <ps-input :disabled="true" type="number" class="w-full rounded" :placeholder="$t(custom_field_header.placeholder)" v-model:value="form.product_relation[custom_field_header.core_keys_id]" />
            </div>

            <!-- multi Select-->
            <div class="mt-4" v-if="custom_field_header.ui_type_id === 'uit00008' && custom_field_header.enable === 1 && custom_field_header.is_delete === 0">
                <ps-label class="text-base font-medium mb-2">{{ $t(custom_field_header.name) }}<span class="text-red-800 font-medium ms-1" v-show="custom_field_header.mandatory === 1">*</span></ps-label>
                <div class="flex flex-row">
                    <CheckBox :disabled="true" :oldData="form.product_relation[custom_field_header.core_keys_id]" @toParent="handleMultiSelect" :customizeDetails="customizeDetails" :customizeHeader="custom_field_header" />
                </div>
            </div>

            <!-- Image-->
            <div class="mt-4" v-if="custom_field_header.ui_type_id === 'uit00009' && custom_field_header.enable === 1 && custom_field_header.is_delete === 0">
                <ps-label class="text-base font-medium mb-2">{{ $t(custom_field_header.name) }}<span class="text-red-800 font-medium ms-1" v-show="custom_field_header.mandatory ===1">*</span></ps-label>
                <ps-image-upload uploadType="image" v-model:imageFile="form.product_relation[custom_field_header.core_keys_id]" />
            </div>

            <!-- time Only -->
            <div class="mt-4" v-if="custom_field_header.ui_type_id === 'uit00010' && custom_field_header.enable === 1 && custom_field_header.is_delete === 0">
                <ps-label class="text-base font-medium mb-2">{{ $t(custom_field_header.name) }}<span class="text-red-800 font-medium ms-1" v-show="custom_field_header.mandatory === 1">*</span></ps-label>
                <input type="time" class="w-full rounded" v-model="form.product_relation[custom_field_header.core_keys_id]" />
            </div>

            <!-- date Only -->
            <div class="mt-4" v-if="custom_field_header.ui_type_id === 'uit00011' && custom_field_header.enable === 1 &&custom_field_header.is_delete === 0">
                <ps-label class="text-base font-medium mb-2">{{ $t(custom_field_header.name) }}<span class="text-red-800 font-medium ms-1" v-show="custom_field_header.mandatory === 1">*</span></ps-label>
                <div>
                    <date-picker v-model:value="form.product_relation[custom_field_header.core_keys_id]" :inline="false" :autoApply="false" />
                </div>
            </div>
        </div>
        <!-- /.custom field end -->

        <!-- logo -->
        <div class="mt-4">
            <ps-label class="text-base font-medium mb-1">{{ $t('core_be__vendor_logo') }}</ps-label>
            <ps-label class="text-xs mb-2" textColor="text-secondary-400">{{ $t('core_be__vendor_logo_recommended_size') }}</ps-label>
            <ps-image-upload-2 :disabled="true" :title="vendor.logo.img_path" :placeholder="$t('core_be__or_drop_files')"/>
        </div>

        <!-- banner 1 -->
        <div class="mt-4">
            <ps-label class="text-base font-medium mb-1">{{ $t('core_be__vendor_banner_1') }}</ps-label>
            <ps-label class="text-xs mb-2" textColor="text-secondary-400">{{ $t('core_be__vendor_banner_1_recommended_size') }}</ps-label>
            <ps-image-upload-2 :disabled="true" :title="vendor.banner_1.img_path" :placeholder="$t('core_be__or_drop_files')"/>
        </div>

        <!-- banner 2 -->
        <div class="mt-4">
            <ps-label class="text-base font-medium mb-1">{{ $t('core_be__vendor_banner_2') }}</ps-label>
            <ps-label class="text-xs mb-2" textColor="text-secondary-400">{{ $t('core_be__vendor_banner_2_recommended_size') }}</ps-label>
            <ps-image-upload-2 :disabled="true" :title="vendor.banner_2.img_path" :placeholder="$t('core_be__or_drop_files')"/>
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
import PsImageUpload from "@/Components/Core/Upload/PsImageUpload.vue";
import PsRadioValue from "@/Components/Core/Radio/PsRadioValue.vue";

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
            PsImageUpload,
            PsRadioValue,
        },
        props: {
            vendor: Object,
            coreFieldFilterSettings: Array,
            customizeHeaders: Object,
            customizeDetails: Object,
        },
        setup(props) {
            console.log(props.vendor);
            const country_code = ref('+93');
            const user_phone = ref('');

            if(props.vendor.owner.user_phone != null){
                country_code.value = props.vendor.owner.user_phone.split('-')[0];
                user_phone.value = props.vendor.owner.user_phone.split('-')[1];
            }

            let vendorStatus = [
                {
                    status: 0,
                    label: 'core__be_inactive'
                },
                {
                    status: 1,
                    label: 'core__be_active'
                },
                {
                    status: 2,
                    label: 'core__be_terminated'
                },
            ];

            let form = useForm({
                product_relation: {}
            });

            return {
                vendorStatus,
                form,
                country_code,
                user_phone
            }
        }
    })
</script>
