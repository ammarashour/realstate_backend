<template>
    <Head :title="$t('edit_transaction_status')" />
    <ps-layout>
        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white  shadow-xl sm:rounded-lg">
                    <div class="mt-6 ms-6 mx-auto flex justify-center">
                        <form @submit.prevent="handleSubmit">
                            <ps-label-header-4>Create Transaction Status</ps-label-header-4>
                            <div>
                                <ps-label><ps-label
                                        class="text-red-800 font-medium me-1">*</ps-label>Title</ps-label>
                                <ps-input type="text" v-model:value="form.title" placeholder="Title"
                                    @keyup="validateTitleInput('title', form.title)"
                                    @blur="validateTitleInput('title', form.title)"/>
                                <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ errors.title }}</ps-label-caption>
                            </div>
                            <div>
                                <ps-label>Ordering</ps-label>
                                <ps-input type="text" v-model:value="form.ordering" placeholder="Ordering"
                                     @keypress="onlyNumber"/>
                                <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ errors.ordering }}</ps-label-caption>
                            </div>
                            <div>
                                <ps-label><ps-label
                                        class="text-red-800 font-medium me-1">*</ps-label>Color</ps-label>
                                <ps-input type="text" v-model:value="form.color_value" placeholder="Color"
                                    @keyup="validateEmptyInput('color_value', form.color_value)"
                                    @blur="validateEmptyInput('color_value', form.color_value)"/>
                                <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ errors.color_value }}</ps-label-caption>
                            </div>
                            <div>
                                <input type="radio" value="start_stage" v-model="form.stage"/>
                                <ps-label class="ms-2" for="">Start Stage</ps-label>
                            </div>
                            <div>
                                <input type="radio" value="final_stage" v-model="form.stage"/>
                                <ps-label class="ms-2" for="">Fianl Stage</ps-label>
                            </div>
                            <div>
                                <input type="radio" value="optional" v-model="form.stage"/>
                                <ps-label class="ms-2" for="">Optional</ps-label>
                            </div>
                            <div class="mb-4" v-show="form.stage == 'start_stage' || form.stage == 'final_stage'">
                                <input type="checkbox" class="rounded border" value="0" v-model="form.is_refundable"/>
                                <ps-label class="ms-2" for="">Is Refundable On? (If an order can refund to user at this
                                    transaction status ).</ps-label>
                            </div>
                            <div>
                                <ps-button
                                    >{{ $t('core__be_btn_save') }}</ps-button>
                            </div>
                        </form>
                    </div>
                </div>
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
    name: "Create",
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
    props: ['errors'],
    data() {
        return {
            form : useForm({
                title: "",
                ordering: "",
                color_value: "",
                stage: "",
                is_refundable: false,
            })
        }
    },
    setup(props) {
        // Client Side Validation
        const { isEmpty, minLength } = useValidators();

        const validateTitleInput = (fieldName, fieldValue) => {
            props.errors[fieldName] = !fieldValue ? isEmpty(fieldName, fieldValue) : minLength(fieldName, fieldValue, 3);
        }

        const validateEmptyInput = (fieldName, fieldValue) => {
            props.errors[fieldName] = !fieldValue ? isEmpty(fieldName, fieldValue) : '';
        }

        // for only number input validate at keypress
        const onlyNumber = ($e) => {
            let keyCode = ($e.keyCode ? $e.keyCode : $e.which);
            if (keyCode < 48 || keyCode > 57){
                $e.preventDefault();
            }
        }

        return { validateTitleInput, validateEmptyInput, onlyNumber }
    },
    methods: {
        handleSubmit() {
            this.$inertia.post(route('transaction_status.store'), this.form,{forceFormData: true});
        },
    },
})
</script>
