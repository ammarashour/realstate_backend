<template>
    <ps-modal ref="psmodal" maxWidth="560px" bodyHeight="max-h-full" line="hidden" :isClickOut='false' theme=" px-6 py-6 rounded-lg shadow-xl" class='z-50 h-56 bg-white '>
        <template #title>
            <div class="flex flex-row justify-between w-full px-2">
                <ps-label class="text-lg font-semibold">{{$t('core__be_language_string_label')}}</ps-label>
                 <ps-icon @click="closeModal()" name="cross" class="font-semibold cursor-pointer me-1" theme="text-secondary-400" />
            </div>
        </template>
        <template #body>
            <div class="flex flex-col w-full mt-4 mb-4">
                <!-- card body start -->
                <div class="px-2 mt-6 overflow-y-scroll h-76">
                    <div >
                        <div class="w=full after:flex flex-col items-start justify-start space-y-6">
                            <div v-if="!isCreateUI">
                                <ps-label>{{$t('core__be_key_label')}}<span class="font-medium text-red-800 ms-1">*</span></ps-label>
                                <ps-input type="text" :disabled="true" v-model:value="form.key" :placeholder="$t('core__be_key_placeholder')"/>
                            </div>

                            <div v-for="languageString in languages.data" :key="languageString.id">
                                <div v-if="languageString != null">
                                    <ps-label class="text-base mb-2">{{languageString.name}}<span class="text-red-800 font-medium ms-1">*</span>
                                    </ps-label>
                                </div>

                                <ps-input type="text" v-if="languageStrings.data[languageString.id] != null " v-model:value="languageStrings.data[languageString.id].value" :placeholder="$t('core__be_value_placeholder')"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>
        <template #footer>
             <div class="w-full flex flex-row justify-end mb-2.5">
                <ps-button @click="closeModal()" textSize="text-base" type="reset" class="me-4" colors="text-primary-500" focus="" hover="">{{ $t('core__be_btn_cancel') }}</ps-button>
                <ps-button @click="handleSubmit()" class="transition-all duration-300 min-w-3xs" padding="px-7 py-2" rounded="rounded" hover="" focus="" >
                    <ps-loading v-if="loading" theme="border-2 border-t-2 border-text-8 border-t-primary-500"  loadingSize="h-5 w-5" />
                    <ps-icon v-if="success" name="check" w="20" h="20" class="me-1.5 transition-all duration-300" />
                    <span v-if="success" class="transition-all duration-300">{{ $t('core__be_btn_saved') }}</span>
                    <span v-if="!loading && !success" class="" > {{ $t('core__be_btn_save') }} </span>
                </ps-button>
            </div>
        </template>
    </ps-modal>
</template>

<script>
import { defineComponent,ref,reactive } from 'vue';
import PsModal from '@/Components/Core/Modals/PsModal.vue';
import PsLabel from '@/Components/Core/Label/PsLabel.vue';
import PsButton from '@/Components/Core/Buttons/PsButton.vue';
import PsToggle from '@/Components/Core/Toggle/PsToggle.vue';
import PsIcon from "@/Components/Core/Icons/PsIcon.vue";
import PsInput from "@/Components/Core/Input/PsInput.vue";
import PsLoading from "@/Components/Core/Loading/PsLoading.vue";
import { useForm } from '@inertiajs/vue3';

export default defineComponent({
    name : "LanguageUpdateModal",
    components : {
        PsModal,
        PsLabel,
        PsButton,
        PsToggle,
        PsIcon,
        PsInput,
        PsLoading
    },
    emit: ['onSaved'],
    setup(_, {emit}) {
        const psmodal = ref();
        const languageStrings = reactive({data : []});
        const languages = reactive({data : {}});
        const loading = ref(false);
        const success = ref(false);
        const isCreateUI = ref(false);
        const langaugesValues = ref();
        let form = useForm({
            key: "",
            values: [],
        })

        let saveClicked;

        function setLanguageStrings(key, languages, languagesValues = null) {
            this.form.key = key;
            if(this.languageStrings.data.length == 0){
                languages.forEach(language => {
                    let languageStringObj = {};
                    languageStringObj.key = languagesValues == null ? '' : languagesValues.filter(lang => lang.language_id == language.id)[0]?.key;
                    languageStringObj.value = languagesValues == null ? '' : languagesValues.filter(lang => lang.language_id == language.id)[0]?.value;
                    languageStringObj.language_id = language.id;

                    this.languageStrings.data[language.id] = {
                        key: languageStringObj.key,
                        value: languageStringObj.value,
                        language_id: language.id
                    };
                    // console.log(this.languageStrings.data[language.id]);

                })
            }

            this.langaugesValues = languagesValues;

        }

        function openModal(v,updateStr,isCreate = false) {
            isCreateUI.value = isCreate;
            saveClicked = updateStr;
            // if(!isCreate && form.key == ""){
                form.key = v;

                axios.post(route('language_string.getLanguageString',form))
                .then(res => {
                    // console.log("Data From Server : ");
                    // console.log(res.data);
                    if(languageStrings.data == null || languageStrings.data.length <= 0){
                        languageStrings.data= res.data.values;
                    }
                    languages.data = res.data.languages;
                    psmodal.value.toggle(true);
                })
                .catch(error => {
                    consoloe.log(error);
                });
            // }else{
            //     psmodal.value.toggle(true);
            // }
        }

        function handleSubmit(){
            
            const symbol = localStorage.activeLanguage ? localStorage.activeLanguage : 'en';
            let languageValueString = '';
            form.values = [];
            for(let i=0;i<languages.data.length;i++){
                var value = languageStrings.data[languages.data[i].id].value;

                if(languages.data[i].symbol == symbol) {
                    languageValueString = value;
                }

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
                    symbol : languages.data[i].symbol
                });
                // console.log(form.values);
            }
            if(saveClicked){
                saveClicked(form);
            }

            emit('onSaved', languageValueString, form);
            psmodal.value.toggle(false);

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
            handleSubmit,
            closeModal,
            isCreateUI,
            setLanguageStrings

        }
    },
})
</script>
