<template>
    <ps-modal ref="psmodal" :isClickOut='false' :maxWidth="'405px'" :bodyHeight="'100px'" theme="p-6 rounded-md -z-10">
        <template #title>
            <!-- Item entry title -->
            <div class="flex justify-between">
                <ps-label-header-6> {{ $t('chat_make_a_booking') }}</ps-label-header-6>
                <ps-icon name="close" class="text-feAchromatic-400 hover:cursor-pointer"
                    @click.prevent="psmodal.toggle(false)"></ps-icon>
            </div>
        </template>
        <template #body>

            <!-- Start Input Field for md .. -->
            <div class="flex justify-between container w-full h-auto">
                <!-- Start Left Screen -->
                <div class="flex flex-col w-full">
                    <div
                        class="flex flex-row w-full justify-center bg-feAchromatic-50 rounded-md mb-4 dark:bg-feAchromatic-900">

                        <div class="my-4">
                            <img alt="Placeholder" width="300px" height="300px"
                                class="rounded-md w-[120px] h-[120px] flex items-center justify-center object-contail"
                                v-lazy="{ src: $page.props.thumb2xUrl + '/' + itemImage, loading: $page.props.sysImageUrl + '/loading_gif.gif', error: $page.props.sysImageUrl + '/default_photo.png' }">
                        </div>
                        <div class="ms-6 my-auto">
                            <ps-label class="text-medium text-lg " textColor="font-semibold text-fePrimary-500"> {{
                                itemName.length > 20 ? itemName.slice(0, 15) + '....' : itemName }} </ps-label>
                            <ps-label class="font-normal text-lg"> {{ itemCategory }} </ps-label>
                            <ps-label class='font-normal text-xs' textColor="text-feAchromatic-500"> {{ currency }} {{
                                formatPrice(itemPrice) }} </ps-label>
                        </div>

                    </div>
                    <div class="flex flex-row w-full">
                    </div>
                    <!-- Choose Date And Time -->
                    <ps-label class="font-medium text-sm lg:text-base mb-2 pl-2"> {{ $t('chat_pick_date_to_book') }}
                    </ps-label>
                    <div class="flex ml-2 mr-12 mb-6">
                        <ps-date-picker class="w-50" ref="startDate" :pickedDateProps="pickedDate" />

                        <ps-time-picker class="bg-fePrimary-50 dark:bg-feAchromatic-800" v-model:hour="startTimeH"
                            v-model:min="startTimeM" v-model:ampm="startTimeAmpm" />
                    </div>

                </div>
                <!-- End Left Screen -->
            </div>
            <!-- End Input Field -->
        </template>
        <template #footer>
            <div class="grid grid-cols-4 gap-6">
                <ps-button @click="submitClicked()" textSize="text-xxs lg:text-sm" class="col-span-2"> {{
                    $t("chat_book") }}</ps-button>
                <ps-button @click="psmodal.toggle(false)" textSize="text-xxs lg:text-sm" class="col-span-2"
                    colors="bg-feAchromatic-50 dark:bg-feAchromatic-800 dark:text-feAchromatic-200 hover:text-feAchromatic-50"
                    border="border border-feAchromatic-300 dark:border-feAchromatic-500"> {{ $t("offer_modal__cancel") }}
                </ps-button>
            </div>
        </template>

    </ps-modal>

    <ps-loading-dialog ref="ps_loading_dialog" :isClickOut='false' />

    <ps-error-dialog ref="ps_error_dialog" />
</template>

<script lang='ts'>

