<template>

    <Head :title="$t('thumbnail_generator_module')" />
    <ps-layout>
        <!-- breadcrumb start -->
        <ps-breadcrumb-2 :items="breadcrumb" class="mb-5 sm:mb-6 lg:mb-8" />
        <!-- breadcrumb end -->

        <ps-card class="w-full h-auto">
            <div class="rounded-xl">
                <!-- card header start -->
                <div class="bg-primary-50 dark:bg-primary-900 py-2.5 ps-4">
                    <ps-label-header-6 textColor="text-secondary-800 dark:text-secondary-100">{{$t('thumbnail_generator_module')}}</ps-label-header-6>
                </div>
                <!-- card header end -->

                <!-- card body start -->
                <div class="px-4 pt-6 dark:bg-backgroundDark">
                    <form @submit.prevent="handleThumbnail()">
                        <div class="grid gap-6">
                            <div>
                                <ps-label>It will generate the thumbnails images for each item. Please take note that this process will take some time based on
                                your data.</ps-label>
                            </div>
                            <div>
                                <ps-button>Thumbnail(1x, 2x, 3x) Generate</ps-button>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- card body end -->
            </div>
        </ps-card>

    </ps-layout>
</template>

<script>
import { defineComponent } from 'vue'
import PsLayout from "@/Components/PsLayout.vue";
import PsBreadcrumb2 from "@/Components/Core/Breadcrumbs/PsBreadcrumb2.vue";
import { Head, useForm } from "@inertiajs/vue3";
import PsInput from "@/Components/Core/Input/PsInput.vue";
import PsLabel from "@/Components/Core/Label/PsLabel.vue";
import PsButton from "@/Components/Core/Buttons/PsButton.vue";
import PsTextarea from '@/Components/Core/Textarea/PsTextarea.vue';
import PsLabelHeader4 from "@/Components/Core/Label/PsLabelHeader4.vue";
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
        PsBreadcrumb2
    },
    layout: PsLayout,
    props: ['errors', 'status'],
    computed: {
        breadcrumb() {
            return [
                {
                    label: trans('core__be_dashboard_label'),
                    url: route('admin.index')
                },
                {
                    label: trans('thumbnail_generator_module'),
                    color: "text-primary-500"
                }
            ]

        }
    },
    methods: {
        handleThumbnail() {
            this.$inertia.put(route('thumbnail_generator.update'));
        },
    },
})
</script>
