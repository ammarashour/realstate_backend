
import { reactive, ref } from 'vue';

import PsApiService from '@templateCore/api/PsApiService';
import PsResource from '@templateCore/api/common/PsResource';
import DefaultPhoto from '@templateCore/object/DefaultPhoto';
import ApiStatus from '@templateCore/object/ApiStatus';
import PsUtils from '@templateCore/utils/PsUtils';
import PsApi from '@templateCore/api/common/PsApi';
import { defineStore } from 'pinia'

import makeSeparatedStore from '@templateCore/store/modules/core/PsSepetetedStore';


export const useGalleryStoreState = makeSeparatedStore((key: string) =>
    defineStore(`galleryUsStore/${key}`,
        () => {

            const galleryList = reactive<PsResource<DefaultPhoto[]>>(new PsResource());

            const loading = reactive({
                value: false
            });

            const isNoMoreRecord = reactive({
                value: false
            })

            let limit = ref(30);
            let offset: Number = 0;

            function updateGalleryList(responseData: PsResource<DefaultPhoto[]>) {

                if (galleryList != null
                    && galleryList.data != null
                    && galleryList.data.length > 0
                    && offset != 0) {

                    if (responseData.data != null && responseData.data.length > 0) {
                        if (responseData.data?.length < limit.value) {
                            isNoMoreRecord.value = true;
                        } else {
                            isNoMoreRecord.value = false;
                        }

                        galleryList.data.push(...responseData.data);
                    } else {

                        if (responseData.data?.length < limit.value || responseData.data == null) {
                            isNoMoreRecord.value = true;
                        } else {
                            isNoMoreRecord.value = false;
                        }

                        isNoMoreRecord.value = true;
                    }

                    galleryList.code = responseData.code;
                    galleryList.status = responseData.status;
                    galleryList.message = responseData.message;

                } else {

                    galleryList.data = responseData.data;
                    galleryList.code = responseData.code;
                    galleryList.status = responseData.status;
                    galleryList.message = responseData.message;


                }

                if (galleryList != null && galleryList.data != null) {
                    offset = galleryList.data.length;
                }

            }

            async function loadGalleryList(isEnableVideoSetting: string, loginUserId: string, imgParentId: string, imgType: string, videoThumbnail: DefaultPhoto) {

                loading.value = true;
                const responseData = await PsApiService.getImageList<DefaultPhoto>(new DefaultPhoto(), loginUserId, imgParentId, imgType, limit.value, offset);


                // if (isEnableVideoSetting != '1') {
                    responseData.data = responseData.data.filter(item => item.imgType != 'item-video' && item.imgType != 'item-video-icon');
                // }

                PsUtils.log(responseData);

                updateGalleryList(responseData);

                loading.value = false;

                return responseData;

            }

            async function resetData() {



                galleryList.data = [] ;

                loading.value=false;


                isNoMoreRecord.value=false;

                limit.value = 30;
                offset = 0;



            }

            async function resetGalleryList(loginUserId: String, imgParentId: string, imgType: string) {

                offset = 0;

                loading.value = true;

                const responseData = await PsApiService.getImageList<DefaultPhoto>(new DefaultPhoto(), loginUserId, imgParentId, imgType, limit.value, offset);

                updateGalleryList(responseData);
            }

            async function resetGalleryListProps(images: PsResource<DefaultPhoto[]>) {

                offset = 0;

                loading.value = true;

                const responseData = await PsApi.wrapDataFromProps(new DefaultPhoto(), images);

                updateGalleryList(responseData);

                loading.value = false;
            }

            async function deleteImage(imageId: string, loginUserId: string) {

                loading.value = true;

                const map = {};

                map['img_id'] = imageId;

                const responseData = await PsApiService.deleteImage<ApiStatus>(new ApiStatus(), limit.value, offset, loginUserId, map);

                loading.value = false;
                return responseData;


            }
            async function deleteVideo(imageId: string, loginUserId: string) {

                loading.value = true;

                const map = {};

                map['img_id'] = imageId;

                const responseData = await PsApiService.deleteVideo<ApiStatus>(new ApiStatus(), limit.value, offset, loginUserId, map);

                loading.value = false;
                return responseData;


            }

            async function postChatImageUpload(senderId: string,
                sellerUserId: string,
                buyerUserId: string,
                itemId: string,
                type: string,
                isUserOnlineFlag: string,
                imageFile: File) {

                loading.value = true;

                const returnData = await PsApiService.postChatImageUpload<DefaultPhoto>(new DefaultPhoto(), senderId, sellerUserId, buyerUserId, itemId, type, isUserOnlineFlag, imageFile);

                loading.value = false;

                return returnData;

            }

            async function postChatImageDelete(fileName: string, loginUserId: string) {
                loading.value = true;
                const map = {};

                map['file_name'] = fileName;
                const returnData = await PsApiService.postChatImageDelete<ApiStatus>(new ApiStatus(), loginUserId, map);

                loading.value = false;

                return returnData;
            }

            return {
                galleryList,
                loading,
                isNoMoreRecord,
                limit,
                offset,
                loadGalleryList,
                resetGalleryList,
                deleteImage,
                deleteVideo,
                postChatImageUpload,
                postChatImageDelete,
                resetData,
                resetGalleryListProps
            }



        }),
);
