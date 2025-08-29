<template>
    <Head :title="$t('vendor_menu_module')" />
    <ps-layout>
        <!-- breadcrumb start -->
        <ps-breadcrumb-2 :items="breadcrumb" class="mb-5 sm:mb-6 lg:mb-8" />
        <!-- breadcrumb end -->

        <ps-card class="w-full h-auto">
            <div class="rounded-lg ">
                <!-- card header start -->
                <div class="bg-primary-50 dark:bg-primary-900 py-2.5 ps-4 rounded-t-lg">
                    <ps-label-header-6 textColor="text-secondary-800 dark:text-secondary-100">{{$t('core_vendor_menu_onfo')}}</ps-label-header-6>
                </div>
                <!-- card header end -->

                <!-- card body start -->
                <div class="px-4 pt-6 dark:bg-backgroundDark">
                    <form @submit.prevent="handleSubmit(menu.id)">
                        <div class="grid w-1/2 gap-6">

                         <div v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'module_name' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                <ps-label class="text-base">{{ $t(coreField.label_name) }} <span v-if="coreField.mandatory==1" class="text-red-800 font-medium ms-1">*</span></ps-label>
                                <ps-input ref="module_name" type="text" v-model:value="form.module_name" :placeholder="$t(coreField.placeholder)"
                                    @keyup="coreField.mandatory==1?validateEmptyInput( 'module_name', form.module_name ):''" @blur="coreField.mandatory==1?validateEmptyInput('module_name', form.module_name ):''" />
                                <ps-label-caption v-if="coreField.mandatory==1" textColor="text-red-500 " class="mt-2 block">{{ errors.module_name }}</ps-label-caption>
                            </div>

                            <!-- for module dropdown -->
                            <div>
                                <ps-label class="text-base">{{ $t('core__be_select_module') }}<span class="text-red-800 font-medium ms-1">*</span>
                                </ps-label>
                                <ps-dropdown align="left" class='lg:mt-2 mt-1  w-full'>
                                    <template #select>
                                        <ps-dropdown-select :placeholder="$t('core__be_select_module')"
                                                            :selectedValue="(form.module_id == '' || form.module_id === null) ? forSelectedModules.filter(mod => mod.id == menu['module_id'])[0].title : forSelectedModules.filter(module => module.id == form.module_id)[0].title"
                                                            @change="validateEmptyInput('module_id', form.module_id)"
                                                            @blur="validateEmptyInput('module_id', form.module_id)" />
                                    </template>
                                    <template #list>
                                        <div class="rounded-md shadow-xs w-56 ">
                                            <div class="pt-2 z-30 ">
                                                <div class="w-56 flex py-4 px-2 hover:bg-primary-000 dark:hover:bg-primary-900 cursor-pointer items-center"
                                                    @click="[form.module_id = menu['module_id'], validateEmptyInput('module_id', form.module_id)]">
                                                    <ps-label v-if="form.module_id == '' || form.module_id == menu['module_id']" class="ms-2 font-bold">{{forSelectedModules.filter(mod => mod.id == menu['module_id'])[0].title}}</ps-label>
                                                    <ps-label v-else class="ms-2">{{forSelectedModules.filter(mod => mod.id == menu['module_id'])[0].title}}</ps-label>
                                                </div>
                                                <div v-for="module in modules" :key="module.id" class="w-56 flex py-4 px-2 hover:bg-primary-000 dark:hover:bg-primary-900 cursor-pointer items-center"
                                                     @click="[form.module_id = module.id, validateEmptyInput('module_id', form.module_id)]">
                                                    <ps-label class="ms-2" :class="module.id == form.module_id ? ' font-bold' : ''"> {{ module.title }} </ps-label>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </ps-dropdown>
                                <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ errors.module_id }}</ps-label-caption>
                            </div>
                            <!-- end module group -->

                            <!-- for sub menu group dropdown -->
                            <div>
                                <ps-label class="text-base">{{ $t('core__be_select_sub_menu') }}<span class="text-red-800 font-medium ms-1">*</span>
                                </ps-label>
                                <ps-dropdown align="left" class='lg:mt-2 mt-1  w-full'>
                                    <template #select>
                                        <ps-dropdown-select :placeholder="$t('core__be_select_sub_menu')"
                                            :selectedValue="(form.core_sub_menu_group_id == '') ? '' : sub_menu_groups.filter(sub_menu_group => sub_menu_group.id == form.core_sub_menu_group_id)[0].sub_menu_desc"
                                            @change="validateEmptyInput('core_sub_menu_group_id', form.core_sub_menu_group_id)"
                                            @blur="validateEmptyInput('core_sub_menu_group_id', form.core_sub_menu_group_id)" />
                                    </template>
                                    <template #list>
                                        <div class="rounded-md shadow-xs w-56 ">
                                            <div class="pt-2 z-30 ">

                                                <div v-for="sub_menu_group in sub_menu_groups" :key="sub_menu_group.id"
                                                    class="w-56 flex py-4 px-2 hover:bg-primary-000 dark:hover:bg-primary-900 cursor-pointer items-center"
                                                    @click="[form.core_sub_menu_group_id = sub_menu_group.id, validateEmptyInput('core_sub_menu_group_id', form.core_sub_menu_group_id)]">
                                                    <ps-label class="ms-2"
                                                        :class="sub_menu_group.id == form.core_sub_menu_group_id ? ' font-bold' : ''">
                                                        {{ sub_menu_group.sub_menu_desc }} </ps-label>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </ps-dropdown>
                                <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ errors.core_sub_menu_group_id }}</ps-label-caption>
                            </div>
                            <!-- end sub menu group -->

                            <!-- <div>
                                <ps-label class="text-base mb-1">
                                    Description<span class="text-red-800 font-medium ms-1">*</span>
                                </ps-label>
                                <ps-input type="text" v-model:value="form.module_desc" placeholder="Description"
                                    @keyup="validateEmptyInput('module_desc', form.module_desc)"
                                    @blur="validateEmptyInput('module_desc', form.module_desc)"/>
                                <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ errors.module_desc }}</ps-label-caption>
                            </div> -->
                            <!-- module_desc-->
                            <div v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'module_desc' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                <ps-label class="text-base">{{ $t(coreField.label_name) }} <span v-if="coreField.mandatory==1" class="text-red-800 font-medium ms-1">*</span></ps-label>
                                <ps-textarea rows="4" v-model:value="form.module_desc" :placeholder="$t(coreField.placeholder)"
                                    @keyup="coreField.mandatory==1?validateEmptyInput( 'module_desc', form.module_desc ):''" @blur="coreField.mandatory==1?validateEmptyInput('module_desc', form.module_desc ):''" />
                                <ps-label-caption v-if="coreField.mandatory==1" textColor="text-red-500 " class="mt-2 block">{{ errors.module_desc }}</ps-label-caption>
                            </div>

                            <!-- <div>
                                <ps-label class="text-base mb-1">
                                    Language Key<span class="text-red-800 font-medium ms-1">*</span>
                                </ps-label>
                                <ps-input type="text" v-model:value="form.module_lang_key" placeholder="Language key"
                                    @keyup="validateEmptyInput('module_lang_key', form.module_lang_key)"
                                    @blur="validateEmptyInput('module_lang_key', form.module_lang_key)"/>
                                <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ errors.module_lang_key }}</ps-label-caption>
                            </div> -->
                             <!-- module_lang_key-->
                            <div v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'module_lang_key' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                <ps-label class="text-base">{{ $t(coreField.label_name) }} <span v-if="coreField.mandatory==1" class="text-red-800 font-medium ms-1">*</span></ps-label>
                                <ps-input ref="module_lang_key" type="text" v-model:value="form.module_lang_key" :placeholder="$t(coreField.placeholder)"
                                    @keyup="coreField.mandatory==1?validateEmptyInput( 'module_lang_key', form.module_lang_key ):''" @blur="coreField.mandatory==1?validateEmptyInput('module_lang_key', form.module_lang_key ):''" />
                                <ps-label-caption v-if="coreField.mandatory==1" textColor="text-red-500 " class="mt-2 block">{{ errors.module_lang_key }}</ps-label-caption>
                            </div>
                            <!-- <div>
                                <ps-label class="text-base mb-1">Module Icon</ps-label>
                                <ps-input type="text" v-model:value="form.module_icon" placeholder="Module Icon"
                                    />
                                <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ errors.module_icon }}</ps-label-caption>
                            </div> -->

                            <!-- for icon dropdown -->
                            <!-- <div>
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
                                                <div class="w-56 flex py-4 px-2 hover:bg-primary-000 dark:hover:bg-primary-900 cursor-pointer items-center"
                                                     @click="[form.icon_id = '', validateEmptyInput('icon_id', form.icon_id)]">
                                                    <ps-label class="text-gray-200">{{ $t('select_icon') }}</ps-label>
                                                </div>
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
                            </div> -->
                            <!-- end icon group -->

                            <div>
                                <ps-label class="text-base mb-1">Ordering</ps-label>
                                <ps-input type="text" v-model:value="form.ordering" placeholder="Ordering"
                                        @keypress="onlyNumber"/>
                                <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ errors.ordering }}</ps-label-caption>
                            </div>
                            <div>
                                <ps-label class="text-base">Status</ps-label>
                                <ps-checkbox-value v-model:value="form.is_show_on_menu" class="font-normal" title="Publish" />
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
import { Head, useForm } from "@inertiajs/vue3";
import useValidators from '@/Validation/Validators'
import PsInput from "@/Components/Core/Input/PsInput.vue";
import PsLabel from "@/Components/Core/Label/PsLabel.vue";
import PsButton from "@/Components/Core/Buttons/PsButton.vue";
import PsTextarea from '@/Components/Core/Textarea/PsTextarea.vue';
import PsLabelHeader4 from "@/Components/Core/Label/PsLabelHeader4.vue";
import PsLabelCaption from "@/Components/Core/Label/PsLabelCaption.vue";
import PsIcon from "@/Components/Core/Icons/PsIcon.vue";
import PsLoading from "@/Components/Core/Loading/PsLoading.vue";
import PsBreadcrumb2 from "@/Components/Core/Breadcrumbs/PsBreadcrumb2.vue";
import PsDropdown from "@/Components/Core/Dropdown/PsDropdown.vue";
import PsDropdownSelect from "@/Components/Core/Dropdown/PsDropdownSelect.vue";
import PsCheckboxValue from "@/Components/Core/Checkbox/PsCheckboxValue.vue";
import { trans } from 'laravel-vue-i18n';

