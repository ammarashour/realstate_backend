<template>
    <div :class="noti.isRead == '0' ? noti.type == PsConst.PUSH_NOTI ? 'bg-feSecondary-100' : 'bg-fePrimary-50 dark:bg-fePrimary-50' : 'bg-feSecondary-50'" class="w-full cursor-pointer h-[62px] sm:h-[100px]  dark:bg-feAchromatic-800 rounded" v-on:click="onClick != null ? onClick(noti) : null">
        <div class="flex flex-row justify-between h-[62px] sm:h-[100px]">
            <div class="flex-grow flex flex-row" @click="clickNoti(noti)">
                <div class="rounded-md bg-feAchromatic-50 dark:bg-feAchromatic-800">
                    <div class="sm:w-[100px] w-[62px] h-[62px] sm:h-[100px] ">
                        <img alt="Placeholder" width="15px" height="10px" class="w-[62px] h-[62px] sm:w-full sm:h-full rounded-md"
                        v-lazy=" { src: $page.props.thumb2xUrl + '/' + noti.senderCoverPhoto, loading: $page.props.sysImageUrl+'/loading_gif.gif', error: $page.props.sysImageUrl+'/default_photo.png' }"
                        v-if="noti?.senderCoverPhoto">
                        <img alt="Placeholder" width="15px" height="10px" class="w-[62px] h-[62px] sm:w-full sm:h-full rounded-md"
                        v-lazy=" { src: $page.props.thumb2xUrl + '/' + noti.defaultPhoto.imgPath, loading: $page.props.sysImageUrl+'/loading_gif.gif', error: $page.props.sysImageUrl+'/default_photo.png' }"
                        v-else>
                    </div>
                </div>

                <div class="flex flex-col ms-4 my-auto">
                    <ps-label class="text-xxs sm:text-sm lg:text-base" :textColor="noti.isRead == '0' ? noti.type == PsConst.PUSH_NOTI ? 'text-feAchromatic-500 dark:text-feAchromatic-200' : 'text-feAchromatic-500' : 'text-feAchromatic-500 dark:text-feAchromatic-200'" v-html=" noti.message.length > 75 ? noti.message.slice(0,75)+' ....' : noti.message "> </ps-label>
                    <!-- <ps-label class="hidden sm:block text-xxs sm:text-xxs lg:text-xs mt-1" :textColor="noti.isRead == '0' ? noti.type == PsConst.PUSH_NOTI ? 'text-feAchromatic-500 dark:text-feAchromatic-200' : 'text-feAchromatic-500' : 'text-feAchromatic-500 dark:text-feAchromatic-200'"  v-html=" noti.description.length > 100 ? noti.description.slice(0,95)+' ....' : noti.description "> </ps-label> -->
                </div>
                <!-- <div class="hidden sm:flex flex-col ms-4">
                    <ps-label class="text-xs sm:text-sm lg:text-base" v-html=" noti.message.length > 50 ? noti.message.slice(0,45)+' ....' : noti.message "> </ps-label>
                    <ps-label class="text-xxs sm:text-xxs lg:text-xs mt-1"  v-html=" noti.description.length > 200 ? noti.description.slice(0,190)+' ....' : noti.description "> </ps-label>
                </div> -->
            </div>
            <div class="flex flex-col w-[62px] sm:w-[120px]">
               <div class="flex justify-end w-full h-1/2">
                    <ps-dropdown horizontalAlign="right" h="h-auto " class='' @click="showMenu = !showMenu" >
                        <template #select>
                            <ps-icon  name="vertical-dot" w="24" h="24" :textColor="noti.isRead == '0' ? noti.type == PsConst.PUSH_NOTI ? 'text-feAchromatic-500 dark:text-feAchromatic-200' : 'text-feAchromatic-500' : 'text-feAchromatic-500 dark:text-feAchromatic-200'" class=" mt-2 text-base me-1 cursor-pointer" />
                        </template>

                        <template #list >

                            <div class="z-30">
                                <div @click="markAsRead(noti.id, noti.type)" v-if="noti.isRead == '0' && noti.type != PsConst.PUSH_NOTI" class='w-56 flex justify-center items-center py-4 px-2 hover:bg-fePrimary-50 dark:hover:bg-feAchromatic-800 cursor-pointer'>
                                    <ps-icon name="checkNoCircle" class="mt-2"></ps-icon>
                                    <ps-label class="ms-2" > {{ $t("noti_horizontal_item__mark_as_read") }} </ps-label>
                                </div>
                                <div @click="markAsUnRead(noti.id, noti.type)"  v-if="noti.isRead == '1'" class='w-56 flex justify-center py-4 px-2 hover:bg-fePrimary-50 dark:hover:bg-feAchromatic-800 cursor-pointer items-center'>
                                    <ps-icon name="checkNoCircle" class="mt-2"></ps-icon>
                                    <ps-label class="ms-2" > {{ $t("noti_horizontal_item__mark_as_unread") }} </ps-label>
                                </div>
                                <ps-line class="mx-4"/>
                                <div  @click="deleteConfirm(noti)" class="w-56 flex justify-center py-4 px-2 hover:bg-fePrimary-50 dark:hover:bg-feAchromatic-800 cursor-pointer items-center"  >
                                    <ps-icon name="trash-alt"></ps-icon>
                                    <ps-label class="ms-2"  > {{ $t("noti_horizontal_item__delete_noti") }} </ps-label>
                                </div>

                            </div>
                        </template>

                    </ps-dropdown>
               </div>

               <div class="h-1/2 w-full flex justify-end items-end p-1 sm:p-2">
                    <ps-label class="text-3xs sm:text-xs truncate" :textColor="noti.isRead == '0' ? noti.type == PsConst.PUSH_NOTI ? 'text-feAchromatic-500 dark:text-feAchromatic-200' : 'text-feAchromatic-500' : 'text-feAchromatic-500 dark:text-feAchromatic-200'">{{ moment(noti.addedDate).format($page.props.dateFormat) }}</ps-label>
               </div>
            </div>
        </div>
         <ps-loading-dialog ref="ps_loading_dialog" />
        <ps-confirm-dialog-2 ref="ps_confirm_dialog"/>
    </div>
