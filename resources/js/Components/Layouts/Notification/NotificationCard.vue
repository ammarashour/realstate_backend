<template>
    <div class="flex gap-4 p-6 mb-4 border-l-4 border-yellow-500 rounded shadow bg-yellow-50">
                        
        <ps-icon class="text-primary-500" :name="icon" w="32" h="32" viewBox="0 0 32 32"/>
                        
        <div class="w-full">
            <div class="flex justify-between w-full">
                <ps-label textColor="leading-6 text-base font-medium text-primary-500">{{ title }}</ps-label>
                <ps-label textColor="leading-5 text-sm text-secondary-400 flex items-center gap-2 cursor-pointer" @click="dismiss">
                    <ps-icon name="close-fill" w="16" h="16" viewBox="0 0 16 16"/>
                    {{ $t("core_be__dismiss") }}
                </ps-label>
            </div>
            <ps-label textColor="text-sm text-secondary-500 mt-3">{{ description }}</ps-label>
            <slot name="action" />
            
        </div>
    </div>
</template>

<script setup>

import PsIcon from "@/Components/Core/Icons/PsIcon.vue";
import PsLabel from "@/Components/Core/Label/PsLabel.vue";

const props = defineProps({
  title: { type: String },
  description: { type: String },
  icon: { type: String, default: "hond" },
  cardAction: { type:String },
  dismissActionRef: { type:Boolean }
})

const emit = defineEmits();

function dismiss() {
  emit('dismiss', false)
}


</script>