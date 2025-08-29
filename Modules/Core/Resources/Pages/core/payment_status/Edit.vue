<template>
    <Head :title="$t('mobile_setting_module')" />
    <ps-layout>
        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white  shadow-xl sm:rounded-lg">
                    <div class="mt-6 ms-6 mx-auto flex justify-center">
                        <form @submit.prevent="handleSubmit(this.payment_status.id)">
                            <ps-label-header-4>{{$t('edit_payment_status')}}</ps-label-header-4>
                            <div>
                                <ps-label><ps-label
                                        class="text-red-800 font-medium me-1">*</ps-label>Title</ps-label>
                                <ps-input type="text" v-model:value="form.title" placeholder="Title"
                                    @keyup="validateTitleInput('title', form.title)"
                                    @blur="validateTitleInput('title', form.title)"/>
                                <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ errors.title }}</ps-label-caption>
                            </div>
                            <div>
                                <ps-button
                                    >Update</ps-button>
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
    name: "Edit",
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
    props: ['errors', 'payment_status'],
    data() {
        return {
            form: useForm(
                {
                    title: this.payment_status.title,
                    "_method": "put"
                }
            )
        }
    },
    setup(props) {
        // Client Side Validation
        const { isEmpty, minLength } = useValidators();

        const validateTitleInput = (fieldName, fieldValue) => {
            props.errors[fieldName] = !fieldValue ? isEmpty(fieldName, fieldValue) : minLength(fieldName, fieldValue, 3);
        }

        return { validateTitleInput }
    },
    methods: {
        handleSubmit(id) {
            this.$inertia.post(route('payment_status.update', id), this.form,{forceFormData: true});
        },
    },
})
</script>
