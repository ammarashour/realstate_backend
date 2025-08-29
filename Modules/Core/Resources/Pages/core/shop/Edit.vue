
<template>
    <Head :title="$t('edit_shop')" />
    <ps-layout>
        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white  shadow-xl sm:rounded-lg">
                    <div class="mt-6 ms-6 mx-auto flex justify-center">
                        <form @submit.prevent="handleSubmit(this.shop.id)">
                            <ps-label-header-4>Edit Shop</ps-label-header-4>
                            <div>
                                <ps-label><ps-label class="text-red-800 font-medium me-1">*</ps-label>Shop
                                    Name</ps-label>
                                <ps-input type="text" v-model:value="form.name" placeholder="Shop Name"
                                    @keyup="validateEmptyInput('name', form.name)"
                                    @blur="validateEmptyInput('name', form.name)"/>
                                <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ errors.name }}</ps-label-caption>
                            </div>
                            <div>
                                <ps-label>Description</ps-label>
                                <ps-textarea row="3" v-model:value="form.description" placeholder="Description"
                                    ></ps-textarea>
                                <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ errors.description }}</ps-label-caption>
                            </div>
                            <div>
                                <ps-label>Email</ps-label>
                                <ps-input type="text" v-model:value="form.email" :placeholder="$t('email_label')" />
                                <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ errors.email }}</ps-label-caption>
                            </div>
                            <div>
                                <ps-label>Latitude</ps-label>
                                <ps-input type="text" v-model:value="form.lat" placeholder="Latitude" />
                                <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ errors.lat }}</ps-label-caption>
                            </div>
                            <div>
                                <ps-label>Longitude</ps-label>
                                <ps-input type="text" v-model:value="form.lng" placeholder="Longitude" />
                                <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ errors.lng }}</ps-label-caption>
                            </div>
                            <div>
                                <ps-label>COD Email</ps-label>
                                <ps-input type="text" v-model:value="form.cod_email" placeholder="COD Email"
                                    />
                                <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ errors.cod_email }}</ps-label-caption>
                            </div>
                            <div>
                                <input type="checkbox" class="rounded border" value="0" v-model="form.cod_enable"/>
                                <ps-label class="ms-2" for="">Is COD Enabled?</ps-label>
                            </div>
                            <div>
                                <ps-label class="block">Payment Status</ps-label>
                                <select type="text" class="w-full rounded flex-1" v-model="form.payment_status_id">
                                    <option value="">Please select payment status</option>
                                    <option v-for="payment_status in payment_statuses" :value="payment_status.id"
                                        :key="payment_status.id">
                                        {{ payment_status.title }}
                                    </option>
                                </select>
                                <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ errors.payment_status_id }}</ps-label-caption>
                            </div>
                            <div>
                                <input type="checkbox" class="rounded border" value="0" v-model="form.is_featured"/>
                                <ps-label class="ms-2" for="">Is Featured?</ps-label>
                            </div>
                            <div>
                                <ps-label>Overall Tax Label</ps-label>
                                <ps-input type="text" v-model:value="form.overall_tax_label" placeholder="Overall Tax Label"
                                    />
                                <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ errors.overall_tax_label }}</ps-label-caption>
                            </div>
                            <div>
                                <ps-label>Overall Tax Value</ps-label>
                                <ps-input type="text" v-model:value="form.overall_tax_value" placeholder="Overall Tax Value"
                                    />
                                <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ errors.overall_tax_value }}</ps-label-caption>
                            </div>
                            <div>
                                <ps-label>Shipping Tax Label</ps-label>
                                <ps-input type="text" v-model:value="form.shipping_tax_label" placeholder="Shipping Tax Label"
                                    />
                                <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ errors.shipping_tax_label }}</ps-label-caption>
                            </div>
                            <div>
                                <ps-label>Shipping Tax Value</ps-label>
                                <ps-input type="text" v-model:value="form.shipping_tax_value" placeholder="Shipping Tax Value"
                                    />
                                <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ errors.shipping_tax_value }}</ps-label-caption>
                            </div>
                            <div>
                                <ps-label>Whatsapp No.</ps-label>
                                <ps-input type="text" v-model:value="form.whatsapp_no" placeholder="Whatsapp No."
                                    />
                                <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ errors.whatsapp_no }}</ps-label-caption>
                            </div>
                            <div>
                                <ps-label>Refund Policy</ps-label>
                                <ps-textarea row="3" v-model:value="form.refund_policy" placeholder="Refund Policy"
                                    ></ps-textarea>
                                <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ errors.refund_policy }}</ps-label-caption>
                            </div>
                            <div>
                                <ps-label>Terms</ps-label>
                                <ps-textarea row="3" v-model:value="form.terms" placeholder="Terms"
                                    ></ps-textarea>
                                <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ errors.terms }}</ps-label-caption>
                            </div>
                            <div>
                                <ps-label>Facebook</ps-label>
                                <ps-input type="text" v-model:value="form.facebook" placeholder="Facebook"
                                    />
                                <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ errors.facebook }}</ps-label-caption>
                            </div>
                            <div>
                                <ps-label>Messenger</ps-label>
                                <ps-input type="text" v-model:value="form.messenger" placeholder="Messenger"
                                    />
                                <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ errors.messenger }}</ps-label-caption>
                            </div>
                            <div>
                                <ps-label>Instagram</ps-label>
                                <ps-input type="text" v-model:value="form.instagram" placeholder="Instagram"
                                    />
                                <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ errors.instagram }}</ps-label-caption>
                            </div>
                            <div>
                                <ps-label>Youtube</ps-label>
                                <ps-input type="text" v-model:value="form.youtube" placeholder="Youtube" />
                                <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ errors.youtube}}</ps-label-caption>
                            </div>
                            <div>
                                <ps-label>{{ $t('phone_label') }}</ps-label>
                                <ps-input type="text" v-model:value="form.phone_no" :placeholder="$t('phone_label')" />
                                <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ errors.phone_no }}</ps-label-caption>
                            </div>
                            <div>
                                <ps-label>{{ $t('address_label') }}</ps-label>
                                <ps-input type="text" v-model:value="form.address" :placeholder="$t('address_label')" />
                                <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ errors.address }}</ps-label-caption>
                            </div>
                            <div>
                                <input type="radio" value="checkout_with_email" v-model="form.checkout"/>
                                <ps-label class="ms-2" for="">Checkout with email</ps-label>
                            </div>
                            <div>
                                <input type="radio" value="checkout_with_whatsapp" v-model="form.checkout"/>
                                <ps-label class="ms-2" for="">Checkout with whatsapp</ps-label>
                            </div>
                            <div>
                                <input type="checkbox" class="rounded border" v-model="form.multi_page_checkout"/>
                                <ps-label class="ms-2" for="">Multi Page Checkout</ps-label>
                            </div>
                            <div>
                                <ps-label>Price Level</ps-label>
                                <ps-input type="text" v-model:value="form.price_level" placeholder="Price Level"
                                    />
                                <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ errors.price_level }}</ps-label-caption>
                            </div>

                            <!-- custom field start -->
                            <div v-for="custom_field_header in custom_field_headers" :key="custom_field_header.id">
                                <!-- dropdown-->
                                <div class="mb-4"
                                    v-if="custom_field_header.ui_type_id === 1">
                                    <ps-label class="block"><ps-label class="text-red-800 font-medium me-1" v-show="custom_field_header.mandatory==1">*</ps-label>{{ custom_field_header.name }}</ps-label>
                                    <select type="text" class="w-full rounded flex-1"
                                        v-model="form.custom_fields[custom_field_header.id]"
                                        @change="custom_field_header.mandatory==1?validateEmptyInput(custom_field_header.id, form.custom_fields[custom_field_header.id], 'The '+ custom_field_header.name.toLowerCase() +' field is required.'): ''"
                                        @blur="custom_field_header.mandatory==1?validateEmptyInput(custom_field_header.id, form.custom_fields[custom_field_header.id], 'The '+ custom_field_header.name.toLowerCase() +' field is required.'): ''"
                                        >
                                        <option value="">{{ $t('please_select_label') }}</option>
                                        <option
                                            v-for="detail in custom_field_header.custom_field_detail"
                                            :value="detail.attribute" :key="detail.id">
                                            {{ detail.attribute }}
                                        </option>
                                    </select>
                                    <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ errors[custom_field_header.id] }}</ps-label-caption>
                                </div>

                                <!-- text-->
                                <div class="mb-4"
                                    v-if="custom_field_header.ui_type_id === 2">
                                    <ps-label class="block"><ps-label class="text-red-800 font-medium me-1" v-show="custom_field_header.mandatory==1">*</ps-label>{{ custom_field_header.name }}</ps-label>
                                    <ps-input type="text" class="w-full rounded"
                                        v-model:value="form.custom_fields[custom_field_header.id]"
                                        @keyup="custom_field_header.mandatory==1?validateEmptyInput(custom_field_header.id, form.custom_fields[custom_field_header.id], 'The '+ custom_field_header.name.toLowerCase() +' field is required.'): ''"
                                        @blur="custom_field_header.mandatory==1?validateEmptyInput(custom_field_header.id, form.custom_fields[custom_field_header.id], 'The '+ custom_field_header.name.toLowerCase() +' field is required.'): ''"
                                    />
                                    <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ errors[custom_field_header.id] }}</ps-label-caption>
                                </div>

                                <!-- radio-->
                                <div class="mb-4"
                                    v-if="custom_field_header.ui_type_id === 3">
                                    <ps-label class="block"><ps-label class="text-red-800 font-medium me-1" v-show="custom_field_header.mandatory==1">*</ps-label>{{ custom_field_header.name }}</ps-label>
                                    <div class="flex flex-row ">
                                        <div class="me-2"
                                            v-for="detail in custom_field_header.custom_field_detail"
                                            :key="detail.id">
                                            <ps-input class="me-2" type="radio"
                                                :id="detail.id" :value="detail.attribute"
                                                v-model:value="form.custom_fields[custom_field_header.id]"
                                                @change="custom_field_header.mandatory==1?validateEmptyInput(custom_field_header.id, form.custom_fields[custom_field_header.id], 'The '+ custom_field_header.name.toLowerCase() +' field is required.'): ''"/>
                                            <ps-label :for="detail.id">{{ detail.attribute }}</ps-label>
                                        </div>
                                    </div>
                                    <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ errors[custom_field_header.id] }}</ps-label-caption>
                                </div>

                                <!-- checkbox-->
                                <div class="mb-4"
                                    v-if="custom_field_header.ui_type_id === 4">
                                    <div class="flex flex-row ">
                                        <div class="me-2">
                                            <input type="checkbox" class="rounded border" value="0"
                                            v-model="form.custom_fields[custom_field_header.id]"
                                            @change="custom_field_header.mandatory==1?validateEmptyInput(custom_field_header.id, form.custom_fields[custom_field_header.id], 'The '+ custom_field_header.name.toLowerCase() +' field is required.'): ''"/>
                                        <ps-label class="ms-2">{{ custom_field_header.name }}</ps-label>
                                        </div>
                                    </div>
                                    <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ errors[custom_field_header.id] }}</ps-label-caption>
                                </div>

                                <!-- datetime -->
                                <div class="mb-4"
                                    v-if="custom_field_header.ui_type_id === 5">
                                    <ps-label class="block"><ps-label class="text-red-800 font-medium me-1" v-show="custom_field_header.mandatory==1">*</ps-label>{{ custom_field_header.name }}</ps-label>
                                    <ps-input type="date" class="w-full rounded"
                                        v-model:value="form.custom_fields[custom_field_header.id]"
                                        @keyup="custom_field_header.mandatory==1?validateEmptyInput(custom_field_header.id, form.custom_fields[custom_field_header.id], 'The '+ custom_field_header.name.toLowerCase() +' field is required.'): ''"
                                        @blur="custom_field_header.mandatory==1?validateEmptyInput(custom_field_header.id, form.custom_fields[custom_field_header.id], 'The '+ custom_field_header.name.toLowerCase() +' field is required.'): ''"
                                        />
                                    <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ errors[custom_field_header.id] }}</ps-label-caption>
                                </div>

                                <!-- textarea -->
                                <div class="mb-4"
                                    v-if="custom_field_header.ui_type_id === 6">
                                    <ps-label class="block"><ps-label class="text-red-800 font-medium me-1" v-show="custom_field_header.mandatory==1">*</ps-label>{{ custom_field_header.name }}</ps-label>
                                    <ps-textarea rows="3" placeholder="Placeholder" class="w-full rounded"
                                        v-model:value="form.custom_fields[custom_field_header.id]"
                                        @keyup="custom_field_header.mandatory==1?validateEmptyInput(custom_field_header.id, form.custom_fields[custom_field_header.id], 'The '+ custom_field_header.name.toLowerCase() +' field is required.'): ''"
                                        @blur="custom_field_header.mandatory==1?validateEmptyInput(custom_field_header.id, form.custom_fields[custom_field_header.id], 'The '+ custom_field_header.name.toLowerCase() +' field is required.'): ''"
                                    ></ps-textarea>
                                    <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ errors[custom_field_header.id] }}</ps-label-caption>
                                </div>

                                <!-- number-->
                                <div class="mb-4"
                                    v-if="custom_field_header.ui_type_id === 7">
                                    <ps-label class="block"><ps-label class="text-red-800 font-medium me-1" v-show="custom_field_header.mandatory==1">*</ps-label>{{ custom_field_header.name }}</ps-label>
                                    <ps-input type="number" class="w-full rounded"
                                        v-model:value.number="form.custom_fields[custom_field_header.id]"
                                        @keyup="custom_field_header.mandatory==1?validateNumberInput(custom_field_header.id, form.custom_fields[custom_field_header.id], 'The '+ custom_field_header.name.toLowerCase() +' field is required.', 'The '+ custom_field_header.name.toLowerCase() +' field only have number.'): ''"
                                        @blur="custom_field_header.mandatory==1?validateNumberInput(custom_field_header.id, form.custom_fields[custom_field_header.id], 'The '+ custom_field_header.name.toLowerCase() +' field is required.', 'The '+ custom_field_header.name.toLowerCase() +' field only have number.'): ''"
                                        @keypress="onlyNumber"/>
                                    <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ errors[custom_field_header.id] }}</ps-label-caption>
                                </div>

                                <!-- multi Select-->
                                <div class="mb-4"
                                    v-if="custom_field_header.ui_type_id === 8">
                                    <ps-label  class="text-base">{{ $t(custom_field_header.name) }}<ps-label class="text-red-800 font-medium me-1" v-show="custom_field_header.mandatory==1">*</ps-label>{{ custom_field_header.name }}</ps-label>
                                    <div class="flex flex-row ">
                                        <CheckBox :oldData="form.custom_fields[custom_field_header.id]" @toParent="handleMultiSelect" :customizeDetails="customizeDetails"
                                            :customizeHeader="custom_field_header" />
                                    </div>
                                    <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ errors[custom_field_header.id] }}</ps-label-caption>
                                </div>

                                <!-- Image-->
                                <div class="mb-4"
                                    v-if="custom_field_header.ui_type_id === 9">
                                    <ps-label class="block"><ps-label class="text-red-800 font-medium me-1" v-show="custom_field_header.mandatory==1">*</ps-label>{{ custom_field_header.name }}</ps-label>
                                    <ps-input name="image" type="file" accept="image/*" class="w-full rounded"
                                        @input="form.custom_fields[custom_field_header.id] = $event.target.files[0]"/>
                                    <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ errors[custom_field_header.id] }}</ps-label-caption>
                                </div>
                            </div>
                            <!-- /.custom field end -->
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
import CheckBox from "../components/CheckBox.vue";
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
        CheckBox,
        PsInput,
        PsLabel,
        PsButton,
        PsTextarea,
        PsLabelHeader4,
        PsLabelCaption
    },
    layout: PsLayout,
    props: ['errors', 'shop', 'custom_field_headers', 'custom_field_details', 'shop_custom_fields'],
    data() {
        return {
            form: useForm(
                {
                    name: this.shop.name,
                    description: this.shop.description,
                    email: this.shop.email,
                    lat: this.shop.lat,
                    lng: this.shop.lng,
                    cod_email: this.shop.cod_email,
                    cod_enable: this.shop.cod_enable == 1 ? true : false,
                    payment_status_id: this.shop.payment_status_id,
                    is_featured: this.shop.is_featured == 1 ? true : false,
                    overall_tax_label: this.shop.overall_tax_label,
                    overall_tax_value: this.shop.overall_tax_value,
                    shipping_tax_label: this.shop.shipping_tax_label,
                    shipping_tax_value: this.shop.shipping_tax_value,
                    whatsapp_no: this.shop.whatsapp_no,
                    refund_policy: this.shop.refund_policy,
                    terms: this.shop.terms,
                    facebook: this.shop.facebook,
                    messenger: this.shop.messenger,
                    instagram: this.shop.instagram,
                    youtube: this.shop.youtube,
                    phone_no: this.shop.phone_no,
                    address: this.shop.address,
                    checkout: this.shop.checkout_with_email == 1 ? 'checkout_with_email' : 'checkout_with_whatsapp',
                    multi_page_checkout: this.shop.multi_page_checkout == 1 ? true : false,
                    price_level: this.shop.price_level,
                    custom_fields: [],
                    "_method": "put"
                }
            )
        }
    },
    setup(props) {
        // Client Side Validation
        const { isEmpty, isNum } = useValidators();

        const validateEmptyInput = (fieldName, fieldValue, errorMessage = '') => {
            props.errors[fieldName] = !fieldValue ? isEmpty(fieldName, fieldValue, errorMessage) : '';
        }

        const validateNumberInput = (fieldName, fieldValue, errorMessage1 = '', errorMessage2 = '') => {
            props.errors[fieldName] = !fieldValue ? isEmpty(fieldName, fieldValue, errorMessage1) : isNum(fieldName, fieldValue, errorMessage2);
        }

        // for custom number input validate at keypress
        const onlyNumber = ($e) => {
            let keyCode = ($e.keyCode ? $e.keyCode : $e.which);
            if (keyCode < 48 || keyCode > 57) {
                $e.preventDefault();
            }
        }

        return { validateEmptyInput, validateNumberInput, onlyNumber }
    },
    created() {
        this.shop_custom_fields.map((value, index) => {
            let data = value.data;
            this.form.custom_fields[value.custom_field_header_id] = data === '1' ? true : (data === '0' ? false : data)
        });
    },
    methods: {
        handleSubmit(id) {
            this.$inertia.post(route('shop.update', id), this.form,{forceFormData: true});
        },
    },
})
</script>
