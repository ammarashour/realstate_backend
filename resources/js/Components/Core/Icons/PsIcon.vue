<style scoped>
svg{
    fill: currentColor;
}
</style>
<template>
  <svg :width="w" :height="h" :viewBox="viewBox"  :class="theme"  :fill="fillOne" xmlns="http://www.w3.org/2000/svg">

    <path :fill-rule="fillRule" :clip-rule="clipRule" :d="path" fill="fill-current" />

  </svg>
</template>

<script>
import icons from './Icons/Icons.json';

export default {
  name: 'AppIcon',
  props: {
    name: String,
    style : {
        type : String,
        default : "width:20px;height:20px"
    },
    h: {
        type: String,
        default : "24"
    },
    w: {
        type: String,
        default : "24"
    },
    cursorPointer: {
        type: String,
        default : ""
    },
    theme: {
        type: String,
        default : ""
    },
    viewBox: {
        type: String,
        default : "0 0 24 24"
    },
    fillOne: {
        type: String,
        default : "none"
    },
    fillRule: {
        type: String,
        default : "evenodd"
    },
    clipRule: {
        type: String,
        default : "evenodd"
    }

  },
  computed: {
    path() {
      return icons[this.name];
    },

  },
};
</script>
