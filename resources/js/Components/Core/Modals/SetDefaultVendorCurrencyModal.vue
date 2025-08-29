<template>
    <ps-modal ref="psmodal" maxWidth="370px" line="hidden" :isClickOut='false' theme=" px-4 pt-8 pb-6 rounded-lg">
        <template #body>
            <div class="flex flex-col items-center gap-4">
                <ps-icon name="dollarCircle" w="72" h="72" viewBox="0 0 72 72" class="text-yellow-500 bg-white rounded-full"/>
                <ps-label textColor="text-secondary-800 text-2xl font-medium">{{ $t("vendor_panel__set_currency_title") }}</ps-label>
                <ps-label textColor="text-secondary-700 text-base text-center">{{ $t("vendor_panel__set_currency_desc") }}</ps-label>
                <ps-button @click="okClick">
                    {{ $t("vendor_panel__go_to_vendor_currency_setting") }}
                </ps-button>
            </div>
        </template>
    </ps-modal>
</template>

<script lang="ts">
import { defineComponent,ref } from 'vue';
import PsModal from '../Modals/PsModal.vue';
import PsLabel from '../Label/PsLabel.vue';
import PsButton from '../Buttons/PsButton.vue';
import { trans } from 'laravel-vue-i18n';
import PsIcon from '../Icons/PsIcon.vue';
import { router } from '@inertiajs/vue3';

export default defineComponent({
    name : "SetDefaultVendorCurrencyModal",
    props: {
        currencyId: { type: String, default: null },
    },
    components : {
        PsModal,
        PsLabel,
        PsButton,
        PsIcon
    },
    setup() {
        const psmodal = ref();

        function okClick() {
            psmodal.value.toggle(false);
            router.get(route('vendor_currency.index'));
        }

        function openModal() {
            psmodal.value.toggle(true);
        }

        return {
            psmodal,
            openModal,
            okClick,
        }
    },
})
</script>
