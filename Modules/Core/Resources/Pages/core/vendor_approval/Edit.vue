<template>
    <Head :title="$t('pending_vendor_module')" />
    <ps-layout>
        <!-- breadcrumb start -->
        <ps-breadcrumb-2 :items="breadcrumb" class="mb-5 sm:mb-6 lg:mb-8" />

        <!-- vendor information -->
        <div class="">
            <div class="bg-primary-50 px-4 py-2 rounded-t dark:bg-primary-900">
                <ps-label-header-6>{{ $t('core_be__approval_vendor_details') }}</ps-label-header-6>
            </div>
            <div class="grid grid-cols-2 gap-10 p-4">
                <div class="">
                    <!-- vendor name -->
                    <div class="mt-4">
                        <ps-label class="text-base font-medium mb-2">{{ $t('core__be_vendor_user_name') }}</ps-label>
                        <ps-input  :disabled="true" type="text" ref="symbol" v-model:value="vendor.owner.name" />
                    </div>

                    <!-- vendor email -->
                    <div class="mt-4">
                        <ps-label class="text-base font-medium mb-2">{{ $t('core__be_vendor_user_email') }}</ps-label>
                        <ps-input  :disabled="true" type="email" ref="symbol" v-model:value="vendor.email" :placeholder="$t('')" />
                    </div>

                    <!-- vendor name -->
                    <div class="mt-4">
                        <ps-label class="text-base font-medium mb-2">{{ $t('core__be_vendor_name') }}</ps-label>
                        <ps-input  :disabled="true" type="text" ref="symbol" v-model:value="vendor.name" />
                    </div>

                    <!-- business document -->
                    <div class="mt-4">
                        <ps-label class="text-base font-medium mb-2">{{ $t('core__be_business_document') }}</ps-label>
                        <ps-input-with-right-icon rounded="rounded" :disabled="true" v-model:value="application.document"  :placeholder="$t('')" >
                            <template #icon >
                                <a :href="route('reject_vendor.download_document', application.vendor_id)" class="font-medium transition duration-150 ease-in-out py-2 px-4 text-dark dark:text-white text-sm cursor-pointer opacity-100">
                                    <ps-icon name="import"/>
                                </a>
                            </template>
                        </ps-input-with-right-icon>
                        <!-- <ps-input  :disabled="true" type="email" ref="symbol" v-model:value="application.document" :placeholder="$t('')" /> -->
                    </div>

                    <!-- store description -->
                    <div class="mt-4">
                        <ps-label class="text-base font-medium mb-2">{{ $t('core__be_cover_letter') }}</ps-label>
                        <ps-textarea :disabled="true" rows="4" v-model:value="application.cover_letter" :placeholder="$t('')" />
                    </div>

                    <ps-button @click="back()" class="float-end mt-4">{{ $t('core_be__back') }}</ps-button>
                </div>
            </div>
        </div>

    </ps-layout>
</template>

<script>
import { ref, defineComponent, watch } from "vue";
import { Head, useForm, router } from "@inertiajs/vue3";
import { trans } from 'laravel-vue-i18n';
import PsLayout from "@/Components/PsLayout.vue";
import PsBreadcrumb2 from "@/Components/Core/Breadcrumbs/PsBreadcrumb2.vue";
import PsLabelHeader6 from "@/Components/Core/Label/PsLabelHeader6.vue";
import PsButton from "@/Components/Core/Buttons/PsButton.vue";
import PsLabel from "@/Components/Core/Label/PsLabel.vue";
import PsInput from "@/Components/Core/Input/PsInput.vue";
import PsTextarea from '@/Components/Core/Textarea/PsTextarea.vue';
import PsInputWithRightIcon from "@/Components/Core/Input/PsInputWithRightIcon.vue";
import PsIcon from "@/Components/Core/Icons/PsIcon.vue";

    export default defineComponent({
        name: "Edit",
        components: {
            Head,
            PsBreadcrumb2,
            PsLabelHeader6,
            PsButton,
            PsLabel,
            PsInput,
            PsTextarea,
            PsInputWithRightIcon,
            PsIcon
        },
        layout: PsLayout,
        props: {
            vendor : Object,
            application : Object,
        },
        setup(props){
            let form = useForm({
                id: props.vendor.id,
                name: props.vendor.name,
                status: props.vendor.status,
            });

            function back() {
                router.get(route('pending_vendor.index'));
            }

            return {
                form,
                back,
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
                        label: trans('pending_vendor_module'),
                        url: route('pending_vendor.index')
                    },
                    {
                        label: trans('core_be__vendor_details'),
                        color: "text-primary-500"
                    }
                ]
            },
        },
    })
</script>
