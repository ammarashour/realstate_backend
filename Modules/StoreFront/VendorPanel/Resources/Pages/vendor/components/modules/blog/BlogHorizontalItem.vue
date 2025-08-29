<template>
    <div class="rounded-lg cursor-pointer w-full h-full shadow-sm overflow-hidden bg-feSecondary-50 dark:bg-feSecondary-800" v-on:click="onClick != null ? onClick(blog) : null">
        <div class="h-56 overflow-hidden">

            <img alt="Placeholder" class="transform transition duration-500 hover:scale-110 w-full h-full object-cover" width="360px" height="100px"
                v-lazy=" { src: $page.props.thumb2xUrl + '/' + blog.defaultPhoto.imgPath, loading: $page.props.sysImageUrl+'/loading_gif.gif', error: $page.props.sysImageUrl+'/default_photo.png' }"
                >
        </div>
        <div class="p-4" v-if="blog">

            <ps-label class="font-semibold text-xl dark:text-feSecondary-200" v-html=" blog.name.length > 20 ? blog.name.slice(0,15)+' ....' : blog.name "> </ps-label>
            <ps-label class="font-medium text-base text-feSecondary-400 dark:text-feSecondary-300">Admin </ps-label>
            <ps-label class="font-normal text-base text-feSecondary-500 dark:text-feSecondary-400">{{ moment(blog.addedDate).format($page.props.dateFormat) }}</ps-label>

        </div>
    </div>
</template>

<script lang="ts">
import PsLabel from '@template1/vendor/components/core/label/PsLabel.vue';
import { useBlogStoreState } from "@templateCore/store/modules/blog/BlogStore";
import Blog from '@templateCore/object/Blog';

import moment from "moment";

export default {
    name : "BlogHorizontalItem",
    components : {
        PsLabel
    },
    props : {
        blog : { type : Blog } ,
        onClick : Function,
        dateFormat:{ type : String} ,
    },
    setup() {
        // Inject Provider
        const blogStore = useBlogStoreState();

        return {
            blogStore,
            tagStore,
            moment
        }
    }
}
</script>
