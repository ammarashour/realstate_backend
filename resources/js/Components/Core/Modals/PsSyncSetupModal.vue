<template>

    <ps-modal ref="psmodal" maxWidth="552px" bodyHeight="100%" line="hidden" :isClickOut='false' theme=" px-4 py-4 rounded-lg shadow-xl" class=' z-20'>

        <template #title>
            <!-- <div class="flex justify-end mb-3">
                <ps-icon v-if="isClickOut" name="cross" theme="text-secondary-400" />
            </div> -->

            <ps-label class="font-medium text-xl lg:text-2xl px-4"> {{$t(title)}} </ps-label>

        </template>
        <template #body>
            <div class="w-full mt-6 pt-4">
                <div class="mb-4">
                        <ps-label class="text-base mb-1" >
                            <!-- <ps-label class="text-red-800 font-medium me-1">*</ps-label> -->
                            {{ $t('setup_connection_with_builder__label') }}
                        </ps-label>
                        <ps-input-with-right-icon type="text" disabled v-model:value="form.token" :placeholder="$t('setup_connection_with_builder__enter_token')" ref="token">
                            <template #icon >
                                <!-- <ps-icon @click="copyToken()"  name="clipboard-icon" v-if="!tokenCopied"/>
                                <ps-icon name="check" v-else/> -->
                                <ps-icon :name="copied?'clipboard-icon':'check'" w="24" h="24" @click="copyToken(form.token)"/>
                            </template>
                        </ps-input-with-right-icon>
                </div>
                <!-- alert start   -->
                <div class="p-4 bg-red-100 border border-red-200 rounded-md" v-if="show_error">
                    <div class="flex justify-between flex-wrap">
                        <div class="w-0 flex-1 flex">
                            <div class="mr-3 pt-1">
                                <ps-icon name="error-icon" theme="text-red-500"/>
                            </div>
                            <div>
                                <h4 class="text-md leading-6 font-medium">
                                    {{ $t('setup_connection_with_builder__auto_sync_error_title') }}
                                </h4>
                                <p class="text-sm">
                                    {{ show_error }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-4 bg-red-100 border border-red-200 rounded-md" v-if="!show_error && reconnect">
                    <div class="flex justify-between flex-wrap">
                        <div class="w-0 flex-1 flex">
                            <div class="mr-3 pt-1">
                                <ps-icon name="error-icon" theme="text-red-500"/>
                            </div>
                            <div>
                                <h4 class="text-md leading-6 font-medium">
                                    {{ $t('setup_connection_with_builder__error_title') }}
                                </h4>
                                <p class="text-sm">
                                    {{ $t('setup_connection_with_builder__error_dialog') }}
                                </p>
                                <div class="flex mt-3">
                                    <a target="_blank" href="https://www.products.panacea-soft.co/psx-builder-qa/project" class="underline text-indigo-500">
                                        {{ $t('navigate_to_builder') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-4 bg-green-100 border border-red-200 rounded-md" v-if="!show_error && connect">
                    <div class="flex justify-between flex-wrap">
                        <div class="w-0 flex-1 flex">
                            <div class="mr-3 pt-1">
                                <ps-icon name="success-icon" theme="text-green-500"/>
                            </div>
                            <div>
                                <h4 class="text-md leading-6 font-medium">
                                    {{ $t('setup_connection_with_builder__success_title') }}
                                </h4>
                                <p class="text-sm">
                                    {{ $t('setup_connection_with_builder__success_dialog') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- alert end -->
                <div class="mt-3">
                       <ps-button  @click="checkConnection" class="mb-0.5" padding="px-3 py-2" v-if="!upgrade && !reconnect">
                           <!-- <ps-icon name="plus" class="mx-0.5 font-semibold" /> -->
                           <ps-label textColor="text-white">{{ $t('setup_connection_with_builder__connect') }}</ps-label>
                       </ps-button>

                       <ps-button  @click="checkConnection" class="mb-0.5" padding="px-3 py-2" v-if="!upgrade && reconnect">
                           <!-- <ps-icon name="plus" class="mx-0.5 font-semibold" /> -->
                           <ps-label textColor="text-white">{{ $t('setup_connection_with_builder__reconnect') }}</ps-label>
                       </ps-button>

                       <ps-button  @click="setupClicked" class="mb-0.5" padding="px-3 py-2" v-if="upgrade && connect">
                           <!-- <ps-icon name="plus" class="mx-0.5 font-semibold" /> -->
                           <ps-label textColor="text-white">{{ $t('setup_connection_with_builder__upgrade') }}</ps-label>
                       </ps-button>
                </div>



                <div class="mb-6 mt-4">
                    <!-- <div class="mb-2">
                        <Link @click="generateApiKey()" class="text-base underline text-primary-500 dark:text-secondaryDark-black">{{ $t('builder_token__generate_here') }}</Link>
                        or
                        <Link :href="route('api_token.index')" class="text-base underline text-primary-500 dark:text-secondaryDark-black">{{ $t('builder_token__create_here') }}</Link>
                    </div> -->
                    <ps-label class="text-base mb-3" >
                        {{ $t("guide_link") }}
                    </ps-label>
                    <div class="mb-2">
                        <a target="_blank" href="https://www.docs.panacea-soft.com/psx-mpc/faqs/web/how-to-solve-connectivity-error-with-psx-builder" class="underline text-indigo-500">
                            {{ $t('how_to_connect_with_psx_builder') }}
                        </a>
                    </div>
                </div>
            </div>
        </template>
        <template #footer>
        </template>
    </ps-modal>
    <ps-confirm-dialog ref="ps_confirm_dialog" />
    <ps-loading-circle-dialog ref="ps_loading_circle_dialog" />
    <!-- <ps-error-dialog ref="ps_error_dialog"/> -->
    <ps-success-dialog ref="ps_success_dialog"/>
</template>

<script lang="ts">
import { defineComponent,ref } from 'vue';
import PsModal from '../Modals/PsModal.vue';
import PsLoading from "../Loading/PsLoading.vue";
import PsLabel from '../Label/PsLabel.vue';
import PsButton from '../Buttons/PsButton.vue';
import { trans } from 'laravel-vue-i18n';
import PsIcon from "../Icons/PsIcon.vue";
import PsInput from "../Input/PsInput.vue";
import PsLabelCaption from "../Label/PsLabelCaption.vue";
import {useForm,Link, usePage} from "@inertiajs/vue3";
import PsBannerIcon from "@/Components/Core/Banners/PsBannerIcon.vue";
import { router } from '@inertiajs/vue3';
import PsConfirmDialog from '@/Components/Core/Dialog/PsConfirmDialog.vue';
import PsLoadingCircleDialog from '@/Components/Core/Dialog/PsLoadingCircleDialog.vue';
import PsErrorDialog from '@/Components/Core/Dialog/PsErrorDialog.vue';
import PsInputWithRightIcon from "../Input/PsInputWithRightIcon.vue";
import PsSuccessDialog from '@/Components/Core/Dialog/PsSuccessDialog.vue';


export default defineComponent ({
    name: 'PsLicenseActivateModal',
    components : {
        PsModal,
        PsLabel,
        PsButton,
        PsIcon,
        PsInput,
        PsLabelCaption,
        PsBannerIcon,
        PsConfirmDialog,
        PsLoadingCircleDialog,
        PsLoading,
        PsErrorDialog,
        Link,
        PsInputWithRightIcon,
        PsSuccessDialog
    },
    props: ['project','autoSyncStatus','autoSyncMessage'],
    setup(props) {
        const psmodal = ref();
        const title = ref(trans('ps_success_dialog__success'));
        const message = ref(trans('ps_success_dialog__success_message'));
        const okButton = ref(trans('ps_confirm_dialog__yes'));
        const ps_confirm_dialog = ref();
        const ps_loading_circle_dialog = ref();
        let okClicked: Function;
        const showIcon = ref(true);
        let isClickOut = ref(true);
        let visible = ref(false);
        const ps_error_dialog = ref();
        const ps_success_dialog = ref();
        const show_error = ref('');

        const upgrade = ref(false);
        const connect = ref(false);
        const reconnect = ref(false);

        const copied = ref(true);


        function actionClicked() {
            okClicked();
            psmodal.value.toggle(false);
        }

        function close() {
            psmodal.value.toggle(false);
        }

        function openModal(
            titleString,
            messageString,
            okString,
            okClickedAction: Function,showIconBol = true , errorMessage = null ) {
            title.value = titleString;
            message.value = messageString.toString();
            okButton.value = okString;
            psmodal.value.toggle(true);
            okClicked = okClickedAction;
            showIcon.value = showIconBol;
            if(errorMessage){
                show_error.value = errorMessage;
            }
        }

        console.log(props.project);

        let form = useForm({
            token: props.project.token,
        });

        function checkConnection(){
            if (form.token != ""){
                router.post(route('admin.dashboard.checkConnection'),form,{
                    onBefore:() => {
                        // ps_loading_circle_dialog.value.openModal(trans('creating_auto_sync'), trans('wait_msg'));
                    },
                    onSuccess: () => {
                        // alert('Hello');
                        connect.value = true;
                        upgrade.value = true;
                        reconnect.value = false;
                        setupClicked();
                    },
                    onError: () => {
                        reconnect.value = true;
                    },
                })
            }
        }


        function setupClicked(){
            if (form.token != ""){
                router.post(route('admin.dashboard.setupAutoSync'),form,{
                    onBefore:() => {
                        ps_loading_circle_dialog.value.openModal(trans('setup_connection_with_builder__upgrading'), trans('setup_connection_with_builder__wait_msg'));
                        close();
                    },
                    onSuccess: () => {
                        ps_loading_circle_dialog.value.closeModal();
                    },
                    onError: () => {
                        ps_loading_circle_dialog.value.closeModal();
                    },
                })
            }
        }

        function generateApiKey(){
            let form1 = useForm({
                name : "defaultBuilderToken",
                permissions: ["builderToken"],
            });

            if(form.token == null || form.token == ''){
                router.post(route('api_token.default_creating_token'),form1,{
                    onSuccess:(res) => {
                        form.token = !res.props.defaultBuilderToken ? "" : res.props.defaultBuilderToken;
                        console.log(form.token);
                        psmodal.value.toggle(false);
                        setTimeout(() => {
                            psmodal.value.toggle(true);
                        }, 1000);
                    },
                });

            }

        }

        async function copyToken(value) {
            copied.value = false;
            await navigator.clipboard.writeText(value);
            setInterval(()=>{
                copied.value = true;
            }, 1000);
        }


        return {
            isClickOut,
            psmodal,
            title,
            message,
            openModal,
            actionClicked,
            okButton,
            close,
            showIcon,
            visible,
            ps_confirm_dialog,
            setupClicked,
            generateApiKey,
            ps_loading_circle_dialog,
            ps_error_dialog,
            form,
            upgrade,
            reconnect,
            copied,
            copyToken,
            checkConnection,
            connect,
            ps_success_dialog,
            show_error
        }
    },
    mounted(){
        if(localStorage.setupAutoSync == 0){
            this.generateApiKey();
        }
    }
})
</script>
