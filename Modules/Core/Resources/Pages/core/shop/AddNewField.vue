<template>
    <Head title="Create New Field" />
    <ps-layout>
        <div class="py-6">
            <div class="bg-white py-3 px-6">
                <form @submit.prevent="handleSubmit">
                    <div>
                        <ps-label for="" class="me-2 flex items-center">Name</ps-label>
                        <ps-input type="text" class="w-60 me-2 rounded" v-model:value="form.name"
                            placeholder="Please enter custom field name"
                            @keyup="validateEmptyInput('name', form.name)"
                            @blur="validateEmptyInput('name', form.name)"/>
                            <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ errors.name }}</ps-label-caption>
                    </div>
                    <div>
                        <ps-label>Type</ps-label>
                        <select type="text" class="w-60 rounded" v-model="form.ui_type_id"
                            @change="validateEmptyInput('ui_type_id', form.ui_type_id, 'The type field is required.')"
                            @blur="validateEmptyInput('ui_type_id', form.ui_type_id, 'The type field is required.')">
                            <option value="">{{ $t('please_select_label') }}</option>
                            <option v-for="uiType in uiTypes" :value="uiType.id" :key="uiType.id">
                                {{ uiType.name }}
                            </option>
                        </select>
                        <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ errors.ui_type_id }}</ps-label-caption>
                    </div>
                    <div>
                        <ps-label class="me-2">Is Mandatory?</ps-label>
                        <input type="checkbox" class="rounded border" value="0" v-model="form.mandatory"/>
                    </div>
                    <div class="mt-4">
                        <ps-button
                            class="hover:bg-gray-100 text-white hover:text-gray-400 py-1 px-6 border rounded bg-indigo-500">
                            {{ $t('core__be_btn_save') }}
                        </ps-button>
                    </div>
                </form>
            </div>
        </div>
    </ps-layout>
</template>

<script>
import { defineComponent } from 'vue'
import PsLayout from "@/Components/PsLayout.vue";
import { Head, useForm } from "@inertiajs/vue3";
import useValidators from '@/Validation/Validators'
import PsInput from "@/Components/Core/Input/PsInput.vue";
import PsLabel from "@/Components/Core/Label/PsLabel.vue";
import PsButton from "@/Components/Core/Buttons/PsButton.vue";
import PsTextarea from '@/Components/Core/Textarea/PsTextarea.vue';
import PsLabelHeader4 from "@/Components/Core/Label/PsLabelHeader4.vue";
import PsLabelCaption from "@/Components/Core/Label/PsLabelCaption.vue";
import { trans } from 'laravel-vue-i18n';

export default defineComponent({
    name: "AddNewField",
    components: {
        Head,
        PsInput,
        PsLabel,
        PsButton,
        PsTextarea,
        PsLabelHeader4,
        PsLabelCaption
    },
    layout: PsLayout,
    props: ['errors', 'uiTypes'],
    data() {
        return {
            form: useForm(
                {
                    name: "",
                    ui_type_id: "",
                    mandatory: "0"
                }
            )
        }
    },
    setup(props) {
        // Client Side Validation
        const { isEmpty } = useValidators();

        const validateEmptyInput = (fieldName, fieldValue, errorMessage = '') => {
            props.errors[fieldName] = !fieldValue ? isEmpty(fieldName, fieldValue, errorMessage) : '';
        }

        return { validateEmptyInput }
    },
    methods: {
        handleSubmit() {
            
            this.$inertia.post(route('shop.addNewField.store'), this.form,{forceFormData: true});
        }
    },

})
</script>
