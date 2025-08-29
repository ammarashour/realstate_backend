<template>
    <div class="w-full">

        <ps-draggable 
            v-if="previewVideo.data != '' || imagePath == null"
            :disabled="disabled" class=" mt-4 px-2.5 h-52 rounded-lg" :class="previewVideo.data[0] ? 'border-b-0' : ''">
            <ps-icon  name="small-cloud" :theme="disabled?'text-secondary-300 dark:text-secondary-700':'text-secondary-500'" />

            <!-- <ps-label :textColor="disabled?'text-secondary-300 dark:text-secondary-700':'text-secondary-500'">{{ $t("ps_video_upload__drag_drop") }}</ps-label>
            <ps-label class="mt-2" :textColor="disabled?'text-secondary-300 dark:text-secondary-700':'text-secondary-500'">{{$t('ps_video_upload__or')}}</ps-label> -->
            <ps-label v-if="previewVideo.data !=''" :textColor="disabled?'text-secondary-300 dark:text-secondary-700 mb-2':'text-secondary-500  mb-2'">{{previewVideo.data.length }}  {{ $t("ps_image_upload__file_is_choosen") }}</ps-label>
            <ps-label v-else :textColor="disabled?'text-secondary-300 dark:text-secondary-700 mb-2':'text-secondary-500  mb-2'">{{ $t("ps_image_upload__no_file_is_choosen") }}</ps-label>
            <input type="file" size=1 max=1 accept="video/*" ref="video" style="display: none;" @change="onVideoSelected($event)">
            <ps-button v-if="disabled == false"  type="button" @click="videoClick()" class="w-26  py-1 px-2 mt-2" rounded="rounded">{{$t('ps_video_upload__choose_files')}}</ps-button>
            <ps-button v-else type="button" :disabled="true" colors="bg-secondary-600 text-white" class="w-26  py-1 px-2 mt-2" rounded="rounded">{{$t('ps_video_upload__choose_files')}}</ps-button>
        </ps-draggable>
        <div v-if="previewVideo.data !=''" class="flex flex-row bg-primary-50 h-16 dark:bg-indigo-900 dark:rounded-b-lg">
            <div class="w-20 flex flex-row justify-center items-center">
                <div class="w-20 absolute">
                    <video id="slider-full" alt="Placeholder" width="68px" height="62px" class=" w-20 h-16 object-contain dark:rounded">
                        <source :src="getVideoUrl()" type="video/mp4">
                        <source :src="getVideoUrl()" type="video/ogg">
                    </video>
                </div>
                
                <div 
                    v-if="previewVideo.data == ''"
                    class="relative w-7 h-7 bg-secondary-200 rounded-full mx-auto mt-2">
                    <ps-icon name="playCircleOutline"  
                     class="text-white text-md"  />
                </div>
                <img v-else v-for='video in previewVideo.data' alt="Placeholder" width="300px" height="300px"
                            class="rounded-xl w-20 h-20 object-cover " :key="video" v-lazy="video" />
                
            </div>
            <p class="ms-4 mt-2 flex-rows">
                <ps-label class="font-bold">{{videoName}}</ps-label>
                <ps-label-title-3 class="dark:text-gray-500">{{$t('ps_video_upload__video_size')}}{{videoSize}}</ps-label-title-3>
            </p>
            <ps-icon v-if="cancelAble" @click="close()" :textColor="textTheme" name="cross"  class="cursor-pointer text-sm ms-auto text-red-400 mt-1 focus:shadow-none hover:text-purple-500"  />
        </div>
        
        <div v-else-if="imagePath != null" class="flex items-end pt-4">
            <div class="relative">
                <img
                    v-lazy="{
                        src: imagePath,
                        loading: $page.props.sysImageUrl + '/loading_gif.gif',
                        error: $page.props.sysImageUrl + '/default_photo.png',
                    }"
                    width="w-full"
                    height="250"
                    class="h-48 w-auto"
                    alt="blog cover"
                />
                <!-- <video class="w-96 h-48" controls>
                    <source :src="imagePath"
                        type="video/mp4">
                    <source :src="imagePath"
                        type="video/ogg">
                    {{ $t('core__be_item_video') }}
                </video> -->
                <div class="absolute inset-0 bg-black bg-opacity-20 flex justify-center items-center ">
                    <ps-icon textColor="text-feAchromatic-50" name="playCircle" w="60" h="60" class="text-white" />
                </div>
            </div>
            <input
                type="file"
                accept="video/*"
                ref="video"
                style="display: none"
                @change="onVideoSelected($event)"
            />
            <ps-button
                type="button"
                @click="videoClick()"
                rounded="rounded-full"
                shadow="drop-shadow-2xl"
                class="mb-2 -ms-10"
                colors="bg-white text-primary-500 dark:bg-secondaryDark-black"
                border="border border-1 dark:border-secondary-700 border-secondary-300"
                padding="p-1.5"
            >
                <ps-icon name="pencil-btn" />
            </ps-button>
            <ps-image-icon-modal ref="ps_image_icon_modal" />
        </div>
    </div>

    <ps-error-dialog ref="ps_error_dialog" />

