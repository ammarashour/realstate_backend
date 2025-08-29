<template>
    <Head :title="$t('transaction_status_module')" />
    <ps-layout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex justify-between mb-6">
                            <h4>Transaction Status List</h4>
                            <Link
                                :href="route('transaction_status.create')"
                                type="button"
                                data-mdb-ripple="true"
                                data-mdb-ripple-color="light"
                                class="inline-block px-6 py-3 bg-indigo-500 hover:bg-indigo-600 text-white font-medium text-xs leading-tight uppercase rounded shadow-md hover:shadow-lg focus:bg-blue-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-blue-800 active:shadow-lg transition duration-150 ease-in-out"
                            >Create New Transaction Status</Link>
                        </div>
                        <hr>
                        <FlashMessage :status="status" />
                        <div class="flex">
                            <div class="overflow-x-auto sm:-mx-6 lg:-mx-8 flex-1">
                                <div class="py-2 inline-block min-w-full sm:px-6 lg:px-8">
                                    <div class="overflow-hidden">
                                        <table class="w-full">
                                            <thead class="bg-white border-b">
                                            <tr>
                                                <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                                    #
                                                </th>
                                                <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                                    Title
                                                </th>
                                                <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                                    Color
                                                </th>
                                                <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                                    Control
                                                </th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr v-for="transaction_status in transaction_statuses" :key="transaction_status.id" class="bg-white border-b transition duration-300 ease-in-out hover:bg-gray-100">
                                                <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                                    {{transaction_status.id}}
                                                </td>
                                                <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                                    {{ transaction_status.title }}
                                                </td>
                                                <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                                    {{ transaction_status.color_value }}
                                                </td>
                                                <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                                    <ps-button @click="handleDelete(transaction_status.id)" class="hover:bg-gray-100 text-white hover:text-gray-400 py-1 px-3 border rounded bg-red-600">
                                                        Delete
                                                    </ps-button>
                                                    <ps-button @click="handleEdit(transaction_status.id)" class="hover:bg-gray-100 text-white hover:text-gray-400 py-1 px-3 border rounded bg-yellow-500">
                                                        Edit
                                                    </ps-button>
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
    import PsInput from "@/Components/Core/Input/PsInput.vue";
    import PsLabel from "@/Components/Core/Label/PsLabel.vue";
    import PsButton from "@/Components/Core/Buttons/PsButton.vue";
    import PsTextarea from '@/Components/Core/Textarea/PsTextarea.vue';
    import PsLabelHeader4 from "@/Components/Core/Label/PsLabelHeader4.vue";
    import { trans } from 'laravel-vue-i18n';

    export default defineComponent({
        name: "Index",
        components: {
            Head,
            Link,
            PsInput,
            PsLabel,
            PsButton,
            PsTextarea,
            PsLabelHeader4
        },
        layout : PsLayout,
        props: ['transaction_statuses', 'status'],
        methods: {
            handleDelete(id) {
                this.$inertia.delete(route('transaction_status.destroy',id));
            },
            handleEdit(id){
                this.$inertia.get(route('transaction_status.edit',id));
            },
        },
    })
</script>
