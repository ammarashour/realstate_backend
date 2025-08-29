<template>
    <Head title="Item Customization Detail" />
    <ps-layout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex justify-between mb-6">
                            <h4>Customzation Detail For <ps-label class="font-semibold text-indigo-600">{{
                                    custom_field_header.name
                            }}</ps-label></h4>
                            <Link :href="route('shop.customizationDetail.create', custom_field_header.id)" type="button"
                                data-mdb-ripple="true" data-mdb-ripple-color="light"
                                class="inline-block px-6 py-3 bg-indigo-500 text-white font-medium text-xs leading-tight uppercase rounded shadow-md hover:bg-blue-700 hover:shadow-lg focus:bg-blue-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-blue-800 active:shadow-lg transition duration-150 ease-in-out">
                            Create Attribute</Link>
                        </div>
                        <hr>
                        <div v-if="status"
                            class="mt-2 bg-green-100 rounded-lg py-3 px-6 mb-3 text-base text-green-700 inline-flex items-center w-full"
                            role="alert">
                            <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check-circle"
                                class="w-4 h-4 me-2 fill-current" role="img" xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 512 512">
                                <path fill="currentColor"
                                    d="M504 256c0 136.967-111.033 248-248 248S8 392.967 8 256 119.033 8 256 8s248 111.033 248 248zM227.314 387.314l184-184c6.248-6.248 6.248-16.379 0-22.627l-22.627-22.627c-6.248-6.249-16.379-6.249-22.628 0L216 308.118l-70.059-70.059c-6.248-6.248-16.379-6.248-22.628 0l-22.627 22.627c-6.248 6.248-6.248 16.379 0 22.627l104 104c6.249 6.249 16.379 6.249 22.628.001z">
                                </path>
                            </svg>
                            {{ status }}
                        </div>
                        <div class="flex">
                            <div class="overflow-x-auto sm:-mx-6 lg:-mx-8 flex-1">
                                <div class="py-2 inline-block w-full sm:px-6 lg:px-8">
                                    <div class="overflow-hidden">
                                        <table class="w-full">
                                            <thead class="bg-white border-b">
                                                <tr>
                                                    <th scope="col"
                                                        class="text-sm font-medium text-gray-900 px-5 py-4 text-left">
                                                        #
                                                    </th>
                                                    <th scope="col"
                                                        class="text-sm font-medium text-gray-900 px-5 py-4 text-left">
                                                        Customize Detail
                                                    </th>
                                                    <th scope="col"
                                                        class="text-sm font-medium text-gray-900 px-5 py-4 text-left">
                                                        Control
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="custom_field_detail in custom_field_details"
                                                    :key="custom_field_detail.id">
                                                    <td
                                                        class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                                        {{ custom_field_detail.id }}
                                                    </td>
                                                    <td
                                                        class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                                        {{ custom_field_detail.attribute }}
                                                    </td>
                                                    <td
                                                        class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                                        <ps-button
                                                            class="rounded text-white uppercase px-2 py-2 bg-red-600 hover:bg-red-500"
                                                            @click="handleDelete(custom_field_detail.id)">
                                                            Delete
                                                        </ps-button>
                                                        <Link
                                                            :href="route('shop.customizationDetail.edit', [custom_field_header.id, custom_field_detail.id])"
                                                            type="button"
                                                            class="hover:bg-indigo-500 text-white hover:text-gray-400 py-2 px-4 border uppercase rounded bg-indigo-500">
                                                            Edit
                                                        </Link>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
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
import { defineComponent } from 'vue'
import PsLayout from "@/Components/PsLayout.vue";
import { Head, Link } from '@inertiajs/vue3';
import moment from 'moment';
import PsInput from "@/Components/Core/Input/PsInput.vue";
import PsLabel from "@/Components/Core/Label/PsLabel.vue";
import PsButton from "@/Components/Core/Buttons/PsButton.vue";
import PsTextarea from '@/Components/Core/Textarea/PsTextarea.vue';
import PsLabelHeader4 from "@/Components/Core/Label/PsLabelHeader4.vue";
import { trans } from 'laravel-vue-i18n';

export default defineComponent({
    name: "CustomizationDetailIndex",
    components: {
        Head,
        Link,
        PsInput,
        PsLabel,
        PsButton,
        PsTextarea,
        PsLabelHeader4
    },
    layout: PsLayout,
    props: ['posts', 'custom_field_header', 'custom_field_details', 'status'],
    data() {
        return {
            moment: moment
        }
    },
    methods: {
        handleDelete(id) {
            this.$inertia.delete(route('shop.customizationDetail.destroy', [this.custom_field_header, id]))
        }
    },
})
</script>
