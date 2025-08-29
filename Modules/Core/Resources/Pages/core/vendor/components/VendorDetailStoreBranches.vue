<template>
    <div>
        <ps-label>{{ $t('core_be__vendor_branches') }}</ps-label>
        <!-- store name -->
        <div class="mt-4" v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'name' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
            <ps-label class="text-base font-medium mb-2">{{ $t(coreField.label_name) }}</ps-label>
            <ps-input  :disabled="true" type="text" ref="symbol" v-model:value="storeBranches.name" :placeholder="$t(coreField.placeholder)" />
        </div>

        <!-- store phone -->
        <div class="mt-4" v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'phone' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
            <ps-label class="text-base font-medium mb-2">{{ $t(coreField.label_name) }}</ps-label>
            <div class="grid grid-cols-4">
                <ps-dropdown disabled align="left" class="col-span-1 w-full">
                    <template #select>
                        <ps-dropdown-select ref="status" disabled :placeholder="$t(coreField.placeholder)"
                            :showCenter="true"
                            :selectedValue="storeBranches?.phone.split('-')[0] ? storeBranches.phone.split('-')[0]:'+93'" />
                    </template>
                </ps-dropdown>
                <ps-input class="col-span-3" :disabled="true" type="text" ref="symbol" v-model:value="storeBranches.phone.split('-')[1]" :placeholder="$t(coreField.placeholder)" />
            </div>
        </div>

        <!-- store address -->
        <div class="mt-4" v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'address' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
            <ps-label class="text-base font-medium mb-2">{{ $t(coreField.label_name) }}</ps-label>
            <ps-textarea :disabled="true" rows="4" v-model:value="storeBranches.address" :placeholder="$t(coreField.placeholder)" />
        </div>

        <!-- store description -->
        <div class="mt-4" v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'description' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
            <ps-label class="text-base font-medium mb-2">{{ $t(coreField.label_name) }}</ps-label>
            <ps-textarea :disabled="true" rows="4" v-model:value="storeBranches.description" :placeholder="$t(coreField.placeholder)" />
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
import DatePicker from "@/Components/Core/DateTime/DatePicker.vue";
import CheckBox from "../../components/CheckBox.vue";
import PsImageUpload from "@/Components/Core/Upload/PsImageUpload.vue";
import PsRadioValue from "@/Components/Core/Radio/PsRadioValue.vue";

    export default defineComponent({
        name: "VendorDetailStoreBranches",
        components: {
            PsLabel,
            PsInput,
            PsDropdown,
            PsDropdownSelect,
            PsTextarea,
            DatePicker,
            CheckBox,
            PsImageUpload,
            PsRadioValue,
        },
        props: {
            storeBranches: Object,
            coreFieldFilterSettings: Object,
            customizeHeaders: Object,
            customizeDetails: Object,
        },
        setup(props){
            console.log(props.coreFieldFilterSettings);
            let form = useForm({
                product_relation: {}
            });

            return {
                form
            }
        }
    })
</script>
