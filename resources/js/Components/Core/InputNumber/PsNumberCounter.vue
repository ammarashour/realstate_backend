<template>
  <div class="flex items-center justify-center gap-x-2">
    <div class="bg-primary-500 rounded-md items-center justify-center">
        <ps-icon name="minus" :class="number <= minimum ? 'text-white' : 'text-white '"  :w='iconSize' :h='iconSize'  @click="decrement" />
    </div>

    <span :class="textSize" class="text-default dark:text-white"> {{ number }} </span>

    <div class="bg-primary-500 rounded-md items-center justify-center">
        <ps-icon name="plus" class="text-white " :w='iconSize' :h='iconSize'  @click="increment" />
    </div>
  </div>
</template>
<script>
import {ref } from 'vue';
import PsIcon from "../icons/PsIcon.vue";
export default {
    name: "PsInputNumber",
    components: { PsIcon },
    props: {
        "minimum" : { type :Number, default : 1 },
        "num" : { type :Number, default : 1 },
        "id" : { type :String, default : '-1' },
        "iconSize" : { type :String, default : '24' },
        "textSize" : { type :String, default : 'text-2xl mx-4 my-auto' },
        "onInput" : Function         
    },
    
    setup( props ) {
        const number = ref(props.num);
        function decrement() { 
            if(number.value <= props.minimum){
            }else{
                number.value--;
                if(props.onInput != null) {
                    props.onInput(number.value, props.id);
                }
            }
            
        }
        function increment() { 
            number.value++;
            
            if(props.onInput != null) {
                props.onInput(number.value, props.id);
            }
        }

        return {
            number,
            increment,
            decrement
        }
    }
}
</script>