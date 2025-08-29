<template>
    <Head :title="$t('create_menu_group')" />
    <ps-layout>
        <div class="">
            <!-- breadcrumb start -->
            <ps-breadcrumb-2 :items="breadcrumb" class="mb-5 sm:mb-6 lg:mb-8" />
            <!-- breadcrumb end -->

           <div class="max-w-lg">
                <div class="mt-8 rounded-lg bg-white dark:bg-secondaryDark-black  shadow-sm">
                    <ps-label class="text-lg px-4 py-2.5 bg-primary-50 dark:bg-primary-900">{{$t('add_menu_group')}}</ps-label>
                    <form @submit.prevent="handleSubmit">
                        <!-- group_name-->
                        <div class="mb-6 mt-4" v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'group_name' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                            <ps-label class="text-base mb-2">{{ $t(coreField.label_name) }} <span v-if="coreField.mandatory==1" class="text-red-800 font-medium ms-1">*</span></ps-label>
                            <ps-input ref="group_name" type="text" v-model:value="form.group_name" :placeholder="$t(coreField.placeholder)"
                                @keyup="coreField.mandatory==1?validateEmptyInput( 'group_name', form.group_name ):''" @blur="coreField.mandatory==1?validateEmptyInput('group_name', form.group_name ):''" />
                            <ps-label-caption v-if="coreField.mandatory==1" textColor="text-red-500 " class="mt-2 block">{{ errors.group_name }}</ps-label-caption>
                        </div>
                        <!-- <div class="px-4 py-2">
                            <ps-label class="text-base mb-1" >

                                Menu Group
                            </ps-label>
                            <ps-input type="text" ref="input_name" v-model:value="form.group_name" placeholder="Module Group Name"
                                @keyup="validateGroupNameInput('group_name', form.group_name)"
                                @blur="validateGroupNameInput('group_name', form.group_name)"/>
                            <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ errors.group_name }}</ps-label-caption>
                        </div> -->
                         <!-- group_icon-->
                        <!-- <div v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'group_icon' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                            <ps-label class="text-base">{{ $t(coreField.label_name) }} <span v-if="coreField.mandatory==1" class="text-red-800 font-medium ms-1">*</span></ps-label>
                            <ps-input ref="group_icon" type="text" v-model:value="form.group_icon" :placeholder="$t(coreField.placeholder)"
                                @keyup="coreField.mandatory==1?validateEmptyInput( 'group_icon', form.group_icon ):''" @blur="coreField.mandatory==1?validateEmptyInput('group_icon', form.group_icon ):''" />
                            <ps-label-caption v-if="coreField.mandatory==1" textColor="text-red-500 " class="mt-2 block">{{ errors.group_icon }}</ps-label-caption>
                        </div> -->
                        <!-- <div class="px-4 py-2">
                            <ps-label class="text-base mb-1" >Menu Group Icon</ps-label>
                            <ps-input type="text" ref="input_icon" v-model:value="form.group_icon" placeholder="Module Group Icon"
                                />
                            <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ errors.group_icon }}</ps-label-caption>
                        </div> -->
                         <!-- group_lang_key-->

                        <div class="mb-6" v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'group_lang_key' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                            <ps-label class="text-base mb-2">{{ $t(coreField.label_name) }} <span v-if="coreField.mandatory==1" class="text-red-800 font-medium ms-1">*</span></ps-label>
                            <ps-input ref="group_lang_key" type="text" v-model:value="form.group_lang_key" :placeholder="$t(coreField.placeholder)"
                                @keyup="coreField.mandatory==1?validateEmptyInput( 'group_lang_key', form.group_lang_key ):''" @blur="coreField.mandatory==1?validateEmptyInput('group_lang_key', form.group_lang_key ):''" />
                            <ps-label-caption v-if="coreField.mandatory==1" textColor="text-red-500 " class="mt-2 block">{{ errors.group_lang_key }}</ps-label-caption>
                        </div>

                        <div class="mb-6" v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'ordering' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                            <ps-label class="text-base mb-2">{{ $t(coreField.label_name) }} <span v-if="coreField.mandatory==1" class="text-red-800 font-medium ms-1">*</span></ps-label>
                            <ps-input ref="ordering" type="text" v-model:value="form.ordering" :placeholder="$t(coreField.placeholder)"
                                @keyup="coreField.mandatory==1?validateEmptyInput( 'ordering', form.ordering ):''" @blur="coreField.mandatory==1?validateEmptyInput('ordering', form.ordering ):''" />
                            <ps-label-caption v-if="coreField.mandatory==1" textColor="text-red-500 " class="mt-2 block">{{ errors.ordering }}</ps-label-caption>
                        </div>
                        <!-- <div class="px-4 py-2">
                            <ps-label class="text-base mb-1" >Language Key</ps-label>
                            <ps-input type="text" ref="input_lang" v-model:value="form.group_lang_key" placeholder="Language Key"
                                />
                            <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ errors.group_lang_key }}</ps-label-caption>
                        </div> -->
                        <div class="mb-6">
                            <ps-label class="text-base mb-2">Status</ps-label>
                            <ps-checkbox-value v-model:value="form.is_show_on_menu" class="font-normal" title="Publish" />
                        </div>
                        <div class="mb-6">
                            <ps-label class="text-base mb-2">{{$t('hide_menu_group_name')}}</ps-label>
                            <ps-checkbox-value v-model:value="form.is_invisible_group_name" class="font-normal" :title="$t('hide')" />
                        </div>
                        <div class="flex flex-row px-4 py-3 justify-end mb-2.5 mt-4">
                            <ps-button @click="handleCancel()" textSize="text-base" type="reset" class="me-4"
                                    colors="text-primary-500" focus="" hover="">{{ $t('core__be_btn_cancel') }}</ps-button>
                            <ps-button class="transition-all duration-300 min-w-3xs" padding="px-5 py-2" rounded="rounded" hover="" focus="" >
                                <ps-loading v-if="loading" theme="border-2 border-t-2 border-text-8 border-t-primary-500"  loadingSize="h-5 w-5" />
                                <ps-icon v-if="success" name="check" w="20" h="20" class="me-1.5 transition-all duration-300" />
                                <ps-label v-if="success" class="transition-all duration-300" textColor="text-white dark:text-secondaryDark-black">{{ $t('core__be_btn_saved') }}</ps-label>
                                <ps-label v-if="!loading && !success" textColor="text-white dark:text-secondaryDark-black" > {{$t('core__be_btn_save')}} </ps-label>
                            </ps-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
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
import PsLabelCaption from "@/Components/Core/Label/PsLabelCaption.vue";
import { trans } from 'laravel-vue-i18n';

