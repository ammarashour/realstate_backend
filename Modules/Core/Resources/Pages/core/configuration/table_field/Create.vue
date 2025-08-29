<template>
    <Head :title="$t('core__be_create_custom_field_label')" />
    <ps-layout>
        <!-- <ps-label-header-5 class="font-bold mb-2" textColor="text-secondary-900 dark:text-secondary-100">
                {{$t('core__be_create_custom_field_label')}}
            </ps-label-header-5> -->

        <!-- breadcrumb start -->
        <ps-breadcrumb-2 :items="breadcrumb" class="mt-4 mb-7" />
        <!-- breadcrumb end -->

        <div class="w-full md:w-196 xl:196">
            <div class="mt-8  rounded-lg bg-secondary-50 dark:bg-secondary-900 border border-secondary-200 dark:bprder-secondary-900 shadow-md ps-6 pe-6 md:ps-8 md:pe-24 py-6" v-if="selectedClientTable.core_key_type_id == 1">
                <ps-label-header-5 textColor=" text-secondary-800 dark:text-secondary-100">
                    {{ $t('core__be_choose_category_for_custom_field') }}
                    <span class="text-red-500">*</span>
                </ps-label-header-5>
                <div class="mt-4 md:mt-6 grid grid-cols-3 items-center">
                    <ps-label class="text-base">{{ $t("core__category_label") }}</ps-label>
                    <ps-dropdown align="left" class='col-span-2  w-full border-red-500 '>
                        <template #select>
                            <ps-dropdown-select :placeholder="$t('core_be_select_category')"

                                                :selectedValue="(form.category_id == '') ? $t('core__be_general_for_all_category') : categories.filter(category => category.id === form.category_id)[0]?.name" />
                        </template>


                        <template #list>
                            <div class="rounded-md shadow-xs w-56 ">
                                <div class="pt-2 z-30 ">
                                    <div class="w-56 flex py-4 px-2 hover:bg-primary-000 dark:hover:bg-primary-900 cursor-pointer items-center"
                                            @click="[form.category_id = '']">
                                        <ps-label class="ms-2">{{$t('core__be_general_for_all_category')}}</ps-label>
                                    </div>
                                    <div v-for="category in categories" :key="category.id"
                                            class="w-56 flex py-4 px-2 hover:bg-primary-000 dark:hover:bg-primary-900 cursor-pointer items-center"
                                            @click="[form.category_id = category.id]">
                                        <ps-label class="ms-2"
                                                    :class="category.id == form.category_id ? ' font-bold' : ''">
                                            {{ category.name }} </ps-label>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </ps-dropdown>
                </div>
            </div>
            <ps-label v-if="selectedClientTable.core_key_type_id == 1" class="px-6 mt-4 text-sm md:text-base"> <span class="text-red-500">*</span>{{ $t('core__be_choose_category_for_custom_field_desc') }} <span class="text-red-500">*</span></ps-label>

                <div class="mt-10 ">
                <!-- card header start -->
                <!-- <div class="bg-primary-50 dark:bg-primary-900 py-2.5 ps-4 rounded-t-xl">
                    <ps-label-header-5 textColor="py-4 ps-6 pe-6 md:ps-8 md:pe-24 text-secondary-800 dark:text-secondary-100">{{ $t('new_custom_field_label') }}</ps-label-header-5>
                </div> -->
                <!-- card header end -->


                <!-- card body start -->


                <div>
                    <div class=" bg-white dark:bg-secondary-900 rounded-lg border border-secondary-200 dark:bprder-secondary-900 shadow-lg">
                        <ps-label-header-5 textColor="py-4 ps-6 pe-6 md:ps-8 md:pe-24 text-secondary-800 dark:text-secondary-100">
                            {{ $t('core_be_field_configuration') }}
                            <span class="text-red-500">*</span>
                        </ps-label-header-5>
                        <hr class="">
                        <div class="py-6 ps-6 pe-6 md:ps-8 md:pe-24 flex flex-col space-y-6">
                            <div class="grid grid-cols-3 items-center">
                                <ps-label class="text-base">
                                    {{$t('core_custom__name_label')}}<span class="text-red-500 font-medium ms-1">*</span>
                                </ps-label>

                                <div  class=" w-full  col-span-2">
                                    <ps-input-with-right-icon  class="w-full text-base" :disabled="true" v-model:value="nameString"  :placeholder="$t('core_custom__name_placeholder') + ' (' + activeLanguageName + ')'"  >
                                        <template #icon >

                                            <ps-icon class="cursor-pointer" @click="nameLanguageClicked(form.name)" name="editPencil"/>
                                        </template>
                                    </ps-input-with-right-icon>

                                </div>
                                <ps-label-caption textColor="text-red-500 "  v-if="validiteName" class="col-span-3 mt-2 block">{{ $t('core__be_name_required') }}</ps-label-caption>
                            </div>

                            <div class="grid grid-cols-3 items-center">
                                <ps-label class="text-base">
                                    {{ $t("core_custom__placeholder_label") }}<span class="text-red-500 font-medium ms-1">*</span>
                                </ps-label>
                                 <div  class=" w-full col-span-2">
                                    <ps-input-with-right-icon  class="w-full text-base" :disabled="true" v-model:value="placeholderString"  :placeholder="$t('core_custom__placeholder_placeholder') + ' (' + activeLanguageName + ')'" >
                                        <template #icon >

                                            <ps-icon class="cursor-pointer" @click="placeholderLanguageClicked(form.name)" name="editPencil"/>
                                        </template>
                                    </ps-input-with-right-icon>

                                </div>
                                <ps-label-caption textColor="text-red-500 "  v-if="validitePlaceholder" class="col-span-3 mt-2 block">{{ $t('core__be_placeholder_required') }}</ps-label-caption>
                            </div>

                            <div class="grid grid-cols-3 items-center">
                                <ps-label class="text-base">{{ $t("core_custom__ui_type_label") }}<span class="text-red-500 font-medium ms-1">*</span>
                                </ps-label>
                                <ps-dropdown align="left" class='col-span-2  w-full'>
                                    <template #select>
                                        <ps-dropdown-select :placeholder="$t('core_custom__select_ui_type_label')"
                                                            :selectedValue="(form.ui_type_id == '') ? '' : uiTypes.filter(uiType => uiType.core_keys_id === form.ui_type_id)[0].name"
                                                            @change="validateEmptyInput('ui_type_id', form.ui_type_id)"
                                                            @blur="validateEmptyInput('ui_type_id', form.ui_type_id)" />
                                    </template>
                                    <template #list>
                                        <div class="rounded-md shadow-xs w-56 ">
                                            <div class="pt-2 z-30 ">
                                                <div class="w-56 flex py-4 px-2 hover:bg-primary-000 dark:hover:bg-primary-900 cursor-pointer items-center"
                                                        @click="[form.ui_type_id = '', validateEmptyInput('ui_type_id', form.ui_type_id)]">
                                                    <ps-label class="text-gray-200">{{$t('core_custom__select_ui_type_label')}}</ps-label>
                                                </div>
                                                <div v-for="uiType in uiTypes" :key="uiType.core_keys_id"
                                                        class="w-56 flex py-4 px-2 hover:bg-primary-000 dark:hover:bg-primary-900 cursor-pointer items-center"
                                                        @click="[validiteUIType = false, form.ui_type_id = uiType.core_keys_id, validateEmptyInput('ui_type_id', form.ui_type_id)]">
                                                    <ps-label class="ms-2"
                                                                :class="uiType.core_keys_id == form.ui_type_id ? ' font-bold' : ''">
                                                        {{ uiType.name }} </ps-label>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </ps-dropdown>
                                <ps-label-caption textColor="text-red-500 " v-if="validiteUIType" class="col-span-3 mt-2 block">{{ $t('core__be_ui_type_is_required') }}</ps-label-caption>
                            </div>

                            <!-- <div class="">
                                <ps-checkbox-value v-model:value.number="form.mandatory" class="font-normal" title="Is Mandatory" />
                            </div> -->
                            <div class="grid grid-cols-3 items-center">
                                <ps-label class="text-base">{{$t('core__custom_type_of_conditions_label')}}<span class="text-red-500 font-medium ms-1">*</span></ps-label>
                                <ps-radio-value class=""
                                    v-model:value="form.mandatory"
                                    title="1"
                                    :title_label="$t('core__custom_mandatory_label')"

                                    />
                                    <ps-radio-value
                                    v-model:value="form.mandatory"
                                    title="0"
                                    :title_label="$t('core__custom_optional_label')"

                                    />
                            </div>

                            <div class="grid grid-cols-3 items-center">
                                <ps-label class="text-base">
                                    {{ $t("core__custom_column_order_label") }}<span class="text-red-500 font-medium ms-1">*</span>
                                </ps-label>
                                <ps-input class="col-span-2" type="number" v-model:value.number="form.ordering" :placeholder="$t('column_order_placeholder')"
                                           @keypress="validateNumber($event)" />
                                <ps-label-caption textColor="text-red-500 "  v-if="validiteOrdering" class="col-span-3 mt-2 block">{{ $t('core__be_ordering_required') }}</ps-label-caption>
                            </div>
                        </div>
                    </div>


                    <div class="mt-6 md:mt-8  bg-white dark:bg-secondary-900 rounded-lg border border-secondary-200 dark:bprder-secondary-900 shadow-lg">
                        <ps-label-header-5 textColor="py-4 ps-6 pe-6 md:ps-8 md:pe-24 text-secondary-800 dark:text-secondary-100">
                            {{ $t('core__custom_display_setting_label') }}
                            <span class="text-red-500">*</span>
                        </ps-label-header-5>
                        <hr class="">
                        <div class="py-6 ps-6 pe-6 md:ps-8 md:pe-24 flex flex-col space-y-6">

                            <div class="flex justify-between">
                                <ps-label class="text-base"> {{$t('core__custom_enable_sorting_this_field')}}</ps-label>
                                <ps-toggle :selectedValue="form.is_show_sorting == 1 ? true : false"
                                            @click="handleIsShowSorting()" toggleOnTheme="bg-primary-500 border-primary-500 "></ps-toggle>
                            </div>

                            <div class="flex justify-between">
                                <ps-label class="text-base"> {{$t('core__custom_enable_shown_in_table_for_field_label')}}</ps-label>
                                <ps-toggle :selectedValue="form.is_include_in_hideshow == 1 ? true : false"
                                    @click="handleEnableFilter()" toggleOnTheme="bg-primary-500 border-primary-500 "></ps-toggle>
                            </div>
                            <div v-if="form.is_include_in_hideshow" class="flex justify-between">
                                <ps-label class="text-base"> {{$t('core__custom_enable_shown_in_table_label')}}</ps-label>
                                <ps-toggle :selectedValue="form.is_show == 1 ? true : false" :disabled="form.is_include_in_hideshow == 0 ? true :false"
                                    @click="handleHideShow()" toggleOnTheme="bg-primary-500 border-primary-500 "></ps-toggle>
                            </div>

                            <div v-if="form.is_include_in_hideshow" class="flex justify-between">
                                <ps-label class="text-base"> {{$t('core__custom_is_show_in_filter_label')}}</ps-label>
                                <ps-toggle :selectedValue="form.is_show_in_filter == 1 ? true : false" :disabled="form.is_include_in_hideshow == 0 ? true :false"
                                            @click="handleIsShowInFilter()" toggleOnTheme="bg-primary-500 border-primary-500 "></ps-toggle>
                            </div>

                        </div>
                    </div>

                        <div class="mt-6 md:mt-8 mb-2.5 flex flex-row justify-end">
                            <ps-button @click="handleCancel()" textSize="text-base" type="reset" class="me-4"
                                colors="text-primary-500" focus="" hover="">{{ $t('core__be_btn_cancel') }}</ps-button>
                            <ps-button @click="handleSubmit()" class="transition-all duration-300 min-w-3xs" padding="px-8.5 py-2.5"
                                rounded="rounded" hover="" focus="">
                                <ps-loading v-if="loading"
                                    theme="me-2 border-2 border-t-2 border-text-8 border-t-primary-500"
                                    loadingSize="h-5 w-5" />
                                <ps-icon v-if="success"  name="check"
                                    class="me-1.5 transition-all duration-300" />
                                <span v-if="success" class="transition-all duration-300" textColor="text-white">{{ $t('core__be_btn_saved') }}</span>
                                <span v-if="!loading && !success" class="" textColor="text-white"> {{ $t('core__be_btn_save') }} </span>
                            </ps-button>
                        </div>
                    <!-- </div> -->
                </div>
            </div>
        </div>
        <ps-success-dialog ref="ps_success_dialog" />
        <ps-confirm-dialog ref="ps_confirm_dialog" />
        <ps-error-dialog ref="ps_error_dialog" />
        <language-update-modal ref="language_update_modal_name" />
        <language-update-modal ref="language_update_modal_placeholder" />
        <ps-loading-circle-dialog ref="ps_loading_circle_dialog" />
    </ps-layout>
