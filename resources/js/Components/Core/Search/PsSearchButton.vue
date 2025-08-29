<template>
    <div class="flex">
        <div :class="[inputWidth, 'relative']">
            <input type="search" class="w-full border border-secondary-100 rounded-l-lg px-4 py-2" placeholder="Search...">
            <div class="absolute inset-y-0 flex items-center me-4 end-0">
                <slot name="icon" />
            </div>
        </div>
       
        <slot name="button" class="w-full" />
    </div>
</template>
<script>
export default {
    name: "PsSearchButton",
    props: {
        inputWidth : { type: String, default: "" }
    }
    
}
</script>