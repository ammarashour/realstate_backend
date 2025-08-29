<template>
    <Head :title="$t('create_shipping')" />
    <ps-layout>
        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white  shadow-xl sm:rounded-lg">
                    <div class="mt-6 ms-6 mx-auto flex justify-center">
                        <form @submit.prevent="handleSubmit">
                            <ps-label-header-4>{{$t('create_shipping')}}</ps-label-header-4>
                            <div>
                                <ps-label><ps-label class="text-red-800 font-medium me-1">*</ps-label>Shipping
                                    Name</ps-label>
                                <ps-input type="text" v-model:value="form.name" placeholder="Shipping  Name"
                                    @keyup="validateNameInput('name', form.name)"
                                    @blur="validateNameInput('name', form.name)"/>
                                <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ errors.name }}</ps-label-caption>
                            </div>
                            <div>
                                <ps-label class="block"><ps-label class="text-red-800 font-medium me-1">*</ps-label>Shop</ps-label>
                                <select type="text" class="w-full rounded flex-1" v-model="form.shop_id"
                                    @change="validateEmptyInput('shop_id', form.shop_id)"
                                    @blur="validateEmptyInput('shop_id', form.shop_id)">
                                    <option value="">Please select shop name</option>
                                    <option v-for="shop in shops" :value="shop.id" :key="shop.id">
                                        {{ shop.name }}
                                    </option>
                                </select>
                                <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ errors.shop_id }}</ps-label-caption>
                            </div>
                            <div>
                                <ps-label><ps-label
                                        class="text-red-800 font-medium me-1">*</ps-label>Price</ps-label>
                                <ps-input type="text" v-model:value="form.price" placeholder="Price"
                                    @keyup="validatePriceInput('price', form.price)"
                                    @blur="validatePriceInput('price', form.price)"
                                    @keypress="onlyNumberWithCustom"/>
                                <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ errors.price }}</ps-label-caption>
                            </div>
                            <div>
                                <ps-label>Days</ps-label>
                                <ps-input type="text" v-model:value="form.days" placeholder="Days" />
                                <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ errors.days }}</ps-label-caption>
                            </div>
                            <div>
                                <input type="checkbox" class="rounded border" value="0" v-model="form.status"/>
                                <ps-label class="ms-2" for="">Is Publish?</ps-label>
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
    props: ['errors', 'shops'],
    data() {
        return{
            form : useForm({
                name: "",
                shop_id: "",
                price: "",
                days: "",
                status: true,
            })
        }
    },
    setup(props) {
        // Client Side Validation
        const { isEmpty, minLength, isPrice } = useValidators();

        const validateEmptyInput = (fieldName, fieldValue) => {
            props.errors[fieldName] = !fieldValue ? isEmpty(fieldName, fieldValue) : '';
        }

        const validateNameInput = (fieldName, fieldValue) => {
            props.errors[fieldName] = !fieldValue ? isEmpty(fieldName, fieldValue) : minLength(fieldName, fieldValue, 3);
        }

        const validatePriceInput = (fieldName, fieldValue) => {
            props.errors[fieldName] = !fieldValue ? isEmpty(fieldName, fieldValue) : isPrice(fieldName, fieldValue);
        }

        // for custom number input validate at keypress
        const onlyNumberWithCustom = ($e) => {
            let keyCode = ($e.keyCode ? $e.keyCode : $e.which);
            if ((keyCode < 48 || keyCode > 57) && keyCode !== 46 && keyCode !== 45 && keyCode !== 44) { // 46 is dot, 45 is dash, 44 is comma
                $e.preventDefault();
            }
        }

        return { validateEmptyInput, validateNameInput, validatePriceInput, onlyNumberWithCustom }
    },
    methods: {
        handleSubmit() {
            this.$inertia.post(route('shipping.store'), this.form,{forceFormData: true});
        },
    },
})
</script>
