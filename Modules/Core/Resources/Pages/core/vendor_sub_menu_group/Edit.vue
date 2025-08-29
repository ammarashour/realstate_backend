<template>
    <Head :title="$t('edit_vendor_sub_menu_group')" />
    <ps-layout>
        <!-- breadcrumb start -->
        <ps-breadcrumb-2 :items="breadcrumb" class="mb-5 sm:mb-6 lg:mb-8" />
        <!-- breadcrumb end -->

        <ps-card class="w-full h-auto">
            <div class="rounded-xl">
                <!-- card header start -->
                <div class="bg-primary-50 dark:bg-primary-900 py-2.5 ps-4 rounded-t-xl">
                    <ps-label-header-6 textColor="text-secondary-800 dark:text-secondary-100">{{$t('core__vendor_sub_menu_group_info')}}</ps-label-header-6>
                </div>
                <!-- card header end -->

                <!-- card body start -->
                <div class="px-4 pt-6 dark:bg-backgroundDark">

                    <form @submit.prevent="handleSubmit(sub_menu_group.id)">
                        <div class="grid w-1/2 gap-6">
                              <div>
                                <!-- <ps-label class="text-base mb-1" >
                                    Sub Menu Name <span class="text-red-800 font-medium ms-1">*</span>
                                </ps-label> -->
                                <!-- sub_menu_name-->
                                <div v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'sub_menu_name' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                    <ps-label class="text-base mb-2">{{ $t(coreField.label_name) }} <span v-if="coreField.mandatory=1" class="text-red-800 font-medium ms-1">*</span></ps-label>
                                    <ps-input ref="sub_menu_name" type="text" v-model:value="form.sub_menu_name" :placeholder="$t(coreField.placeholder)"
                                        @keyup="coreField.mandatory==1?validateEmptyInput( 'sub_menu_name', form.sub_menu_name ):''" @blur="coreField.mandatory==1?validateEmptyInput('sub_menu_name', form.sub_menu_name ):''" />
                                    <ps-label-caption v-if="coreField.mandatory==1" textColor="text-red-500 " class="mt-2 block">{{ errors.sub_menu_name }}</ps-label-caption>
                                </div>
                                <!-- <ps-input type="text" ref="input_name" v-model:value="form.sub_menu_name" placeholder="Sub Menu Name"
                                    @keyup="validateNameInput('sub_menu_name', form.sub_menu_name)"
                                    @blur="validateNameInput('sub_menu_name', form.sub_menu_name)"/>
                                <ps-label-caption textColor="text-red-500 block">{{ errors.sub_menu_name }}</ps-label-caption> -->
                            </div>

                            <!-- for menu group dropdown -->
                            <div>
                                <ps-label class="text-base mb-2">Select Menu Group<span class="text-red-800 font-medium ms-1">*</span>
                                </ps-label>
                                <ps-dropdown align="left" class='w-full'>
                                    <template #select>
                                        <ps-dropdown-select placeholder="Select Menu Group"
                                            :selectedValue="(form.core_menu_group_id == '') ? '' : menu_groups.filter(menu_group => menu_group.id == form.core_menu_group_id)[0].group_name"
                                            @change="validateEmptyInput('core_menu_group_id', form.core_menu_group_id)"
                                            @blur="validateEmptyInput('core_menu_group_id', form.core_menu_group_id)" />
                                    </template>
                                    <template #list>
                                        <div class="rounded-md shadow-xs w-56 ">
                                            <div class="pt-2 z-30 ">
                                                <!-- <div class="w-56 flex py-4 px-2 hover:bg-primary-000 dark:hover:bg-primary-900 cursor-pointer items-center"
                                                    @click="[form.core_menu_group_id = '', validateEmptyInput('core_menu_group_id', form.core_menu_group_id)]">
                                                    <ps-label class="text-gray-200">Select Sub Menu Group</ps-label>
                                                </div> -->
                                                <div v-for="menu_group in menu_groups" :key="menu_group.id"
                                                    class="w-56 flex py-4 px-2 hover:bg-primary-000 dark:hover:bg-primary-900 cursor-pointer items-center"
                                                    @click="[form.core_menu_group_id = menu_group.id, validateEmptyInput('core_menu_group_id', form.core_menu_group_id)]">
                                                    <ps-label class="ms-2"
                                                        :class="menu_group.id == form.core_menu_group_id ? ' font-bold' : ''">
                                                        {{ menu_group.group_name }} </ps-label>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </ps-dropdown>
                                <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ errors.core_menu_group_id }}</ps-label-caption>
                            </div>
                            <!-- end menu group -->


                            <div>
                            <!-- sub_menu_desc-->
                                <div v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'sub_menu_desc' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                    <ps-label class="text-base mb-2">{{ $t(coreField.label_name) }} <span v-if="coreField.mandatory=1" class="text-red-800 font-medium ms-1">*</span></ps-label>
                                    <ps-input ref="sub_menu_desc" type="text" v-model:value="form.sub_menu_desc" :placeholder="$t(coreField.placeholder)"
                                        @keyup="coreField.mandatory==1?validateEmptyInput( 'sub_menu_desc', form.sub_menu_desc ):''" @blur="coreField.mandatory==1?validateEmptyInput('sub_menu_desc', form.sub_menu_desc ):''" />
                                    <ps-label-caption v-if="coreField.mandatory==1" textColor="text-red-500 " class="mt-2 block">{{ errors.sub_menu_desc }}</ps-label-caption>
                                </div>
                                <!-- <ps-label class="text-base mb-1">
                                    Description<span class="text-red-800 font-medium ms-1">*</span>
                                </ps-label>
                                <ps-input type="text" v-model:value="form.sub_menu_desc" placeholder="Description"
                                    @keyup="validateEmptyInput('sub_menu_desc', form.sub_menu_desc)"
                                    @blur="validateEmptyInput('sub_menu_desc', form.sub_menu_desc)"/>
                                <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ errors.sub_menu_desc }}</ps-label-caption> -->
                            </div>

                            <div>
                                <!-- <ps-label class="text-base mb-1">
                                    Language Key<span class="text-red-800 font-medium ms-1">*</span>
                                </ps-label>
                                <ps-input ref="input_lang" type="text" v-model:value="form.sub_menu_lang_key" placeholder="Language key"
                                    @keyup="validateEmptyInput('sub_menu_lang_key', form.sub_menu_lang_key)"
                                    @blur="validateEmptyInput('sub_menu_lang_key', form.sub_menu_lang_key)"/>
                                <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ errors.sub_menu_lang_key }}</ps-label-caption> -->
                                  <!-- sub_menu_lang_key-->
                                <div v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'sub_menu_lang_key' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                    <ps-label class="text-base mb-2">{{ $t(coreField.label_name) }} <span v-if="coreField.mandatory=1" class="text-red-800 font-medium ms-1">*</span></ps-label>
                                    <ps-input ref="sub_menu_lang_key" type="text" v-model:value="form.sub_menu_lang_key" :placeholder="$t(coreField.placeholder)"
                                        @keyup="coreField.mandatory==1?validateEmptyInput( 'sub_menu_lang_key', form.sub_menu_lang_key ):''" @blur="coreField.mandatory==1?validateEmptyInput('sub_menu_lang_key', form.sub_menu_lang_key ):''" />
                                    <ps-label-caption v-if="coreField.mandatory==1" textColor="text-red-500 " class="mt-2 block">{{ errors.sub_menu_lang_key }}</ps-label-caption>
                                </div>
                            </div>

                            <!-- for icon dropdown -->
                            <div>
                                <ps-label class="text-base">{{ $t('select_icon') }}<span class="text-red-800 font-medium ms-1">*</span>
                                </ps-label>
                                <ps-dropdown align="left" class='lg:mt-2 mt-1  w-full'>
                                    <template #select>
                                        <ps-dropdown-select :placeholder="$t('select_icon')"
                                                            :selectedValue="(form.icon_id == '' || form.icon_id === null) ? '' : icons.filter(icon => icon.id == form.icon_id)[0].icon_name"
                                                            @change="validateEmptyInput('icon_id', form.icon_id)"
                                                            @blur="validateEmptyInput('icon_id', form.icon_id)" />
                                    </template>
                                    <template #list>
                                        <div class="rounded-md shadow-xs w-56 ">
                                            <div class="pt-2 z-30 ">
                                                <!-- <div class="w-56 flex py-4 px-2 hover:bg-primary-000 dark:hover:bg-primary-900 cursor-pointer items-center"
                                                     @click="[form.icon_id = '', validateEmptyInput('icon_id', form.icon_id)]">
                                                    <ps-label class="text-gray-200">{{ $t('select_icon') }}</ps-label>
                                                </div> -->
                                                <div v-for="icon in icons" :key="icon.id"
                                                     class="w-56 flex py-4 px-2 hover:bg-primary-000 dark:hover:bg-primary-900 cursor-pointer items-center"
                                                     @click="[form.icon_id = icon.id, validateEmptyInput('icon_id', form.icon_id)]">
                                                    <ps-label class="ms-2"
                                                              :class="icon.id == form.icon_id ? ' font-bold' : ''">
                                                        {{ icon.icon_name }} </ps-label>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </ps-dropdown>
                                <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ errors.icon_id }}</ps-label-caption>
                            </div>
                            <!-- end icon group -->

                            <div>
                            <!-- ordering-->
                                <div v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'ordering' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                    <ps-label class="text-base mb-2">{{ $t(coreField.label_name) }} <span v-if="coreField.mandatory=1" class="text-red-800 font-medium ms-1">*</span></ps-label>
                                    <ps-input ref="ordering" type="number" v-model:value="form.ordering" :placeholder="$t(coreField.placeholder)"
                                        @keyup="coreField.mandatory==1?validateEmptyInput( 'ordering', form.ordering ):''" @blur="coreField.mandatory==1?validateEmptyInput('ordering', form.ordering ):''" />
                                    <ps-label-caption v-if="coreField.mandatory==1" textColor="text-red-500 " class="mt-2 block">{{ errors.ordering }}</ps-label-caption>
                                </div>
                                <!-- <ps-label class="text-base mb-1">Ordering</ps-label>
                                <ps-input type="text" v-model:value="form.ordering" placeholder="Ordering"
                                        @keypress="onlyNumber"/>
                                <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ errors.ordering }}</ps-label-caption> -->
                            </div>

                            <!-- for has child start -->
                            <div>
                                <ps-label class="text-base mb-2">{{ $t('has_child_label') }}</ps-label>
                                <ps-checkbox-value v-model:value="form.is_dropdown" class="font-normal" :title="$t('has_child')" />
                            </div>
                            <!-- for has child end -->

                            <!-- for module dropdown -->
                            <div v-if="!form.is_dropdown">
                                <ps-label class="text-base mb-2">{{ $t('select_module') }}<span class="text-red-800 font-medium ms-1">*</span>
                                </ps-label>
                                <ps-dropdown align="left" class='w-full'>
                                    <template #select>
                                        <ps-dropdown-select :placeholder="$t('select_module')"
                                                            :selectedValue="(form.module_id == '' || form.module_id === null) ? '' : forSelectedModules.filter(module => module.id == form.module_id)[0].title"
                                                            @change="validateEmptyInput('module_id', form.module_id)"
                                                            @blur="validateEmptyInput('module_id', form.module_id)" />
                                    </template>
                                    <template #list>
                                        <div class="rounded-md shadow-xs w-56 ">
                                            <div class="pt-2 z-30 ">
                                                <div class="w-56 flex py-4 px-2 hover:bg-primary-000 dark:hover:bg-primary-900 cursor-pointer items-center"
                                                     @click="[form.module_id = '', validateEmptyInput('module_id', form.module_id)]">
                                                    <ps-label class="text-gray-200">Select Sub Menu Group</ps-label>
                                                </div>
                                                <div v-for="module in modules" :key="module.id"
                                                     class="w-56 flex py-4 px-2 hover:bg-primary-000 dark:hover:bg-primary-900 cursor-pointer items-center"
                                                     @click="[form.module_id = module.id, validateEmptyInput('module_id', form.module_id)]">
                                                    <ps-label class="ms-2"
                                                              :class="module.id == form.module_id ? ' font-bold' : ''">
                                                        {{ module.title }} </ps-label>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </ps-dropdown>
                                <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ errors.module_id }}</ps-label-caption>
                            </div>
                            <!-- end module group -->

                            <div>
                                <ps-label class="text-base mb-2">{{ $t('status') }}</ps-label>
                                <ps-checkbox-value v-model:value="form.is_show_on_menu" class="font-normal" :title="$t('publish')" />
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
import { defineComponent,ref } from 'vue'
import PsLayout from "@/Components/PsLayout.vue";
import { Head,Link, useForm } from "@inertiajs/vue3";
import useValidators from '@/Validation/Validators'
import PsInput from "@/Components/Core/Input/PsInput.vue";
import PsLabel from "@/Components/Core/Label/PsLabel.vue";
import PsButton from "@/Components/Core/Buttons/PsButton.vue";
import PsTextarea from '@/Components/Core/Textarea/PsTextarea.vue';
import PsLabelHeader4 from "@/Components/Core/Label/PsLabelHeader4.vue";
import PsIcon from "@/Components/Core/Icons/PsIcon.vue";
import PsLoading from "@/Components/Core/Loading/PsLoading.vue";
import PsTextButton from "@/Components/Core/Buttons/PsTextButton.vue";
import PsBreadcrumb2 from "@/Components/Core/Breadcrumbs/PsBreadcrumb2.vue";
import PsCheckboxValue from "@/Components/Core/Checkbox/PsCheckboxValue.vue";
import PsDropdown from "@/Components/Core/Dropdown/PsDropdown.vue";
import PsDropdownSelect from "@/Components/Core/Dropdown/PsDropdownSelect.vue";
import { trans } from 'laravel-vue-i18n';
import PsLabelCaption from "@/Components/Core/Label/PsLabelCaption.vue";

