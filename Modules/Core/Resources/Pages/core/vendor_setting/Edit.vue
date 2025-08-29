<template>
    <Head :title="$t('vendor_setting_module')" />
    <ps-layout>
        <!-- breadcrumb start -->
        <ps-breadcrumb-2 :items="breadcrumb" class="mb-5 sm:mb-6 lg:mb-8" />
        <!-- breadcrumb end -->
        <ps-card class="w-full h-auto">
            <div class="bg-background dark:bg-secondaryDark-black rounded-lg ">
                <!-- card header start -->
                <div class="bg-primary-50 dark:bg-primary-900">
                    <div class="bg-primary-50 flex items-center dark:bg-primary-900 py-2.5 ps-4 rounded-t-lg">
                        <ps-label-header-6 textColor="text-secondary-800 dark:text-secondary-100"> {{ $t(title) }} </ps-label-header-6>
                        <ps-tooltip tooltiptext="absolute z-50 bottom-full ms-0.5">
                            <template #content>
                                <div class="h-8 flex items-center">
                                    <ps-icon name="info" w="20" h="20" class="ms-2.5 transition-all duration-300 text-primary-500" />
                                </div>
                            </template>
                            <template #tooltips>
                                For more details: <a target="_blank" :href="docu" class="underline">Refer to this doc</a>
                            </template>
                        </ps-tooltip>
                    </div>
                </div>
                <!-- card header end -->

                <form @submit.prevent="handleSubmit(this.backend_setting.id)">
                    <div class="grid grid-cols-1 md:grid-cols-2  gap-2 mt-4">
                        <div>
                            <div  v-if="title == settingColumn[0].label">
                                
                                <div class="px-4 py-0 ">

                                    <ps-accordion class="w-130 md:items-right">
                                        
                                        <template #title>
                                            <ps-label-header-5>{{ $t('core__be_vendor_feature_on_off') }}</ps-label-header-5>      
                                        </template>
                                        <template #description>
                                            
                                            
                                            <ps-label class="text-base">{{ $t('core__be_vendor_feature_on_off_desc2') }}</ps-label>
                                            <br>
                                            
                                
                                            <div class="px-4 pt-3" >
                                                <div class="flex items-center">
                                                    <ps-checkbox-value @change="handleVendorSettingCheckbox()" v-model:value="form.vendor_setting" class="font-normal" :title="$t('core__be_is_vendor_feature_on')" />
                                                    <ps-tooltip tooltiptext="absolute z-50 bottom-full mb-2 -ms-2.5">
                                                        <template #content>
                                                            <ps-icon name="info" w="20" h="20" class="-ms-1 transition-all duration-300 text-primary-500"/>
                                                        </template>
                                                        <template #tooltips>
                                                            For more details: <a target="_blank" href="https://doc.clickup.com/24312566/p/h/q5yqp-175222/be600340120429c" class="underline">Refer to this doc</a>
                                                        </template>
                                                    </ps-tooltip>
                                                </div>
                                            </div>
                                            <div class="px-4 pb-3" >
                                                <ps-label class="ms-2 text-xs" textColor="text-secondary-400">{{ $t('core__be_vendor_feature_on_off_desc') }}</ps-label>
                                            </div>
                                
                                            <div class="px-4 pt-3" >
                                                    <div class="flex items-center">
                                                        <ps-checkbox-value :disabled="!form.vendor_setting" v-model:value="form.vendor_checkout_setting" class="font-normal" :title="$t('core__be_is_vendor_checkout_setting_on')" />
                                                        <ps-tooltip tooltiptext="absolute z-50 bottom-full mb-2 -ms-2.5">
                                                            <template #content>
                                                                <ps-icon name="info" w="20" h="20" class="-ms-1 transition-all duration-300 text-primary-500"/>
                                                            </template>
                                                            <template #tooltips>
                                                                For more details: <a target="_blank" href="https://doc.clickup.com/24312566/p/h/q5yqp-175222/be600340120429c" class="underline">Refer to this doc</a>
                                                            </template>
                                                        </ps-tooltip>
                                                    </div>
                                            </div>
                                            <div class="px-4 pb-3" >
                                                <ps-label class="ms-2 text-xs" textColor="text-secondary-400">{{ $t('core__be_vendor_checkout_setting_on_off_desc') }}</ps-label>
                                            </div>
                                
                                            
                                            
                                
                                        </template>
                                
                                        
                                
                                    </ps-accordion> <!-- vendor_onoff_configuration_1 -->
                                
                                </div>

                            </div>
                            <!-- Start Fe Lang Refresh -->
                            <div v-if="title == settingColumn[1].label">
                                <div class="px-4 py-0 ">

                                    <ps-accordion class="w-130 md:items-right">
                                        
                                        <template #title>
                                            <ps-label-header-5>{{ $t('core__be_language_refresh_title') }}</ps-label-header-5>      
                                        </template>
                                        <template #description>
                                            
                                            
                                            <ps-label class="text-base">{{ $t('core__be_language_refresh_desc') }}</ps-label>
                                            <br>
                                            
                                
                                            <ps-card class="w-full h-auto">
                                                <div class="rounded-xl">
                                                    <!-- card body start -->
                                
                                                    <div class="mt-6">
                                
                                                        <div class="">
                                                            <div class="border border-1 rounded p-4">
                                                                <div class="h-auto">
                                                                    <div>
                                                                        <ps-label class="dark:text-white text-secondary-800 font-normal">{{$t('core__be_language_refresh_all_lang')}}</ps-label>
                                                                    </div>
                                
                                                                    <div class="flex flex-row justify-between items-center mt-6">
                                                                        <ps-label class="dark:text-white text-secondary-800 font-normal">
                                                                            {{ $t('core__be_all') }}
                                                                        </ps-label>
                                                                        <ps-button type="button" @click="handleVendorLangRefresh('')" rounded="rounded"
                                                                            class="flex flex-wrap items-center">
                                                                            <ps-icon name="refresh" class="me-2 font-semibold" />
                                                                            <ps-label
                                                                                textColor="text-white dark:text-secondary-800">{{
                                                                                    $t('core__be_lang_refresh') }}</ps-label>
                                                                        </ps-button>
                                                                    </div>
                                                                    <hr class="mt-6" />

                                                                    <div>
                                                                        <ps-label
                                                                            class="dark:text-white text-secondary-800 font-normal mt-5">{{
                                                                                $t('core__be_language_refresh_spec_lang') }}
                                                                        </ps-label>
                                                                    </div>

                                                                    <div v-for="language in languages" :key="language.id">
                                                                        <div class="flex flex-row justify-between items-center mt-6">
                                                                            <ps-label class="dark:text-white text-secondary-800 font-normal">
                                                                                {{ language.name }}
                                                                            </ps-label>
                                                                            <ps-button type="button" @click="handleVendorLangRefresh(language.id)" rounded="rounded"
                                                                                class="flex flex-wrap items-center">
                                                                                <ps-icon name="refresh" class="me-2 font-semibold" />
                                                                                <ps-label
                                                                                    textColor="text-white dark:text-secondary-800">{{
                                                                                        $t('core__be_lang_refresh') }}</ps-label>
                                                                            </ps-button>
                                                                        </div>
                                                                    </div>
                                
                                                                    <!-- <div class="flex flex-row justify-start mt-6">
                                                                        <ps-button type="button" @click="handleVendorLangRefresh()" rounded="rounded" class="flex flex-wrap items-center">
                                                                            <ps-icon name="refresh" class="me-2 font-semibold" />
                                                                            <ps-label textColor="text-white dark:text-secondary-800">{{ $t('core__be_lang_refresh') }}</ps-label>
                                                                        </ps-button>
                                                                    </div> -->
                                                                </div>
                                                            </div>
                                                        </div>
                                
                                                    </div>
                                                    <!-- card body end -->
                                                </div>
                                            </ps-card>
                                
                                            
                                            
                                
                                        </template>
                                
                                        
                                
                                    </ps-accordion> <!-- vendor_lang_configuration_1 -->
                                
                                </div>
                            </div>
                            <!-- End Fe Lang Refresh -->

                             <!-- Start vendor subscription -->
                             <div v-if="title == settingColumn[2].label">
                                <div class="px-4 py-0 ">

                                    <ps-accordion class="w-130 md:items-right">
                                        
                                        <template #title>
                                            <ps-label-header-5>{{ $t('core__be_subscriptions_setting') }}</ps-label-header-5>      
                                        </template>
                                        <template #description>
                                            
                                            
                                            <ps-label class="text-base">{{ $t('core__be_subscriptions_setting_desc') }}</ps-label>
                                            <br>
                                            
                                
                                            <div class="px-4 py-3">
                                                    <div class="flex items-center">
                                                        <ps-label class="text-base">{{ $t("core__be_subscription_setting") }}
                                                        </ps-label>
                                                    </div>
                                
                                                    <ps-dropdown align="left" class='lg:mt-2 mt-1  w-full'>
                                                        <template #select>
                                                                <ps-dropdown-select
                                                                    :placeholder="$t('core__be_select_subscription_plan')"
                                                                        :selectedValue="(form.vendor_subscription == '') ? '' : ref_subscriptions.subscription_plans.filter(plan => plan.id == form.vendor_subscription)[0].value " />
                                                        </template>
                                                        <template #list>
                                                            <div class="rounded-md shadow-xs ">
                                                                <div class="pt-2 z-30 ">
                                                                    <div v-for="(subscription,index) in ref_subscriptions.subscription_plans"
                                                                        :key="index"
                                                                        class="flex py-4 px-2 hover:bg-primary-000 dark:hover:bg-primary-900 cursor-pointer items-center"
                                                                        @click="[form.vendor_subscription = subscription.id]">
                                                                        <ps-label class="ms-2"
                                                                            :class="subscription.id == form.vendor_subscription ? ' font-bold' : ''">
                                                                            {{ subscription.value }} </ps-label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </template>
                                                    </ps-dropdown>
                                                </div>
                                
                                            <div class="px-4 py-3">
                                                <ps-label class="text-base mb-1">{{ $t('core_be__notic_days') }}</ps-label>
                                                <ps-input type="text" ref="notic_days" v-model:value="form.notic_days" placeholder="core_be__notic_days"
                                                    @keyup="validateEmptyInput('notic_days', form.notic_days)"
                                                    @blur="validateEmptyInput('notic_days', form.notic_days)"/>
                                                <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ errors.notic_days }}</ps-label-caption>
                                                <ps-label-caption textColor="text-secondary-400 mt-1">{{ $t('core_be__notic_days_note') }}</ps-label-caption>
                                            </div>
                                
                                            
                                            
                                
                                        </template>
                                
                                        
                                
                                    </ps-accordion> <!-- vendor_subscriptions_configuration_1 -->
                                
                                </div>
                            </div>
                            <!-- End vendor subscription -->

                            <div class="flex flex-row justify-end mt-6 mb-2.5">
                                <ps-button @click="handleCancel()" textSize="text-base" type="reset" class="me-4" colors="text-primary-500" focus="" hover="">{{ $t('core__be_btn_cancel') }}</ps-button>
                                <ps-button class="transition-all duration-300 min-w-3xs" padding="px-7 py-2" rounded="rounded" hover="" focus="" >
                                    <ps-loading v-if="loading" theme="border-2 border-t-2 border-text-8 border-t-primary-500"  loadingSize="h-5 w-5" />
                                    <ps-icon v-if="success" name="check" w="20" h="20" class="me-1.5 transition-all duration-300" />
                                    <ps-label v-if="success" class="transition-all duration-300" textColor="text-white dark:text-secondaryDark-black">{{$t('core__be_btn_saved')}}</ps-label>
                                <ps-label v-if="!loading && !success" textColor="text-white dark:text-secondaryDark-black" > {{$t('core__be_btn_save')}} </ps-label>
                                </ps-button>
                            </div>
                        </div>
                        <!-- column 1 end -->

                        <div class="px-4">

                            <div v-for="column in settingColumn.slice().sort((a, b) => $t(a.label).localeCompare($t(b.label)))" :key="column.id">
                                <div @click="changeSection(column)"
                                    :class="title == column.label ? 'border-l border-s-primary-500' : 'border-l border-s-secondary-300'"
                                    class="px-2 py-3 cursor-pointer">
                                    <ps-label :textColor="title == column.label ? 'text-primary-500 dark:text-primary-500' : 'text-secondary-800 dark:text-white'" >
                                        {{ $t(column.label) }}
                                    </ps-label>
                                </div>
                            </div>

                        </div>

                    </div>
                </form>

            </div>
        </ps-card>
        <ps-loading-circle-dialog ref="ps_loading_circle_dialog" />
        <ps-success-dialog ref="ps_success_dialog" />

    </ps-layout>
