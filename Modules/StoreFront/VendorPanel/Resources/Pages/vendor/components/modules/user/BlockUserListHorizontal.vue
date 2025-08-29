<template>
    <div class="shadow-sm relative bg-feSecondary-50 dark:bg-feSecondary-800 rounded-lg flex flex-col gap-4 p-4">
        <div class="flex gap-4">
            <ps-route-link :to="{ name: 'fe_other_profile', params: { userId: block.userId }}">
                <div class="w-20 h-20 relative cursor-pointer">
                    <img alt="Placeholder" width="15px" height="10px" class="w-full h-full rounded-full object-cover"
                        v-lazy=" { src: $page.props.thumb2xUrl + '/' + block.userCoverPhoto, loading: $page.props.sysImageUrl+'/loading_gif.gif', error: $page.props.sysImageUrl+'/default_profile.png' }" >
                    <div v-if="block.isVerifybluemark == '1'" class="w-8 h-8 bg-feInfo-500 rounded-full p-1 absolute bottom-0 right-0">
                        <ps-icon name="bluemark" textColor="text-feSecondary-50" w="24" h="24" />
                    </div>
                </div>
            </ps-route-link>
            <div class="grow flex flex-col gap-2 justify-center truncate">
                <ps-route-link :to="{ name: 'fe_other_profile', params: { userId: block.userId }}" class="cursor-pointer">
                    <ps-label textColor="text-lg font-semibold text-feSecondary-800 dark:text-feSecondary-50">{{ block.userName.length > 17 ? block.userName.slice(0,17)+"..." : block.userName }}</ps-label>
                </ps-route-link>
                <ps-route-link :to="{ name: 'fe_review_list',query: { user_id: block.userId } }">
                    <rating class="h-4" :grade="block ? block.overallRating:0" :maxStars="5" :hasCounter="true" />
                </ps-route-link>
                <div class="flex flex-col  ">
                    <div>
                        <router-link v-if="block.isShowPhone == '1'" class="cursor-pointer">

                                <!-- <ps-icon name="phone" w="24" h="24"/> -->
                                <ps-label textColor="text-sm font-normal text-feSecondary-800 dark:text-feSecondary-50">{{ block.userPhone }}</ps-label>

                        </router-link>
                    </div>
                    <div>
                        <router-link v-if="block.isShowEmail == '1'" class="cursor-pointer">
                            <!-- <ps-icon name="email" w="24" h="24"/> -->
                            <ps-label textColor="text-sm font-normal text-feSecondary-800 dark:text-feSecondary-50">{{ block.userEmail }}</ps-label>
                        </router-link>
                    </div>
                </div>
            </div>
            <div class="self-center hidden">
                <router-link>
                    <ps-icon textColor="text-feSecondary-800" name="arrowNarrowRight" w="24" h="24" viewBox="0 -3 9 20"/>
                </router-link>
            </div>
        </div>
        <div class="grid grid-cols-2">
            <div class="flex items-center gap-1">
                <ps-icon class="text-feSecondary-800 dark:text-feSecondary-50" name="user-group-fill" w="24" h="24" viewBox="0 0 24 24"/>
                <ps-label textColor="text-sm font-medium text-feSecondary-800 dark:text-feSecondary-50">{{user ? block.followerCount:'0'}} {{ $t("profile__followers")}}</ps-label>
            </div>
            <div class="flex items-center gap-1">
                <ps-icon class="text-feSecondary-800 dark:text-feSecondary-50" name="shoppingCart-fill" w="24" h="24" viewBox="0 0 24 24"/>
                <ps-label textColor="text-sm font-medium text-feSecondary-800 dark:text-feSecondary-50">{{user ? block.activeItemCount:'0'}} {{ $t("user__item") }}</ps-label>
            </div>
        </div>

        <div class="h-9">
            <ps-button v-on:click="onClick != null ? onClick(block) : null" class="absolute bottom-4 right-4 left-4">{{ $t('blocked_user_list__unblock')}}</ps-button>
        </div>
    </div>
</template>

<script lang="ts">

import PsCard from '@template1/vendor/components/core/card/PsCard.vue';
import PsLabel from '@template1/vendor/components/core/label/PsLabel.vue';
import PsButton from '@template1/vendor/components/core/buttons/PsButton.vue';
import PsIcon from '@template1/vendor/components/core/icons/PsIcon.vue';
import Rating from '@template1/vendor/components/core/rating/Rating.vue';
import PsLabelCaption from '@template1/vendor/components/core/label/PsLabelCaption.vue';
import PsRouteLink from '@template1/vendor/components/core/link/PsRouteLink.vue';

//Modules
import BlockedUser from '@templateCore/object/BlockedUser';

export default {
    name : "UserListHorizontal",
    components : {
        PsCard,
        PsLabel,
        Rating,
        PsLabelCaption,
        PsButton,
        PsIcon,
        PsRouteLink,
    },
    props : {
        block : { type : BlockedUser,default : new BlockedUser },
        onClick : Function
    },
    setup() {
        // Inject Provider
        return {
        }
    }
}
</script>
