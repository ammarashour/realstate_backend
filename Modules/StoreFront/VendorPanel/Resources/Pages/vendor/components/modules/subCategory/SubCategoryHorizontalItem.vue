<template>

    <div class="flex flex-col mt-2 w-full h-auto cursor-pointer">
        
        <div class="relative bg-feAchromatic-900 lg:h-40 sm:h-28 h-20 rounded-md">
            
            <div class="absolute opacity-50">
                
                <img alt="Placeholder" width="150px" height="100px" class="w-screen block mx-auto lg:h-40 sm:h-28 h-20 object-cover rounded-md" :src="subCategoryStore.imageUrl(subCategory ? subCategory.defaultPhoto.imgPath : '',3) " @error="subCategoryStore.defaultCarImage">
                
            </div>
            
            <div class="relative justify-end flex">
                <custom-checkbox :isScribe="subCategory.isSubScribe" :checked="subCategory.id" :value="subCategory.isSubScribe" 
                    v-model:selectedValue="checkedSelectedList" v-if="scribe"></custom-checkbox>
            </div>
            <div class="mx-auto lg:pt-16 sm:pt-12 pt-10 relative">
                
                <ps-label class="absolute w-full  lg:text-lg sm:text-sm text-xs text-center leading-4" textColor="text-feAchromatic-50"> {{subCategory ? subCategory.name : ''}} </ps-label>
            </div>

        </div>  
    </div>

</template>

<script lang="ts">
import { useSubCategoryStoreState } from "@templateCore/store/modules/category/SubCategoryStore";
import { defineComponent, reactive } from "vue";
import PsLabel from '@template1/vendor/components/core/label/PsLabel.vue';
import CustomCheckbox from '@template1/vendor/components/core/checkbox/CustomCheckbox.vue';
import SubCategory from "@templateCore/object/SubCategory";

export default defineComponent ({
    name : "SubCategoryHorizontalItem",
    props : {
        subCategory : { type : SubCategory } ,
        scribe : { 
            type : Boolean,
            default : false
        },
        onClick : Function
    },
    components : {
        PsLabel,
        CustomCheckbox,
    },
    setup() {
        // Inject Provider
        const subCategoryStore = useSubCategoryStoreState();
        const checkedSelectedList = reactive([]);
        
        return {
            subCategoryStore,
           // handleInput,
           checkedSelectedList
            
        }

    }
});
</script>