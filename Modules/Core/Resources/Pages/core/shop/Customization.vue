<template>
    <Head title="Customize Item" />
    <ps-layout>
        <div class="py-6">
            <div class="bg-white py-3 px-6">
                <div class="mb-6">
                    <FlashMessage :status="status" />
                </div>

                <!-- custom field start -->
                <div class="" v-for="custom_field_header in custom_field_headers" :key="custom_field_header.id">

                    <!-- dropdown-->
                    <div class="flex flex-row mb-4" v-if="custom_field_header.ui_type_id === 1">
                        <ps-label class="me-2 flex items-center" for="">{{ custom_field_header.name }}</ps-label>
                        <select type="text" class="w-60 rounded me-2">
                            <option value="">{{ $t('please_select_label') }}</option>
                            <option v-for="detail in custom_field_header.custom_field_detail" :value="detail.name" :key="detail.id">
                                {{ detail.attribute }}
                            </option>
                        </select>
                        <ps-button @click="handleEnable(custom_field_header.id)" type="button"
                            class="hover:bg-green-500 text-black hover:text-white duration-300 py-3 px-6 text-xs leading-tight border uppercase rounded bg-green-400">
                            {{ custom_field_header.enable === 1 ? 'Disable' : 'Enable' }}
                        </ps-button>
                        <Link :href="route('shop.customizationDetail.index', custom_field_header.id)" type="button"
                            class="hover:bg-gray-700 text-white hover:text-gray-400 py-3 px-6 text-xs leading-tight border uppercase rounded bg-gray-500">
                        Attribute</Link>
                        <ps-button @click="handleDelete(custom_field_header.id)" type="button"
                            class="hover:bg-gray-100 text-white hover:text-gray-400 py-3 px-6 text-xs leading-tight border uppercase rounded bg-red-600">Delete</ps-button>
                        <ps-button @click="handleMandatory(custom_field_header.id)"
                            class="hover:bg-black duration-300 hover:border-cyan-300 text-white hover:text-cyan-400 py-3 px-6 text-xs leading-tight border uppercase rounded bg-cyan-600">
                            {{ custom_field_header.mandatory === 1 ? 'Optional' : 'Mandatory' }}
                        </ps-button>
                    </div>

                    <!-- Text-->
                    <div class="flex flex-row mb-4" v-if="custom_field_header.ui_type_id === 2">
                        <ps-label class="me-2 flex items-center" for="">{{ custom_field_header.name }}</ps-label>
                        <Text />
                        <ps-button @click="handleEnable(custom_field_header.id)" type="button"
                            class="hover:bg-green-500 text-black hover:text-white duration-300 py-3 px-6 text-xs leading-tight border uppercase rounded bg-green-400">
                            {{ custom_field_header.enable === 1 ? 'Disable' : 'Enable' }}
                        </ps-button>
                        <ps-button @click="handleDelete(custom_field_header.id)" type="button"
                            class="hover:bg-gray-100 text-white hover:text-gray-400 py-3 px-6 text-xs leading-tight border uppercase rounded bg-red-600">Delete</ps-button>
                        <ps-button @click="handleMandatory(custom_field_header.id)"
                            class="hover:bg-black duration-300 hover:border-cyan-300 text-white hover:text-cyan-400 py-3 px-6 text-xs leading-tight border uppercase rounded bg-cyan-600">
                            {{ custom_field_header.mandatory === 1 ? 'Optional' : 'Mandatory' }}
                        </ps-button>
                    </div>

                    <!-- radio-->
                    <div class="flex flex-row mb-4" v-if="custom_field_header.ui_type_id === 3">
                        <ps-label class="me-2 flex items-center" for="">{{ custom_field_header.name }}</ps-label>
                        <div v-show="custom_field_header.custom_field_detail.length == 0">
                            <ps-input
                            class="form-check-input appearance-none rounded-full h-4 w-4 border border-gray-300 bg-white checked:bg-blue-600 checked:border-blue-600 focus:outline-none transition duration-200 mt-1 align-top bg-no-repeat bg-center bg-contain float-left me-2 cursor-pointer"
                            type="radio" name="flexRadioDefault" id="flexRadioDefault1"/>
                            <ps-label class="me-2 inline-block text-gray-800" for="flexRadioDefault1">
                                sample radio
                            </ps-label>
                        </div>
                        <div class="me-2"
                            v-for="detail in custom_field_header.custom_field_detail"
                            :key="detail.id">
                            <ps-input class="me-2" type="radio"
                                :id="detail.id" :value="detail.attribute" name="radio"/>
                            <ps-label :for="detail.id">{{ detail.attribute }}</ps-label>
                        </div>

                        <ps-button @click="handleEnable(custom_field_header.id)" type="button"
                            class="hover:bg-green-500 text-black hover:text-white duration-300 py-3 px-6 text-xs leading-tight border uppercase rounded bg-green-400">
                            {{ custom_field_header.enable === 1 ? 'Disable' : 'Enable' }}
                        </ps-button>
                        <Link :href="route('shop.customizationDetail.index', custom_field_header.id)" type="button"
                            class="hover:bg-gray-700 text-white hover:text-gray-400 py-3 px-6 text-xs leading-tight border uppercase rounded bg-gray-500">
                        Attribute</Link>
                        <ps-button @click="handleDelete(custom_field_header.id)" type="button"
                            class="hover:bg-gray-100 text-white hover:text-gray-400 py-3 px-6 text-xs leading-tight border uppercase rounded bg-red-600">Delete</ps-button>
                        <ps-button @click="handleMandatory(custom_field_header.id)"
                            class="hover:bg-black duration-300 hover:border-cyan-300 text-white hover:text-cyan-400 py-3 px-6 text-xs leading-tight border uppercase rounded bg-cyan-600">
                            {{ custom_field_header.mandatory === 1 ? 'Optional' : 'Mandatory' }}
                        </ps-button>
                    </div>

                    <!-- checkbox-->
                    <div class="flex flex-row mb-4" v-if="custom_field_header.ui_type_id === 4">
                        <input type="checkbox" class="rounded border mt-1" name="flexRadioDefault" id="flexRadioDefault1"/>
                        <ps-label class="mx-2 flex" for="">{{ custom_field_header.name }}</ps-label>
                        <ps-button @click="handleEnable(custom_field_header.id)" type="button"
                            class="hover:bg-green-500 text-black hover:text-white duration-300 py-3 px-6 text-xs leading-tight border uppercase rounded bg-green-400">
                            {{ custom_field_header.enable === 1 ? 'Disable' : 'Enable' }}
                        </ps-button>
                        <ps-button @click="handleDelete(custom_field_header.id)" type="button"
                            class="hover:bg-gray-100 text-white hover:text-gray-400 py-3 px-6 text-xs leading-tight border uppercase rounded bg-red-600">Delete</ps-button>
                        <ps-button @click="handleMandatory(custom_field_header.id)"
                            class="hover:bg-black duration-300 hover:border-cyan-300 text-white hover:text-cyan-400 py-3 px-6 text-xs leading-tight border uppercase rounded bg-cyan-600">
                            {{ custom_field_header.mandatory === 1 ? 'Optional' : 'Mandatory' }}
                        </ps-button>
                    </div>

                    <!-- Textarea -->
                    <div class="flex flex-row mb-4" v-if="custom_field_header.ui_type_id === 5">
                        <div><ps-label class="me-2 flex items-center" for="">{{ custom_field_header.name }}</ps-label></div>
                        <ps-input type="date" class="me-2 rounded"/>
                        <div>
                            <ps-button @click="handleEnable(custom_field_header.id)" type="button"
                                class="hover:bg-green-500 text-black hover:text-white duration-300 py-3 px-6 text-xs leading-tight border uppercase rounded bg-green-400">
                                {{ custom_field_header.enable === 1 ? 'Disable' : 'Enable' }}
                            </ps-button>
                            <ps-button @click="handleDelete(custom_field_header.id)" type="button"
                                class="hover:bg-gray-100 text-white hover:text-gray-400 py-3 px-6 text-xs leading-tight border uppercase rounded bg-red-600">Delete</ps-button>
                            <ps-button @click="handleMandatory(custom_field_header.id)"
                                class="hover:bg-black duration-300 hover:border-cyan-300 text-white hover:text-cyan-400 py-3 px-6 text-xs leading-tight border uppercase rounded bg-cyan-600">
                                {{ custom_field_header.mandatory === 1 ? 'Optional' : 'Mandatory' }}
                            </ps-button>
                        </div>
                    </div>

                    <!-- Textarea -->
                    <div class="flex flex-row mb-4" v-if="custom_field_header.ui_type_id === 6">
                        <div><ps-label class="me-2 flex items-center" for="">{{ custom_field_header.name }}</ps-label></div>
                        <ps-textarea rows="3" class="me-2 rounded" placeholder="Placeholder"></ps-textarea>
                        <div>
                            <ps-button @click="handleEnable(custom_field_header.id)" type="button"
                                class="hover:bg-green-500 text-black hover:text-white duration-300 py-3 px-6 text-xs leading-tight border uppercase rounded bg-green-400">
                                {{ custom_field_header.enable === 1 ? 'Disable' : 'Enable' }}
                            </ps-button>
                            <ps-button @click="handleDelete(custom_field_header.id)" type="button"
                                class="hover:bg-gray-100 text-white hover:text-gray-400 py-3 px-6 text-xs leading-tight border uppercase rounded bg-red-600">Delete</ps-button>
                            <ps-button @click="handleMandatory(custom_field_header.id)"
                                class="hover:bg-black duration-300 hover:border-cyan-300 text-white hover:text-cyan-400 py-3 px-6 text-xs leading-tight border uppercase rounded bg-cyan-600">
                                {{ custom_field_header.mandatory === 1 ? 'Optional' : 'Mandatory' }}
                            </ps-button>
                        </div>
                    </div>

                    <!-- number-->
                    <div class="flex flex-row mb-4" v-if="custom_field_header.ui_type_id === 7">
                        <ps-label class="me-2 flex items-center" for="">{{ custom_field_header.name }}</ps-label>
                        <Text @keypress="onlyNumber" />
                        <ps-button @click="handleEnable(custom_field_header.id)" type="button"
                            class="hover:bg-green-500 text-black hover:text-white duration-300 py-3 px-6 text-xs leading-tight border uppercase rounded bg-green-400">
                            {{ custom_field_header.enable === 1 ? 'Disable' : 'Enable' }}
                        </ps-button>
                        <ps-button @click="handleDelete(custom_field_header.id)" type="button"
                            class="hover:bg-gray-100 text-white hover:text-gray-400 py-3 px-6 text-xs leading-tight border uppercase rounded bg-red-600">Delete</ps-button>
                        <ps-button @click="handleMandatory(custom_field_header.id)"
                            class="hover:bg-black duration-300 hover:border-cyan-300 text-white hover:text-cyan-400 py-3 px-6 text-xs leading-tight border uppercase rounded bg-cyan-600">
                            {{ custom_field_header.mandatory === 1 ? 'Optional' : 'Mandatory' }}
                        </ps-button>
                    </div>

                    <!-- multi select-->
                    <div class="flex flex-row mb-4" v-if="custom_field_header.ui_type_id === 8">
                        <ps-label class="me-2 flex items-center" for="">{{ custom_field_header.name }}</ps-label>
                        <div v-show="custom_field_header.custom_field_detail.length == 0">
                            <input type="checkbox" class="rounded border" name="flexRadioDefault" id="flexRadioDefault1"/>
                            <ps-label class="me-2 form-check-label inline-block text-gray-800" for="flexRadioDefault1">
                                sample checkbox
                            </ps-label>
                        </div>
                        <div class="me-2"
                            v-for="detail in custom_field_header.custom_field_detail"
                            :key="detail.id">
                            <input type="checkbox" class="rounded border"
                                :value="detail.attribute"
                                @change="custom_field_header.mandatory==1?validateEmptyInput(custom_field_header.id, form.product_relation[custom_field_header.id], 'The '+ custom_field_header.name.toLowerCase() +' field is required.'): ''"
                                />
                            <ps-label :for="detail.id">{{ detail.attribute }}</ps-label>
                        </div>
                        <ps-button @click="handleEnable(custom_field_header.id)" type="button"
                            class="hover:bg-green-500 text-black hover:text-white duration-300 py-3 px-6 text-xs leading-tight border uppercase rounded bg-green-400">
                            {{ custom_field_header.enable === 1 ? 'Disable' : 'Enable' }}
                        </ps-button>
                        <Link :href="route('shop.customizationDetail.index', custom_field_header.id)" type="button"
                            class="hover:bg-gray-700 text-white hover:text-gray-400 py-3 px-6 text-xs leading-tight border uppercase rounded bg-gray-500">
                        Attribute</Link>
                        <ps-button @click="handleDelete(custom_field_header.id)" type="button"
                            class="hover:bg-gray-100 text-white hover:text-gray-400 py-3 px-6 text-xs leading-tight border uppercase rounded bg-red-600">Delete</ps-button>
                        <ps-button @click="handleMandatory(custom_field_header.id)"
                            class="hover:bg-black duration-300 hover:border-cyan-300 text-white hover:text-cyan-400 py-3 px-6 text-xs leading-tight border uppercase rounded bg-cyan-600">
                            {{ custom_field_header.mandatory === 1 ? 'Optional' : 'Mandatory' }}
                        </ps-button>
                    </div>

                    <!-- Image-->
                    <div class="flex flex-row mb-4" v-if="custom_field_header.ui_type_id === 9">
                        <ps-label class="me-2 flex items-center" for="">{{ custom_field_header.name }}</ps-label>
                         <ps-input name="image" type="file" accept="image/*" class="me-2 rounded"/>
                        <ps-button @click="handleEnable(custom_field_header.id)" type="button"
                            class="hover:bg-green-500 text-black hover:text-white duration-300 py-3 px-6 text-xs leading-tight border uppercase rounded bg-green-400">
                            {{ custom_field_header.enable === 1 ? 'Disable' : 'Enable' }}
                        </ps-button>
                        <ps-button @click="handleDelete(custom_field_header.id)" type="button"
                            class="hover:bg-gray-100 text-white hover:text-gray-400 py-3 px-6 text-xs leading-tight border uppercase rounded bg-red-600">Delete</ps-button>
                        <ps-button @click="handleMandatory(custom_field_header.id)"
                            class="hover:bg-black duration-300 hover:border-cyan-300 text-white hover:text-cyan-400 py-3 px-6 text-xs leading-tight border uppercase rounded bg-cyan-600">
                            {{ custom_field_header.mandatory === 1 ? 'Optional' : 'Mandatory' }}
                        </ps-button>
                    </div>
                </div>
                <!-- /.custom field end -->

                <div class="mt-4 ms-12">
                    <Link :href="route('shop.addNewField')" type="button"
                        >
                    + Create New Field
                    </Link>
                </div>
            </div>
        </div>
    </ps-layout>
