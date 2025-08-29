<template>
    <nav class="bg-white border-shadow shadow-xl">
        <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">
            <div class="flex flex-row justify-between items-center h-14 w-full">
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
                <div class="lg:flex hidden  rtl:space-x-reverse space-x-8">
                    <div class="flex items-center">
                        <ps-icon name="offer" class='cursor-pointer text-secondary-500'/>
                        <ps-label href="#" textColor="text-secondary-500" class="ms-1.5 text-base">Offers</ps-label>
                    </div>

                    <div class="flex items-center">
                        <ps-icon name="creditCard" fill-rule="evenodd" clip-rule="evenodd" class='cursor-pointer text-secondary-500 '/>
                        <ps-label href="#" textColor="text-secondary-500" class="ms-1.5 text-base">Card</ps-label>
                    </div>
                    
                    <div class="flex items-center">
                        <ps-icon name="transaction" fill="#6B7280" fill-rule="evenodd" class='cursor-pointer text-secondary-500'/>
                        <ps-label href="#" textColor="text-secondary-500" class="ms-1.5 text-base">Transaction</ps-label>
                    </div>
   
                    <div class="flex items-center">
                        <ps-icon name="booth" fill="#6B7280" class='cursor-pointer text-secondary-500 mt-1'/>
                        <ps-label href="#" textColor="text-secondary-500" class="ms-1.5 text-base">Booth</ps-label>
                    </div>

                    <div class="flex items-center">
                        <ps-icon name="refer" fill="#6B7280" class='cursor-pointer text-secondary-500'/>
                        <ps-label href="#" textColor="text-secondary-500" class="ms-1.5 text-base">Refer</ps-label>
                    </div>

                </div>

                <div class="flex items-center">
                    <ps-icon name="search" fill="#1F2937" class='cursor-pointer' />
                    <ps-icon name="whitebell" fill="#1F2937" class='cursor-pointer ms-5' />
                    <img class="ms-5 h-8 w-8 rounded-full" src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="">
                </div>
            </div>
        </div>
    </nav>
</template>
<script>
import PsIcon from "@/Components/Core/Icons/PsIcon.vue";
import PsLabel from "@/Components/Core/Label/PsLabel.vue";
import PsButton from "@/Components/Core/Buttons/PsButton.vue";

export default {
    name : "PsNavBar",
    components: {
        PsIcon,
        PsLabel,
        PsButton,
    }
};
</script>