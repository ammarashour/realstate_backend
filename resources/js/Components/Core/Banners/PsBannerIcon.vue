<template>
    <div :class="[computedTheme]" v-show="openBannerBox">
        <div class="flex items-center justify-center px-3 py-3 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <ps-icon-1  :name="computedIconName" w="20" h="20" class="me-2" 
                :theme="iconColor"            
             />             
            <div class=" sm:text-xs text-md">
                <slot />
            </div>
        </div>
    </div>
</template>
<script>
import { ref, computed, watch } from "vue";
import PsIcon1 from "@/Components/Core/Icons/PsIcon1.vue";

export default {
    name: "PsBannerIcon",
    components: {
        PsIcon1
    },
    props: {
        flag: {
            type: String,
            default: ''
        },
        theme: {
            type: String,
            default: 'bg-red-500'
        },
        iconName: {
            type: String,
            default: 'rightalert'
        },
        iconColor: {
            type: String,
            default: "white"
        },
        duration: {
            type: Number,
            default: 3000
        },
        visible: {
            type: Boolean,
        },
        uicomponent: {
            type: Boolean,
        }
    },
    setup(props) {
        const openBannerBox = ref(props.visible);
        
        if(props.uicomponent){
            showAlways();
        }else {
            show();
        }
        
        function showAlways() {
            openBannerBox.value = true;
        }

        function show() {
            openBannerBox.value = true;
            setTimeout(() => {
                openBannerBox.value = false;
            }, props.duration);
            // this.$emit('update:visible', false)                
        }
        
        const computedTheme = computed(() => {
            // If theme is provided, use it, otherwise compute it based on flag
            return (props.flag === 'danger' ? 'bg-red-500' :
                                props.flag === 'warning' ? 'bg-yellow-500' :
                                'bg-green-500');
        });

        const computedIconName = computed(() => {
            return ( props.flag === 'danger' ? 'close-circle':
                                        props.flat === 'warning' ? 'alert-triangle':
                                        'rightalert');
        });

        
        return {
            openBannerBox,
            showAlways,
            show,
            computedTheme,
            computedIconName
        };
    }
}
</script>
