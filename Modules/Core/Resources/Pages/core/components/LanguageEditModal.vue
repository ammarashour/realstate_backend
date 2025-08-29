<template>
    <ps-modal ref="psmodal" maxWidth="560px" bodyHeight="max-h-full" line="hidden" :isClickOut='false' theme=" px-6 py-6 rounded-lg shadow-xl" class=' z-50 h-56 bg-white'>
        <template #title>
            <div class="px-2 w-full flex flex-row justify-between">
                <ps-label class="text-lg font-semibold">{{$t('core__be_language_string_label')}}</ps-label>
                 <ps-icon @click="closeModal()" name="cross" class="me-1 font-semibold" theme="text-secondary-400" />
            </div>
        </template>
        <template #body>
            <div class="w-full flex flex-col mt-4 mb-4">
                <!-- card body start -->
                <div class="px-2 mt-6">
                    <form @submit.prevent="handleSubmit()">
                        <div class="w=full after:flex flex-col items-start justify-start space-y-6">
                            <div>
                                <ps-label>{{$t('core__be_key_label')}}<span class="text-red-800 font-medium ms-1">*</span></ps-label>
                                <ps-input type="text" :disabled="true" v-model:value="form.key" :placeholder="$t('core__be_key_placeholder')"/>
                            </div>
                            
                            <div v-for="languageString in languages.data" :key="languageString.id">
                                <div v-if="languageString != null">
                                    <ps-label class="text-base mb-2">{{languageString.name}}<span class="text-red-800 font-medium ms-1">*</span>
                                    </ps-label> 
                                </div>
                                
                                <ps-input type="text" v-model:value="languageStrings.data[languageString.id].value" :placeholder="$t('core__be_value_placeholder')"/>
                            </div>

                            <div class="flex flex-row justify-end mb-2.5">
                                <ps-button @click="handleCancel()" textSize="text-base" type="reset" class="me-4" colors="text-primary-500" focus="" hover="">{{ $t('core__be_btn_cancel') }}</ps-button>
                                <ps-button class="transition-all duration-300 min-w-3xs" padding="px-7 py-2" rounded="rounded" hover="" focus="" >
                                    <ps-loading v-if="loading" theme="border-2 border-t-2 border-text-8 border-t-primary-500"  loadingSize="h-5 w-5" />
                                    <ps-icon v-if="success" name="check" w="20" h="20" class="me-1.5 transition-all duration-300" />
                                    <span v-if="success" class="transition-all duration-300">{{ $t('core__be_btn_saved') }}</span>
                                    <span v-if="!loading && !success" class="" > {{ $t('core__be_btn_save') }} </span>
                                </ps-button>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </template>
    </ps-modal>
</template>

<script >
import { defineComponent,ref,reactive } from 'vue';
import PsModal from '@/Components/Core/Modals/PsModal.vue';
import PsLabel from '@/Components/Core/Label/PsLabel.vue';
import PsButton from '@/Components/Core/Buttons/PsButton.vue';
import PsToggle from '@/Components/Core/Toggle/PsToggle.vue';
import PsIcon from "@/Components/Core/Icons/PsIcon.vue";
import PsInput from "@/Components/Core/Input/PsInput.vue";
import PsLoading from "@/Components/Core/Loading/PsLoading.vue";

import { useForm } from '@inertiajs/vue3';

// import { trans } from 'laravel-vue-i18n';

export default defineComponent({
    name : "LanguageEditModal",
    components : {
        PsModal,
        PsLabel,
        PsButton,
        PsToggle,
        PsIcon,
        PsInput,
        PsLoading
    },
    setup() {
        const psmodal = ref();
        const languageStrings = reactive({data : {}});
        const languages = reactive({data : {}});
        const loading = ref(false);
        const success = ref(false);
        let form = useForm({
            key: "",
            values: [],
        })

        function handleCancel(){
            psmodal.value.toggle(false);
        }

        function openModal(v) {
            form.key = v;

            axios.post(route('language_string.getLanguageString',form))
            .then(res => {
                console.log(res);
                // languageStrings.data = res.data;
                languageStrings.data= res.data.values;
                languages.data = res.data.languages;
                psmodal.value.toggle(true);
            })
            .catch(error => {
                    // psmodal.value.toggle(true);
                });            
        }

        function handleSubmit(){

            form.values = [];
            for(let i=0;i<languages.data.length;i++){  
                var value = languageStrings.data[languages.data[i].id].value;
                if(value == null || value == ''){
                    alert("Please fill all values");
                    return;
                }

                form.values.push({
                    value : value,
                    id : languageStrings.data[languages.data[i].id].id, 
                    language_id : languages.data[i].id,
                    key : languageStrings.data[languages.data[i].id].key,
                    added_user_id : languageStrings.data[languages.data[i].id].added_user_id,
                    is_from_builder: languageStrings.data[languages.data[i].id].is_from_builder,
                });
            }
            // console.log(form);
            
            this.$inertia.post(route('language_string.updateLanguageStrings'), form, {
                forceFormData: true,
            onBefore: () => {loading.value = true},
            onSuccess: () => {
                loading.value = false;
                success.value = true;
                setTimeout(()=>{
                    success.value = false;
                    psmodal.value.toggle(false);
                    window.location.reload();
                },1000)
            },
            onError: () => {
                loading.value = false;
            },
            });
        }
        function closeModal(){
            psmodal.value.toggle(false);
        }
        return {
            loading, 
            success,
            psmodal,
            openModal,
            form,
            languageStrings,
            languages,
            handleCancel,
            handleSubmit,
            closeModal

        }
    },
})
</script>
