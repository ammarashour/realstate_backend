<template> 
    <div class="w-full">
       <div class=" flex flex-col my-2 bg-feAchromatic-50 dark:bg-feAchromatic-50  ">
           <div class=" items-center leading-none">
                <div class=" w-full rounded-md mt-1">
                    <Skeletor height="20" class='w-64 rounded-md' />
                    <Skeletor height="14" class='rounded-md mt-2' />
                    <Skeletor height="14" class='rounded-md mt-1' />
                    <Skeletor height="14" class='w-90 rounded-md mt-1' />
                </div>
                <div class=" w-full rounded-md mt-6">
                    <Skeletor height="20" class='w-64 rounded-md' />
                    <Skeletor height="14" class='rounded-md mt-2' />
                    <Skeletor height="14" class='rounded-md mt-1' />
                    <Skeletor height="14" class='rounded-md mt-1' />
                    <Skeletor height="14" class='w-36 rounded-md mt-1' />
                </div>
                <div class=" w-full rounded-md mt-6">
                    <Skeletor height="20" class='w-64 rounded-md' />
                    <Skeletor height="14" class='rounded-md mt-2' />
                    <Skeletor height="14" class='rounded-md mt-1' />
                    <Skeletor height="14" class='w-5/6 rounded-md mt-1' />
                </div>
                <div class=" w-full rounded-md mt-6">
                    <Skeletor height="20" class='w-64 rounded-md' />
                    <Skeletor height="14" class='rounded-md mt-2' />
                    <Skeletor height="14" class='rounded-md mt-1' />
                    <Skeletor height="14" class='rounded-md mt-1' />
                    <Skeletor height="14" class='w-4/6 rounded-md mt-1' />
                </div>
           </div>

           
       </div>  
    </div>
</template>

<script lang="ts">
import { defineComponent } from "vue";
import { Skeletor } from "vue-skeletor";
import "vue-skeletor/dist/vue-skeletor.css";

export default defineComponent ({
    name : "PrivacySkeletor",
    props : {
       
    },
    components : {
       Skeletor
    }
});
</script>