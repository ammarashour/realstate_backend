<template>
    <div class="cursor-pointer w-full">
        
        <!-- Pscard -->
        <ps-card class="flex flex-col  ">

            <div class="px-4 bg-feAchromatic-50 dark:bg-feAchromatic-900  flex items-center md:w-full justify-between">
                <div class="flex flex-row">
                    <div class=" mt-2 my-auto">
                        <Skeletor height="60" class='w-16 rounded-full' />
                    </div>
                    <div class=" flex flex-col mt-2 ms-4  mb-2">
                        <div class=" flex content-center justify-between "> 
                            <Skeletor height="20" class='w-20 rounded-md ' />
                        </div>

                        <div class=" flex content-center justify-between mt-2  "> 
                           <Skeletor height="16" class='w-20 rounded-md ' />
                        </div>

                        <Skeletor height="16" class='w-20 rounded-md mt-2' />
                        <Skeletor height="12" class='w-20 rounded-md mt-2' />
                    </div>
                </div>
                <div class="ms-2 me-12">
                    <Skeletor height="96" class='sm:w-40 w-24 rounded-xl ' />
                </div>
            </div>  
        </ps-card>
    </div>
</template>

<script lang="ts">
import { defineComponent } from "vue";
import { Skeletor } from "vue-skeletor";
import "vue-skeletor/dist/vue-skeletor.css";
import PsCard from '@template1/vendor/components/core/card/PsCard.vue';

export default defineComponent ({
    name : "ChatSkeletorItem",
    components : {
       Skeletor,
       PsCard,
    }
});
</script>
