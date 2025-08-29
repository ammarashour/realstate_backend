<template>
    <nav class="bg-white border-shadow shadow-xl">
        <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">
            <div class="relative flex items-center justify-between h-14">
                <div class="absolute inset-y-0 left-0 flex items-center sm:hidden">
                    <!-- Mobile menu button-->
                    <button type="button" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white" aria-controls="mobile-menu" aria-expanded="false">
                        <span class="sr-only">Open main menu</span>
                        <!--
                            Icon when menu is closed.

                            Heroicon name: outline/menu

                            Menu open: "hidden", Menu closed: "block"
                        -->
                        <svg class="block h-6 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <!--
                            Icon when menu is open.

                            Heroicon name: outline/x

                            Menu open: "block", Menu closed: "hidden"
                        -->
                        <svg class="hidden h-6 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="flex items-center justify-center sm:items-stretch sm:justify-start">
                    <div class="flex">
                        <div>
                            <div class="absolute">
                                <svg width="37" height="37" viewBox="0 0 37 37" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M36.1247 18.6212C36.1247 28.3583 28.2311 36.2519 18.494 36.2519C8.75681 36.2519 0.863281 28.3583 0.863281 18.6212C0.863281 8.88401 8.75681 0.990479 18.494 0.990479C28.2311 0.990479 36.1247 8.88401 36.1247 18.6212ZM18.494 31.1221C25.398 31.1221 30.9949 25.5252 30.9949 18.6212C30.9949 11.7171 25.398 6.12025 18.494 6.12025C11.5899 6.12025 5.99305 11.7171 5.99305 18.6212C5.99305 25.5252 11.5899 31.1221 18.494 31.1221Z" fill="#6366F1"/>
                                </svg>
                            </div>
                            <div class="relative ms-6 mt-4">
                                <svg width="23" height="24" viewBox="0 0 23 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M5.64986 2.45245L2.3345 6.69813C1.86869 7.29466 1.70345 8.07243 1.8865 8.80682L3.65826 15.9148C3.97199 17.1734 5.20927 17.9721 6.48545 17.7397L13.2508 16.5079L15.9814 21.186C17.2457 23.3521 20.561 22.4583 20.5652 19.9502L20.5827 9.54438C20.5853 7.9778 19.1423 6.808 17.6101 7.13472L11.2511 8.49075L9.97503 3.37135C9.47931 1.38259 6.91131 0.837018 5.64986 2.45245Z" fill="#312E81" stroke="white" stroke-width="2.996"/>
                                </svg>
                            </div>
                        </div>
                        <ps-label class="text-lg font-semibold my-auto ms-3">Quickr</ps-label>
                    </div>
                
                    <div class=" lg:flex hidden items-center  rtl:space-x-reverse space-x-4 ms-4">
                    
                        <ps-input-with-right-icon theme="bg-white border-secondary-200" class="bg-red rounded-full flex" placeholder="Search" >
                            <template #icon >
                                <ps-icon name="search" class='cursor-pointer' theme="text-secondary-400"/>
                            </template>
                        </ps-input-with-right-icon>
                        
                        <ps-label href="#" class="text-base text-secondary-500">Home</ps-label>
                        
                        <ps-dropdown align="right" h="h-72" class='z-50' >
                            <template #select >
                                <div class="flex w-22 h-full items-center" @click='userNameClicked'>
                                    
                                    <ps-label class="text-base text-secondary-500 ">Messages</ps-label>
                                    <ps-icon name="downChervon"  class="ms-1 " w="16" h="16"  />
                                    
                                </div>
                            </template>
                            <template #list >
                                <div
                                    class="rounded-md bg-white shadow-xs w-56 z-50" 
                                    role="menu"
                                    aria-orientation="vertical"
                                    aria-labelledby="options-menu">
                                    <div class="pt-2 z-30 absolute">
                                
                                    </div>
                                    <div class="border-t border-gray-100 "></div>
                                </div>
                            </template>
                        </ps-dropdown>

                        <ps-dropdown align="right" h="h-72" class='z-50' >
                            <template #select >
                                <div class="flex w-22 h-full items-center" @click='userNameClicked'>
                                    
                                    <ps-label class="text-base text-secondary-500">Orders</ps-label>
                                    <ps-icon name="downChervon"  class="ms-1 " w="16" h="16"  />
                                    
                                </div>
                            </template>
                            <template #list >
                                <div
                                    class="rounded-md bg-white shadow-xs w-56 z-50" 
                                    role="menu"
                                    aria-orientation="vertical"
                                    aria-labelledby="options-menu">
                                    <div class="pt-2 z-30 absolute">
                                
                                    </div>
                                    <div class="border-t border-gray-100 "></div>
                                </div>
                            </template>
                        </ps-dropdown>

                        <ps-label href="#" class="text-base text-secondary-500">Balance</ps-label>

                        <ps-label href="#" class="text-base text-secondary-500">Groups</ps-label>
                    
                    </div>
                
                </div>

                <div class="flex items-center">
                    <ps-icon name="info" fill="#1F2937" class='cursor-pointer' />
                    <ps-icon name="bell" fill="#1F2937" class='cursor-pointer ms-5' />
                    <ps-avatar w="w-8" h="h-8" theme="bg-primary-500" class="ms-5">
                        <ps-label textColor="text-primary-100" class=" text-base uppercase">jw</ps-label>
                    </ps-avatar>
                </div>
            </div>
        </div>
    </nav>
</template>
<script>
import PsInputWithRightIcon from '@/Components/Core/Input/PsInputWithRightIcon.vue';
import PsIcon from "@/Components/Core/Icons/PsIcon.vue";
import PsLabel from "@/Components/Core/Label/PsLabel.vue";
import PsDropdown from "@/Components/Core/Dropdown/PsDropdown.vue";
import PsAvatar from '@/Components/Core/Avatar/PsAvatar.vue';

export default {
    name : "PsNavBar",
    components: {
        PsInputWithRightIcon,
        PsIcon,
        PsLabel,
        PsDropdown,
        PsAvatar
    }
    };
</script>