<template>
<div> </div>
<!-- <Head :title="$t('landing_page_module')" />
    <div class="absolute  min-h-screen ">
        <div class="relative  z-20 grid grid-cols-1 xl:grid-cols-2 gap-10 mt-8 sm:mt-18 xl:mt-24 mb-10 sm:mb-28 xl:mb-36">
           
            <div class="flex flex-col ps-4 sm:ps-32 pe-4 sm:pe-32 xl:pe-0 my-auto">
                <div class="flex flex-row items-center mb-10">
                    <div class="xl:mt-0 w-11 h-11 md:w-18 md:h-18 xl:w-20 xl:h-20 me-2 sm:me-4 xl:me-6">
                        <img v-if="landing_page.landing_logo[0]" :src="$page.props.uploadUrl + '/' + landing_page.landing_logo[0].img_path" class="w-11 h-11 md:w-18 md:h-18 xl:w-20 xl:h-20" />                    
                    </div>
                    <div class="">
                        <ps-label class="text-base md:text-2xl xl:text-3xl font-bold" textColor="text-landingPageColor ">{{landing_page.title}} </ps-label>
                    </div>
                    
                </div>
                <ps-label class="text-sm md:text-lg xxl:text-xl font-light" textColor="text-secondary-800 ">{{landing_page.description}} </ps-label>

                <div class="flex flex-row mt-12  rtl:space-x-reverse space-x-4 xl:space-x-8  ">
                    <ps-link-1 target="_blank" v-if="landing_page.ios_link && landing_page.ios_link != ''" :url="landing_page.ios_link">
                    <div class="bg-black rounded-lg flex flex-row py-1.5 px-8  rtl:space-x-reverse space-x-3 items-center justify-center">  
                        <svg width="41" height="41" viewBox="0 0 41 41" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g clip-path="url(#clip0_439_57048)">
                            <path d="M9.26 0.5H31.74C36.5781 0.5 40.5 4.42187 40.5 9.26V31.74C40.5 36.5781 36.5781 40.5 31.74 40.5H9.26C4.42187 40.5 0.5 36.5781 0.5 31.74V9.26C0.5 4.42187 4.42187 0.5 9.26 0.5Z" fill="url(#paint0_linear_439_57048)"/>
                            <path d="M13.319 29.534L13.3228 29.5352L11.9551 31.904C11.4559 32.7686 10.3503 33.0649 9.48561 32.5657C8.62107 32.0666 8.32467 30.961 8.82389 30.0961L9.83139 28.3511L9.92811 28.1838C10.1008 27.9355 10.5269 27.5072 11.3792 27.5877C11.3792 27.5877 13.385 27.8054 13.5301 28.8479C13.5301 28.8479 13.5498 29.191 13.319 29.5341V29.534ZM32.7164 23.484H28.4517C28.1612 23.4644 28.0345 23.3607 27.9845 23.3004L27.9814 23.2949L23.4162 15.3877L23.4103 15.3916L23.1365 14.9991C22.6878 14.3129 21.9753 16.0679 21.9753 16.0679C21.1245 18.0235 22.0961 20.2468 22.4347 20.9188L28.7756 31.9016C29.2747 32.7661 30.3803 33.0625 31.2451 32.5632C32.1097 32.064 32.4061 30.9583 31.9069 30.0936L30.3212 27.3474C30.2904 27.2808 30.237 27.1002 30.5622 27.0994H32.7164C33.7148 27.0994 34.5242 26.29 34.5242 25.2916C34.5242 24.2932 33.7148 23.484 32.7164 23.484ZM24.4329 25.9415C24.4329 25.9415 24.6606 27.0994 23.7798 27.0994H8.01436C7.01592 27.0994 6.20654 26.29 6.20654 25.2916C6.20654 24.2932 7.01592 23.4838 8.01436 23.4838H12.0675C12.7219 23.446 12.8769 23.0682 12.8769 23.0682L12.8806 23.07L18.1712 13.9063L18.1697 13.906C18.2661 13.729 18.1858 13.5616 18.1719 13.5352L16.4245 10.5088C15.9253 9.64427 16.2215 8.53848 17.0862 8.03942C17.9509 7.5402 19.0565 7.8363 19.5558 8.70098L20.3661 10.1046L21.175 8.70348C21.6742 7.83895 22.7798 7.54255 23.6445 8.04192C24.5092 8.54114 24.8054 9.64661 24.3062 10.5113L16.944 23.2629C16.9119 23.3405 16.902 23.4624 17.1415 23.4838H21.5422L21.5431 23.5268C21.5431 23.5268 24.0865 23.5663 24.4329 25.9415Z" fill="white"/>
                            </g>
                            <defs>
                            <linearGradient id="paint0_linear_439_57048" x1="20.5" y1="0.5" x2="20.5" y2="40.5" gradientUnits="userSpaceOnUse">
                            <stop stop-color="#17C9FB"/>
                            <stop offset="1" stop-color="#1A74E8"/>
                            </linearGradient>
                            <clipPath id="clip0_439_57048">
                            <rect width="40" height="40" fill="white" transform="translate(0.5 0.5)"/>
                            </clipPath>
                            </defs>
                        </svg>
                        <div>   
                            <p class="text-xs xl:text-sm text-white ">Download on </p>
                            <p class="text-sm md:text-base xl:text-lg font-bold text-white ">App Store </p>
                        </div>
                    </div>
                    </ps-link-1>

                    <ps-link-1 target="_blank" v-if="landing_page.gps_link && landing_page.gps_link != ''"  :url="landing_page.gps_link">
                    <div class="bg-black rounded-lg flex flex-row py-1.5 px-8  rtl:space-x-reverse space-x-3 items-center justify-center">  
                        <svg width="40" height="41" viewBox="0 0 40 41" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g clip-path="url(#clip0_439_57055)">
                            <path d="M2.7638 1.13244C2.30931 1.60965 2.03662 2.35955 2.03662 3.31397V37.6958C2.03662 38.6502 2.30931 39.4001 2.7638 39.8773L2.87742 39.9909L22.1476 20.7207V20.2663L2.87742 0.996094L2.7638 1.13244Z" fill="url(#paint0_linear_439_57055)"/>
                            <path d="M28.5557 27.1523L22.1475 20.7214V20.2669L28.5784 13.8359L28.7148 13.9268L36.3274 18.2444C38.5089 19.4715 38.5089 21.494 36.3274 22.7438L28.7148 27.0615L28.5557 27.1523Z" fill="url(#paint1_linear_439_57055)"/>
                            <path d="M28.7148 27.0595L22.1475 20.4922L2.76367 39.876C3.46812 40.6259 4.67251 40.7395 5.99051 39.9669L28.7148 27.0595Z" fill="url(#paint2_linear_439_57055)"/>
                            <path d="M28.7148 13.9498L5.99051 1.04248C4.64978 0.292575 3.46812 0.383471 2.76367 1.13337L22.1475 20.5172L28.7148 13.9498Z" fill="url(#paint3_linear_439_57055)"/>
                            <path opacity="0.2" d="M28.5557 26.9258L5.99051 39.7423C4.71796 40.4694 3.60447 40.424 2.87729 39.765L2.76367 39.8786L2.87729 39.9922C3.60447 40.6512 4.74068 40.6967 5.99051 39.9695L28.7148 27.0621L28.5557 26.9258Z" fill="black"/>
                            <path opacity="0.12" d="M2.7638 39.6487C2.30931 39.1715 2.03662 38.4216 2.03662 37.4672V37.6944C2.03662 38.6489 2.30931 39.3988 2.7638 39.876L2.87742 39.7623L2.7638 39.6487ZM36.3048 22.5146L28.5558 26.9231L28.6922 27.0595L36.3048 22.7419C37.3956 22.1283 37.9409 21.3103 37.9409 20.4922C37.85 21.2421 37.3047 21.9465 36.3048 22.5146Z" fill="black"/>
                            <path opacity="0.25" d="M5.99064 1.24667L36.3048 18.4716C37.2819 19.0398 37.85 19.7442 37.9409 20.4941C37.9409 19.676 37.3956 18.858 36.3048 18.2444L5.99064 1.01943C3.80911 -0.207682 2.03662 0.814909 2.03662 3.29185V3.51909C2.03662 1.04215 3.80911 0.01956 5.99064 1.24667Z" fill="white"/>
                            </g>
                            <defs>
                            <linearGradient id="paint0_linear_439_57055" x1="20.4196" y1="2.93993" x2="-5.67622" y2="29.0358" gradientUnits="userSpaceOnUse">
                            <stop stop-color="#00A0FF"/>
                            <stop offset="0.007" stop-color="#00A1FF"/>
                            <stop offset="0.26" stop-color="#00BEFF"/>
                            <stop offset="0.512" stop-color="#00D2FF"/>
                            <stop offset="0.76" stop-color="#00DFFF"/>
                            <stop offset="1" stop-color="#00E3FF"/>
                            </linearGradient>
                            <linearGradient id="paint1_linear_439_57055" x1="39.1402" y1="20.5055" x2="1.51543" y2="20.5055" gradientUnits="userSpaceOnUse">
                            <stop stop-color="#FFE000"/>
                            <stop offset="0.409" stop-color="#FFBD00"/>
                            <stop offset="0.775" stop-color="#FFA500"/>
                            <stop offset="1" stop-color="#FF9C00"/>
                            </linearGradient>
                            <linearGradient id="paint2_linear_439_57055" x1="25.14" y1="24.0783" x2="-10.2478" y2="59.4661" gradientUnits="userSpaceOnUse">
                            <stop stop-color="#FF3A44"/>
                            <stop offset="1" stop-color="#C31162"/>
                            </linearGradient>
                            <linearGradient id="paint3_linear_439_57055" x1="-2.1175" y1="-10.3265" x2="13.6847" y2="5.47575" gradientUnits="userSpaceOnUse">
                            <stop stop-color="#32A071"/>
                            <stop offset="0.069" stop-color="#2DA771"/>
                            <stop offset="0.476" stop-color="#15CF74"/>
                            <stop offset="0.801" stop-color="#06E775"/>
                            <stop offset="1" stop-color="#00F076"/>
                            </linearGradient>
                            <clipPath id="clip0_439_57055">
                            <rect width="40" height="40" fill="white" transform="translate(0 0.5)"/>
                            </clipPath>
                            </defs>
                        </svg>
                        <div>   
                            <p class="text-xs xl:text-sm text-white ">Get it on </p>
                            <p class="text-sm md:text-base xl:text-lg font-bold text-white ">Google Play </p>
                        </div>
                    </div>
                    </ps-link-1>

                </div>

                 <div class="flex xl:hidden  px-10 sm:px-32 md:px-40 mt-8 mb-24 xl:mb-0">
                <img v-if="landing_page.landing_cover[0]" :src="$page.props.uploadUrl + '/' + landing_page.landing_cover[0].img_path" class="w-full object-contain" />                    
            </div>

            </div>
            <div class="hidden xl:flex pe-32">
                <img v-if="landing_page.landing_cover[0]" :src="$page.props.uploadUrl + '/' + landing_page.landing_cover[0].img_path" class="w-full object-contain" />                    
            </div>
            
        </div>

        <div class="relative z-20 flex flex-row items-center  rtl:space-x-reverse space-x-8 justify-center mb-6 xl:mb-8 text-secondary-800">
            <ps-link-1 v-if="landing_page.yt_link && landing_page.yt_link != ''" target="_blank" :url="landing_page.yt_link">
                <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                <g clip-path="url(#clip0_11172_468516)">
                <rect width="32" height="32" rx="4" fill="white"/>
                <path d="M28.7238 8.66404C29.3332 11.04 29.3332 16 29.3332 16C29.3332 16 29.3332 20.96 28.7238 23.336C28.3852 24.6494 27.3945 25.6827 26.1398 26.032C23.8612 26.6667 15.9998 26.6667 15.9998 26.6667C15.9998 26.6667 8.1425 26.6667 5.85984 26.032C4.59984 25.6774 3.6105 24.6454 3.27584 23.336C2.6665 20.96 2.6665 16 2.6665 16C2.6665 16 2.6665 11.04 3.27584 8.66404C3.6145 7.35071 4.60517 6.31737 5.85984 5.96804C8.1425 5.33337 15.9998 5.33337 15.9998 5.33337C15.9998 5.33337 23.8612 5.33337 26.1398 5.96804C27.3998 6.32271 28.3892 7.35471 28.7238 8.66404ZM13.3332 20.6667L21.3332 16L13.3332 11.3334V20.6667Z" fill="#121212"/>
                </g>
                <defs>
                <clipPath id="clip0_11172_468516">
                <rect width="32" height="32" rx="4" fill="white"/>
                </clipPath>
                </defs>
                </svg>

            </ps-link-1>
            
            <ps-link-1 v-if="landing_page.fb_link && landing_page.fb_link != ''" target="_blank" :url="landing_page.fb_link">
                <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                <g clip-path="url(#clip0_11172_468520)">
                <rect width="32" height="32" rx="4" fill="white"/>
                <path d="M20.536 28V18.712H23.6467L24.112 15.1013H20.536V12.7973C20.536 11.752 20.8267 11.0387 22.3253 11.0387H24.2373V7.80933C23.3117 7.71113 22.3815 7.6635 21.4507 7.66667C18.6947 7.66667 16.808 9.34933 16.808 12.44V15.1013H13.6907V18.712H16.808V28H5.33333C4.97971 28 4.64057 27.8595 4.39052 27.6095C4.14048 27.3594 4 27.0203 4 26.6667V5.33333C4 4.97971 4.14048 4.64057 4.39052 4.39052C4.64057 4.14048 4.97971 4 5.33333 4H26.6667C27.0203 4 27.3594 4.14048 27.6095 4.39052C27.8595 4.64057 28 4.97971 28 5.33333V26.6667C28 27.0203 27.8595 27.3594 27.6095 27.6095C27.3594 27.8595 27.0203 28 26.6667 28H20.536Z" fill="#121212"/>
                </g>
                <defs>
                <clipPath id="clip0_11172_468520">
                <rect width="32" height="32" rx="4" fill="white"/>
                </clipPath>
                </defs>
                </svg>

            </ps-link-1>

            <ps-link-1 v-if="landing_page.tw_link && landing_page.tw_link != ''" target="_blank" :url="landing_page.tw_link">
               <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                <g clip-path="url(#clip0_11172_468524)">
                <rect width="32" height="32" rx="4" fill="white"/>
                <path d="M29.5494 7.54136C28.5315 7.99162 27.452 8.28732 26.3467 8.41869C27.5117 7.72194 28.3837 6.62538 28.8001 5.33336C27.7067 5.98402 26.5081 6.44002 25.2587 6.68669C24.4195 5.7888 23.3072 5.19331 22.0947 4.9928C20.8821 4.7923 19.6373 4.99801 18.5538 5.57796C17.4702 6.15791 16.6086 7.0796 16.1029 8.19975C15.5972 9.31989 15.4757 10.5757 15.7574 11.772C13.5402 11.6609 11.3712 11.0847 9.39112 10.0809C7.41106 9.0771 5.66424 7.66809 4.26407 5.94536C3.76846 6.79662 3.50801 7.76433 3.5094 8.74936C3.5094 10.6827 4.4934 12.3907 5.9894 13.3907C5.10408 13.3628 4.23825 13.1237 3.46407 12.6934V12.7627C3.46434 14.0503 3.9099 15.2982 4.7252 16.2948C5.54051 17.2914 6.67538 17.9753 7.9374 18.2307C7.11556 18.4534 6.25381 18.4862 5.4174 18.3267C5.77323 19.435 6.46675 20.4043 7.40086 21.0988C8.33497 21.7934 9.46291 22.1784 10.6267 22.2C9.47004 23.1085 8.14564 23.78 6.72924 24.1763C5.31285 24.5725 3.83224 24.6857 2.37207 24.5094C4.921 26.1486 7.8882 27.0189 10.9187 27.016C21.1761 27.016 26.7854 18.5187 26.7854 11.1494C26.7854 10.9094 26.7787 10.6667 26.7681 10.4294C27.8599 9.64025 28.8022 8.66272 29.5507 7.54269L29.5494 7.54136Z" fill="#121212"/>
                </g>
                <defs>
                <clipPath id="clip0_11172_468524">
                <rect width="32" height="32" rx="4" fill="white"/>
                </clipPath>
                </defs>
                </svg>

            </ps-link-1>
        </div>
        

        <div class="absolute bottom-0 w-full z-10">
            <div class="text-landingPageColor ">
                <svg class="flex sm:hidden " width="100%" height="100%" viewBox="0 0 390 85" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M0 34.6661C147.469 49.772 245.24 -19.1733 390 6.49854V84.9986H0V34.6661Z" fill="fill-current"/>
                </svg>

                <svg class="hidden sm:flex xl:hidden" width="100%" height="100%" viewBox="0 0 834 172" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M0 82.8274C315.356 115.563 524.435 -43.7318 834 11.9012V172H0V82.8274Z" fill="fill-current"/>
                </svg>

                <svg  class="hidden xl:flex xxl:hidden " width="100%" height="100%" viewBox="0 0 1440 200" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M0 96.3109C544.5 134.376 905.5 -50.8509 1440 13.8386V200H0V96.3109Z" fill="landingPageColor"/>
                </svg>

                <svg class="hidden xxl:flex" width="100%" height="100%" viewBox="0 0 1920 216" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M0 104.016C726 145.126 1207.33 -54.919 1920 14.9456V216H0V104.016Z" fill="fill-current"/>
                </svg>
            </div>
        </div>

    </div> -->
</template>

<script >
import { defineComponent } from 'vue'
// import PsLabel from "@/Components/Core/Label/PsLabel.vue";
// import PsIcon from "@/Components/Core/Icons/PsIcon.vue";
// import PsLink1 from '@/Components/Core/Link/PsLink1.vue';
// import { Head, Link } from '@inertiajs/vue3';

export default defineComponent({
    // props: ['landing_page'],
    // components: {
    //     PsLabel,
    //     PsIcon,
    //     PsLink1,
    //     Head
    // },
    setup() {
        
    },
})
</script>
<style scoped>
svg{
    fill: currentColor;
}
</style>