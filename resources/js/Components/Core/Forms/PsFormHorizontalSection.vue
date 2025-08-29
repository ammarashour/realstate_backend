<template>
    <div class="">
        <form @submit.prevent="$emit('submitted')">
            <div class="w-full px-3 py-3 ">
                <div class="flex flex-wrap">
                    <slot name="form"></slot>                        
                </div>
                <div class="w-full px-4 py-3  text-right sm:px-6" > 
                    <!-- v-if="hasActions" -->
                    <slot name="actions"></slot>
                </div>
            </div>            
        </form>
    </div>
</template>

<script>
    
export default {
    name : 'PsFormHorizontalSection',
    computed: {
        // hasActions() {
        //     return !! this.$slots.actions
        // }
    }
}
</script>
