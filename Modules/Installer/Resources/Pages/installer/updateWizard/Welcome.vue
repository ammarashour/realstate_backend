<template>
    <div>
        <UpdaterLayout title="welcome_to_the_updater_wizard">
            <template v-slot:container>
                <p class="paragraph text-center">
                    {{ $t('welcome_to_the_updater_wizard_desc') }}
                </p>
                <div class="buttons">
                    <ps-button @click="toOverview()" class="button" type="reset" padding="px-7 py-2" rounded="rounded" hover="">{{ $t('let_s_start') }}</ps-button>
                    <!-- <Button :href="route('NextLaravelUpdater::overview')" class="button">{{ $t('installer_messages.next') }}</Button> -->
                </div>
            </template>

        </UpdaterLayout>
        <ps-loading-circle-dialog ref="ps_loading_circle_dialog" />
        <ps-error-dialog-with-link ref="ps_error_dialog_with_link" />


    </div>
</template>

<script>
    import { defineComponent, computed, onMounted, onUnmounted, ref } from "vue";
    import UpdaterLayout from '@/Layouts/UpdaterLayout.vue';
    import PsButton from "@/Components/Core/Buttons/PsButton.vue";
    import PsLoadingCircleDialog from '@/Components/Core/Dialog/PsLoadingCircleDialog.vue';
    import PsErrorDialogWithLink from "@/Components/Core/Dialog/PsErrorDialogWithLink.vue";
    import { Link } from '@inertiajs/vue3';
    import { trans } from 'laravel-vue-i18n';
    import { router } from '@inertiajs/vue3';

    export default defineComponent({
        components: {
            UpdaterLayout,
            Link,
            PsButton,
            PsLoadingCircleDialog,
            PsErrorDialogWithLink,
        },
        props:['errMsg', 'docLink', 'errType'],
        setup(props) {

            const ps_loading_circle_dialog = ref();
            const ps_error_dialog_with_link = ref();
            const url = props.docLink;

            function toOverview(){
                router.get(route("NextLaravelUpdater::sourceCode"),{},{
                    onBefore: () => {
                        // ps_loading_circle_dialog.value.openModal(trans('core__be_importing_title'),trans('core__be_importing_note'));
                    },
                    onSuccess: (res) => {
                        ps_loading_circle_dialog.value.closeModal();
                        //
                    },
                    onError: () => {
                        ps_loading_circle_dialog.value.closeModal();
                    },
                });
            }

            function checkError(errorType){
                if(errorType == 'proc_open'){
                    ps_error_dialog_with_link.value.openModal(
                        trans('proc_open has been disabled.'),
                        props.errMsg,
                        trans('Continue'),
                        ()=> {
                            router.get(route("NextLaravelUpdater::sourceCode"))
                        },
                        true, url,
                        trans('How to manually update composer'));
                }else{
                    ps_error_dialog_with_link.value.openModal(trans('incompatible_php_version'), props.errMsg,trans('core__be_btn_ok'), ()=> {}, false, url, trans('how_to_change_php_path'));
                }
            }

            // onMounted(() => {
            //     var loading = document. getElementById("home_loading__container");
            //     loading.style.display = "none";
            // });

            return {
                toOverview,
                ps_loading_circle_dialog,
                ps_error_dialog_with_link,
                checkError
            }
        },
        mounted() {
            if(this.errMsg){
                this.checkError(this.errType);
            }
        }
    });
</script>

<style lang="scss" scoped>

</style>
