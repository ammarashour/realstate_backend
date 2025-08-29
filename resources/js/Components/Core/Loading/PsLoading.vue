<style scoped>
.loader {
  -webkit-animation: spinner 1s linear infinite;
  animation: spinner 1s linear infinite;
}

@-webkit-keyframes spinner {
  0% { -webkit-transform: rotate(0deg); }
  100% { -webkit-transform: rotate(360deg); }
}

@keyframes spinner {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style>
<template>
    <div 
        class="loader ease-linear rounded-full "
        :class="[loadingSize, theme ]"
    >
    </div>
</template>
<script>
    export default {
        name: "PsLoading",
        props: {
            theme: {
                type: String,
                default: 'border-4 border-t-4 border-text-8 border-t-primary-500',
            },
            loadingSize: {
                type: String,
                default: 'h-8 w-8',
            },

        }
    }
</script>
