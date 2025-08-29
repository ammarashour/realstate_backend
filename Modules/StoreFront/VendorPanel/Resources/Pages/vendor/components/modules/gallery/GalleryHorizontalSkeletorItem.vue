<template>
    <div class='w-screen overflow-hidden bg-fePrimary-50 dark:bg-feAchromatic-800' >
        <div class='w-full h-64'>
            <Skeletor class='rounded-sm w-full h-64'/>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent } from "vue";
import DefaultPhoto from "@templateCore/object/DefaultPhoto";
import { Skeletor } from "vue-skeletor";
import "vue-skeletor/dist/vue-skeletor.css";

export default defineComponent ({
    name : "GalleryHorizontalSkeletorItem",
    components : {
       Skeletor
    },
    props : {
        gallery : { type : DefaultPhoto } ,
        onClick : Function,
        customClass : {
            type : String,
            default : ' h-112 object-contain'
        }
    },
    setup() {
        // Inject Provider

        return {
        }

    }
});
</script>
