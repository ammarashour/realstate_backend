<template>
    <Head :title="$t('vendor_list_module')" />
    <ps-layout>
        <!-- breadcrumb start -->
        <ps-breadcrumb-2 :items="breadcrumb" class="mb-5 sm:mb-6 lg:mb-8" />

        <!-- vendor information -->
        <div class="">
            <div class="bg-primary-50 px-4 py-2 rounded-t dark:bg-primary-900">
                <ps-label-header-6>{{ $t('core_be__vendor_details') }}</ps-label-header-6>
            </div>
            <div class="grid grid-cols-2 gap-10 p-4">
                <div class="">
                    <!-- info -->
                    <vendor-detail-vendor-info
                        :vendor="vendor"
                        :coreFieldFilterSettings="coreFieldFilterSettings"
                        :customizeHeaders="customizeHeaders"
                        :customizeDetails="customizeDetails"/>
                </div>

                <div class="">
                    <!-- store barnches -->
                    <div class="mb-4" v-for="(storeBranch, index) in vendor.vendorBranches" :key="index">
                        <vendor-detail-store-branches
                            :storeBranches="storeBranch"
                            :coreFieldFilterSettings="branchesCoreFieldFilterSettings"/>
                    </div>
                </div>

            </div>
            <div class="p-4">
                <ps-button @click="back()" class="float-end">{{ $t('core_be__back') }}</ps-button>
            </div>
        </div>

    </ps-layout>
</template>

<script>
import { ref, defineComponent, watch } from "vue";
import { Head, useForm, usePage, router } from "@inertiajs/vue3";
import { trans } from 'laravel-vue-i18n';
import PsLayout from "@/Components/PsLayout.vue";
import PsBreadcrumb2 from "@/Components/Core/Breadcrumbs/PsBreadcrumb2.vue";
import PsLabelHeader6 from "@/Components/Core/Label/PsLabelHeader6.vue";
import PsButton from "@/Components/Core/Buttons/PsButton.vue";
import VendorDetailVendorInfo from './components/VendorDetailVendorInfo.vue';
import VendorDetailStoreBranches from './components/VendorDetailStoreBranches.vue';

    export default defineComponent({
        name: "Index",
        components: {
            Head,
            PsBreadcrumb2,
            PsLabelHeader6,
            PsButton,
            VendorDetailVendorInfo,
            VendorDetailStoreBranches
        },
        layout: PsLayout,
        props: {
            vendor : Object,
            coreFieldFilterSettings : Object,
            branchesCoreFieldFilterSettings : Object,
            customizeHeaders : Object,
            customizeDetails: Object,
        },
        setup(props){
            let form = useForm({
                id: props.vendor.id,
                name: props.vendor.name,
                status: props.vendor.status,
            });
            // console.log(props.customizeHeaders);
            function back() {
                router.get(route('vendor.index'));
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
                        label: trans('vendor_list_module'),
                        url: route('vendor.index')
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