</template>
<script lang="ts">
import { ref } from 'vue';
import PsLabel from '@template1/vendor/components/core/label/PsLabel.vue';
import { useNotiStoreState } from "@templateCore/store/modules/noti/NotificationStore";
import Noti from '@templateCore/object/Noti';
import PsIcon from '@template1/vendor/components/core/icons/PsIcon.vue';
import PsDropdown from '@template1/vendor/components/core/dropdown/PsDropdown.vue';
import { PsValueStore } from "@templateCore/store/modules/core/PsValueStore";
import NotiActionParamHolder from '@templateCore/object/holder/NotiActionParamHolder';
import NotiParameterHolder from '@templateCore/object/holder/NotiParameterHolder';
import PsLoadingDialog from '@template1/vendor/components/core/dialog/PsLoadingDialog.vue';
import { router } from '@inertiajs/vue3';
import PsConst from '@templateCore/object/constant/ps_constants';
import { useUserUnReadMessageStore } from "@templateCore/store/modules/chat/UserUnreadMessageStore";
import UserUnReadMessageParameterHolder from '@templateCore/object/holder/UserUnReadMessageParameterHolder';
import PsConfirmDialog2 from '@template1/vendor/components/core/dialog/PsConfirmDialog2.vue';
import PsUtils from '@templateCore/utils/PsUtils';
import { trans } from 'laravel-vue-i18n';

import PsLine from '@template1/vendor/components/core/line/PsLine.vue';
import moment from "moment";

export default {
    name : "NotiHorizontalItem",
    components : {
        PsLabel,
        PsDropdown,
        PsIcon,
        PsLoadingDialog,
        PsConfirmDialog2,
        PsLine
    },
    props : {
        noti : { type : Noti, default: new Noti } ,
        onClick : Function
    },
    setup() {
        // Inject Provider
        const notiStore = useNotiStoreState();
        const psValueStore = PsValueStore();
        const loginUserId = psValueStore.getLoginUserId();
        const holder = new NotiActionParamHolder();
        const listHolder = new NotiParameterHolder();
        const ps_loading_dialog = ref();
        listHolder.userId = loginUserId;

        const userunreadmsgStore = useUserUnReadMessageStore();
        const userUnreadHolder = new UserUnReadMessageParameterHolder();
        userUnreadHolder.userId = loginUserId;

        const showMenu = ref(false);
        const ps_confirm_dialog = ref();


        async function markAsRead(id,type){
            ps_loading_dialog.value.closeModal();
            showMenu.value = false;
            holder.userId = loginUserId;
            holder.deviceToken = localStorage.deviceToken;
            holder.notiId = id;
            holder.notiType = type;
            await notiStore.markAsRead(holder,loginUserId);
            await notiStore.resetNotiList(listHolder);
            await userunreadmsgStore.postUserUnreadMessageCount(userUnreadHolder);
            ps_loading_dialog.value.closeModal();
        }
        async function markAsUnRead(id,type){
            ps_loading_dialog.value.closeModal();
            showMenu.value = false;
            holder.userId = loginUserId;
            holder.deviceToken = localStorage.deviceToken;
            holder.notiId = id;
            holder.notiType = type;
            await notiStore.markAsUnRead(holder,loginUserId);
            await notiStore.resetNotiList(listHolder);
            await userunreadmsgStore.postUserUnreadMessageCount(userUnreadHolder);
            ps_loading_dialog.value.closeModal();
        }
        async function deleteNoti(id,type){
            ps_loading_dialog.value.closeModal();
            showMenu.value = false;
            holder.userId = loginUserId;
            holder.deviceToken = localStorage.deviceToken;
            holder.notiId = id;
            holder.notiType = type;
            await notiStore.deleteNoti(holder,loginUserId);
            await notiStore.resetNotiList(listHolder);
            await userunreadmsgStore.postUserUnreadMessageCount(userUnreadHolder);
            ps_loading_dialog.value.closeModal();
        }
        async function clickNoti(noti){
            if(noti.type == PsConst.PUSH_NOTI){
                router.get(route('fe_notification_detail'),{'notiId' : noti.id });
            }else if(noti.type == PsConst.CHAT_MESSAGE || noti.type == PsConst.OFFER_RECEIVED || noti.type == PsConst.OFFER_ACCEPTED || noti.type == PsConst.OFFER_REJECTED){
                await markAsRead(noti.id,noti.type);
                ps_loading_dialog.value.closeModal();
                router.get(route('fe_chat'),{
                    'buyer_user_id' : noti.buyerUserId,
                    'seller_user_id' : noti.sellerUserId,
                    'item_id' : noti.itemId,
                    'chat_flag' : noti.chatFlag,
                    });
            }

        }

        function deleteConfirm(noti){
            ps_confirm_dialog.value.openModal(
                trans('noti_list__delete_title'),
                trans('noti_list__delete_dialog'),
                trans('chat__confirm'),
                trans('chat__cancel'),
                () => {
                    deleteNoti(noti.id, noti.type)
                },
                () => {
                    PsUtils.log("cancel");
                }
            );
        }
        return {
            notiStore,
            showMenu,
            markAsRead,
            markAsUnRead,
            deleteNoti,
            ps_loading_dialog,
            ps_confirm_dialog,
            clickNoti,
            PsConst,
            deleteConfirm,
            moment
        }
    }
}
</script>
