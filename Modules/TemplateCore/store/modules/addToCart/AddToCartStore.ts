import { reactive,ref, computed } from 'vue';
import PsApiService from '@templateCore/api/PsApiService';
import ApiStatus from '@templateCore/object/ApiStatus';
import PsResource from '@templateCore/api/common/PsResource';
import Cart from '@templateCore/object/Cart';
import { defineStore } from 'pinia'
import makeSeparatedStore from '@templateCore/store/modules/core/PsSepetetedStore';
import AddToCartParameterHolder from '@templateCore/object/holder/AddToCartParameterHolder';
import DeleteItemsFromCartParameterHolder from '@templateCore/object/holder/DeleteItemsFromCartParameterHolder';

export const useAddToCartStoreState = makeSeparatedStore((key: string) =>
    defineStore(`addToCartStore/${key}`,
        () => {

            const loading = reactive({
                value: false
            });

            const cart = reactive<PsResource<Cart>>(new PsResource());

            async function getAllItemFromCart(loginUserId :string, isCheckoutPage : string) {
                loading.value = true;
                const responseData = await PsApiService.getAllItemFromCart<Cart>(new Cart(), loginUserId, isCheckoutPage);
                cart.data = responseData.code == 204 ? null : responseData.data;
                cart.code = responseData.code;
                cart.status = responseData.status;
                cart.message = responseData.message;
                loading.value = false;

                return cart;
            }

            async function addToCart(loginUserId: String, holder: AddToCartParameterHolder) {
                loading.value = true;

                const status = await PsApiService.postAddToCart<ApiStatus>(new ApiStatus(), loginUserId, holder.toMap());

                loading.value = false;

                return status;
            }

            async function deleteItemsFromCart(loginUserId: String, holder: DeleteItemsFromCartParameterHolder) {
                loading.value = true;

                const status = await PsApiService.postDeleteItemsFromCart<ApiStatus>(new ApiStatus(), loginUserId, holder.toMap());

                loading.value = false;

                return status;
            }

            // Computed property to calculate total quantity
            const totalQuantity = computed(() => {
                if (!cart.data) return 0; // If cart is empty, return 0
                return cart.data.items.reduce((total, item) => total + parseInt(item.quantity), 0);
            });

            return {
                cart,
                getAllItemFromCart,
                addToCart,
                deleteItemsFromCart,
                loading,
                totalQuantity
            }

        }),
);
