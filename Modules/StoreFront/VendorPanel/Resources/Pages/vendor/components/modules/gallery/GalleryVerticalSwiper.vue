<template>
  <div class="wrapper relative rounded-lg overflow-hidden"  >
    <div class="" dir="ltr">
        <Splide class="z-0"
            aria-labelledby="thumbnail-example-heading"
            :options="mainOptions"
            ref="main"
            >
            <SplideSlide v-if="galleryList == null || video == null">
                    <div class="w-full h-full flex flex-col justify-center items-center gap-8 absolute bg-feSecondary-50 dark:bg-feSecondary-800">
                        <span class="loader"></span>
                        <span class="text-2xl text-gray-500 font-semibold">Loading</span>
                    </div>
                    <!-- <img class="w-full h-full object-cover" v-lazy=" { src: $page.props.sysImageUrl+'/loading_gif.gif', loading: $page.props.sysImageUrl+'/loading_gif.gif', error: $page.props.sysImageUrl+'/default_photo.png' }" /> -->
            </SplideSlide>

            <SplideSlide v-for="(gallery, index) in galleryList" :key="index">
                    <div class="w-full h-full relative bg-feSecondary-50">
                        <img v-if="gallery.imgType == 'item'" class="h-full w-full mx-auto object-cover overflow-hidden" v-lazy=" { src: $page.props.uploadUrl + '/' + gallery.imgPath, loading: $page.props.sysImageUrl+'/loading_gif.gif', error: $page.props.sysImageUrl+'/default_photo.png' }" @click="openImage(gallery.imgType, gallery.imgPath, gallery.imgId, video)"/>
                        <img v-else class="h-full w-full mx-auto object-cover overflow-hidden" v-lazy=" { src: $page.props.uploadUrl + '/' + gallery.imgPath, loading: $page.props.sysImageUrl+'/loading_gif.gif', error: $page.props.sysImageUrl+'/default_photo.png' }"  />
                        <div v-if="gallery.imgType == 'item-video-icon'" class="w-full h-full absolute top-0 left-0 flex justify-center items-center" @click="openImage(gallery.imgType, video.filter(item => item.imgType == 'item-video').pop().imgPath, gallery.imgId, video)"  >
                            <ps-icon textColor="text-feAchromatic-50" name="play" w="96" h="96" viewBox="0 0 96 96"/>
                        </div>
                    </div>
            </SplideSlide>
        </Splide>
    </div>

    <div class="absolute bottom-4 left-4">
        <Splide
            aria-label="The carousel with thumbnails. Selecting a thumbnail will change the main carousel"
            :options="thumbsOptions"
            ref="thumbs"
            >
            <SplideSlide v-for="gallery in galleryList" :key="gallery.imgId">
                <div class="w-full h-full">
                    <img v-if="gallery.imgType != 'item-video-icon'" class="w-full h-full object-cover rounded-lg" v-lazy=" { src: $page.props.uploadUrl + '/' + gallery.imgPath, loading: $page.props.sysImageUrl+'/loading_gif.gif', error: $page.props.sysImageUrl+'/default_photo.png' }" />
                    <img v-else class="w-full h-full object-cover rounded-lg" v-lazy=" { src: $page.props.uploadUrl + '/' + gallery.imgPath, loading: $page.props.sysImageUrl+'/loading_gif.gif', error: $page.props.sysImageUrl+'/default_photo.png' }" />
                    <div v-if="gallery.imgType == 'item-video-icon'" class="w-full h-full absolute top-0 left-0 flex justify-center items-center">
                        <div class="absolute inset-0 bg-black bg-opacity-50 flex justify-center items-center">
                            <ps-icon textColor="text-feAchromatic-50" name="play" w="28" h="28" viewBox="0 0 96 96"/>
                        </div>
                    </div>

                </div>
            </SplideSlide>


        </Splide>
    </div>

  </div>
  <ps-gallery-modal ref="ps_gallery_modal" />
</template>

<script lang="ts">
import { Splide, SplideSlide, Options } from '@splidejs/vue-splide';
import { defineComponent, onMounted, ref } from 'vue';
import '@splidejs/vue-splide/css';

import DefaultPhoto from '@templateCore/object/DefaultPhoto';
import GalleryHorizontalItem from '@template1/vendor/components/modules/gallery/GalleryHorizontalItem.vue';
import PsGalleryModal from './PsGalleryModal.vue';
import PsIcon from '@template1/vendor/components/core/icons/PsIcon.vue';

export default defineComponent( {
  name: 'ThumbnailsExample',

  components: {
    Splide,
    SplideSlide,
    GalleryHorizontalItem,
    PsGalleryModal,
    PsIcon,
  },

  props: {
    galleryList: { //item, icon
        type: Array as () => Array<DefaultPhoto>,
            default: () => [DefaultPhoto]
    },
    video: { //item, video
        type: Array as () => Array<DefaultPhoto>,
            default: () => [DefaultPhoto]
    },
    isLoading : {
        type : Boolean,
        default : true
    }
  },

  setup(props) {
    const main   = ref<InstanceType<typeof Splide>>();
    const thumbs = ref<InstanceType<typeof Splide>>();

    const mainOptions: Options = {
      height: '400px',
      type      : 'loop',
      perPage   : 1,
      perMove   : 1,
      gap       : '2px',
      pagination: true,
      focus       : 'center',
      arrows: false
    };

    const thumbsOptions: Options = {
      height: '300px',
      direction: 'ttb',
      type        : 'slide',
      rewind      : true,
      gap         : '8px',
      pagination  : false,
      fixedWidth  : 68,
      fixedHeight : 68,
      cover       : true,
      focus       : 'center',
      isNavigation: true,
      updateOnMove: true,
      arrows: false,
    };

    let clickCount = 0;
    let clickTimer = null;
    const ps_gallery_modal = ref();
    const gallery_list = ref();


    function openImage(imgType, activeImgPath, imgId, galleryList){



        clickCount++;
        if(clickCount === 1){
            clickTimer = setTimeout(()=>{
                clickCount = 0;
            }, 250);
        }else if(clickCount === 2){
            // if(ps_gallery_modal.value.gallery_list == null || ps_gallery_modal.value.gallery_list == undefined){
            //     ps_gallery_modal.value.openModal(
            //         imgType,
            //         activeImgPath,
            //         imgId,
            //         galleryList,
            //     );
            //     console.log(ps_gallery_modal.value.gallery_list);
            // }


            clearTimeout(clickTimer);
            clickCount = 0;
            // setTimeout(()=>{
            //     ps_gallery_modal.value.close();
            // }, 75);

            // setTimeout(()=>{
                ps_gallery_modal.value.openModal(
                    imgType,
                    activeImgPath,
                    imgId,
                    galleryList,
                );
            // }, 1000);

        }

    }


    onMounted( () => {
        const thumbsSplide = thumbs.value?.splide;

        if ( thumbsSplide ) {
            main.value?.sync( thumbsSplide );
        }

    } );


    return {
      main,
      thumbs,
      mainOptions,
      thumbsOptions,
      openImage,
      ps_gallery_modal,
      gallery_list,
    }
  },
} );
</script>

<style scoped>
.splide__track--nav>.splide__list>.splide__slide.is-active{
    border: none;
}

.loader{
    width: 80px;
    height: 80px;
    border-radius: 50%;
    border: 10px solid #4f5a84;
    border-bottom-color: transparent;
    animation: animate 1.2s linear infinite;
}

@keyframes animate{
    0%{
        transform: rotate(0deg);
    }
    100%{
        transform: rotate(360deg);
    }
}
</style>