</template>

<script>
import { defineComponent,ref, onMounted } from 'vue'
import PsLayout from "@/Components/PsLayout.vue";
import { Head, useForm } from "@inertiajs/vue3";
import useValidators from '@/Validation/Validators'
import PsInput from "@/Components/Core/Input/PsInput.vue";
import PsInputWithRightIcon from "@/Components/Core/Input/PsInputWithRightIcon.vue";
import PsLabel from "@/Components/Core/Label/PsLabel.vue";
import PsButton from "@/Components/Core/Buttons/PsButton.vue";
import PsTextarea from '@/Components/Core/Textarea/PsTextarea.vue';
import PsLabelHeader3 from "@/Components/Core/Label/PsLabelHeader3.vue";
import PsLabelHeader5 from "@/Components/Core/Label/PsLabelHeader5.vue";
import PsLabelCaption from "@/Components/Core/Label/PsLabelCaption.vue";
import PsIcon from "@/Components/Core/Icons/PsIcon.vue";
import PsLoading from "@/Components/Core/Loading/PsLoading.vue";
import PsBreadcrumb2 from "@/Components/Core/Breadcrumbs/PsBreadcrumb2.vue";
import PsDropdown from "@/Components/Core/Dropdown/PsDropdown.vue";
import PsDropdownSelect from "@/Components/Core/Dropdown/PsDropdownSelect.vue";
import PsCheckboxValue from "@/Components/Core/Checkbox/PsCheckboxValue.vue";
import PsToggle from '@/Components/Core/Toggle/PsToggle.vue';
import PsRadioValue from "@/Components/Core/Radio/PsRadioValue.vue";
import { trans } from 'laravel-vue-i18n';
    import PsErrorDialog from "@/Components/Core/Dialog/PsErrorDialog.vue";
    import PsSuccessDialog from '@/Components/Core/Dialog/PsSuccessDialog.vue';
    import PsConfirmDialog from '@/Components/Core/Dialog/PsConfirmDialog.vue';
