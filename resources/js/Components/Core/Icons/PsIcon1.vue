// AppIcon.vue
// https://materialdesignicons.com/
<template>
  <svg :width="w" :height="h" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
 
    <path fill-rule="evenodd" clip-rule="evenodd" :d="path" :fill="theme" />

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
        default : "width:24px;height:24px"
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
        default : "#1F2937"
    }

  },
  computed: {
    path() {
      return icons[this.name];
    },
   
  },
};
</script>
