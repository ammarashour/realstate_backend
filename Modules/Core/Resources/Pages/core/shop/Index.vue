<template>
    <Head :title="$t('shop_module')" />
    <ps-layout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex justify-between mb-6">
                            <h4>Shop List</h4>
                            <Link
                                :href="route('shop.create')"
                                type="button"
                                data-mdb-ripple="true"
                                data-mdb-ripple-color="light"
                                class="inline-block px-6 py-3 bg-indigo-500 hover:bg-indigo-600 text-white font-medium text-xs leading-tight uppercase rounded shadow-md hover:shadow-lg focus:bg-blue-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-blue-800 active:shadow-lg transition duration-150 ease-in-out"
                            >Create New Shop</Link>

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
                                                    Name
                                                </th>
                                                <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                                    Publish
                                                </th>
                                                <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                                    Control
                                                </th>
                                                <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                                    Date / Time
                                                </th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr v-for="shop in shops" :key="shop.id" class="bg-white border-b transition duration-300 ease-in-out hover:bg-gray-100">
                                                <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                                    {{shop.id}}
                                                </td>
                                                <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                                    {{ shop.name }}
                                                </td>
                                                <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                                    <ps-button @click="handlePublish(shop.id)" class="hover:bg-gray-100 text-white hover:text-gray-400 py-1 px-3 border rounded " :class="shop.status == 1? 'bg-green-600': 'bg-red-600'">
                                                        <ps-label v-if="shop.status==1">Yes</ps-label>
                                                        <ps-label v-else>No</ps-label>
                                                    </ps-button>
                                                </td>
                                                <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                                    <ps-button @click="handleDelete(shop.id)" class="hover:bg-gray-100 text-white hover:text-gray-400 py-1 px-3 border rounded bg-red-600">
                                                        Delete
                                                    </ps-button>
                                                    <ps-button @click="handleEdit(shop.id)" class="hover:bg-gray-100 text-white hover:text-gray-400 py-1 px-3 border rounded bg-yellow-500">
                                                        Edit
                                                    </ps-button>
                                                </td>
                                                <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                                    {{ moment(shop.created_date).format($page.props.dateFormat) }}
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
    import FlashMessage from "../components/FlashMessage.vue";
    import moment from 'moment';
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
            FlashMessage,
            PsInput,
            PsLabel,
            PsButton,
            PsTextarea,
            PsLabelHeader4
        },
        layout : PsLayout,
        props: ['shops', 'status'],
        data() {
            return {
                moment: moment
            }
        },
        methods: {
            handleDelete(id) {
                this.$inertia.delete(route('shop.destroy',id));
            },
            handleEdit(id){
                this.$inertia.get(route('shop.edit',id));
            },
            handlePublish(id){
                this.$inertia.put(route('shop.statusChange',id));
            },
        },
    })
</script>



<style scoped>

</style>
