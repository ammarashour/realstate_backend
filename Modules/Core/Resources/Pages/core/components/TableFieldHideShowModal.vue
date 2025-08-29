<template>
    <ps-modal ref="psmodal" maxWidth="480px" line="hidden" :isClickOut='false' theme=" px-6 py-6 rounded-lg shadow-xl" class=' z-50 h-56 bg-white'>
        <template #title>
            <div class="w-full text-start">
                <div class="flex flex-row justify-between">
                    <ps-label class="text-lg font-semibold"> {{$t('display_setting') }} </ps-label>
                     <ps-icon @click="actionClicked('no')" name="cross" class="me-1 font-semibold" theme="text-secondary-400" />
                </div>
            </div>
        </template>
        <template #body>
            <div class="w-full flex flex-col mt-4 mb-6">
                <div class="flex flex-row justify-between">
                    <ps-label class="font-light text-sm lg:text-base"> {{$t('enable_shown_in_table') }} </ps-label>
                    <ps-toggle :selectedValue="isIncluded"
                        @click="isIncludedClicked()" toggleOnTheme="bg-primary-500 border-primary-500 "></ps-toggle>
                </div>
                <div class="flex flex-row justify-between mt-6" v-if="isIncluded">
                    <ps-label class="font-light text-sm lg:text-base" > {{$t('shown_in_table') }} </ps-label>
                    <ps-toggle :selectedValue="isShow"
                        @click="isShowClicked()" toggleOnTheme="bg-primary-500 border-primary-500 "></ps-toggle>
                </div>
                <div class="flex flex-row justify-between mt-6" v-if="isIncluded">
                    <ps-label class="font-light text-sm lg:text-base" > {{$t('is_show_in_filter_label') }} </ps-label>
                    <ps-toggle :disabled="!isShow" :selectedValue="isShowInFilter"
                               @click="isShowInFilterClicked()" toggleOnTheme="bg-primary-500 border-primary-500 "></ps-toggle>
                </div>

            </div>
        </template>
        <template #footer>
            <div class=" flex flex-row justify-end">
                <ps-button rounded="rounded" @click="actionClicked('no')" textSize="text-xs lg:text-sm" class=" me-4" border="border border-gray-200 dark:border-secondary-700" colors="bg-none dark:text-secondary-100" hover="hover:outline-none hover:ring hover:ring-secondary-100 dark:hover:ring-secondary-600" > {{$t('cancel') }} </ps-button>
                <ps-button rounded="rounded" @click="actionClicked('yes')" textSize="text-sm lg:text-mase" class=""  > {{$t('confirm') }}</ps-button>
            </div>
        </template>
    </ps-modal>
</template>

<script lang="ts">
import { defineComponent,ref } from 'vue';
import PsModal from '@/Components/Core/Modals/PsModal.vue';
import PsLabel from '@/Components/Core/Label/PsLabel.vue';
import PsButton from '@/Components/Core/Buttons/PsButton.vue';
import PsToggle from '@/Components/Core/Toggle/PsToggle.vue';
import PsIcon from "@/Components/Core/Icons/PsIcon.vue";

// import { trans } from 'laravel-vue-i18n';

export default defineComponent({
    name : "TableFieldHideShowModal",
    components : {
        PsModal,
        PsLabel,
        PsButton,
        PsToggle,
        PsIcon
    },
    setup() {
        const psmodal = ref();
        const isIncluded = ref(false);
        const isShow = ref(false);
        const isShowInFilter = ref(false);
        let okClicked: Function;
        let cancelClicked: Function;

        function actionClicked(status) {
            if(status == 'yes') {
                if(isIncluded.value == false){
                    isShow.value = false;
                }
                if(isShow.value == false){
                    isShowInFilter.value = false;
                }
                okClicked(isIncluded.value ? '1' : '0', isShow.value ? '1' : '0', isShowInFilter.value ? '1' : '0');
            }else {
                cancelClicked();
            }
            psmodal.value.toggle(false);
        }

        function openModal(
            is_included,
            is_show,
            is_show_in_filter,
                okClickedAction: Function,
                cancelClickedAction: Function) {

            if(is_included == '1'){
                isIncluded.value = true;
            }else{
                isIncluded.value = false;
            }
            if(is_show == '1'){
                isShow.value = true;
            }else{
                isShow.value = false;
            }
            if(is_show_in_filter == '1'){
                isShowInFilter.value = true;
            }else{
                isShowInFilter.value = false;
            }
            psmodal.value.toggle(true);
            okClicked = okClickedAction;
            cancelClicked= cancelClickedAction;
        }
        function isIncludedClicked(){
            isIncluded.value = !isIncluded.value;
            if (!isIncluded.value){
                isShow.value = false;
                isShowInFilter.value = false;
            }
        }
        function isShowClicked(){
            isShow.value = !isShow.value;
            if (!isShow.value){
                isShowInFilter.value = false;
            }
        }
        function isShowInFilterClicked(){
            if (!isShow.value){
                isShowInFilter.value = false;
            } else {
                isShowInFilter.value = !isShowInFilter.value;
            }
        }

        return {
            psmodal,
            openModal,
            actionClicked,
            isIncluded,
            isShow,
            isShowInFilter,
            isIncludedClicked,
            isShowClicked,
            isShowInFilterClicked

        }
    },
})
</script>
