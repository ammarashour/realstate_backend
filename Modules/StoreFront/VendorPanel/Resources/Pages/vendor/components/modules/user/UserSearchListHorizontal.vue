<template>
    <div class="cursor-pointer h-full w-full" v-on:click="onClick != null ? onClick(user) : null">
        <!-- admin Pscard -->
        <ps-card class="flex-col h-full bg-fePrimary-50 dark:bg-feAchromatic-800">
            <div class="p-4 bg-fePrimary-50 dark:bg-feAchromatic-800 rounded-xl lg:rounded-2xl">
                <div class="flex items-start justify-between leading-none" >
                    <div class="flex items-start no-underline text-feAchromatic-900 cursor-pointer">
                        <div class="flex mt-2 w-10 ">
                            <img alt="admin" class="rounded-full bg-transparent mx-1 w-10 h-10 object-cover"  width="15" height="10" 
                            v-lazy=" { src: $page.props.thumb1xUrl + '/' + user.userProfilePhoto, loading: $page.props.sysImageUrl+'/loading_gif.gif', error: $page.props.sysImageUrl+'/default_photo.png' }"
                            >
                            
                        </div>
                        <div class="flex flex-col ms-4 w-28 sm:w-32">
                            <ps-route-link  :to="{ name: 'fe_other_profile', query: {userId: user?.userId } }">
                            <div class="flex">
                                <ps-label class="font-medium lg:text-base text-sm" > {{ user ? user.userName : '' }} </ps-label>
                                <!-- <img alt="Placeholder" class="mx-1 w-3 h-3" width="15" height="10" src="@template1/assets/images/mark2.png" v-if="user?.isVerifybluemark == '1'">     -->
                            </div>
                            <!-- <ps-route-link :to="{ name: 'review-list',query: { user_id: user?.userId } }"> -->
                            <div class="flex items-center mt-1.5">
                                <rating :grade="user ? user.overallRating:0" :maxStars="5" :hasCounter="true" />
                            </div>
                            <!-- </ps-route-link> -->
                            <ps-label v-if="user && user.isShowEmail == '1' " class="font-light text-xxs lg:text-xs mt-0.5"> {{ user ? user.userEmail :''  }} </ps-label>
                            <ps-label v-if="user && user.isShowPhone == '1' " class="font-light text-xxs lg:text-xs  mt-0.5"> {{  user ? user.userPhone :''  }} </ps-label>
                            <ps-label class="font-light text-xxs lg:text-xs  mt-1.5"> {{  user ? user.userAboutMe.length > 20 ? user.userAboutMe.slice(0,20)+' ....' : user?.userAboutMe : ''  }} </ps-label>
                            </ps-route-link> 
                        </div>
                        <div v-if="user?.userId != LoginUserId" @click="followClick(user)">
                            <div class="flex ms-2 mt-2" v-if="user?.isFollowed == '0'">
                                <ps-button rounded="rounded-2xl" textSize="text-xxs lg:text-xs" > {{ $t("other_profile__follow") }} </ps-button>
                            </div>
                            <div class="flex ms-2 mt-2" v-else>
                                <ps-button rounded="rounded-2xl" textSize="text-xxs lg:text-xs" > {{ $t("other_profile__unfollow") }}  </ps-button>
                            </div>
                        </div>
                        
                    </div>
                </div>  
            </div> 
        </ps-card>
        <!-- end admin Pscard -->
    </div>
    <ps-loading-dialog ref="psloading"  :isClickOut='false'/> 
</template>
<script lang="ts">
import { ref } from 'vue'

import PsCard from '@template1/vendor/components/core/card/PsCard.vue';
import PsLabel from '@template1/vendor/components/core/label/PsLabel.vue'
import PsButton from '@template1/vendor/components/core/buttons/PsButton.vue'
import Rating from '@template1/vendor/components/core/rating/Rating.vue';
import PsRouteLink from '@template1/vendor/components/core/link/PsRouteLink.vue';

//Modules
import { PsValueStore } from '@templateCore/store/modules/core/PsValueStore';
import User from '@templateCore/object/User';
import { router } from '@inertiajs/vue3';
import { useUserStore } from "@templateCore/store/modules/user/UserStore";

import PsLoadingDialog from '@template1/vendor/components/core/dialog/PsLoadingDialog.vue';
import UserFollowHolder from '@templateCore/object/holder/UserFollowHolder';
import { trans } from 'laravel-vue-i18n';
import PsConst from '@templateCore/object/constant/ps_constants';
export default {
    name : "UserListHorizontal",
    components : {        
        PsCard,
        PsLabel,
        Rating,
        PsRouteLink,
        PsButton,
        PsLoadingDialog
    },
    props : {
        user : { type : User } ,
        onClick : Function,
        storeName: {
            type : String,
            default : 'storeName'
        }
    },
    setup( props) {
    // setup() {
        // Get Login User Id
        const psValueStore = PsValueStore();
        const LoginUserId = psValueStore.getLoginUserId();
        const psloading = ref();
        const followHolder = new UserFollowHolder();
        const userStore = useUserStore(props.storeName);
        async function followClick(user){
            if(LoginUserId && LoginUserId != PsConst.NO_LOGIN_USER){
                psloading.value.openModal();
                if( user.isFollowed == '1'){
                    psloading.value.message = trans('other_profile__removing_user_from_follower_list');
                }else{
                    psloading.value.message = trans('other_profile__adding_user_to_follower_list');
                }
                followHolder.userId = LoginUserId;
                followHolder.followedUserId = user.userId;
                const res = await userStore.postUserFollow(followHolder);
                refleshData();
                psloading.value.closeModal();
                
            }
            else{

                router.get(route('login'));
            }
            
            
        }
        async function refleshData(){
            await userStore.refleshUserSearchList( LoginUserId,userStore.userparamHolder);
        }
        return {
            userStore,
            LoginUserId,
            followClick,
            psloading,
        }
    }
}
</script>