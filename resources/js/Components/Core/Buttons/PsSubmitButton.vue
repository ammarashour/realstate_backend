<template>
    <ps-button class="transition-all duration-300 min-w-3xs" padding="px-7 py-2" rounded="rounded" hover="" focus="" >
        <ps-loading v-if="loadingRef" theme="border-2 border-t-2 border-text-8 border-t-primary-500"  loadingSize="h-5 w-5" />
        <ps-icon v-if="successRef" name="check" w="20" h="20" class="me-1.5 transition-all duration-300" />
        <ps-label v-if="successRef" class="transition-all duration-300" textColor="text-white dark:text-secondaryDark-black">{{ props.titleSuccess }}</ps-label>
        <ps-label v-if="!loadingRef && !successRef" textColor="text-white dark:text-secondaryDark-black" > {{ props.title }} </ps-label>
    </ps-button>
</template>

<script setup>
 
import { ref } from "vue";
import PsLoading from "@/Components/Core/Loading/PsLoading.vue";
import PsLabel from "@/Components/Core/Label/PsLabel.vue";
import PsButton from "@/Components/Core/Buttons/PsButton.vue";
import PsIcon from "@/Components/Core/Icons/PsIcon.vue";

const props= defineProps({
    title: {
        type: String,
        default: "Save",
    },
    titleSuccess: {
        type: String,
        default: "Saved",
    }
});

const loadingRef = ref();
const successRef = ref();

function startLoading() {
    loadingRef.value = true;
}

function endLoading(isSuccess) {
    loadingRef.value = false;
    successRef.value = isSuccess;
}

defineExpose({
    startLoading,
    endLoading
});

</script>