</template>

<script  lang="ts">
import { reactive, ref, computed } from 'vue';
import PsButton from '@/Components/Core/Buttons/PsButton.vue';
import PsLabel from '@/Components/Core/Label/PsLabel.vue';
import PsLabelTitle3 from '@/Components/Core/Label/PsLabelTitle3.vue';
import PsIcon from '@/Components/Core/Icons/PsIcon.vue';
import PsDraggable from '@/Components/Core/Draggable/PsDraggable.vue';
import PsErrorDialog from '@/Components/Core/Dialog/PsErrorDialog.vue';
import { usePage } from '@inertiajs/vue3'

export default {
    name : "PsVideoUpload",
    props :{
        videoFile: {
            type: Object
        },
        disabled: {
            type: Boolean,
            default: false
        },
        cancelAble: {
            type: Boolean,
            default : false
        },
        imagePath: {
            type: String,
            default: null,
        },
    },
    components : {
        PsButton,
        PsLabel,
        PsLabelTitle3,
        PsIcon,
        PsDraggable,
        PsErrorDialog
    },
    setup(props , { emit }) {
        const page = usePage()
        const videoDuration = page.props.videoDuration
        const previewVideo = reactive({
            data : [] as any
        });
        const video = ref();
        let selectedFileVideo;
        let selectedFileVideoThumb;
        const ps_error_dialog = ref();

        function videoClick() {
            video.value.click();
        }

        function getVideoUrl() {
            return previewVideo.data[0];
        }

        let videoName = ref();
        let videoSize = ref();
        function onVideoSelected(event) {

            const selectedFiles = event.target.files;

            if(selectedFiles && selectedFiles.length > 1) {

            }else {
                previewVideo.data = [];
                if(selectedFiles && selectedFiles[0]) {
                    const fileReader = new FileReader
                    fileReader.onload = () => {
                        const file = selectedFiles[0];
                        videoName.value = file.name;
                        videoSize.value = file.size * (1  / 8 ) * (1 / 1000);
                        const blob = new Blob([ fileReader.result ?? ''], {type: file.type});
                        const url = URL.createObjectURL(blob);
                        const video = document.createElement('video');
                        video.oncanplay = function () {

                            if(video.duration > videoDuration/1000) {
                                selectedFileVideo = undefined;
                                ps_error_dialog.value.openModal('Video Upload!', "Video must be less than "+videoDuration/1000+" seconds.","Ok", ()=> {});
                                return false;
                            }else {

                                const timeupdate = function() {
                                    if (snapImage()) {
                                        video.removeEventListener('timeupdate', timeupdate);
                                        video.pause();
                                    }
                                };
                                video.addEventListener('loadeddata', function() {
                                    if (snapImage()) {
                                    video.removeEventListener('timeupdate', timeupdate);
                                    }
                                });
                                const snapImage  = function() {
                                    const canvas = document.createElement('canvas') as HTMLCanvasElement;
                                    canvas.width = video.videoWidth;
                                    canvas.height = video.videoHeight;
                                    canvas.getContext('2d')!.drawImage(video, 0, 0, canvas.width, canvas.height);
                                    const image = canvas.toDataURL();

                                    const fileData = dataURLtoFile(image);

                                    const success = image.length > 100000;
                                    if (success) {
                                        previewVideo.data.push(image);
                                        URL.revokeObjectURL(url);
                                    }
                                    selectedFileVideoThumb = fileData;
                                    emit("update:videoFileThumb", selectedFileVideoThumb);
                                    return success;

                                };
                                video.addEventListener('timeupdate', timeupdate);

                                selectedFileVideo = selectedFiles[0];

                            }
                        }

                        video.preload = 'metadata';
                        video.src = url;
                        // Load video in Safari / IE11
                        video.muted = true;
                        // video.playsInline = true;
                        video.play();


                    };
                    fileReader.readAsArrayBuffer(selectedFiles[0]);
                    emit("update:videoFile", selectedFiles[0]);
                    
                }
            }
        }

        function dataURLtoFile(dataurl) {
            let arr = dataurl.split(','), mime = arr[0].match(/:(.*?);/)[1],
            bstr = atob(arr[1]), n = bstr.length, u8arr = new Uint8Array(n);
            const imgExt = mime.split('/');
            const fileName = Date.now() +'.'+imgExt[1];
            while(n--){
            u8arr[n] = bstr.charCodeAt(n);
            }
            return new File([u8arr], fileName, {type:mime});
        }

        function close() {
            if (previewVideo.data) {
                return previewVideo.data.splice(previewVideo.data[0], 1);
            }
        }

        return {
            videoClick,
            video,
            onVideoSelected,
            previewVideo,
            getVideoUrl,
            ps_error_dialog,
            videoName,
            videoSize,
            close
        }
    }
}
</script>
