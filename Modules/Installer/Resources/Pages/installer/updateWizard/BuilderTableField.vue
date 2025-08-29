<template>
    <div>
        <UpdaterLayout title="sync_tables_and_fields">
            <template v-slot:container>
                <p class="paragraph text-center">
                    {{ $t('sync_tables_and_fields_desc') }}
                </p>
                <div class="buttons">

                    <ps-button v-if="isLoading" :disabled="true" class="button" type="reset" padding="px-7 py-2" rounded="rounded" hover="">{{ $t('core__be_loading') }}</ps-button>
                    <ps-button v-else-if="isGoNext" @click="goToDashboard()" class="button" type="reset" padding="px-7 py-2" rounded="rounded" hover="">{{ $t('go_next') }}</ps-button>
                    <ps-button v-else @click="handleFieldTableSync()" class="button" type="reset" padding="px-7 py-2" rounded="rounded" hover="">{{ $t('code_sync') }}</ps-button>
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
            const isLoading = ref(false);

            console.log(props.status);

            function openSuccessDialog(){
                ps_reload_dialog.value.openModal(trans('ps_success_dialog__update_process_complete'),trans(props.status.msg), 'Done',
                // ()=>{
                //     router.get(route('admin.index'));
                // },
                'admin.index',
                false, 'checkCircle');
            }

            function handleFieldTableSync(){

                router.post(route("NextLaravelUpdater::builderTableFieldSync"),{},{
                    onBefore: () => {
                        ps_loading_circle_dialog.value.openModal(trans('core__be_sync_table_title'),trans('core__be_table_field_syncing_note'));
                        isLoading.value = true;
                    },
                    onSuccess: (res) => {
                        console.log(props.status);
                        isLoading.value = false;
                        if(props.status.flag == "success"){
                            isGoNext.value = 1;
                            ps_loading_circle_dialog.value.closeModal();
                            isLoading.value = true;
                            openSuccessDialog();
                        }
                    },
                    onError: (errors) => {
                        isLoading.value = false;
                        ps_loading_circle_dialog.value.closeModal();
                        ps_error_dialog.value.openModal(trans('ps_error_dialog__error'),trans(errors.message), 'OK');
                    },
                });
            }



            // onMounted(() => {
            //     var loading = document. getElementById("home_loading__container");
            //     loading.style.display = "none";
            // });

            function goToDashboard(){
                isLoading.value = true;
                router.get(route('admin.index'));
            }

            return {
                goToDashboard,
                handleFieldTableSync,
                ps_loading_circle_dialog,
                ps_error_dialog,
                isGoNext,
                ps_success_dialog,
                ps_reload_dialog,
                openSuccessDialog,
                isLoading
            }
        },
        mounted(){
            // console.log("mounted" + this.status);
            if (this.status.flag == "success") {
                this.openSuccessDialog();
            }
        },
        beforeUpdate(){
            // console.log("beforeUpdate" + this.status);
        }

    });
</script>

<style lang="scss" scoped>

</style>
