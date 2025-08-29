<template>
    <div class="border rounded-lg">
        <div class="py-10 flex flex-col items-center justify-center bg-secondary-100">
            <div>
                <div class="mb-3 flex gap-3">
                    <ps-label textColor="text-base text-secondary-500 font-medium">{{$t("vendor_panel__price")}}</ps-label>
                    <ps-label v-if="plan?.is_most_popular_plan == 1" textColor="text-sm text-white font-medium bg-primary-500 px-2 py-[2px] rounded">{{$t("vendor_panel__most_popular")}}</ps-label>
                </div>
                <div v-if="plan?.discount_price == 'Unavailable'" class="flex items-end gap-2">
                    <ps-label textColor="lg:text-5xl text-primary-500 font-semibold">{{ plan.currency_symbol }}{{plan.sale_price}}</ps-label>
                </div>
                <div v-else class="flex items-end gap-2">
                    <ps-label textColor="lg:text-5xl text-primary-500 font-semibold">{{ plan.currency_symbol }}{{plan.discount_price}}</ps-label>
                    <ps-label textColor="text-base text-secondary-500 font-medium line-through">{{ plan.currency_symbol }}{{ plan.sale_price }}</ps-label>
                </div>
            </div>
        </div>
        <div class="px-4 py-8 flex flex-col items-center">
            <ps-label textColor="lg:text-2xl text-secondary-500 font-semibold">{{ plan.value }}</ps-label>
            <ps-label textColor="text-sm text-secondary-500 font-medium mt-3 mb-8">{{ plan.duration }}</ps-label>

            <ps-button @click="purchaseClick(plan.id, plan?.discount_price == 'Unavailable' ? plan.sale_price : plan?.discount_price, plan.currency_id)" rounded="rounded" colors="w-full" border="border" hover="hover:bg-primary-500 hover:text-white" >{{$t("vendor_panel__purchase")}}</ps-button>
        </div>
    </div>
</template>

<script>
import { ref, defineComponent } from "vue";
import PsButton from "@/Components/Core/Buttons/PsButton.vue";
import PsLabel from "@/Components/Core/Label/PsLabel.vue";

    export default defineComponent({
        name: "SubscriptionPlanHorizontalPlan",
        components: {
            PsButton,
            PsLabel,
        },
        props:{
            plan: Object,
        },
        setup(props, {emit}){
            function purchaseClick(id, price, currency){
                emit('purchaseClick', id, price, currency)
            }

            return {
                purchaseClick
            }
        },
    })
</script>

