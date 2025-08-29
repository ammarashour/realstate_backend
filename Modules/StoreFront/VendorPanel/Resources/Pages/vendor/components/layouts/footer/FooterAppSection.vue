<template>
    
    <div class="z-0 relative h-96 overflow-hidden ">
        <div class="absolute flex w-full h-full">
            <div class="relative z-10 w-full lg:w-8/12 px-6 py-8  lg:py-10 ">
                <div class="flex">
                    <div class="h-16">                        
                        <svg @click="gotToHome" class="mx-6 mt-2 cursor-pointer " version="1.2" baseProfile="tiny-ps" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 148 98" width="118.4" height="78.4">
                            <title>logo</title>
                            <defs>
                                <!-- <image  width="148" height="98" id="img1" href="data:image/png;base64,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"/> -->
                            </defs>

                            <use id="Background" href="#img1" x="0" y="0" />
                        </svg>

                    </div>
                    <div class="flex flex-col">
                        <ps-label-header-2 class=' font-bold ' >Get the apps</ps-label-header-2>
                        <ps-label-title class="mt-1" >Browse the cars </ps-label-title>
                        <ps-link class="me-2" url="https://www.panacea-soft.com/" > 
                            <!-- <img src="@template1/assets/images/get_on_google_play.png" alt="goole icon" width="40px" height="40px" class="mt-6 w-56 h-4 cursor-pointer object-cover "/> -->
                         </ps-link>
                        
                        <ps-link class="me-2" url="https://www.panacea-soft.com/" >                         
                            <!-- <img @click="gotToHome" src="@template1/assets/images/get_on_apple_store.png" width="40px" height="40px" alt="appStore icon" class="mt-4 w-56 h-4 cursor-pointer object-cover "/> -->
                        </ps-link>
                    </div>
                </div>
            </div>
            <div class="hidden lg:flex absolute top-0 right-0 w-full h-full">
                <div class="w-5/12 h-full bg-fePrimary-50"></div>
                <div class="relative w-1/4">
                    <svg
                        fill="currentColor"
                        viewBox="0 0 100 100"
                        class="absolute inset-y-0 z-10 h-full text-fePrimary-100">
                            <polygon id="diagonal" points="0,0 50,0 0,100 0,100"></polygon>
                    </svg>                    
                </div>
            </div>
        </div>
        <div class="hidden absolute top-0 right-0 lg:flex w-7/12 h-full bg-fePrimary-500 dark:bg-feAccent-500 items-end justify-center">
            <!-- <img @click="gotToHome" src="@template1/assets/images/hand_and_phone.png" alt="ps icon" width="40px" height="40px" class=" h-80 cursor-pointer object-cover "/>                         -->
        </div>
    </div>

</template>

<script lang='ts'>
// import router from '@template1/router';
import PsLabelHeader2 from '@template1/vendor/components/core/label/PsLabelHeader2.vue';
import PsLabelTitle from '@template1/vendor/components/core/label/PsLabelTitle.vue';
import PsLink from '@template1/vendor/components/core/link/PsLink.vue';
import { router } from '@inertiajs/vue3';

export default {
    components: { PsLabelHeader2, PsLabelTitle , PsLink},
    setup() {
        function gotToHome() {
            router.get(route('dashboard'));
        }

        return  {
            gotToHome
        }
    }
}
</script>