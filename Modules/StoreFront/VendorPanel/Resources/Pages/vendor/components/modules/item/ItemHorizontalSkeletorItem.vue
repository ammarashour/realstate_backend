<template>
    <div class="w-full">
       <ps-card class=" flex flex-col my-2 bg-feAchromatic-50 dark:bg-feAchromatic-800  ">
           <div class="flex items-center justify-between leading-none p-3">
               <div>
                    <Skeletor circle size="3rem" />
                </div>
                <div class=" w-full rounded-md mx-4 mt-1">
                    <Skeletor height="14" class='rounded-md' />
                    <Skeletor height="14" class='rounded-md mt-2' />
                </div>
           </div>
           <Skeletor height="10rem" class='w-full mt-2' />
           <div class="rounded-md mt-2">
                <Skeletor height="16" class='w-40 rounded-md ms-2' />
                <Skeletor height="16" class='w-20 rounded-md m-2' />
                <Skeletor height="16" class='rounded-md m-2' />
                <Skeletor height="1" class='rounded-md' />
                <Skeletor height="10" class='w-32 rounded-md mx-2 mt-2' />
                <Skeletor height="10" class='w-10 rounded-md mx-2 my-4' />
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
    name : "ItemHorizontalSkeletorItem",
    props : {
       
    },
    components : {
       Skeletor,
       PsCard
    },
    setup() {}
});
</script>