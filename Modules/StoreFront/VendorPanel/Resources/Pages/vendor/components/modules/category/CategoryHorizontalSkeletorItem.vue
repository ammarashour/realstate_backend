<template>
    <div class="text-center cursor-pointer w-full overflow-hidden bg-feAchromatic-50 
                 dark:bg-feSecondary-800 rounded-x sm:rounded-xl lg:rounded-lg ">
 
         <div class="h-44 relative flex justify-center">
             <Skeletor height="176" class='w-full h-44 rounded-md' />
             <div class="w-14 h-14 absolute -bottom-7 bg-feAchromatic-50 p-3 items-center
                         rounded-full border-2 border-white dark:bg-feSecondary-800">
                 <Skeletor height="28" class='rounded-full' />
             </div>
             
         </div>
 
         <Skeletor height="14" class='mt-9 w-full h-20 rounded-sm' />
         
     </div>
 </template>
 
 <script lang="ts">
 import { defineComponent } from "vue";
 import { Skeletor } from "vue-skeletor";
 import "vue-skeletor/dist/vue-skeletor.css";
 
 export default defineComponent ({
     name : "CategoryHorizontalSkeletorItem",
     components : {
        Skeletor
     }
 });
 </script>
 