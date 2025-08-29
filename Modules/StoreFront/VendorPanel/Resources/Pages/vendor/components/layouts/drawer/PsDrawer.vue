<template>
    <div class="flex flex-col">
        
        <div class="flex justify-between cursor-pointer" @click="toggleMobileMenu">
            <div class=""> 
                <slot name="title" ></slot>
            </div>
            <ps-icon v-if="showArrow" :name="arrowIcon" class=" me-4"  />            
        </div>
        <div class=" mt-2" >
            <transition @enter="enter" @leave="leave">
                <div v-if="activeMobileMenu" class="flex flex-col w-full" >                    
                    <slot name="content"></slot>
                </div>
            </transition>
            
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent, ref } from "vue";
// import Velocity from "velocity-animate";
import PsIcon from "@template1/vendor/components/core/icons/PsIcon.vue";

export default defineComponent ({
    components : {
        PsIcon         
    },
    props : {
        isActive : {
            type: Boolean,
            default: false,
        },
        showArrow: {
            type: Boolean,
            default: true,
        },
    },
    setup(props) {
        
        const activeMobileMenu = ref(props.isActive);
        
        const arrowIcon = ref("downArrow");

        if(props.isActive) {
            arrowIcon.value = "upArrow";
        }
        
        function toggleMobileMenu() {
			activeMobileMenu.value = !activeMobileMenu.value;
		}

        function enter(el, done) {
            arrowIcon.value = "upArrow";
			// Velocity(
			// 	el,
			// 	"slideDown",
			// 	{
			// 	duration: 300
			// 	},
			// 	{
			// 	complete: done
			// 	}
			// );
        }
        
		function leave(el, done) {
            arrowIcon.value = "downArrow";
			// Velocity(
			// 	el,
			// 	"slideUp",
			// 	{
			// 	duration: 300
			// 	},
			// 	{
			// 	complete: done
			// 	}
			// );
        }
        
        return {
            arrowIcon,
            activeMobileMenu,
            enter,
            leave,
            toggleMobileMenu
        }

    }
});
</script>