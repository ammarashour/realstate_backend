<style scoped>

.swiper-container {
  z-index: 0 !important;
}

.swiper-wrapper {
  z-index: 0 !important;
}

.swiper-slide {
    text-align: center;
    font-size: 18px;
    display: -webkit-box;
    display: -ms-flexbox;
    display: -webkit-flex;
    display: flex;
    -webkit-box-pack: center;
    -ms-flex-pack: center;
    -webkit-justify-content: center;
    justify-content: center;
    -webkit-box-align: center;
    -ms-flex-align: center;
    -webkit-align-items: center;
    align-items: center;
}

</style>

<template>

    <div class='overflow: hidden;'>
        <div v-if="galleryList == null && isLoading " class="flex flex-row">
            <!-- <swiper
                class='swiper-container w-full overflow-hidden'
                slides-per-view= "auto"
                @swiper="onSwiper"
                @slideChange="onSlideChange" >

                <swiper-slide class='swiper-slide ' v-for="index in 2" :key="index" >
                    <gallery-horizontal-skeletor-item />
                </swiper-slide>

            </swiper> -->
            <gallery-horizontal-skeletor-item />
            <gallery-horizontal-skeletor-item />

        </div>
        <div v-else-if="galleryList == null && !isLoading " class="flex flex-row rtl:space-x-reverse sm:space-x-3 space-x-0">
            <img alt="Placeholder" class="sm:w-1/2 w-full h-56 object-cover rounded-xl"
                width="30px" height="30px" :src="require('@template1/assets/images/default-placeholder.png')" >
            <img alt="Placeholder" class="hidden sm:block w-1/2 h-56 object-cover rounded-xl"
                width="30px" height="30px" :src="require('@template1/assets/images/default-placeholder.png')" >
        </div>
        <div v-else>
           <swiper
                class='hidden sm:block'
                navigation
                :slides-per-view="galleryList.length < 3 ? galleryList.length : 3"
                :space-between="14"
                @slideChange="onSlideChange">
                    <swiper-slide class='swiper-slide bg-feAchromatic-50 dark:bg-feAchromatic-900' v-for="gallery in galleryList.slice(0,totalCount)" :key="gallery.imgId" >
                        <gallery-horizontal-item :imageCount="imageCount" :gallery="gallery" @click="imgClicked(gallery)" />
                    </swiper-slide>

            </swiper>

            <swiper
                class='block sm:hidden'
                navigation
                :slides-per-view="1"
                :space-between="14"
                @slideChange="onSlideChange">
                    <swiper-slide class='swiper-slide' v-for="gallery in galleryList.slice(0,totalCount)" :key="gallery.imgId" >
                        <gallery-horizontal-item  :imageCount="imageCount" :gallery="gallery" @click="imgClicked(gallery)" />
                    </swiper-slide>
            </swiper>
        </div>
    </div>
</template>

<script lang="ts">
import GalleryHorizontalItem from '@template1/vendor/components/modules/gallery/GalleryHorizontalItem.vue';

import DefaultPhoto from '@templateCore/object/DefaultPhoto';
import GalleryHorizontalSkeletorItem from './GalleryHorizontalSkeletorItem.vue';

export default {
    name: "GalleryHorizontalSwiper",
    components : {
        // Swiper,
        // SwiperSlide,
        GalleryHorizontalItem,
        GalleryHorizontalSkeletorItem
    },
    props : {
        totalCount : {
            type : Number,
            default : 0,
        },
        imageCount : {
            type : Number,
            default : 1,
        },
        galleryList : {
            type: Array as () => Array<DefaultPhoto>,
            default: () => [DefaultPhoto]
        },
        isLoading : {
            type : Boolean,
            default : true
        }
    },
    emits: ['clickImage'],
    methods: {
        setThumbsSwiper() {

        },
    },
    setup(  props, context ) {
        let thumbsSwiper : typeof Swiper;

        function onSwiper(swiper) {

            thumbsSwiper = swiper;
        }

        function onSlideChange() {}

        function onClick(swiper) {
            thumbsSwiper.slideTo( swiper.clickedIndex, 100);
        }

        function imgClicked(gallery) {
            context.emit('clickImage', gallery, props.galleryList);
        }

        return {
            onSwiper,
            onSlideChange,
            onClick,
            imgClicked,
        }

    }

}
</script>

