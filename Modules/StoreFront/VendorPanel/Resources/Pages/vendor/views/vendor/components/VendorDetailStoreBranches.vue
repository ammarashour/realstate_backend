<template>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- store name -->
        <div class="mt-4" v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'name' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
            <ps-label class="text-base font-medium mb-2">{{ $t(coreField.label_name) }}</ps-label>
            <ps-input   type="text" ref="symbol" v-model:value="storeBranches.name" :placeholder="$t(coreField.placeholder)" />
        </div>

        <!-- store email -->
        <!-- <div class="mt-4" v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'email' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
            <ps-label class="text-base font-medium mb-2">{{ $t(coreField.label_name) }}</ps-label>
            <ps-input   type="email" ref="symbol" v-model:value="storeBranches.email" :placeholder="$t(coreField.placeholder)" />
        </div> -->

        <!-- store phone -->
        <div class="mt-4" v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'phone' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
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
        </div>

        <!-- store address -->
        <div class="mt-4" v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'address' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
            <ps-label class="text-base font-medium mb-2">{{ $t(coreField.label_name) }}</ps-label>
            <ps-textarea rows="4" v-model:value="storeBranches.address" :placeholder="$t(coreField.placeholder)" />
        </div>

        <!-- store description -->
        <div class="mt-4" v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'description' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
            <ps-label class="text-base font-medium mb-2">{{ $t(coreField.label_name) }}</ps-label>
            <ps-textarea rows="4" v-model:value="storeBranches.description" :placeholder="$t(coreField.placeholder)" />
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
import PsLabelCaption from "@/Components/Core/Label/PsLabelCaption.vue";
import { getPhoneCountryCodes } from '@/Api/psApiService.js'
import PsInputWithRightIcon from "@/Components/Core/Input/PsInputWithRightIcon.vue";

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
            PsLabelCaption,
            PsInputWithRightIcon
        },
        props: {
            storeBranches: Object,
            coreFieldFilterSettings: Object,
            customizeHeaders: Object,
            customizeDetails: Object,
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
            if(props.storeBranches.phone != null && props.storeBranches.phone != ''){
                const phoneArray = props.storeBranches.phone.split("-");
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

            function phoneFilterClicked(value) {
                countryCode.value = value.country_code;

                if(tempPhone.value == ''){
                    phone.value = '';
                }else{
                    phone.value = countryCode.value+"-"+tempPhone.value;
                }

                props.storeBranches.phone = phone.value;
            }


            function checkPhone(e) {
                const char = String.fromCharCode(e.keyCode); // Get the character
                if(tempPhone.value == ''){
                    phone.value = '';
                }else{
                    phone.value = countryCode.value+"-"+tempPhone.value+char;
                }

                props.storeBranches.phone = phone.value;

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