export default defineComponent({
    name: "Create",
    components: {
        PsBreadcrumb2,
        Head,
        PsInput,
        PsLabel,
        PsButton,
        PsTextarea,
        PsLabelHeader4,
        PsIcon,
        PsLoading,
        PsTextButton,
        PsCheckboxValue,
        PsDropdown,
        PsDropdownSelect,
        Link,
        PsLabelCaption
    },
    layout: PsLayout,
    props: ['errors','sub_menu_group','coreFieldFilterSettings','menu_groups', 'icons', 'modules', 'forSelectedModules'],
    setup(props) {
        // Client Side Validation
        const { isEmpty, minLength} = useValidators();
        const loading = ref(false);
        const success = ref(false);
        const input_name = ref(false);
        const input_icon = ref(false);
        const input_lang = ref(false);
        const input_desc = ref(false);

        let form = useForm(
                {
                    sub_menu_name: props.sub_menu_group.sub_menu_name,
                    sub_menu_icon: props.sub_menu_group.sub_menu_icon,
                    sub_menu_lang_key: props.sub_menu_group.sub_menu_lang_key,
                    // icon_id: props.sub_menu_group.icon_id,
                    icon_id: props.icons.find(element=> element.id == props.sub_menu_group.icon_id) ? props.sub_menu_group.icon_id : '',
                    is_dropdown: props.sub_menu_group.is_dropdown,
                    module_id: props.forSelectedModules.find(element=> element.id == props.sub_menu_group.module_id) ? props.sub_menu_group.module_id : '',
                    sub_menu_desc: props.sub_menu_group.sub_menu_desc,
                    ordering: props.sub_menu_group.ordering,
                    // core_menu_group_id: props.sub_menu_group.core_menu_group_id,
                    core_menu_group_id: props.menu_groups.find(element=> element.id == props.sub_menu_group.core_menu_group_id) ? props.sub_menu_group.core_menu_group_id : '',
                    is_show_on_menu: props.sub_menu_group.is_show_on_menu == true? 1: 0,
                    "_method": "put"
                }
            )



        const validateNameInput = (fieldName, fieldValue) => {
            props.errors[fieldName] = !fieldValue ? isEmpty(fieldName, fieldValue) : minLength(fieldName, fieldValue, 3);
        }
        const validateEmptyInput = (fieldName, fieldValue, errorMessage = '') => {
            props.errors[fieldName] = !fieldValue ? isEmpty(fieldName, fieldValue, errorMessage) : '';
            if(props.errors[fieldName] == ''){
                if(fieldName == 'sub_menu_name'){
                    input_name.value.isError = false;
                }
                if(fieldName == 'sub_menu_desc'){
                    input_desc.value.isError = false;
                }
                if(fieldName == 'sub_menu_icon'){
                    input_icon.value.isError = false;
                }
                if(fieldName == 'sub_menu_lang_key'){
                    input_lang.value.isError = false;
                }
            }else{
                if(fieldName == 'sub_menu_name'){
                    input_name.value.isError = true;
                }
                if(fieldName == 'sub_menu_desc'){
                    input_desc.value.isError = true;
                }
                if(fieldName == 'sub_menu_icon'){
                    input_icon.value.isError = true;
                }
                if(fieldName == 'sub_menu_lang_key'){
                    input_lang.value.isError = true;
                }
            }
        }
        function handleSubmit(id) {
            this.$inertia.post(route('vendor_sub_menu_group.update', id), form, {
                forceFormData: true,
            onBefore: () => {loading.value = true},
            onSuccess: () => {
                loading.value = false;
                success.value = true;
                setTimeout(()=>{
                    success.value = false;
                },2000)
            },
            onError: () => {
                loading.value = false;
                if(props.errors.sub_menu_name != undefined && props.errors.sub_menu_name != ''){
                    input_name.value.isError = true;
                }
                if(props.errors.sub_menu_desc != undefined && props.errors.sub_menu_desc != ''){
                    input_desc.value.isError = true;
                }
                if(props.errors.sub_menu_icon != undefined && props.errors.sub_menu_icon != ''){
                    input_icon.value.isError = true;
                }
                if(props.errors.sub_menu_lang_key != undefined && props.errors.sub_menu_lang_key != ''){
                    input_lang.value.isError = true;
                }
            },
            })
        }

        return {
            validateNameInput,
            validateEmptyInput,
            route,
            form,
            handleSubmit,
            loading,
            success,
            input_name,
            input_icon,
            input_lang,
            input_desc,

        }
    },
    computed: {
        breadcrumb() {

            return [
                {
                    label: trans('core__be_dashboard_label'),
                    url: route('admin.index')
                },
                {
                    label: trans('vendor_sub_menu_group_module'),
                    url: route('vendor_sub_menu_group.index'),
                },
                {

                    label: trans('edit_vendor_sub_menu_group'),
                    color: "text-primary-500"
                }
            ]

        },
        // moduleId() {
        //     if (this.form.is_dropdown){
        //         return this.form.module_id = 0;
        //     } else {
        //         return this.form.module_id = this.sub_menu_group.module_id;
        //     }
        // }
    },
    methods: {
        handleCancel() {
            this.$inertia.get(route('vendor_sub_menu_group.index'));
        },
    }
})
</script>

