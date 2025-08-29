<template>
    <Head :title="$t('vendor_info_module')" />
    <ps-layout>
        <!-- breadcrumb start -->
        <!-- <ps-breadcrumb-2 :items="breadcrumb" class="mb-5 sm:mb-6 lg:mb-8" /> -->
        <!-- breadcrumb end -->
        <div class="m-14">
            This is Vendor Info Page (coming soon ...)
        </div>
        <!-- {{vendor}} -->

    </ps-layout>
</template>

<script>
import { ref, defineComponent,defineAsyncComponent, watch } from "vue";
import PsVendorLayout from "@vendorPanel/vendor/components/layouts/container/PsVendorLayout.vue";
import { Head, usePage } from "@inertiajs/vue3";
// import PsBreadcrumb2 from "@/Components/Core/Breadcrumbs/PsBreadcrumb2.vue";

export default defineComponent({
    name: "Index",
    components: {
        // PsBreadcrumb2,

    },
    layout: PsVendorLayout,
    props: {
        vendor: Object,
    },
    setup(props) {

        return {
            
        };
    }
});
</script>