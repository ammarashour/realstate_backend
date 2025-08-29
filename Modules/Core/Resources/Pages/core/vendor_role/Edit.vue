<template>
    <Head :title="$t('core__be_edit_vendor_role')" />
    <ps-layout>
        <!-- breadcrumb start -->
        <ps-breadcrumb-2 :items="breadcrumb" class="mb-5 sm:mb-6 lg:mb-8" />
        <!-- breadcrumb end -->

        <ps-card class="w-full h-auto">
            <div class="rounded-xl">
                <!-- card header start -->
                <div class="bg-primary-50 dark:bg-primary-900 py-2.5 ps-4 rounded-t-xl">
                    <ps-label-header-6 textColor="text-secondary-800 dark:text-secondary-100">{{$t('core__be_vendor_role_info')}}</ps-label-header-6>
                </div>
                <!-- card header end -->

                <!-- card body start -->
                <div class="px-4 pt-6 dark:bg-backgroundDark">
                    <form @submit.prevent="handleSubmit">
                        <div class="grid w-full sm:w-1/2 gap-6">
                            <!-- name-->
                            <div v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'name' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                <ps-label class="text-base">{{ $t(coreField.label_name) }} <span v-if="coreField.mandatory=1" class="text-red-800 font-medium ms-1">*</span></ps-label>
                                <ps-input ref="name" type="text" v-model:value="form.name" :placeholder="$t(coreField.placeholder)"
                                    @keyup="coreField.mandatory==1?validateEmptyInput( 'name', form.name ):''" @blur="coreField.mandatory==1?validateEmptyInput('name', form.name ):''" />
                                <ps-label-caption v-if="coreField.mandatory==1" textColor="text-red-500 " class="mt-2 block">{{ errors.name }}</ps-label-caption>
                            </div>

                            <!-- for description-->
                            <div v-for="( coreField, index ) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'description' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                <ps-label class="text-base">{{ $t(coreField.label_name) }} <span v-if="coreField.mandatory==1" class="text-red-800 font-medium ms-1">*</span></ps-label>
                                <ps-textarea rows="4" v-model:value="form.description" :placeholder="$t(coreField.description)" />
                                <ps-label-caption v-if="coreField.mandatory==1" textColor="text-red-500 " class="mt-2 block">{{ errors.description }}</ps-label-caption>
                            </div>

                            <!-- for can access admin panel-->
                            <!-- <div class="flex justify-between items-center" v-for="( coreField, index ) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'can_access_admin_panel' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                <ps-label class="text-base">{{ $t(coreField.label_name) }} <span v-if="coreField.mandatory==1" class="text-red-800 font-medium ms-1">*</span></ps-label>
                                <ps-toggle @click="handleAdminPanelAccess()" :selectedValue="form.can_access_admin_panel == 1 ? true : false" toggleOnTheme="bg-primary-500 border-primary-500 "></ps-toggle>
                            </div> -->

                            <!-- for modules -->
                            <div class="px-4 pb-6 flex flex-row justify-between items-center">
                                <ps-label class="text-base font-medium">Allow Modules</ps-label>
                                <div class="flex flex-row items-center" >
                                    <input v-model="selectAll" @change="toogleSelectAll()" class="me-2 border-1 border-secondary-200 rounded" type="checkbox" >
                                    <ps-label class="text-base font-light" >Select all </ps-label>
                                </div>
                            </div>

                            <!-- for permission -->
                            <div>
                                <div class="px-4 pb-5 flex flex-row justify-between items-center" v-for="module in modules" :key="module.id">
                                    <ps-label class="text-sm" >{{ $t(module.lang_key) }}</ps-label>
                                    <div class="flex flex-row items-center">
                                        <div class="flex flex-row me-2 items-center" v-for="permission in permissions" :key="permission.id">
                                            <input v-model="booleanPermission[module.id]" @change="handleUserMultiSelect(module.id)" class="me-2 border-1 border-secondary-200 rounded" type="checkbox" :id="permission.id" :value="permission.id">
                                            <ps-label class="text-base font-light" :for="permission.id">{{ permission.title }}</ps-label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="flex flex-row justify-end mb-2.5">
                                <ps-button @click="handleCancel()" textSize="text-base" type="reset" class="me-4" colors="text-primary-500" focus="" hover="">{{ $t("core__be_btn_cancel") }}</ps-button>
                                <ps-button class="transition-all duration-300 min-w-3xs" padding="px-7 py-2" rounded="rounded" hover="" focus="">
                                    <ps-loading v-if="loading" theme="border-2 border-t-2 border-text-8 border-t-primary-500" loadingSize="h-5 w-5" />
                                    <ps-icon v-if="success" name="check" w="20" h="20" class="me-1.5 transition-all duration-300" />
                                    <span v-if="success" class="transition-all duration-300">{{ $t("core__be_btn_saved") }}</span>
                                    <span v-if="!loading && !success" class="">{{ $t("core__be_btn_save") }}</span>
                                </ps-button>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- card body end -->
            </div>
        </ps-card>

    </ps-layout>
</template>

