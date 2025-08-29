<template>
    <div>
        <UpdaterLayout title="code_update">
            <template v-slot:container>
                <p class="paragraph text-center">
                    {{ $t('code_update_desc') }}
                </p>
                <div class="buttons">
                    <ps-button v-if="isGoNext" @click="handleCodeSync()" class="button" type="reset" padding="px-7 py-2" rounded="rounded" hover="">{{ $t('go_next') }}</ps-button>
                    <ps-button v-else @click="handleCodeSync()" class="button" type="reset" padding="px-7 py-2" rounded="rounded" hover="">{{ $t('code_sync') }}</ps-button>
                    <!-- <a href="{{ route('NextLaravelUpdater::database') }}" class="button">{{ $t('installer_messages.updater.overview.install_updates') }}</a> -->
                </div>
            </template>

        </UpdaterLayout>
        <ps-loading-circle-dialog ref="ps_loading_circle_dialog" />
        <ps-error-dialog ref="ps_error_dialog" />
        <ps-success-dialog ref="ps_success_dialog" />
        <ps-reload-dialog ref="ps_reload_dialog" />

    </div>
</template>

<script>
    import { defineComponent, computed, onMounted, onUnmounted, ref } from "vue";
    import UpdaterLayout from '@/Layouts/UpdaterLayout.vue';
    import PsButton from "@/Components/Core/Buttons/PsButton.vue";
    import PsLoadingCircleDialog from '@/Components/Core/Dialog/PsLoadingCircleDialog.vue';
    import PsErrorDialog from '@/Components/Core/Dialog/PsErrorDialog.vue';
    import PsSuccessDialog from '@/Components/Core/Dialog/PsSuccessDialog.vue';
    import PsReloadDialog from '@/Components/Core/Dialog/PsReloadDialog.vue';
    import { Link } from '@inertiajs/vue3';
    import { trans } from 'laravel-vue-i18n';
    import { router } from '@inertiajs/vue3';
    import { usePage } from '@inertiajs/vue3'


    export default defineComponent({
        components: {
            UpdaterLayout,
            Link,
            PsButton,
            PsLoadingCircleDialog,
            PsErrorDialog,
            PsSuccessDialog,
            PsReloadDialog
        },
        props: ['status'],
        setup(props) {

            const ps_loading_circle_dialog = ref();
            const ps_error_dialog = ref();
            const ps_success_dialog = ref();
            const ps_reload_dialog = ref();
            const isGoNext = ref(0);
            const queryString = window.location.search;
            const urlParams = new URLSearchParams(queryString);
            if(urlParams.has('next')){
                router.get(route("NextLaravelUpdater::addNewLangString"));
            }

            function handleCodeSync(){

                // axios.post(route("NextLaravelUpdater::sourceCodeSync"))
                // .then(response => {
                //     if (!response.data) {
                //         ps_loading_circle_dialog.value.openModal(trans('core__be_importing_title'),trans('core__be_importing_note'));
                //     } else {
                //         console.log(response.data);
                //     }
                // });

                router.post(route("NextLaravelUpdater::sourceCodeSync"),{},{
                    onBefore: () => {
                        ps_loading_circle_dialog.value.openModal(trans('core__be_source_code_title'),trans('core__be_source_code_syncing_note'));
                    },
                    onSuccess: (res) => {
                        console.log(usePage().props.status);
                        if(props.status.flag == "success"){
                            location.reload(true);
                            isGoNext.value = 1;
                            ps_loading_circle_dialog.value.closeModal();
                            // ps_success_dialog.value.openModal(trans('ps_success_dialog__success'),trans(props.status.msg), 'OK');
                        }
                    },
                    onError: (errors) => {
                        ps_loading_circle_dialog.value.closeModal();
                        ps_reload_dialog.value.openModal(trans('ps_error_dialog__error'),trans(errors.message), 'Reload',
                            // () => {
                            //     location.reload();
                            // },
                            'NextLaravelUpdater::sourceCode',
                            false, 'alert-triangle'
                        );
                    },
                });
            }

            // onMounted(() => {
            //     var loading = document. getElementById("home_loading__container");
            //     loading.style.display = "none";
            // });

            return {
                handleCodeSync,
                ps_loading_circle_dialog,
                ps_error_dialog,
                isGoNext,
                ps_success_dialog,
                ps_reload_dialog
            }
        }
    });
</script>

<style lang="scss" scoped>

</style>
