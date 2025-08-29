import { reactive,ref } from 'vue';
import CategoryListParameterHolder from '@templateCore/object/holder/CategoryListParameterHolder';
import PsApiService from '@templateCore/api/PsApiService';
import Category from '@templateCore/object/Category';
import PsResource from '@templateCore/api/common/PsResource';
import { defineStore } from 'pinia'
import { store } from '@templateCore/store/modules/core/PsStore';
import makeSeparatedStore from '@templateCore/store/modules/core/PsSepetetedStore';
import { router, useForm } from '@inertiajs/vue3';
import { route } from 'ziggy-js';

export const useImageStoreState = makeSeparatedStore((key: string) =>
    defineStore(`imageStore/${key}`,
    () => {

        function replaceImageInertia(id, imageFile, callback?: (status) => void){ 
            
            const imageForm = new FormData();
            imageForm.append('image', imageFile);
            imageForm.append('_method', 'put');

            router.post(route("image.replace", id), imageForm, {
                forceFormData: true,
                onBefore: () => {
                    if(callback) {
                        callback('before');
                    }
                },
                onSuccess: (data) => {
                    const status = (data.props as any).status; 
    
                    if(callback) {
                        if(status.flag == 'danger'){
                            callback('error');
                        }else {
                            callback('success');
                        }
                    }  
                },
                onError: () => {
                    if(callback) {
                        callback('error');
                    }
                },
            });
        }

        function deleteImageInertia(id, callback?: (status) => void){ 
            router.delete(route("image.destroy", id), {
                onBefore: () => {
                    if(callback) {
                        callback('before');
                    }
                },
                onSuccess: (data) => {
                    const status = (data.props as any).status; 
    
                    if(callback) {
                        if(status.flag == 'danger'){
                            callback('error');
                        }else {
                            callback('success');
                        }
                    }  
                },
                onError: () => {
                    if(callback) {
                        callback('error');
                    }
                },
            });
        }

        return{
            replaceImageInertia,
            deleteImageInertia
        }

    }),
);


// Need to be used outside the setup
export function useImageStoreStateWithout() {
    return useImageStoreState(store);
  }