export default defineComponent({
    name: "Edit",
    components: {
        Head,
        PsInput,
        PsLabel,
        PsButton,
        PsTextarea,
        PsLabelHeader4,
        PsLabelCaption,
        PsIcon,
        PsLoading,
        PsBreadcrumb2,
        PsDropdown,
        PsDropdownSelect,
        PsCheckboxValue
    },
    layout: PsLayout,
    props: ['errors','menu', 'sub_menu_groups','coreFieldFilterSettings', 'icons', 'modules', 'forSelectedModules'],
    setup(props) {
        const loading = ref(false);
        const success = ref(false);
        let form = useForm(
                {
                    module_name: props.menu.module_name,
                    module_desc: props.menu.module_desc,
                    module_lang_key: props.menu.module_lang_key,
                    // icon_id: props.menu.icon_id,
                    ordering: props.menu.ordering,
                    module_id: props.menu.module_id,
                    old_module_id: props.menu.module_id,
                    // module_id: props.modules.find(element=> element.id == props.menu.module_id) ? props.menu.module_id : '',
                    // core_sub_menu_group_id: props.menu.core_sub_menu_group_id,
                    core_sub_menu_group_id: props.sub_menu_groups.find(element=> element.id == props.menu.core_sub_menu_group_id) ? props.menu.core_sub_menu_group_id : '',
                    is_show_on_menu: props.menu.is_show_on_menu == 1 ? true : false,
                    "_method": "put"
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
        function handleSubmit(id) {
            this.$inertia.post(route('vendor_menu.update',id), form, {
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
            },
            })
        }

        return { validateModuleNameInput, validateEmptyInput, onlyNumber,form,handleSubmit,loading,success }
    },
    computed: {
        breadcrumb() {

            return [
                {
                    label: trans('core__be_dashboard_label'),
                    url: route('admin.index')
                },
                {
                    label: trans('vendor_menu_module'),
                    url: route('vendor_menu.index'),
                },
                {
                    label: trans('core_edit_vendor_menu'),
                    color: "text-primary-500"
                }
            ]

        }
    },
    methods: {
        handleCancel() {
            this.$inertia.get(route('vendor_menu.index'));
        },
    }
})
</script>