<script>
import { defineComponent, ref, reactive } from 'vue'
import PsLayout from "@/Components/PsLayout.vue";
import { Head, useForm } from "@inertiajs/vue3";
import CheckBox from "../components/CheckBox.vue";
import PsRadioValue from "@/Components/Core/Radio/PsRadioValue.vue";
import DatePicker from "@/Components/Core/DateTime/DatePicker.vue";
import useValidators from '@/Validation/Validators'
import PsInput from "@/Components/Core/Input/PsInput.vue";
import PsLabel from "@/Components/Core/Label/PsLabel.vue";
import PsButton from "@/Components/Core/Buttons/PsButton.vue";
import PsTextarea from '@/Components/Core/Textarea/PsTextarea.vue';
import PsLabelHeader6 from "@/Components/Core/Label/PsLabelHeader6.vue";
import PsBreadcrumb2 from "@/Components/Core/Breadcrumbs/PsBreadcrumb2.vue";
import PsDropdown from "@/Components/Core/Dropdown/PsDropdown.vue";
import PsDropdownSelect from "@/Components/Core/Dropdown/PsDropdownSelect.vue";
import PsCard from "@/Components/Core/Card/PsCard.vue";
import PsLabelCaption from "@/Components/Core/Label/PsLabelCaption.vue";
import PsVideoUpload from "@/Components/Core/Upload/PsVideoUpload.vue";
import PsLabelTitle3 from "@/Components/Core/Label/PsLabelTitle3.vue";
import PsIcon from "@/Components/Core/Icons/PsIcon.vue";
import PsLoading from "@/Components/Core/Loading/PsLoading.vue";
import PsCheckboxValue from "@/Components/Core/Checkbox/PsCheckboxValue.vue";
import PsActionModal from '@/Components/Core/Modals/PsActionModal.vue';
import PsImageIconModal from '@/Components/Core/Modals/PsImageIconModal.vue';
import PsDangerDialog from "@/Components/Core/Dialog/PsDangerDialog.vue";
import PsImageUpload from "@/Components/Core/Upload/PsImageUpload.vue";
import PsToggle from '@/Components/Core/Toggle/PsToggle.vue';

import { trans } from 'laravel-vue-i18n';

export default defineComponent({
    name: "Edit",
    components: {
        Head,
        CheckBox,
        PsToggle,
        DatePicker,
        PsInput,
        PsRadioValue,
        PsLabel,
        PsButton,
        PsTextarea,
        PsLabelHeader6,
        PsDropdown,
        PsDropdownSelect,
        PsCard,
        PsBreadcrumb2,
        PsLabelCaption,
        PsImageUpload,
        PsIcon,
        PsLoading,
        PsCheckboxValue,
        PsActionModal,
        PsImageIconModal,
        PsDangerDialog,
        PsVideoUpload,
        PsLabelTitle3,
    },
    layout: PsLayout,
    props: [
        'errors',
        'coreFieldFilterSettings',
        'role',
        'modules',
        'customizeHeaders',
        'customizeDetails',
        'permissions',
        'rolePermissions'
    ],
    setup(props) {
        let form = useForm(
                {
                    name: props.role.name,
                    description : props.role.description,
                    // can_access_admin_panel : props.role.can_access_admin_panel,
                    permissions: [],
                    permissionObj: [],
                    '_method' : 'put'
                }
            )
        let booleanPermission = reactive([]);
        let selectAll = ref(false);
        const loading = ref(false);
        const success = ref(false);
        for(let i=0; i<props.modules.length; i++){
            booleanPermission[props.modules[i].id] = []
        }
        const moduleAndPermissionObj = JSON.parse(props.rolePermissions[0].module_and_permission);
        let allSelected = true;
        for(let i=0; i<props.modules.length; i++){
            if(moduleAndPermissionObj.hasOwnProperty(props.modules[i].id)){
                booleanPermission[props.modules[i].id] = moduleAndPermissionObj[props.modules[i].id];
                form.permissions[props.modules[i].id]= moduleAndPermissionObj[props.modules[i].id].toString();
                if(!(form.permissions[props.modules[i].id].includes("1") && form.permissions[props.modules[i].id].includes("2") && form.permissions[props.modules[i].id].includes("3") && form.permissions[props.modules[i].id].includes("4"))){
                    allSelected = false;
                }
            }else{
                allSelected = false
            }
            
        }
        selectAll.value = allSelected;

        function handleUserMultiSelect(id) {
            form.permissions[id] = booleanPermission[id].toString();
        }

        function handleCancel() {
            this.$inertia.get(route('vendor_role.index'));
        }

        function handleSubmit() {
            for(let i=0; i<props.modules.length; i++){
                form.permissionObj[i] = {
                    'key' : props.modules[i].id,
                    'value': booleanPermission[props.modules[i].id].toString()
                };
            }
            this.$inertia.post(route('vendor_role.update', props.role.id), form, {
                forceFormData: true,
            onBefore: () => {loading.value = true},
            onSuccess: () => {
                loading.value = false;
                success.value = true;
                setTimeout(()=>{
                    success.value = false;
                },2000)
            },
            onError: () => {
                loading.value = false;;
            },
            });
        }
        function toogleSelectAll(){

            for(let i=0; i<props.modules.length; i++){
                for(let j=0; j<props.permissions.length; j++){
                    if(!selectAll.value){
                        booleanPermission[props.modules[i].id] = [];
                    }else{
                        booleanPermission[props.modules[i].id][j] = props.permissions[j].id;
                    }

                }
                form.permissions[props.modules[i].id] = booleanPermission[props.modules[i].id].toString();
            }
        }

        function handleAdminPanelAccess(){
            form.can_access_admin_panel = !form.can_access_admin_panel;
        }
        return {
            handleUserMultiSelect,
            handleAdminPanelAccess,
            handleSubmit,
            toogleSelectAll,
            form,
            booleanPermission,
            selectAll,
            handleCancel,
            loading,
            success,

        }
    },
    computed: {
        breadcrumb() {

            return [
                {
                    label: trans('core__be_dashboard_label'),
                    url: route('admin.index')
                },
                {
                    label: trans('vendor_role_module'),
                    url: route('vendor_role.index')
                },
                {
                    label: trans('core__be_edit_vendor_role'),
                    color: "text-primary-500"
                }
            ]

        }
    }
})
</script>
