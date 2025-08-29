<template>
    <ps-modal ref="psmodal" maxWidth="100%" bodyHeight="h-full" line="hidden" :isClickOut='false' theme="rounded-lg" bgColor="rounded-lg bg-transparent dark:bg-transparent" class=' z-20'>
        <template #body>
                <div class="flex justify-end pb-20">
                    <div class="h-8 w-8 rounded flex bg-feSecondary-300 dark:bg-feSecondary-800 justify-center items-center">
                        <ps-icon name="close" class="text-feSecondary-800 hover:cursor-pointer" @click.prevent="psmodal.toggle(false)"></ps-icon>
                    </div>
                </div>
                <div class="w-full mb-28" dir="ltr">
                    <Splide :options="options" :has-track="false">
                        <SplideTrack>
                            <SplideSlide>
                                <video v-if="img_type == 'item-video-icon'" class="w-auto h-full mx-auto rounded-lg" controls>
                                    <source :src="$page.props.uploadUrl + '/' + active_img_path"
                                    type="video/mp4">
                                    <source :src="$page.props.uploadUrl + '/' + active_img_path"
                                    type="video/ogg">
                                </video>
                                <img v-else class="w-auto h-full mx-auto rounded-lg" v-lazy=" { src: $page.props.uploadUrl + '/' + active_img_path, loading: $page.props.sysImageUrl+'/loading_gif.gif', error: $page.props.sysImageUrl+'/default_photo.png' }" />
                            </SplideSlide>
                            <SplideSlide v-for="gallery in gallery_list.filter((gallery)=>gallery.imgPath != active_img_path)" :key="gallery.imgId">
                                <img v-if="gallery.imgType == 'item'" class="w-auto h-full mx-auto rounded-lg" v-lazy=" { src: $page.props.uploadUrl + '/' + gallery.imgPath, loading: $page.props.sysImageUrl+'/loading_gif.gif', error: $page.props.sysImageUrl+'/default_photo.png' }" />
                                <video v-else class="w-auto h-full mx-auto rounded-lg" controls>
                                    <source :src="$page.props.uploadUrl + '/' + gallery.imgPath"
                                    type="video/mp4">
                                    <source :src="$page.props.uploadUrl + '/' + gallery.imgPath"
                                    type="video/ogg">
                                </video>
                            </SplideSlide>
                        </SplideTrack>
                        <div class="splide__arrows splide__arrows--ltr">
                            <button
                                class="bg-feSecondary-50 dark:bg-feSecondary-800 w-10 h-10 rounded shadow-sm p-2 splide__arrow--prev"
                                type="button"
                                aria-label="Previous slide"
                                aria-controls="splide01-track"
                            >
                                <ps-icon textColor="dark:text-feSecondary-200" name="arrowNarrowRight" h="23" w="23" viewBox="0 -3 9 20"/>
                            </button>
                            <button
                                class="bg-feSecondary-50 dark:bg-feSecondary-800 w-10 h-10 rounded shadow-sm p-2 splide__arrow--next"
                                type="button"
                                aria-label="Next slide"
                                aria-controls="splide01-track"
                            >
                                <ps-icon textColor="dark:text-feSecondary-200" name="arrowNarrowRight" h="23" w="23" viewBox="0 -3 9 20"/>
                            </button>
                        </div>
                    </Splide>
                </div>
        </template>

    </ps-modal>
</template>

<script lang="ts">
import { defineComponent,ref } from 'vue';
import { Splide, SplideSlide, SplideTrack } from '@splidejs/vue-splide';
import '@splidejs/vue-splide/css';

import PsModal from '@template1/vendor/components/core/modals/PsModal.vue';
import PsLabel from '@template1/vendor/components/core/label/PsLabel.vue';
import PsButton from '@template1/vendor/components/core/buttons/PsButton.vue';
import PsIcon from '@template1/vendor/components/core/icons/PsIcon.vue';


export default defineComponent({
    name : "PsGalleryModal",
    components : {
        Splide,
        SplideSlide,
        SplideTrack,
        PsModal,
        PsLabel,
        PsButton,
        PsIcon
    },
    setup() {

        const psmodal = ref();
        const active_img_path = ref();
        const img_id = ref();
        const img_type = ref();
        const options = {
            rewind : true,
            perPage: 1,
            gap    : '0rem',
            fixedHeight: '400px',
            pagination: false,
            // start: 1,
            breakpoints: {
                744:{
                    height: "400px"
                },
                640:{
                    height: "328px"
                }
            }
        }
        const gallery_list = ref();
        let okClicked: Function;
        let cancelClicked: Function;

        function actionClicked(status) {
            if(status == 'yes') {
                okClicked();
            }else {
                cancelClicked();
            }
            psmodal.value.toggle(false);
        }

        function update(
            imgType,
            activeImgPath,
            imgId,
            galleryList,
        ) {
            gallery_list.value = galleryList;
            img_type.value = imgType;
            active_img_path.value = activeImgPath;
            img_id.value = imgId;
        }

        function openModal(
            imgType,
            activeImgPath,
            imgId,
            galleryList,
        ) {
            gallery_list.value = galleryList;
            img_type.value = imgType;
            active_img_path.value = activeImgPath;
            img_id.value = imgId;
            psmodal.value.toggle(true);
        }

        function close() {
            psmodal.value.toggle(false);
        }

        return {
            img_type,
            active_img_path,
            gallery_list,
            img_id,
            psmodal,
            options,
            openModal,
            actionClicked,
            close,
            update
        }
    },
})
</script>

<style scoped>
.splide__arrow--prev{
    position:absolute;
    top:50%;
    transform:translateY(-50%);
    left: 0;
}
.splide__arrow--next{
    position:absolute;
    top:50%;
    transform:translateY(-50%);
    right: 0;
}
</style>