export default defineComponent({
    name: "Create",
    components: {
        Head,
        PsInput,
        PsLabel,
        PsButton,
        PsTextarea,
        PsLabelHeader4,
        PsIcon,
        PsLoading,
        PsTextButton,
        Link,
        PsBreadcrumb2,
        PsCheckboxValue,
        PsLabelCaption
    },
    layout: PsLayout,
    props: ['errors','coreFieldFilterSettings'],
    setup(props) {
        // Client Side Validation
        const { isEmpty, minLength} = useValidators();
        const loading = ref(false);
        const success = ref(false);
        const input_icon = ref(false);
        const input_lang = ref(false);
        const input_name = ref(false);
        let form = useForm(
                {
                    group_name: "",
                    group_lang_key: "",
                    // group_icon:"",
                    is_show_on_menu: 1,
                    // ordering: '',
                    is_invisible_group_name: 0,
                }
            )

        const validateGroupNameInput = (fieldName, fieldValue) => {
            props.errors[fieldName] = !fieldValue ? isEmpty(fieldName, fieldValue) : minLength(fieldName, fieldValue, 3);
        }
        const validateEmptyInput = (fieldName, fieldValue, errorMessage = "") => {
            props.errors[fieldName] = !fieldValue ? isEmpty(fieldName, fieldValue, errorMessage) : "";
            // if(fieldName == 'title'){
            //     title.value.isError = (props.errors.title == '') ? false: true;
            // }
        };
        // const validateEmptyInput = (fieldName, fieldValue, errorMessage = '') => {
        //     props.errors[fieldName] = !fieldValue ? isEmpty(fieldName, fieldValue, errorMessage) : '';
        //     if(props.errors[fieldName] == ''){
        //         if(fieldName == 'group_name'){
        //             input_name.value.isError = false;
        //         }
        //         if(fieldName == 'group_icon'){
        //             input_icon.value.isError = false;
        //         }
        //         if(fieldName == 'group_lang_key'){
        //             input_lang.value.isError = false;
        //         }
        //     }else{
        //         if(fieldName == 'group_name'){
        //             input_name.value.isError = true;
        //         }
        //         if(fieldName == 'group_icon'){
        //             input_icon.value.isError = true;
        //         }
        //         if(fieldName == 'group_lang_key'){
        //             input_lang.value.isError = true;
        //         }
        //     }
        // }
        function handleSubmit() {
            this.$inertia.post(route('menu_group.store'), form, {
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
                    // if(props.errors.group_name != undefined && props.errors.group_name != ''){
                    //     input_name.value.isError = true;
                    // }
                    // if(props.errors.group_icon != undefined && props.errors.group_icon != ''){
                    //     input_icon.value.isError = true;
                    // }
                    // if(props.errors.group_lang_key != undefined && props.errors.group_lang_key != ''){
                    //     input_lang.value.isError = true;
                    // }
                    loading.value = false;
                },
            })
        }
        return {
            validateGroupNameInput,
            validateEmptyInput,
            route,
            form,
            handleSubmit,
            loading,
            success,
            input_name,
            input_icon,
            input_lang
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
                    label: trans('menu_group_module'),
                    url: route('menu_group.index'),
                },
                {
                    label: trans('add_menu_group'),
                    color: "text-primary-500"
                }
            ]

        }
    },
    methods: {
        handleCancel() {
            this.$inertia.get(route('menu_group.index'));
        },
    },
})
</script>
