<template>
    <div>
        <UpdaterLayout title="installer_messages.updater.addNewFeLangString.title">
            <template v-slot:container>
                <p class="paragraph text-center">
                    {{ $t('installer_messages.updater.addNewFeLangString.message2') }}
                </p>

                <div class="" style="margin-bottom: 5px">
                    <!-- <form action="{{ route('NextLaravelUpdater::addNewFeLangString') }}" enctype="multipart/form-data" method="post" style="display: flex; justify-content: space-between;">
                        <div class="">
                            <label for="">{{ $t('import_lang_csv_zip_file') }}</label>
                            <input type="file" name="csvFileZip">
                        </div>
                        <div class="buttons">
                            <button class="button" style="font-size:12px;">{{ $t('import') }}</button>
                        </div>
                    </form> -->
                </div>

                <div class="buttons">
                    <ps-button @click="toAddNewMobileLangString()" class="button" type="reset" padding="px-7 py-2"
                        rounded="rounded" hover="">{{ $t('btn_update') }}</ps-button>
                </div>
            </template>
        </UpdaterLayout>

        <ps-error-dialog ref="ps_error_dialog" />
        <ps-success-dialog ref="ps_success_dialog" />
        <ps-loading-circle-dialog ref="ps_loading_circle_dialog" />

    </div>
</template>

<script>
import { defineComponent, computed, onMounted, onUnmounted, ref } from "vue";
import UpdaterLayout from '@/Layouts/UpdaterLayout.vue';
import PsButton from "@/Components/Core/Buttons/PsButton.vue";
import PsLoadingCircleDialog from '@/Components/Core/Dialog/PsLoadingCircleDialog.vue';
import { Link } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';
import { router } from '@inertiajs/vue3';
import { usePage } from '@inertiajs/vue3'
import PsErrorDialog from '@/Components/Core/Dialog/PsErrorDialog.vue';
import PsSuccessDialog from '@/Components/Core/Dialog/PsSuccessDialog.vue';



export default defineComponent({
    components: {
        UpdaterLayout,
        Link,
        PsButton,
        PsLoadingCircleDialog,
        PsErrorDialog,
        PsSuccessDialog,
    },
    setup(props) {

        const ps_error_dialog = ref();
        const ps_success_dialog = ref();

        const ps_loading_circle_dialog = ref();

        let go_next = usePage().props.logMessages

        if(go_next=='fe_lang_sync_success'){
            router.get(route("NextLaravelUpdater::addNewMobileLangString"));
        }


        function toAddNewMobileLangString() {
            router.post(route("NextLaravelUpdater::addNewFeLangStringStore"), {}, {
                onBefore: () => {
                    ps_loading_circle_dialog.value.openModal(trans('core__be_importing_title2_be'),trans('core__be_importing_note2_fe'));
                },
                onSuccess: (res) => {
                    if(usePage().props.logMessages == 'fe_lang_sync_success'){
                        router.get(route("NextLaravelUpdater::addNewMobileLangString"));
                    }
                },
                onError: (errors) => {
                    ps_loading_circle_dialog.value.closeModal();
                    ps_error_dialog.value.openModal(trans('ps_error_dialog__error'), trans(errors.message), 'OK');
                },
            });
        }

        // onMounted(() => {
        //     var loading = document. getElementById("home_loading__container");
        //     loading.style.display = "none";
        // });

        return {
            toAddNewMobileLangString,
            ps_loading_circle_dialog,
            ps_error_dialog,
            ps_success_dialog,
            go_next
        }
    }
});
</script>

<style lang="scss" scoped></style>
