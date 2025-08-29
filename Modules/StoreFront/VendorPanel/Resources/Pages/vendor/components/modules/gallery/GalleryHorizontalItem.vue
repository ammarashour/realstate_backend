<template>

    <div v-if="gallery.ordering <= imageCount" class='w-screen bg-fePrimary-50 dark:bg-feAchromatic-800 overflow-hidden rounded-xl  h-56 flex flex-row justify-center items-center'  >
        <div v-if="gallery.imgType == 'item-video-icon' " class=' relative w-full h-full' >

            <div class="absolute w-full h-56">
                <img id="slider-full" alt="Placeholder" width="400px" height="400px" class=" w-full h-56 object-contain "
                    :class="customClass " label="Image 1"
                    v-if="gallery != null && gallery.imgPath != null"
                     v-lazy=" { src: $page.props.thumb1xUrl + '/' + gallery.imgPath, loading: $page.props.sysImageUrl+'/loading_gif.gif', error: $page.props.sysImageUrl+'/default_photo.png' }"
                     >
            </div>
            <div class="relative mx-auto mt-24">
                <ps-icon name="playCircleOutline" class="text-feAchromatic-50" w="35" h="35" />
            </div>

        </div>
        <div v-else class='  w-full h-full ' >

            <img id="slider-full" alt="Placeholder" width="400px" height="400px" class="w-full h-56 object-contain   "
                :class="customClass " label="Image 1"
                v-if="gallery != null && gallery.imgPath != null"
                v-lazy=" { src: $page.props.thumb1xUrl + '/' + gallery.imgPath, loading: $page.props.sysImageUrl+'/loading_gif.gif', error: $page.props.sysImageUrl+'/default_photo.png' }">

        </div>

    </div>
</template>
<script lang="ts">

import { defineComponent } from "vue";
import DefaultPhoto from "@templateCore/object/DefaultPhoto";
import PsIcon from '@template1/vendor/components/core/icons/PsIcon.vue';

export default defineComponent ({
    name : "GalleryHorizontalItem",
    components: {
        PsIcon
    },
    props : {
        imageCount : {
            type : Number,
            default : 1,
        },
        gallery : { type : DefaultPhoto,
                    default: new DefaultPhoto
         } ,
        customClass : {
            type : String,
            default : ' h-64 object-contain'
        }
    },
    setup() {

        // Inject Provider

        return {
        }

    }
});
</script>