</template>

<script>
    import { defineComponent, ref, reactive, defineAsyncComponent } from 'vue'
    import PsLayout from "@/Components/PsLayout.vue";
    import { Head, useForm } from "@inertiajs/vue3";
    import useValidators from '@/Validation/Validators'
    import PsInput from "@/Components/Core/Input/PsInput.vue";
    import PsLabel from "@/Components/Core/Label/PsLabel.vue";
    import PsButton from "@/Components/Core/Buttons/PsButton.vue";
    import PsTextarea from '@/Components/Core/Textarea/PsTextarea.vue';
    import PsLabelHeader4 from "@/Components/Core/Label/PsLabelHeader4.vue";
    import PsLabelCaption from "@/Components/Core/Label/PsLabelCaption.vue";
    import PsCheckbox from '@/Components/Core/Checkbox/PsCheckbox.vue';
    import PsCheckboxValue from '@/Components/Core/Checkbox/PsCheckboxValue.vue';
    import PsBreadcrumb2 from "@/Components/Core/Breadcrumbs/PsBreadcrumb2.vue";
    import PsDropdown from "@/Components/Core/Dropdown/PsDropdown.vue";
    import PsDropdownSelect from "@/Components/Core/Dropdown/PsDropdownSelect.vue";

    import PsIcon from "@/Components/Core/Icons/PsIcon.vue";
    import PsLoading from "@/Components/Core/Loading/PsLoading.vue";
    import PsTooltip from '@/Components/Core/Tooltips/PsTooltip.vue';
    import PsTooltips from "@/Components/Core/Tooltips/PsTooltips.vue";
    import PsLoadingCircleDialog from '@/Components/Core/Dialog/PsLoadingCircleDialog.vue';
    import PsSuccessDialog from '@/Components/Core/Dialog/PsSuccessDialog.vue';
    import PsUtils from '@templateCore/utils/PsUtils';
    import PsAccordion from "@/Components/Core/Accordion/PsAccordion.vue";

    import { trans } from 'laravel-vue-i18n';

    export default defineComponent({
        name: "Edit",
        components: {
            PsIcon,
            PsLoading,
            Head,
            PsInput,
            PsLabel,
            PsButton,
            PsTextarea,
            PsLabelHeader4,
            PsLabelCaption,
            PsCheckbox,
            PsCheckboxValue,
            PsBreadcrumb2,
            PsDropdown,
            PsDropdownSelect,
            PsTooltips,
            PsTooltip,
            PsLoadingCircleDialog,
            PsSuccessDialog,
            PsAccordion,
        },
        layout: PsLayout,
        props: ['errors',
                'status',
                'backend_setting',
                'vendor_subscription',
                'languages',
                'SettingPage'],
        setup(props) {
            const loading = ref(false);
            const success = ref(false);

            const ps_loading_circle_dialog = ref();
            const ps_success_dialog = ref();

            const frontendColors = ref(props.frontendColors);
            const notic_days = ref();
            const vendor_checkout_setting = ref();


            // Client Side Validation
            const { isEmpty, isNum } = useValidators();

            const validateEmptyInput = (fieldName, fieldValue) => {
                props.errors[fieldName] = !fieldValue ? isEmpty(fieldName, fieldValue) : isNum(fieldName, fieldValue);
                if (fieldName == 'notic_days') {
                    notic_days.value.isError = (props.errors.notic_days == '') ? false : true;
                }
                if (fieldName == 'vendor_checkout_setting') {
                    vendor_checkout_setting.value.isError = (props.errors.vendor_checkout_setting == '') ? false : true;
                }
            }

            const settingColumn = [
                {
                    index: 0,
                    label: "core__be_vendor_feature_on_off",
                    docu: 'https://doc.clickup.com/24312566/p/h/q5yqp-175202/4fc3a8c00ffc0ba'
                },
                {
                    index: 1,
                    label: "core__lang_refresh_setting",
                    docu: 'https://doc.clickup.com/24312566/p/h/q5yqp-175242/5c613e184364a09'
                },
                {
                    index: 2,
                    label: "core__be_subscriptions_setting",
                    docu: 'https://doc.clickup.com/24312566/p/h/q5yqp-179742/8be0d3082e8aac6'
                }
            ]

            var pageIndex = ( props.SettingPage ?? 0) - 1 ;
            if(pageIndex < 0 || pageIndex >= settingColumn.length){
                pageIndex = 1;
            }
            const title = ref(settingColumn[pageIndex].label);
            const docu = ref(settingColumn[pageIndex].docu);

            function changeSection(v){
                title.value = v.label;
                docu.value = v.docu;

                // const newUrl = `/admin/vendor_setting?page=${v.index+1}`;
                // window.history.replaceState({}, '', newUrl)
                PsUtils.updateBrowserUrl('vendor_setting', `page=${v.index + 1}`);
            }

            let ref_subscriptions = JSON.parse(props.vendor_subscription.ref_selection);
            let selected_data =JSON.parse(props.vendor_subscription.setting);

            // console.log(ref_subscriptions.subscription_plans[0].value);
            // console.log(selected_data.subscription_plan[0].value);

            const form = reactive(useForm(
                {
                    vendor_setting: props.backend_setting.vendor_setting == 1 ? true : false,
                    vendor_subscription : selected_data.subscription_plan[0].id,
                    notic_days : selected_data.notic_days,
                    vendor_checkout_setting: selected_data.vendor_checkout_setting == 1 ? true : false,
                    "_method": "put"
                }
            ))

            console.log(ref_subscriptions);
            console.log(selected_data);


            function handleSubmit(id) {

                // console.log('here');
                this.$inertia.post(route('vendor_setting.update', id), form, {
                    forceFormData: true,
                onBefore: () => {loading.value = true},
                onSuccess: () => {
                    loading.value = false;
                    success.value = true;
                    setTimeout(()=>{
                        success.value = false;
                    },1000)


                },
                onError: () => {
                    loading.value = false;;
                },
                });
            }


            function handleVendorLangRefresh (id)
            {

                this.$inertia.get(route('vendor_setting.languageRefresh'), {key: "refresh", languageId: id}, {
                    onBefore: () => {
                        ps_loading_circle_dialog.value.openModal(trans('core__be_updating_title'),trans('core__be_lang_refreshing_desc'));
                    },
                    onSuccess: () => {
                        window.location.reload();
                        ps_loading_circle_dialog.value.closeModal();
                    },
                    onError: () => {
                        ps_loading_circle_dialog.value.closeModal();
                    }
                });
            }

            function langRefreshSuccessDialog()
            {
                ps_success_dialog.value.openModal(trans('core__be_awesome_title'),trans('core__be_lang_refresh_success_desc'),trans('core__be_btn_back'),
                ()=>{

                },true);
            }

            function handleVendorSettingCheckbox()
            {
                if(!form.vendor_setting){
                    form.vendor_checkout_setting = 0;
                }
            }

            return {

                handleSubmit,
                validateEmptyInput,
                form ,
                title,
                settingColumn,
                changeSection,
                loading,
                success,
                docu,
                handleVendorLangRefresh,
                ps_loading_circle_dialog,
                langRefreshSuccessDialog,
                ps_success_dialog,
                ref_subscriptions,
                notic_days,
                vendor_checkout_setting,
                handleVendorSettingCheckbox
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
                        label: trans('vendor_setting_module'),
                        color: "text-primary-500"
                    }
                ]
            }
        },
        mounted() {
            if(this.status.flag == "langSuccess"){
                this.langRefreshSuccessDialog();
            }
        }
    })
</script>
