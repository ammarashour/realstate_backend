<template>
    <div class="relative inline-block text-left text-secondary-500 " ref="dropdown" >
        <div v-if="disabled" >
            <slot name="select"   />
        </div>
        <div v-else @click="clicked"  >
            <slot name="select"   />
        </div>
            <transition
                enter-active-class="transition ease-out duration-100"
                enter-class="transform opacity-0 scale-95"
                enter-to-class="transform opacity-100 scale-100"
                leave-active-class="transition ease-in duration-75"
                leave-class="transform opacity-100 scale-100"
                leave-to-class="transform opacity-0 scale-95">
                <div v-if="isMenuOpen"

                    class="absolute mt-2 rounded-md shadow-lg text-sm overflow-hidden border  z-20 overflow-y-auto bg-background dark:bg-backgroundDark"
                    :class="[horizontalAlign == 'right' ? 'ltr:origin-top-right ltr:right-0 rtl:origin-top-left rtl:left-0' : horizontalAlign == 'left' ? 'ltr:origin-top-left ltr:left-0 rtl:origin-top-right rtl:right-0' : 'ltr:origin-top-right ltr:right-0 rtl:origin-top-right rtl:right-0',verticalAlign, h]">
                        <div class="flex flex-col">
                            <slot name="filter" />
                            <div @click="isMenuOpen = !isMenuOpen" >
                                <div v-if="placeholder" @click="clearSelectedValue"
                                    class="w-96 flex py-4 px-2 hover:bg-primary-000 dark:hover:bg-secondary-700 cursor-pointer items-center ps-4 text-secondary-800 dark:text-secondary-100 text-sm font-medium">
                                    {{ placeholder }}
                                </div>
                                <slot name="list" />
                            </div>
                            <slot name="loadmore" />
                        </div>
                </div>
                <!--  -->
            </transition>

    </div>
</template>

<script>
import { ref, onMounted, onUnmounted } from 'vue';

export default {
    name:"PsDropdown",
    props: {
        horizontalAlign : {
            type : String,
            default : 'left'
        },
        h: {
            type : String,
            default : ' h-56 '
        },
        verticalAlign: {
            type : String,
            default : ''
        },
        disabled:{
            type : Boolean,
            default : false
        },
        placeholder:{
            type : String,
            default : ''
        }
    },
    emits : [
        'on-click'
    ],
    setup(_, { emit }) {

        const isMenuOpen = ref(false);
        function hide() {
            if(isMenuOpen.value) {
                isMenuOpen.value = !isMenuOpen.value;
            }
        }

        const dropdown = ref();
        function close(e) {
            if(!dropdown.value.contains(e.target)) {
                hide();
            }
        }

        onMounted(() => {
            document.addEventListener('click', close)
        });

        onUnmounted(() => {
            document.removeEventListener('click', close)
        });

        function clicked() {

            isMenuOpen.value = !isMenuOpen.value;
            emit('on-click');

        }

        function clearSelectedValue() {
            emit('clear');
        }

        return {
            isMenuOpen,
            dropdown,
            hide,
            clicked,
            clearSelectedValue,
            // psValueHolder
        }
    }
}
</script>