import LanguageUpdateModal from '../../components/LanguageUpdateModal.vue'
import PsLoadingCircleDialog from '@/Components/Core/Dialog/PsLoadingCircleDialog.vue';

export default defineComponent({
    name: "create",
    components: {
        Head,
        PsInput,
        PsLabel,
        PsButton,
        PsTextarea,
        PsLabelHeader3,
        PsLabelCaption,
        PsIcon,
        PsLoading,
        PsBreadcrumb2,
        PsDropdown,
        PsDropdownSelect,
        PsCheckboxValue,
        PsToggle,
        PsRadioValue,
        PsConfirmDialog,
        PsSuccessDialog,
        PsErrorDialog,
        LanguageUpdateModal,
        PsLoadingCircleDialog,
        PsInputWithRightIcon,
        PsLabelHeader5
    },
    layout: PsLayout,
    props: ['languages','errors','status', 'selectedClientTable',  'uiTypes','categories'],
    setup(props) {
        const loading = ref(false);
        const success = ref(false);

        const ps_success_dialog = ref();
        const ps_confirm_dialog = ref();
        const ps_error_dialog = ref();
        const language_update_modal_name = ref();
        const language_update_modal_placeholder = ref();
        const ps_loading_circle_dialog = ref();
        const showdialog = ref(false);

        const nameString = ref('');
        const placeholderString = ref('');
        const validiteUIType = ref(false);
        const validiteOrdering = ref(false);
        const validiteName = ref(false);
        const validitePlaceholder = ref(false);

        const activeLanguageName = ref('');

        let form = useForm(
            {
                table_id: props.selectedClientTable.id,
                name: '',
                ordering: "",
                is_show_sorting: 0,
                is_show_in_filter: 0,
                placeholder: '',
                ui_type_id: "",
                is_core_field: 0,
                mandatory: '0',
                is_show: 0,
                is_include_in_hideshow: 0,
                category_id: "",
                name_str: "",
                placeholder_str: "",
            }
        )
        // Client Side Validation
        const { isEmpty, minLength} = useValidators();

        const validateModuleNameInput = (fieldName, fieldValue) => {
            props.errors[fieldName] = !fieldValue ? isEmpty(fieldName, fieldValue) : minLength(fieldName, fieldValue, 3);
        }

        const validateEmptyInput = (fieldName, fieldValue) => {
            props.errors[fieldName] = !fieldValue ? isEmpty(fieldName, fieldValue) : '';
        }

        // for only number input validate at keypress
        const onlyNumber = ($e) => {
            let keyCode = ($e.keyCode ? $e.keyCode : $e.which);
            if (keyCode < 48 || keyCode > 57) {
                $e.preventDefault();
            }
        }
        function handleEnableFilter(){
            if(form.is_include_in_hideshow == 0)
            {
                form.is_include_in_hideshow = 1;
            }
            else{
                form.is_include_in_hideshow = 0;
                form.is_show = 0;
                form.is_show_in_filter = 0;
            }

        }
         function handleHideShow(){
            if(form.is_show == 0)
            {
                form.is_show = 1;
            }
            else{
                form.is_show = 0;
            }

        }

        function handleIsShowSorting(){
            if(form.is_show_sorting == 0)
            {
                form.is_show_sorting = 1;
            }
            else{
                form.is_show_sorting = 0;
            }
        }

        function handleIsShowInFilter(){
            if(form.is_show_in_filter == 0)
            {
                form.is_show_in_filter = 1;
            }
            else{
                form.is_show_in_filter = 0;
            }
        }

        function submit() {

            //validation start
            let errorStatus = true;
            if(form.ui_type_id == ''){
                validiteUIType.value = true;
                errorStatus = false;
            }
            if(form.ordering == '' || isNaN(form.ordering)){
                validiteOrdering.value = true;
                errorStatus = false;
            }

            if(language_update_modal_name.value.form.values.length == 0){
                validiteName.value = true;
                errorStatus = false;
            }else{
                let hasvalue = false;
                for( let i = 0; i < language_update_modal_name.value.form.values.length; i++){
                    if(language_update_modal_name.value.form.values[i]?.value.length !== 0){
                        hasvalue = true;
                        break;
                    }
                }
                if(!hasvalue){
                    validiteName.value = true;
                    errorStatus = false;
                }
            }

            if(language_update_modal_placeholder.value.form.values.length == 0){
                validitePlaceholder.value = true;
                errorStatus = false;
            }else{
                let hasPlaceholdervalue = false;
                for( let i = 0; i < language_update_modal_placeholder.value.form.values.length; i++){
                    if(language_update_modal_placeholder.value.form.values[i]?.value.length !== 0){
                        hasPlaceholdervalue = true;
                        break;
                    }
                }
                if(!hasPlaceholdervalue){
                    validitePlaceholder.value = true;
                    errorStatus = false;
                }
            }

            if (errorStatus != true) {
                window.scrollTo(0, 0);
                return;
            }

            //validation end

            form.placeholder_str = placeholderString.value;
            form.name_str = nameString.value;
            form.nameForm = language_update_modal_name.value.form;
            form.placeholderForm = language_update_modal_placeholder.value.form;
            form.enable = 1;

            this.$inertia.post(route("tables.fields.store", [ props.selectedClientTable.id]),
                // useForm(
                //     {
                //         'nameForm': language_update_modal_name.value.form,
                //         'form': form,
                //         'placeholderForm':language_update_modal_placeholder.value.form,
                //     }
                // )
                form, {
                forceFormData: true,
                onBefore: () => {
                    loading.value = true;
                    showdialog.value = true;
                    ps_loading_circle_dialog.value.openModal(trans('core__be_custom_creating_title'),trans('core__be_custom_creating_message'));

                },
                onSuccess: () => {
                    loading.value = false;
                    success.value = true;
                    setTimeout(() => {
                        success.value = false;
                        window.location.reload();
                    }, 100)
                },
                onError: () => {
                    loading.value = false;;
                },
            });

        }

        function handleSubmit()
        {
            this.submit();
        }

        function showErrorDialog(msg = 'error'){
            ps_loading_circle_dialog.value.closeModal();
            if(showdialog.value){
                showdialog.value = false;
                ps_error_dialog.value.openModal(trans('ps_error_dialog__error'), msg,trans('core__be_btn_ok'), ()=> {
                });
            }
        }

        function showSuccessDialog(msg){
            ps_loading_circle_dialog.value.closeModal();

                showdialog.value = false;
                ps_success_dialog.value.openModal(trans('core__be_awesome_title'),msg,trans('core__be_btn_back_to_list'),
                    ()=>{
                        this.$inertia.get(route("tables.fields.index",[this.selectedClientTable.id]),{symbol: localStorage.activeLanguage ? localStorage.activeLanguage : 'en',});
                    })

        }

        function nameLanguageClicked(key){

            if(language_update_modal_name.value.form.key == ''){
                language_update_modal_name.value.form.key = '_name';
            }
            language_update_modal_name.value.setLanguageStrings(language_update_modal_name.value.form.key, props.languages);
            language_update_modal_name.value.openModal(key, (form) => {
                validiteName.value = false;
                const symbol = localStorage.activeLanguage ? localStorage.activeLanguage : 'en';
                form.values.forEach(language => {
                    if(language.symbol == symbol){
                        nameString.value = language.value;
                    }
                });
                // if(nameString.value == ''){
                //     form.values.forEach(language => {
                //         if(language.value.length !== 0){
                //             nameString.value = language.value;
                //             return;
                //         }
                //     });
                // }
            },
            true);

        }
        function placeholderLanguageClicked(key){

            if(language_update_modal_placeholder.value.form.key == ''){
                language_update_modal_placeholder.value.form.key = '_placeholder';
            }
            language_update_modal_name.value.setLanguageStrings(language_update_modal_name.value.form.key, props.languages);
            language_update_modal_placeholder.value.openModal(key, (form) => {
                validitePlaceholder.value = false;
                const symbol = localStorage.activeLanguage ? localStorage.activeLanguage : 'en';
                form.values.forEach(language => {
                    if(language.symbol == symbol){
                        placeholderString.value = language.value;
                    }
                });
                // if(placeholderString.value == ''){
                //     form.values.forEach(language => {
                //         if(language.value.length !== 0){
                //             placeholderString.value = language.value;
                //             return;
                //         }
                //     });
                // }
            },
            true);

        }

        function validateNumber(e) {
            validiteOrdering.value = false;
            const char = String.fromCharCode(e.keyCode); // Get the character
            if(/^[0-9]+$/.test(char)) return true; // Match with regex
            else e.preventDefault(); // If not match, don't add to input text

        }

        onMounted(async () => {
            const symbol = localStorage.activeLanguage ? localStorage.activeLanguage : 'en';
            props.languages.forEach(language => {
                if(language.symbol == symbol){
                    activeLanguageName.value = language.name;
                }
            });
        });

        return { ps_success_dialog,
        showdialog,
        activeLanguageName,
        validateNumber,
        validiteUIType,
        validiteOrdering,
        validitePlaceholder,
        validiteName,
        nameString,
        placeholderString,
        nameLanguageClicked,
        placeholderLanguageClicked,
        language_update_modal_name,
        language_update_modal_placeholder,
        ps_loading_circle_dialog,
        submit,
            ps_confirm_dialog,
            ps_error_dialog,
            showErrorDialog,
            showSuccessDialog,validateModuleNameInput, validateEmptyInput, onlyNumber,form,handleSubmit,loading,success,handleEnableFilter ,handleHideShow, handleIsShowSorting, handleIsShowInFilter }
    },
    computed: {
        breadcrumb() {

            return [
                {
                    label: trans('core__be_dashboard_label'),
                    url: route('admin.index')
                },
                {
                    label: trans('table_setting_group'),
                    url: route('table.index'),
                },
                {
                    label: trans('core__be_custom_fields'),
                    url: route("tables.fields.index",[this.selectedClientTable.id],{symbol: localStorage.activeLanguage ? localStorage.activeLanguage : 'en',})
                },
                {
                    label: trans('core__be_create_custom_field_label'),
                    color: "text-primary-500"
                }
            ]

        }
    },
    methods: {
        handleCancel() {
            this.$inertia.get(route("tables.fields.index",[this.selectedClientTable.id]),{symbol: localStorage.activeLanguage ? localStorage.activeLanguage : 'en',});
        },
        handleIsInclude(){
            if(!this.form.is_include_in_hideshow)
            {
                this.form.is_show = 0
            }
        }
    },
    mounted() {
        if(this.status && this.status.flag == 'success'){
            sessionStorage.setItem("custom_field_reloading", "true");
            sessionStorage.setItem("custom_field_flag", this.status.flag);
            sessionStorage.setItem("custom_field_message", this.status.msg);
            window.location.reload();
        }
        if(this.status && this.status.flag == 'error'){
            this.showErrorDialog(this.status.msg)
        }
        const flag = sessionStorage.getItem("custom_field_flag");
        const message = sessionStorage.getItem("custom_field_message");
        if(flag && flag == 'success'){
            sessionStorage.removeItem("custom_field_flag");
            sessionStorage.removeItem("custom_field_message");
            this.showSuccessDialog(message)
        }
        if(this.status.isDupicate){
            console.log(this.status.msg);
            this.handleSubmit();
        }
    },
    beforeUpdate() {
        if(this.status && this.status.flag == 'success'){
            sessionStorage.setItem("custom_field_reloading", "true");
            sessionStorage.setItem("custom_field_flag", this.status.flag);
            sessionStorage.setItem("custom_field_message", this.status.msg);
            window.location.reload();
        }
        if(this.status && this.status.flag == 'error'){
            this.showErrorDialog(this.status.msg)
        }
        if(this.status.isDupicate){
            console.log(this.status.msg);
            this.handleSubmit();
        }
    },
})
</script>
