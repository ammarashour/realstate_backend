<template>
    <div class="custom-number-input w-full">
        <div class="flex flex-row h-8 w-full rounded relative mt-1" :class="theme">
            <button @click="decrement" data-action="decrement" class="text-gray-600 hover:text-gray-700 hover:bg-gray-200 h-full w-20 cursor-pointer outline-none">
                <span class="m-auto text-2xl font-thin">−</span>
            </button>
            <input type="number" :class="[borderLeft,borderRight]" class="border-t-0 border-b-0 outline-none focus:outline-none text-center w-full font-semibold text-md hover:text-black focus:text-black  md:text-basecursor-default flex items-center text-gray-700  outline-none" :value="number" />
            <button  @click="increment" data-action="increment" class="text-gray-600 hover:text-gray-700 hover:bg-gray-200 h-full w-20 cursor-pointer">
                <span class="m-auto text-2xl font-thin">+</span>
            </button>
        </div>
    </div>
</template>

<script>
import {ref } from 'vue';
export default {
    name: "PsInputCount",
    props: {
        "num" : { type :Number, default : 1 },
        "theme" : { type: String, default : "border border-gray-300" },
        "borderLeft" : { type: String, default : "border border-s-secondary-200"},
        "borderRight" : { type: String, default : "border border-e-secondary-200"},
        "onInput" : Function 
    },
    setup( props ) {
        const number = ref(props.num);
        function decrement() { 
          
            number.value--;
            if(props.onInput != null) {
                props.onInput(number.value, props.id);
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