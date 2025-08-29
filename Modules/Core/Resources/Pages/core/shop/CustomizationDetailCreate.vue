<template>
    <Head title="Create Attribute" />
    <ps-layout>
        <div class="py-6 px-4">
            <div class="border rounded px-4  py-3 shadow">
                <h4 class="text-xl font-semibold">Customization Detail</h4>
                <hr class="mt-3 pb-3">
                <p class="mb-4">
                    <ps-label class="text-red-500 font-semibold text-xl">*</ps-label>
                    Selected Customize Header Name : {{ custom_field_header.name }}
                </p>
                <form @submit.prevent="handleSubmit">
                    <ps-label class="block">
                        <ps-label class="text-red-500 font-semibold text-xl">*</ps-label>
                        Customize Detail Name
                    </ps-label>
                    <ps-input type="hidden" class="w-60 rounded" v-model:value="form.custom_field_header_id"
                        placeholder="Please Enter title"/>
                    <ps-input type="text" class="w-60 rounded" v-model:value="form.attribute" placeholder="Please enter attribute name"
                        @keyup="validateEmptyInput('attribute', form.attribute)"
                        @blur="validateEmptyInput('attribute', form.attribute)"/>
                        <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ errors.attribute }}</ps-label-caption>
                    <div class="mt-6">
                        <ps-button class="me-2 bg-indigo-500 text-white px-4 py-2 rounded">{{ $t('core__be_btn_save') }}</ps-button>
                        <Link :href="route('shop.customization')"
                            class="bg-white border border-indigo-400 px-4 py-2 rounded hover:bg-indigo-600">{{ $t('core__be_btn_cancel') }}
                        </Link>
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
    name: "CustomizationDetailCreate",
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
    props: ['errors', 'custom_field_header'],
    data() {
        return {
            form: useForm(
                {
                    attribute: "",
                    custom_field_header_id: this.custom_field_header.id,
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
            this.$inertia.post(route('shop.customizationDetail.store', this.custom_field_header), this.form,{forceFormData: true})
        }
    },
});
</script>