// Libs
import { defineComponent, onMounted, ref } from 'vue';
// Compone
import PsModal from '@template1/vendor/components/core/modals/PsModal.vue';
import PsLabelHeader6 from '@template1/vendor/components/core/label/PsLabelHeader6.vue';
import PsTimePicker from '@template1/vendor/components/core/picker/PsTimePicker.vue';
import PsDatePicker from '@template1/vendor/components/core/picker/PsDatePicker.vue';
import PsLabel from '@template1/vendor/components/core/label/PsLabel.vue';
import PsInput from '@template1/vendor/components/core/input/PsInput.vue'
import PsButton from '@template1/vendor/components/core/buttons/PsButton.vue';
import PsErrorDialog from '@template1/vendor/components/core/dialog/PsErrorDialog.vue';
import PsLoadingDialog from '@template1/vendor/components/core/dialog/PsLoadingDialog.vue';
import PsIcon from '@template1/vendor/components/core/icons/PsIcon.vue';
import { useGetChatHistoryStoreState } from "@templateCore/store/modules/chat/GetChatHistoryStore";
import PsDropdown from '@template1/vendor/components/core/dropdown/PsDropdown.vue';
import ItemEntryParameterHolder from '@templateCore/object/holder/ItemEntryParameterHolder';
import PsInputWithLeftIcon from '@template1/vendor/components/core/input/PsInputWithLeftIcon.vue';
import PsLabelCaption from '@template1/vendor/components/core/label/PsLabelCaption.vue';
import format from 'number-format.js';
import { trans } from 'laravel-vue-i18n';
import { PsValueStore } from "@templateCore/store/modules/core/PsValueStore";
import { usePsAppInfoStoreState } from '@templateCore/store/modules/appinfo/AppInfoStore'
import PsUtils from '@templateCore/utils/PsUtils';


export default defineComponent({
    name: "BookingModal",
    components: {
        PsModal,
        PsLabelHeader6,
        PsLabel,
        PsButton,
        PsErrorDialog,
        PsLoadingDialog,
        PsInput,
        PsIcon,
        PsDropdown,
        PsInputWithLeftIcon,
        PsLabelCaption,
        PsDatePicker,
        PsTimePicker
    },
    props: {
        price: {
            type: String,
            default: '0'
        }
    },
    emits: ['submit'],
    setup(props, context) {
        const psmodal = ref();
        const title = ref('');
        const message = ref('');
        const ps_loading_dialog = ref();
        const ps_error_dialog = ref();
        const itemId = '';
        const pickedDate = ref(new Date());
        const startDate = ref();
        const startTimeH = ref('0');
        const startTimeM = ref('0');
        const startTimeAmpm = ref('1');
        let itemName = ref('');
        let itemImage = ref('');
        let itemCategory = ref('');
        let currency = ref('');
        const buyerUserId = '';
        const sellerUserId = '';

        let itemPrice = ref('');
        // Init Provider
        const appInfoStore = usePsAppInfoStoreState();
        const chatHistoryProvider = useGetChatHistoryStoreState();
        const psValueStore = PsValueStore();
        const loginUserId = psValueStore.getLoginUserId();
        const paramHolder1 = ref(new ItemEntryParameterHolder());

        function openModal(itemNameParam, itemCategoryParam, itemImageNameParam, currencyParam, priceParam) {

            itemName.value = itemNameParam;
            itemCategory.value = itemCategoryParam;
            itemImage.value = itemImageNameParam;
            currency.value = currencyParam;
            itemPrice.value = priceParam;
            psmodal.value.toggle(true);

        }

        function submitClicked() {
            const day = parseInt(startDate.value.pickedDate.getDate(), 10);
            const month = parseInt(startDate.value.pickedDate.getMonth(), 10);
            const year = parseInt(startDate.value.pickedDate.getFullYear(), 10);
            let h = parseInt(startTimeH.value, 10);
            const m = parseInt(startTimeM.value, 10);
            const s = 0;
            if(startTimeAmpm.value == '1') {
                h = parseInt(startTimeH.value, 10);
            } else {
                h = parseInt(startTimeH.value, 10) + 12;
            }

            const selectedDate = new Date(year, month, day, h, m, s ).getTime();
            const startDateTimeStr = PsUtils.timeStampToDateStringWithPeriod(selectedDate);

            context.emit('submit', startDateTimeStr);
            psmodal.value.toggle(false);
        }

        function formatPrice(value) {
            if (value.toString() == '0') {
                return trans('item_price__free');
            } else {
                return PsUtils.formatPrice(appInfoStore, value);
            }
        }

        return {
            psmodal,
            openModal,
            itemId,
            itemName,
            itemCategory,
            itemImage,
            currency,
            buyerUserId,
            sellerUserId,
            itemPrice,
            title,
            message,
            submitClicked,
            ps_loading_dialog,
            ps_error_dialog,
            chatHistoryProvider,
            paramHolder1,
            formatPrice,
            pickedDate,
            startDate,
            startTimeH,
            startTimeM,
            startTimeAmpm
        }
    },
})
</script>
