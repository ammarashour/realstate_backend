<template>
    <div :style="'width:' + w + 'px;height:' + h + 'px;'" class="fill-current " :class = "textColor">
        <slot />
    </div>
    
</template>

<style module>
/* .svg {
    fill: currentColor;
    height: 1em;
    margin-top: -4px;
    vertical-align: middle;
    width: 1em;
} */
</style>

<script>

export default {
    name: "PsIconAssets",
    components: {
        // InlineSvg
    },
    props: {           
        h: {
            type: String, 
            default : "20"
        }, 
        w: {
            type: String, 
            default : "20"
        },
        textColor: {
            type: String, 
            default : " dark:text-secondaryDark-white"
        }
    },
    mounted() {
        // this.$el.firstChild.classList.add(this.$style.svg)
        // this.$el.firstChild.classList.add('fill-current')
    },
}
</script>