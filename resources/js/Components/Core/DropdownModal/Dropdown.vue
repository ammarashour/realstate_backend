<template>
    <div class="relative inline-block" ref="dropdown" >
        <div @click="clicked"  >
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
                    class="absolute mt-2 rounded-md shadow-lg text-sm overflow-visible border z-20 bg-background dark:bg-backgroundDark"
                    :class="[horizontalAlign == 'right' ? 'origin-top-right right-0 rtl:origin-top-left rtl:left-0' : 'origin-top-left left-0 rtl:origin-top-right rtl:right-0',verticalAlign, h]">
                    <div class="flex flex-col w-68 gap-8">

                        <div class="mt-2.5">
                            <ps-label class="ms-2.5 font-semibold">{{$t('ps_dropdown_modal__filter_option')}}</ps-label>
                            <slot name="list">
                                <div class="py-8 px-2.5 z-30 ">
                                    <!-- Shown Section -->
                                    <div v-if="colFilterOptions.filter(row=>row.hidden===false).length > 0">
                                        <div class="flex justify-between">
                                            <ps-label class="font-semibold" textColor="text-gray-300">{{$t('ps_dropdown_modal__shown')}}</ps-label>
                                            <ps-label class="font-semibold cursor-pointer" textColor="text-primary-500" @click="hideAll">{{$t('ps_dropdown_modal__hide_all')}}</ps-label>
                                        </div>
                                        <template class="mt-4" v-for="(colFilterOption, index) in colFilterOptions.filter(row=>row.hidden===false)"  :key="index">
                                            <div class="flex justify-between font-semibold mt-5 dark:hover:bg-primary-900">
                                                <ps-label>{{ colFilterOption.label }} </ps-label>
                                                <div class="cursor-pointer" @click="colFilters[colFilterOption.key] = !colFilters[colFilterOption.key]"><ps-icon theme="font-semibold" :name="colFilters[colFilterOption.key]?'eyeOff': 'eye-on'"/></div>
                                            </div>
                                        </template>
                                    </div>

                                    <!-- Hidden Section -->
                                    <div class="mt-8.5" v-if="colFilterOptions.filter(row=>row.hidden===true).length > 0">
                                        <div class="flex justify-between">
                                            <ps-label class="font-semibold" textColor="text-gray-300">{{$t('ps_dropdown_modal__hidden')}}</ps-label>
                                            <ps-label class="font-semibold cursor-pointer" textColor="text-primary-500" @click="showAll">{{$t('ps_dropdown_modal__show_all')}}</ps-label>
                                        </div>
                                        <template class="mt-4" v-for="(colFilterOption, index) in colFilterOptions.filter(row=>row.hidden===true)"  :key="index">
                                            <div class="flex justify-between font-semibold mt-5 dark:hover:bg-primary-900">
                                                <ps-label class="ms-2">{{ colFilterOption.label }} </ps-label>
                                                <div class="cursor-pointer" @click="colFilters[colFilterOption.key] = !colFilters[colFilterOption.key]"><ps-icon theme="font-semibold" :name="colFilters[colFilterOption.key]?'eyeOff': 'eye-on'"/></div>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </slot>
                            <div class="px-2.5">
                                <ps-button @click="[changeFilter(), isMenuOpen = !isMenuOpen]" rounded="rounded" class="w-full">{{$t('core__be_btn_confirm')}}</ps-button>
                                <ps-text-button @click="[clearFilter(), isMenuOpen = !isMenuOpen]" class="w-full mt-5" border="" focus="" hover="" colors="text-gray-900">{{$t('core__be_btn_cancel')}}</ps-text-button>
                            </div>
                        </div>
                    </div>
                </div>
            </transition>
    </div>
</template>

<script>
import { ref, onMounted, onUnmounted } from 'vue';
import PsButton from "@/Components/Core/Buttons/PsButton.vue";
import PsTextButton from "@/Components/Core/Buttons/PsTextButton.vue";
import PsLabelHeader6 from "@/Components/Core/Label/PsLabelHeader6.vue";
import PsLabel from "@/Components/Core/Label/PsLabel.vue";
import PsIcon from "@/Components/Core/Icons/PsIcon.vue";

export default {
    name:"Dropdown",
    components: {
        PsButton,
        PsTextButton,
        PsLabelHeader6,
        PsLabel,
        PsIcon
    },
    props: {
        horizontalAlign : {
            type : String,
            default : 'right'
        },
        h: {
            type : String,
            default : ' h-auto'
        },
        verticalAlign: {
            type : String,
            default : ''
        },
        colFilterOptions: {
            type: Object,
        }
    },
    emits : [
        'on-click'
    ],
    data() {
        return{
            colFilters: {},
        }
    },
    created() {
        this.colFilterOptions.map((value, index) => {
            this.colFilters[value.key] = value.hidden
        });
    },
    methods: {

        showAll(){
            let hideData = this.colFilterOptions.filter(row=>row.hidden===true);

            for (let item of hideData) {
                this.colFilters[item.key] = false
            }
        },

        hideAll(){
            let hideData = this.colFilterOptions.filter(row=>row.hidden===false);

            for (let item of hideData) {
                this.colFilters[item.key] = true
            }
        },

        changeFilter(){
            for (let colFilterOption of this.colFilterOptions) {
                colFilterOption.hidden = this.colFilters[colFilterOption.key]
            }
            this.$emit("update:colFilterOptions", this.colFilterOptions);
        },

        clearFilter(){
            this.colFilterOptions.map((value, index) => {
                this.colFilters[value.key] = value.hidden
            });
        }
    },
    setup(_, { emit } ) {

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

        // function changeFilter(){
        //     for (let colFilterOption of props.colFilterOptions) {
        //         colFilterOption.hidden = colFilters[colFilterOption.key]
        //     }
        //     // emit('colFilterOptions');
        // }

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


        return {
            isMenuOpen,
            dropdown,
            hide,
            clicked,
        }
    }
}
</script>