</template>

<script>
import { defineComponent } from 'vue'
import PsLayout from "@/Components/PsLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import Text from "../components/Text.vue";
import CheckBox from "../components/CheckBox.vue";
import FlashMessage from "../components/FlashMessage.vue";
import PsInput from "@/Components/Core/Input/PsInput.vue";
import PsLabel from "@/Components/Core/Label/PsLabel.vue";
import PsButton from "@/Components/Core/Buttons/PsButton.vue";
import PsTextarea from '@/Components/Core/Textarea/PsTextarea.vue';
import PsLabelHeader4 from "@/Components/Core/Label/PsLabelHeader4.vue";
import { trans } from 'laravel-vue-i18n';

export default defineComponent({
    name: "Customization",
    components: {
        FlashMessage,
        CheckBox,
        Text,
        Head,
        Link,
        PsInput,
        PsLabel,
        PsButton,
        PsTextarea,
        PsLabelHeader4
    },
    layout: PsLayout,
    props: ['errors', 'custom_field_headers', 'status'],
    data() {},
    setup() {

        const onlyNumber = ($e) => {
            let keyCode = ($e.keyCode ? $e.keyCode : $e.which);
            if (keyCode < 48 || keyCode > 57) {
                $e.preventDefault();
            }
        }

        return { onlyNumber }
    },
    methods: {
        handleDelete(id) {
            this.$inertia.delete(route('shop.customization.delete', id))
        },
        handleMandatory(id) {
            this.$inertia.put(route('shop.addNewField.optionalOrMandatory', id))
        },
        handleEnable(id) {
            this.$inertia.put(route('shop.addNewField.enableOrDisable', id))
        }
    },
})
</script>
