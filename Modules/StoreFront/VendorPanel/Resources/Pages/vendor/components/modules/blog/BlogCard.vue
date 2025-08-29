<template>
    <div class="w-full lg:h-[390px] flex flex-wrap shadow-sm">
        <div class=" overflow-hidden md:w-1/2 w-full sm:h-[400px] md:h-auto h-[202px] " >
            <img class="transform transition duration-500 hover:scale-110 w-full h-full rounded object-cover" style="cursor: auto;"
            v-lazy=" { src: $page.props.thumb2xUrl + '/' + blog?.defaultPhoto?.imgPath, loading: $page.props.sysImageUrl+'/loading_gif.gif', error: $page.props.sysImageUrl+'/default_photo.png' }">
        </div>
        <div class="md:w-1/2 w-full md:[h-285px] md:ps-10" style="cursor: auto;">
            <ps-label class="text-feAchromatic-800 font-semibold mt-2 text-lg sm:mt-2 sm:text-2xl dark:text-feAchromatic-200" style="cursor: auto;">
                {{ blog.name.length > 30 ?  blog.name.slice(0,20)+' ....' : blog.name  }}
            </ps-label>
            <div class="flex mb-4 mt-4">
                <span class="flex text-xs sm:text-sm pe-3  rtl:space-x-reverse space-x-2s text-feAchromatic-500 dark:text-feAchromatic-400">
                    Admin 
                </span>
                <span class="flex text-xs sm:text-sm ms-3 ps-5 border-s-2  rtl:space-x-reverse space-x-2s text-feAchromatic-500 border-feAchromatic-400 dark:border-feAchromatic-400  dark:text-feAchromatic-400">
                    {{ moment(blog.addedDate).format($page.props.dateFormat) }}
                </span>
            </div>
            <p class="leading-relaxed mb-4 text-base sm:text-lg dark:text-feAchromatic-300"> {{ blog.description.length > 200 ? blog.description.slice(0,200)+'.....':blog.description}}</p>
            <ps-route-link :to="{ name: 'fe_blog_detail', params: { blogId: blog.id, blogName: blog.name  }}">
                <ps-button class="mb-3" rounded="rounded" colors="bg-feAchromatic-50 dark:bg-feAchromatic-800 dark:text-feAchromatic-200 hover:text-feAchromatic-50" border="border border-feAchromatic-300 dark:border-feAchromatic-500">{{ $t('blog__read_more') }}</ps-button>
            </ps-route-link>
        </div>
    </div>
</template>

<script lang="ts">
import PsLabel from '@template1/vendor/components/core/label/PsLabel.vue';
import PsButton from '@template1/vendor/components/core/buttons/PsButton.vue';
import PsRouteLink from '@template1/vendor/components/core/link/PsRouteLink.vue';
import { useBlogStoreState } from "@templateCore/store/modules/blog/BlogStore";
import Blog from '@templateCore/object/Blog';
import moment from "moment";

export default {
    name : "BlogHorizontalItem",
    components : {
        PsLabel,
        PsButton,
        PsRouteLink
    },
    props : {
        blog : { type : Blog } ,
        onClick : Function,
        dateFormat:{ type : String,default: 'YYYY/MM/DD' } ,
    },
    setup() {
        // Inject Provider
        const blogStore = useBlogStoreState();
        return {
            blogStore,
            moment
        }
    }
}
</script>
