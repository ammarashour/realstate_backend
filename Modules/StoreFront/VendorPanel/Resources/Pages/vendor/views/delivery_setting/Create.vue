<template>
    <Head :title="$t('core__vendor_delivery_setting_module')" />
    <ps-layout>
        <div>
            <!-- breadcrumb start -->
            <ps-breadcrumb-2 :items="breadcrumb" class="mb-5 sm:mb-6 lg:mb-8" />
            <!-- breadcrumb end -->

            <div class="w-full h-auto">
                <div class="bg-background dark:bg-secondaryDark-black rounded-lg  mb-20 ">
                    <!-- card header start -->
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
                    <!-- card header end -->

                    <div >
                        <div class="grid grid-cols-1 md:grid-cols-2  gap-2 mt-4">
                            <div>
                                <div v-if="title == settingColumn[0].label">
                                    <div class="px-4 py-3">
                                        <ps-checkbox-value v-model:value="form.delivery_setting" class="font-normal" :title="$t('vendor_panel__delivery_setting')" />
                                        <ps-label-caption textColor="text-secondary-400 mt-1">{{ $t('vendor_panel__delivery_setting_note') }}</ps-label-caption>
                                        <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ errors.delivery_setting }}</ps-label-caption>
                                    </div>

                                    <div class="px-4 py-3">
                                        <ps-label class="text-base flex flex-row">{{ $t("vendor_panel__delivery_charges") }}</ps-label>
                                        <ps-input type="text" v-model:value="form.delivery_charges" :placeholder="$t('vendor_panel__delivery_charges')"
                                            @keyup="form.delivery_setting ? validateEmptyInput('delivery_charges', form.delivery_charges) : ''"
                                            @blur="form.delivery_setting ? validateEmptyInput('delivery_charges', form.delivery_charges ) : ''"/>
                                        <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ errors.delivery_charges }}</ps-label-caption>
                                    </div>

                                </div>


                                <div class="flex flex-row  px-4 py-3 justify-end mb-2.5 mt-4">
                                    <ps-button @click="handleCancel()" textSize="text-base" type="reset" class="me-4" colors="text-primary-500" focus="" hover="">{{$t('core__be_btn_cancel')}}</ps-button>
                                    <ps-button @click="handleSubmit()" class="transition-all duration-300 min-w-3xs" padding="px-8 py-0" rounded="rounded" hover="" focus="" >
                                        <ps-loading v-if="loading" theme="border-2 border-t-2 border-text-8 border-t-primary-500"  loadingSize="h-5 w-5" />
                                        <ps-icon v-if="success" name="check" w="20" h="20" class="me-1.5 transition-all duration-300" />
                                        <ps-label v-if="success" class="transition-all duration-300" textColor="text-white dark:text-secondaryDark-black">{{$t('core__be_btn_saved')}}</ps-label>
                                        <ps-label v-if="!loading && !success" textColor="text-white dark:text-secondaryDark-black" > {{$t('core__be_btn_save')}} </ps-label>
                                    </ps-button>
                                </div>

                            </div>
                            <div class="px-4">

                                <div v-for="column in settingColumn" :key="column.id">
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
                    </div>
                </div>
            </div>
        </div>
    </ps-layout>
</template>

<script>
import { defineComponent, ref, defineAsyncComponent, reactive, onMounted, onUnmounted } from 'vue';
import PsVendorLayout from "@vendorPanel/vendor/components/layouts/container/PsVendorLayout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import { trans } from 'laravel-vue-i18n';
import useValidators from '@/Validation/Validators';
import PsBreadcrumb2 from "@/Components/Core/Breadcrumbs/PsBreadcrumb2.vue";
import PsLabelHeader6 from "@/Components/Core/Label/PsLabelHeader4.vue";
import PsTooltip from '@/Components/Core/Tooltips/PsTooltip.vue';
import PsIcon from "@/Components/Core/Icons/PsIcon.vue";
import PsInput from "@/Components/Core/Input/PsInput.vue";
import PsInputWithRightIcon from "@/Components/Core/Input/PsInputWithRightIcon.vue";
import PsLabel from "@/Components/Core/Label/PsLabel.vue";
import PsLabelCaption from "@/Components/Core/Label/PsLabelCaption.vue";
import PsButton from "@/Components/Core/Buttons/PsButton.vue";
import PsLoading from "@/Components/Core/Loading/PsLoading.vue";
import PsCheckboxValue from "@/Components/Core/Checkbox/PsCheckboxValue.vue";

    export default defineComponent({
        name: 'Create',
        components: {
            Head,
            Link,
            PsBreadcrumb2,
            PsLabelHeader6,
            PsTooltip,
            PsIcon,
            PsInput,
            PsInputWithRightIcon,
            PsLabel,
            PsLabelCaption,
            PsButton,
            PsLoading,
            PsCheckboxValue
        },
        layout: PsVendorLayout,
        props: ['vendor_delivery_setting', 'errors'],
        setup(props){
            const loading = ref(false);
            const success = ref(false);
            const reRenderImage = ref(true);
            const copied = ref(true);
            console.log(props.vendor_delivery_setting)

            let form = useForm({
                delivery_setting: 0,
                delivery_charges: '',
            });

            const settingColumn = [
                {
                    label: 'vendor_delivery_setting_module',
                    docu: 'https://doc.clickup.com/24312566/p/h/q5yqp-167764/af19f3c3f0ff989'
                },
            ];

            const title = ref(settingColumn[0].label);
            const docu = ref(settingColumn[0].docu);

            const { isEmpty } = useValidators();

            const validateEmptyInput = (fieldName, fieldValue, errorMessage = '') => {
                props.errors[fieldName] = !fieldValue ? isEmpty(fieldName, fieldValue, errorMessage) : '';
            }

            function changeSection(v){
                title.value = v.label;
                docu.value = v.docu;
            }

            function handleCancel() {
                this.$inertia.get(route('vendor_delivery_setting.index'));
            }

            function handleSubmit(id) {
                this.$inertia.post(route('vendor_delivery_setting.store'), form, {
                    forceFormData: true,
                onBefore: () => {loading.value = true},
                onSuccess: () => {
                    loading.value = false;
                    success.value = true;
                    setTimeout(()=>{
                        success.value = false;
                        reRenderImage.value= false;
                        setTimeout(() => {
                            reRenderImage.value = true;
                        }, 200);

                    },1000)


                },
                onError: () => {
                    loading.value = false;;
                },
                });
            }

            return {
                title,
                docu,
                loading,
                success,
                copied,
                form,
                settingColumn,
                changeSection,
                validateEmptyInput,
                handleCancel,
                handleSubmit,
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
                        label: trans('core__vendor_delivery_setting_module'),
                        color: "text-primary-500"
                    }
                ];
            }
        },
    })
</script>

<style lang="stylus" scoped>

</style>
