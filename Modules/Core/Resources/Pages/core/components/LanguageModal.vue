<template>
    <ps-modal ref="psmodal" maxWidth="960px" bodyHeight="max-h-full" line="hidden" :isClickOut='false' theme=" px-6 py-6 rounded-lg shadow-xl" class=' z-50 h-56 bg-white'>
        <template #title>
            <div class="w-full flex flex-row justify-between">
                <ps-label class="text-lg font-semibold">{{$t('core__be_language_string_label')}}</ps-label>
                 <ps-icon @click="closeModal()" name="cross" class="me-1 font-semibold" theme="text-secondary-400" />
            </div>
        </template>
        <template #body>
             <table class="table-auto w-full mt-6">
                <thead class="bg-primary-500 text-white dark:text-black text-2xl justify-content" >
                    <tr>
                        <th><ps-label textColor="flex text-secondary-50 dark:text-secondary-900 font-semibold my-2 ms-3">{{$t('core__be_no_label')}}</ps-label></th>
                        <th><ps-label textColor="flex text-secondary-50 dark:text-secondary-900 font-semibold my-2 ms-3">{{$t('core__be_language_label')}}</ps-label></th>
                        <th><ps-label textColor="flex text-secondary-50 dark:text-secondary-900 font-semibold my-2 ms-3">{{$t('core__be_key_label')}}</ps-label></th>
                        <th><ps-label textColor="flex text-secondary-50 dark:text-secondary-900 font-semibold my-2 ms-3">{{$t('core__be_value_label')}}</ps-label></th>
                        <th><ps-label textColor="flex text-secondary-50 dark:text-secondary-900 font-semibold my-2 ms-3">{{$t('core__be_select_label')}}</ps-label></th>
                        <th><ps-label textColor="flex text-secondary-50 dark:text-secondary-900 font-semibold my-2 ms-3">{{$t('core__be_action_label')}}</ps-label></th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200 dark:bg-secondaryDark-black dark:divide-white border-b border-t">
                    <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
                        <td class="py-2 ps-4">
                            <ps-label>{{1}}</ps-label>
                        </td>
                          <td class="py-2 ps-4">
                            <ps-label>{{table.data?.languageString?.language?.name ? table.data?.languageString?.language?.name : 'N.A'}}</ps-label>
                        </td>
                        <td class="py-2 ps-4">
                            <ps-label>{{key}}</ps-label>
                        </td>
                        <td class="py-2 ps-4">
                            <ps-label>{{table?.data?.languageString?.value ? table.data?.languageString?.value : 'N.A'}}</ps-label>
                        </td>
                        <td class="py-2 ps-4">
                            <ps-label textColor="text-secondary-300"  >{{ $t('core__be_btn_selected') }}</ps-label>
                        </td>
                        <td class="py-2 ps-4">
                            <ps-button @click="handleEdit()" class="me-4" colors="bg-green-400 text-white" padding="p-1.5" hover="hover:outline-none hover:ring hover:ring-green-100" focus="focus:outline-none focus:ring focus:ring-green-200" >
                                <ps-icon theme="text-white dark:text-primary-900" name="editPencil" w="16" h="16" />
                            </ps-button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </template>
        <template #footer>
            <div class=" flex flex-row justify-end">
               
            </div>
        </template>
    </ps-modal>
</template>

<script lang="ts">
import { defineComponent,ref,reactive } from 'vue';
import PsModal from '@/Components/Core/Modals/PsModal.vue';
import PsLabel from '@/Components/Core/Label/PsLabel.vue';
import PsButton from '@/Components/Core/Buttons/PsButton.vue';
import PsToggle from '@/Components/Core/Toggle/PsToggle.vue';
import PsIcon from "@/Components/Core/Icons/PsIcon.vue";

import { useForm } from '@inertiajs/vue3';

// import { trans } from 'laravel-vue-i18n';

export default defineComponent({
    name : "LanguageModal",
    components : {
        PsModal,
        PsLabel,
        PsButton,
        PsToggle,
        PsIcon,

    },
    setup() {
        const psmodal = ref();
        const key = ref('');
        const table = reactive({data : {}});
        let okClicked: Function;

        function openModal(keyStr,okClickedAction: Function) {
            key.value = keyStr;

            let form = useForm({
                        key: key.value,
                        symbol : localStorage.activeLanguage
                    })
            okClicked = okClickedAction;

            axios.post(route('language.languageTable',form))
            .then(res => {
                table.data = res.data;
                psmodal.value.toggle(true);
            })
            .catch(error => {
                    // psmodal.value.toggle(true);
                });
        }

        function handleEdit(){
            psmodal.value.toggle(false);
            okClicked(key.value);
        }
        function closeModal(){
            psmodal.value.toggle(false);
        }
        return {
            psmodal,
            openModal,
            closeModal,
            key,
            table,
            handleEdit,

        }
    },
})
</script>
