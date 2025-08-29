<template>
    <ps-image-modal ref="psmodal" :isClickOut='false' class="z-50 content-center mx-auto" > 
        <template #body>
            <div class="w-screem flex flex-col">
                <div class="flex flex-row justify-between"> 
                    <div class="flex-grow" />
                    <ps-icon @click="psmodal.toggle(false)" name="close" class="text-feSecondary-700 dark:text-feAchromatic-500" w="30" h="30" />
                </div>
                <div class=" flex flex-row justify-between">
                    <div class="my-auto" @click="leftArrowClicked">
                        <ps-icon name="leftArrow" class='block sm:hidden stroke-current stroke-0 ' textColor="text-feSecondary-700 dark:text-feAchromatic-500" w="30" h="30" />
                        <ps-icon name="leftArrow" class='hidden sm:block lg:hidden stroke-current stroke-0 ' textColor="text-feSecondary-700 dark:text-feAchromatic-500" w="40" h="40" />
                        <ps-icon name="leftArrow" class='hidden lg:block stroke-current stroke-0 ' textColor="text-feSecondary-700 dark:text-feAchromatic-500" w="50" h="50" />
                    </div>
                    <div class="flex flex-grow max-w-5/6">
                        <div v-if="gallery.imgType == 'item-video'"  class="w-full h-auto " >
                            <video width="320" height="240" class=" w-full h-112 mt-0 lg:mt-2 object-contain object-contains" controls>
                                <source :src="$page.props.thumb1xUrl + '/' + gallery.imgPath" 
                                type="video/mp4">
                                <source :src="$page.props.thumb1xUrl + '/' + gallery.imgPath" 
                                type="video/ogg">
                                <img  alt="Placeholder" 
                                                     v-lazy=" { src: $page.props.thumb1xUrl + '/' + gallery.imgPath, loading: $page.props.sysImageUrl+'/loading_gif.gif', error: $page.props.sysImageUrl+'/default_photo.png' }"
 width="300px" height="400px" class="w-full h-112 overflow:hidden object-contain" >
                            </video>
                        </div>
                        <img v-else alt="Placeholder" width="300px" height="400px" class="w-full h-112 overflow:hidden object-contain" 
                                             v-lazy=" { src: $page.props.thumb1xUrl + '/' + gallery.imgPath, loading: $page.props.sysImageUrl+'/loading_gif.gif', error: $page.props.sysImageUrl+'/default_photo.png' }"

                         >
                    </div>
                    <div class="my-auto cursor-pointer"  @click="rightArrowClicked">
                        <ps-icon  name="rightArrow" class='block sm:hidden stroke-current stroke-0 ' textColor="text-feSecondary-700 dark:text-feAchromatic-500" w="30" h="30"  />
                        <ps-icon  name="rightArrow" class='hidden sm:block lg:hidden stroke-current stroke-0 ' textColor="text-feSecondary-700 dark:text-feAchromatic-500" w="40" h="40"  />
                        <ps-icon  name="rightArrow" class='hidden lg:block stroke-current stroke-0 ' textColor="text-feSecondary-700 dark:text-feAchromatic-500" w="50" h="50"  />
                    </div>
                </div>
            </div>
        </template>
        
    </ps-image-modal>
</template>

<script lang='ts'>
import { defineComponent,ref } from 'vue';
import PsImageModal from '@template1/vendor/components/core/modals/PsImageModal.vue';
import DefaultPhoto from '@templateCore/object/DefaultPhoto';
import PsIcon from '@template1/vendor/components/core/icons/PsIcon.vue';

import PsUtils from '@templateCore/utils/PsUtils';
export default defineComponent({
    name: "GalleryDetailHorizontalSwiper",
    components : {
        PsImageModal,
        PsIcon
    },
    setup() {
        const psmodal = ref();
        let gallery = ref(new DefaultPhoto());
        let galleryList = new Array();
        
        function openModal(galleryParam , galleryListParam) {
            
            gallery.value = galleryParam;
            galleryList = galleryListParam;
            PsUtils.log(galleryListParam);
            psmodal.value.toggle(true);           
        }

        function rightArrowClicked(){
            for (let i = 0; i < galleryList.length; i++) {
                
                if(galleryList[i].imgId == gallery.value.imgId){
                    if(i == galleryList.length -1 ){
                        gallery.value = galleryList[0];
                         break;
                          
                    }else{
                        
                        gallery.value = galleryList[i+1];
                         break;
                    }     
                }  
            }
        }
        function leftArrowClicked(){
            for (let i = 0; i < galleryList.length; i++) {
                
                if(galleryList[i].imgId == gallery.value.imgId){
                   
                    if(i == 0 ){
                        gallery.value = galleryList[galleryList.length-1];
                         break;
                          
                    }else{
                        
                        gallery.value = galleryList[i-1];
                         break;
                    }     
                }  
            }
        }

        return {
            psmodal,
            openModal,
            gallery,
            galleryList,
            rightArrowClicked,
            leftArrowClicked
        }
    },
})
</script>