import { reactive,ref } from 'vue';

import ShippingAndBillingParameterHolder from '@templateCore/object/holder/ShippingAndBillingParameterHolder';
import PsApiService from '@templateCore/api/PsApiService';
import PsResource from '@templateCore/api/common/PsResource';
import { defineStore } from 'pinia'
import makeSeparatedStore from '@templateCore/store/modules/core/PsSepetetedStore';
import Order from '../../../object/Order';
import OrderSummary from '../../../object/OrderSummary';

export const useVendorCheckoutStoreState = makeSeparatedStore((key: string) =>
    defineStore(`vendorCheckoutStore/${key}`,
        () => {

            const order = reactive<PsResource<Order>>(new PsResource());
            const loading = reactive({
                value: false
            });
            const orderSummary = reactive<PsResource<OrderSummary>>(new PsResource());

            let paramHolder = reactive<ShippingAndBillingParameterHolder>(new ShippingAndBillingParameterHolder());

            async function loadOrder(loginUserId: string, holder: ShippingAndBillingParameterHolder) {

                loading.value = true;

                const responseData = await PsApiService.postShippingAndBilling<Order>(new Order(), loginUserId, holder.toMap());
                console.log(responseData);
                order.data = responseData.data;
                order.code = responseData.code;
                order.status = responseData.status;
                order.message = responseData.message;
                loading.value = false;

                return order;

            }

            async function loadOrderSummary(orderId : string ,loginUserId :string) {
                loading.value = true;
                const responseData = await PsApiService.getOrderSummary<OrderSummary>(new OrderSummary(), orderId, loginUserId);
                orderSummary.data = responseData.data;
                orderSummary.code = responseData.code;
                orderSummary.status = responseData.status;
                orderSummary.message = responseData.message;
                loading.value = false;

                return orderSummary;
            }

            return {
                orderSummary,
                paramHolder,
                order,
                loadOrder,
                loadOrderSummary,
                loading,
            }

        }),
);
