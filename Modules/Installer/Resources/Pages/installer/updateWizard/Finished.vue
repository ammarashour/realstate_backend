<template>
    <UpdaterLayout message=message title="installer_messages.updater.final.title">
        <template v-slot:container>
            <p v-if="message" class="paragraph text-center">{{ $t(message['message']) }}</p>
            <div class="buttons">
                <ps-button @click="toAddNewLangString()" class="button" type="reset" padding="px-7 py-2" rounded="rounded" hover="">{{ $t('installer_messages.next') }}</ps-button>
                <!-- <Button :href="route('NextLaravelUpdater::overview')" class="button">{{ $t('installer_messages.next') }}</Button> -->
            </div>
        </template>

    </UpdaterLayout>
    <ps-loading-circle-dialog ref="ps_loading_circle_dialog" />
</template>

<script>
    import { defineComponent, computed, onMounted, onUnmounted, ref } from "vue";
    import UpdaterLayout from '@/Layouts/UpdaterLayout.vue';
    import PsButton from "@/Components/Core/Buttons/PsButton.vue";
    import PsLoadingCircleDialog from '@/Components/Core/Dialog/PsLoadingCircleDialog.vue';
    import { Link } from '@inertiajs/vue3';
    import { trans } from 'laravel-vue-i18n';
    import { router } from '@inertiajs/vue3';

    export default defineComponent({
        components: {
            UpdaterLayout,
            Link,
            PsButton,
            PsLoadingCircleDialog,
        },
        props: ['message'],
        setup(props) {

            const ps_loading_circle_dialog = ref();


            function toAddNewLangString(){
                router.get(route("NextLaravelUpdater::addNewLangString"),{},{
                    onBefore: () => {
                        ps_loading_circle_dialog.value.openModal(trans('core__be_importing_title'),trans('core__be_importing_note'));
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

            // onMounted(() => {
            //     var loading = document. getElementById("home_loading__container");
            //     loading.style.display = "none";
            // });

            return {
                toAddNewLangString,
                ps_loading_circle_dialog
            }
        }
    });
</script>

<style lang="scss" scoped>

</style>
