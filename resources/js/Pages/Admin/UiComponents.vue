<template>
    <ps-layout>
    <div>
        <!-- Start Typegraphy -->
        <ps-label-title class="mt-4"> Typegraphy </ps-label-title>
        <ps-label-header-1 textColor="text-secondary-900"> Display 1 </ps-label-header-1>
        <ps-label-header-2 textColor="text-secondary-900"> Display 2 </ps-label-header-2>
        <ps-label-header-3 textColor="text-secondary-900"> Display 3 </ps-label-header-3>
        <ps-label-header-1 textColor="text-secondary-900"> H1 </ps-label-header-1>
        <ps-label-header-2 textColor="text-secondary-900"> H2 </ps-label-header-2>
        <ps-label-header-3 textColor="text-secondary-900"> H3 </ps-label-header-3>
        <ps-label-header-4 textColor="text-secondary-900"> H4 </ps-label-header-4>
        <ps-label-header-5 textColor="text-secondary-900"> H5 </ps-label-header-5>
        <ps-label-header-6 textColor="text-secondary-900"> H6 </ps-label-header-6>
        <ps-label-title textColor="text-secondary-900"> Title 1 </ps-label-title>
        <ps-label-title-2 textColor="text-secondary-900"> Title 2 </ps-label-title-2>
        <ps-label-title-2 textColor="text-secondary-900"> Text 1 </ps-label-title-2>
        <ps-label-title-2 textColor="text-secondary-900"> Text 2 </ps-label-title-2>
        <ps-label textColor="text-secondary-900"> Code </ps-label>
        <ps-link-1 url="#" textColor="text-blue-500"> Link </ps-link-1>
        <ps-label-caption-3 textColor="text-secondary-900"> Caption </ps-label-caption-3>
        <ps-label textColor="text-secondary-900"> Small </ps-label>
        <!-- Start Typegraphy -->

        <!-- Start Color Design -->
        <ps-label-title class="my-4">
            Primary Colors
        </ps-label-title>

         <div class=" flex flex-row mt-4">
            <div class=" flex flex-col me-20">
                primary
                <div class="h-8 w-36 bg-primary-900" > </div>
                <div class="h-8 w-36 bg-primary-800" > </div>
                <div class="h-8 w-36 bg-primary-700" > </div>
                <div class="h-8 w-36 bg-primary-600" > </div>
                <div class="h-8 w-36 bg-primary-500" > </div>
                <div class="h-8 w-36 bg-primary-400" > </div>
                <div class="h-8 w-36 bg-primary-300" > </div>
                <div class="h-8 w-36 bg-primary-200" > </div>
                <div class="h-8 w-36 bg-primary-100" > </div>
                <div class="h-8 w-36 bg-primary-000" > </div>
            </div>
             <div class=" flex flex-col me-20">
                secondary
                <div class="h-8 w-36 bg-secondary-900" > </div>
                <div class="h-8 w-36 bg-secondary-800" > </div>
                <div class="h-8 w-36 bg-secondary-700" > </div>
                <div class="h-8 w-36 bg-secondary-600" > </div>
                <div class="h-8 w-36 bg-secondary-500" > </div>
                <div class="h-8 w-36 bg-secondary-400" > </div>
                <div class="h-8 w-36 bg-secondary-300" > </div>
                <div class="h-8 w-36 bg-secondary-200" > </div>
                <div class="h-8 w-36 bg-secondary-100" > </div>
                <div class="h-8 w-36 bg-secondary-000" > </div>
            </div>

        </div>
        <!-- End Color Design -->

        <!-- Start Icon Design -->
        <ps-label-title class="my-4"> Icons </ps-label-title>
        <div class=" flex flex-col mt-4">
            <div class="w-112 grid grid-cols-8 mb-4 items-center ">
                <ps-icon name="downChervon" />
                <ps-icon name="upChervon" />
                <ps-icon name="leftChervon" />
                <ps-icon name="rightChervon" />
                <ps-icon name="image" />
                <ps-icon name="imagePlus" />
                <ps-icon name="camera" />
                <ps-icon name="cameraOff" />
            </div>
            <div class="w-112 grid grid-cols-8 mb-4 items-center ">
                <ps-icon name="check" />
                <ps-icon name="cross" />
                <ps-icon name="target" />
                <ps-icon name="home" />
                <ps-icon name="lock" />
                <ps-icon name="shieldCheck" />
                <ps-icon name="bug" />
                <ps-icon name="eyeOff" />
            </div>
            <div class="w-112 grid grid-cols-8 mb-4 items-center ">
                <ps-icon name="leftArrow" />
                <ps-icon name="rightArrow" />
                <ps-icon name="downArrow" />
                <ps-icon name="upArrow" />
                <ps-icon name="scan" />
                <ps-icon name="faceId" />
                <ps-icon name="fingerPrint" />
                <ps-icon name="eye-on" />
            </div>
            <div class="w-112 grid grid-cols-8 mb-4 items-center ">
                <ps-icon name="plus" />
                <ps-icon name="minus" />
                <ps-icon name="loader" />
                <ps-icon name="arrowTopLeft" />
                <ps-icon name="drag" />
                <ps-icon name="doubleArrowLeft" />
                <ps-icon name="doubleArrowRight" />
                <ps-icon name="expand" />
            </div>

            <div class="w-112 grid grid-cols-8 mb-4 items-center ">
                <ps-icon name="maximize" />
                <ps-icon name="minimize" />
                <ps-icon name="caption" />
                <ps-icon name="repead" />
                <ps-icon name="creditCard" />
                <ps-icon name="creditCardScan"  />
                <ps-icon name="creditCardPlus" />
                <ps-icon name="wallet" />
            </div>

            <div class="w-112 grid grid-cols-8 mb-4 items-center ">
                <ps-icon name="replay" />
                <ps-icon name="forward" />
                <ps-icon name="miniPlayer" />
                <ps-icon name="shuffle" />
                <ps-icon name="cash" />
                <ps-icon name="percent" />
                <ps-icon name="dollar" />
                <ps-icon name="gps" />
            </div>

            <div class="w-112 grid grid-cols-8 mb-4 items-center ">
                <ps-icon name="heart" />
                <ps-icon name="star" />
                <ps-icon name="filter" />
                <ps-icon name="shoppingBag" />
                <ps-icon name="pin" />
                <ps-icon name="pinOff" />
                <ps-icon name="compas" />
                <ps-icon name="pinCheck" />
            </div>

             <div class="w-112 grid grid-cols-8 mb-4 items-center ">
                <ps-icon name="checkCircle" />
                <ps-icon name="alertTriangle" />
                <ps-icon name="info" />
                <ps-icon name="crossCircle" />
                <ps-icon name="mapView" />
                <ps-icon name="landMark" />
                <ps-icon name="direction" />
                <ps-icon name="route" />
            </div>

            <div class="w-112 grid grid-cols-8 mb-4 items-center ">
                <ps-icon name="day" />
                <ps-icon name="night" />
                <ps-icon name="shoppingCart" />
                <ps-icon name="gift" />
                <ps-icon name="userSquare" />
                <ps-icon name="signIn" />
                <ps-icon name="signOut" />
                <ps-icon name="retry" />
            </div>

            <div class="w-112 grid grid-cols-8 mb-4 items-center ">
                <ps-icon name="folder" />
                <ps-icon name="fileUpload" />
                <ps-icon name="folderOpen" />
                <ps-icon name="folderCross" />
                <ps-icon name="phone" />

            </div>

            <div class="w-112 grid grid-cols-8 mb-4 items-center ">
                <ps-icon name="folderCheck" />
                <ps-icon name="folderDownload" />
                <ps-icon name="fileCross" />
                <ps-icon name="folderUpload" />
                <ps-icon name="phoneSlash"  />

            </div>

            <div class="w-112 grid grid-cols-8 mb-4 items-center ">
                <ps-icon name="videoCamera"  />

            </div>


        </div>
        <!-- End Icon Design -->

        <div class="flex flex-row">

            <ps-button class="me-4" colors="bg-green-400 text-white" padding="p-1.5" hover="hover:outline-none hover:ring hover:ring-green-100" focus="focus:outline-none focus:ring focus:ring-green-200" > <ps-icon name="editPencil" /> </ps-button>

            <ps-button colors="bg-red-400 text-white" padding="p-1.5" hover="hover:outline-none hover:ring hover:ring-red-100" focus="focus:outline-none focus:ring focus:ring-red-200" > <ps-icon name="trash"  /> </ps-button>

        </div>

        <!-- Start Accordion Design -->
        <ps-label-title class="my-4"> Accordion </ps-label-title>
        <div class="flex">
            <ps-accordion class="w-72 ">
                <template #title>
                    Accordion
                </template>
                <template #description>
                    Business-to-consumer business plan investor focus alpha conversion ecosystem monetization
                </template>
            </ps-accordion>

            <ps-accordion class="w-72 ms-8" theme="border-t border-t-indigo-500">
                <template #title>
                    Accordion
                </template>
                <template #description>
                    Business-to-consumer business plan investor focus alpha conversion ecosystem monetization
                </template>
            </ps-accordion>

        </div>

        <ps-accordion class="w-72 mt-8"
        theme="bg-indigo-500 rounded-lg"
        titletheme="text-sm font-semibold text-white"
        descriptiontheme="text-sm leading-6 text-white">
            <template #title>
                Accordion
            </template>
            <template #description>
                Business-to-consumer business plan investor focus alpha conversion ecosystem monetization
            </template>
        </ps-accordion>
        <!-- End Accordion Design -->

        <!-- Start Box Shadow Design -->
        <ps-label-header-7 class="my-4">Shadow</ps-label-header-7>
        <div class="w-full h-52 flex items-center bg-gray-300 ">
            <div class="ms-32 flex flex-auto  rtl:space-x-reverse space-x-16">
                <div class="w-48 h-26 flex items-center bg-white shadow-sm text-center rounded-x " > </div>
                <div class="w-48 h-26 bg-white shadow text-center rounded-x" > </div>
                <div class="w-48 h-26 bg-white shadow-md text-center rounded-x " > </div>
            </div>

        </div>
        <div class="w-full h-52 flex items-center bg-gray-300 ">
            <div class="ms-32 flex flex-auto  rtl:space-x-reverse space-x-16">
                <div class="w-48 h-26 bg-white shadow-lg text-center rounded-x " > </div>
                <div class="w-48 h-26 bg-white shadow-xl text-center rounded-x " > </div>
                <div class="w-48 h-26 bg-white shadow-2xl text-center rounded-x " > </div>
            </div>

        </div>
        <!-- End Box Shadow Design -->

        <!-- Start Alert Design -->
        <ps-label-header-7 class="my-4">Alert</ps-label-header-7>
        <ps-alert textTheme="text-red-500" class="border-red-500 text-red-100" theme="bg-red-50">
            <ps-icon-1 theme="#EF4444" name="alert-triangle"  w='24' h='24' class="text-sm"  />
            <div class="ms-4">
                <ps-label class="font-semibold">Title</ps-label>
                <ps-label class="mt-1 font-normal" textColor="text-secondary-600">Paragraph for banner above the nav</ps-label>
            </div>
        </ps-alert>

        <ps-alert theme="bg-blue-100" textTheme="text-blue-500" class="mt-10 border-blue-500">
            <ps-icon-1 theme="#3B82F6" name="warning-icon"  w='24' h='24' class="text-sm"  />
            <div class="mx-4">
                <ps-label class="font-semibold">Title</ps-label>
                <ps-label class="mt-1 font-normal" textColor="text-secondary-600">Paragraph for banner above the nav</ps-label>
            </div>
        </ps-alert>

        <ps-alert theme="bg-green-100" textTheme="text-green-500" class="mt-10 border-green-500">
            <ps-icon-1 theme="#10B981" name="rightalert"  w='24' h='24' class="text-sm"  />
            <div class="mx-4">
                <ps-label class="font-semibold">Title</ps-label>
                <ps-label class="mt-1 font-normal" textColor="text-secondary-600">Paragraph for banner above the nav</ps-label>
            </div>
        </ps-alert>

        <ps-alert theme="bg-yellow-100" textTheme="text-yellow-500" class="mt-10 border-yellow-500">
            <ps-icon-1 theme="#F59E0B" name="alert-triangle"  w='24' h='24' class="text-sm"  />
            <div class="mx-4">
                <ps-label class="font-semibold">Title</ps-label>
                <ps-label class="mt-1 font-normal" textColor="text-secondary-600">Paragraph for banner above the nav</ps-label>
            </div>
        </ps-alert>

        <ps-alert theme="bg-grey-100" textTheme="text-dark-500" class="mt-10 h-20 border-grey-500">
            <ps-icon-1 theme="#6B7280" fill-rule="evenodd" clip-rule="evenodd" name="flag"  w='24' h='24' class="text-sm"  />
            <div class="mx-4">
                <ps-label class="font-semibold">Title</ps-label>
                <ps-label  class="mt-1 font-normal" textColor="text-secondary-600">Paragraph for banner above the nav</ps-label>
            </div>
        </ps-alert>
        <!-- End Alert Design -->

        <!-- Start Card Design -->
        <ps-label-title class="mb-4">Card</ps-label-title>
        <div class="flex flex-auto w-full">
            <ps-card class="w-64 h-auto">
                <div class="bg-background rounded overflow-hidden shadow-lg">
                    <img class="w-full" width="350" height="222" src="https://www.products.panacea-soft.co/flutter-buysell/uploads/thumbnail3x/food_1(1).png" alt="Sunset in the mountains">
                    <div class="p-4">
                        <ps-label class="font-bold text-md sm:text-xs">Title goes to 2 line for a eye catchy Heading</ps-label>
                        <ps-label class="font-light text-xs mt-2">Joe James</ps-label>
                        <ps-label class="font-light text-xs mt-2">90 minutes watch</ps-label>
                    </div>
                </div>
            </ps-card>

            <ps-card-1>
                <template #relative >
                    <img class="w-full h-40" width="350" height="222" src="https://www.products.panacea-soft.co/flutter-buysell/uploads/thumbnail3x/food_1(1).png" alt="Sunset in the mountains">
                    <div class="absolute bottom-4 left-4 ">
                        <ps-button class="normal-case text-white" theme="rounded-md  bg-purple-500">Badge</ps-button>
                    </div>
                </template>

                <div class="px-4 pt-6 dark:bg-backgroundDark">
                    <ps-label class="font-bold text-md sm:text-xs">Title goes to 2 line for a eye catchy Heading</ps-label>
                    <ps-label class="font-light text-xs mt-2">Joe James</ps-label>
                    <ps-label class="font-light text-xs mt-2">90 minutes watch</ps-label>
                </div>
            </ps-card-1>
        </div>
        <!-- End Card Design -->

        <!-- Start Navbar Design -->
        <ps-label-header-7 class="my-4">Navigation</ps-label-header-7>
        <ps-nav-bar />
        <ps-nav-bar-2 class="mt-16" />
        <ps-nav-bar-3 class="mt-16" />
        <ps-nav-bar-4 class="mt-16" />
        <ps-nav-bar-5 class="mt-16" />
        <!-- End Navbar Design -->

        <!-- Start Loading Design -->
        <ps-label-title class="mb-4 mt-4">Loading</ps-label-title>
        <div class="flex flex-row  rtl:space-x-reverse space-x-4 mt-6">

            <div class="animate-spin rounded-full h-9 w-9 border-b-2 border-primary-5"></div>

            <svg role="status" class="w-9 h-9 text-text-8 animate-spin dark:text-text-3 fill-primary-5" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
                <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="#6366F1"/>
            </svg>

            <div class="loader ease-linear rounded-full border-4 border-t-4 border-text-8 border-t-primary-5 h-9 w-9"></div>

            <div class=" animate-pulse w-9 h-9 bg-error-5 rounded-full flex justify-center items-center text-center content-center">
                <ps-icon fill-rule="evenodd" clip-rule="evenodd" fill="white" name="cross-circle-fill"  theme="#FFFFFF" class="flex justify-center mx-auto my-auto"  />
            </div>

            <div class=" w-9 h-9 bg-warning-5 rounded-full flex justify-center animate-pulse">
                <svg width="18" height="20" class="my-auto " viewBox="0 0 18 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M9.82955 2.67975C9.46043 2.04219 8.53993 2.04219 8.17081 2.67975L1.89489 13.52C1.52501 14.1589 1.98602 14.9585 2.72426 14.9585H15.2761C16.0143 14.9585 16.4753 14.1589 16.1055 13.52L9.82955 2.67975ZM6.94479 1.96995C7.85956 0.389896 10.1408 0.389903 11.0556 1.96995L17.3315 12.8102C18.2482 14.3935 17.1056 16.3751 15.2761 16.3751H2.72426C0.894717 16.3751 -0.247798 14.3935 0.668868 12.8102L6.94479 1.96995ZM9.00017 5.79181C9.39137 5.79181 9.70851 6.10894 9.70851 6.50014V9.83347C9.70851 10.2247 9.39137 10.5418 9.00017 10.5418C8.60897 10.5418 8.29184 10.2247 8.29184 9.83347V6.50014C8.29184 6.10894 8.60897 5.79181 9.00017 5.79181ZM9.00018 13.1668C9.46042 13.1668 9.83351 12.7937 9.83351 12.3335C9.83351 11.8732 9.46042 11.5001 9.00018 11.5001C8.53994 11.5001 8.16684 11.8732 8.16684 12.3335C8.16684 12.7937 8.53994 13.1668 9.00018 13.1668Z" fill="white"/>
                </svg>
            </div>

            <div class="w-9 h-9 bg-success-5 rounded-full flex justify-center animate-pulse">
                <svg width="16" height="12" class="my-auto" viewBox="0 0 16 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M15.1453 0.477691C15.4337 0.742035 15.4532 1.1901 15.1888 1.47848L6.02215 11.4785C5.8916 11.6209 5.70854 11.7038 5.51539 11.708C5.32223 11.7122 5.13575 11.6373 4.99913 11.5007L0.832466 7.33404C0.555845 7.05742 0.555845 6.60893 0.832466 6.33231C1.10909 6.05569 1.55758 6.05569 1.8342 6.33231L5.47775 9.97586L14.1445 0.521203C14.4089 0.232827 14.8569 0.213346 15.1453 0.477691Z" fill="white"/>
                </svg>
            </div>

        </div>
        <div class=" flex flex-row  rtl:space-x-reverse space-x-4 mt-3">
            <ps-loading />
            <ps-loading loadingSize ="h-10 w-10" />
            <ps-loading loadingSize ="h-12 w-12" />
            <ps-loading loadingSize ="h-14 w-14" />
        </div>

        <div class=" flex flex-row  rtl:space-x-reverse space-x-4 mt-3">
            <ps-loading theme ="border-8 border-t-8 border-text-8 border-t-success-5" loadingSize ="h-14 w-14" />
            <ps-loading theme ="border-4 border-t-4 border-text-8 border-t-error-5" loadingSize ="h-12 w-12" />
            <ps-loading theme ="border-2 border-t-2 border-text-8 border-t-warning-5" loadingSize ="h-10 w-10" />
        </div>
        <!-- End Loading Design -->

        <!-- Start Badge Design -->
        <ps-label-header-7 class="my-4">Badge</ps-label-header-7>
        <div class="flex  rtl:space-x-reverse space-x-2">
            <ps-badge>Badge Test</ps-badge>
            <ps-badge theme="bg-blue-700" rounded="rounded"> Badge </ps-badge>
            <ps-badge theme="bg-purple-600" rounded="rounded"> Badge </ps-badge>
            <ps-badge theme="bg-green-500" rounded="rounded"> Badge </ps-badge>
            <ps-badge theme="bg-red-600" rounded="rounded"> Badge </ps-badge>
            <ps-badge theme="bg-yellow-500" rounded="rounded"> Badge </ps-badge>
            <ps-badge theme="bg-pink-500" rounded="rounded"> Badge </ps-badge>
            <ps-badge theme="bg-gray-800" rounded="rounded"> Badge </ps-badge>
        </div>
        <div class="flex  rtl:space-x-reverse space-x-2 mt-4">
            <ps-badge-icon theme="bg-blue-700" iconName="badge" iconColor="white" rounded="rounded"> Badge </ps-badge-icon>
            <ps-badge-icon theme="bg-purple-600" iconName="badge" iconColor="white" rounded="rounded"> Badge </ps-badge-icon>
            <ps-badge-icon theme="bg-green-500" iconName="badge" iconColor="white" rounded="rounded"> Badge </ps-badge-icon>
            <ps-badge-icon theme="bg-red-600" iconName="badge" iconColor="white" rounded="rounded"> Badge </ps-badge-icon>
            <ps-badge-icon theme="bg-yellow-500" iconName="badge" iconColor="white" rounded="rounded"> Badge </ps-badge-icon>
            <ps-badge-icon theme="bg-pink-500" iconName="badge" iconColor="white" rounded="rounded"> Badge </ps-badge-icon>
            <ps-badge-icon theme="bg-gray-800" iconName="badge" iconColor="white" rounded="rounded"> Badge </ps-badge-icon>
        </div>
        <div class="flex  rtl:space-x-reverse space-x-2 mt-4">
            <ps-badge-icon-1 theme="bg-blue-700" iconName="badge" iconColor="white" rounded="rounded"> Badge </ps-badge-icon-1>
            <ps-badge-icon-1 theme="bg-purple-600" iconName="badge" iconColor="white" rounded="rounded"> Badge </ps-badge-icon-1>
            <ps-badge-icon-1 theme="bg-green-500" iconName="badge" iconColor="white" rounded="rounded"> Badge </ps-badge-icon-1>
            <ps-badge-icon-1 theme="bg-red-600" iconName="badge" iconColor="white" rounded="rounded"> Badge </ps-badge-icon-1>
            <ps-badge-icon-1 theme="bg-yellow-500" iconName="badge" iconColor="white" rounded="rounded"> Badge </ps-badge-icon-1>
            <ps-badge-icon-1 theme="bg-pink-500" iconName="badge" iconColor="white" rounded="rounded"> Badge </ps-badge-icon-1>
            <ps-badge-icon-1 theme="bg-gray-800" iconName="badge" iconColor="white" rounded="rounded"> Badge </ps-badge-icon-1>
        </div>
        <!-- End Badge Design -->

        <!-- Start Button Design -->
        <ps-label-header-7 class="my-4">
            Buttons
        </ps-label-header-7>
        <div class="flex flex-wrap space-s-2 mb-4" >
            <ps-text-button >Text Button</ps-text-button>
        </div>
        <div class="flex flex-wrap space-s-2" >
            <ps-button colors="bg-primary-500 dark:bg-green-100 text-white dark:text-green-900" focus="" >Button</ps-button>
            <ps-button colors="bg-primary-600 dark:bg-green-200 text-white dark:text-green-800"  >Button</ps-button>
            <ps-button colors="bg-primary-700 dark:bg-green-300 text-white dark:text-green-700"  >Button</ps-button>
            <ps-button colors="bg-primary-500 dark:bg-green-400 text-white dark:text-green-600" focus="">Button</ps-button>
            <ps-button colors="bg-secondary-100 dark:bg-green-400 text-secondary-300 dark:text-green-600" focus="">Button</ps-button>
        </div>

        <div class="flex flex-wrap space-s-2 mt-4" >
            <ps-icon-button border="border border-secondary-200 " colors="bg-white dark:bg-green-100 text-secondary-800 dark:text-green-900"  leftIcon="starFill">Button</ps-icon-button>
            <ps-icon-button border="border border-secondary-200 " colors="bg-secondary-50 dark:bg-green-200 text-secondary-800 dark:text-green-800"  leftIcon="starFill">Button</ps-icon-button>
            <ps-icon-button colors="bg-secondary-200 dark:bg-green-300 text-secondary-800 dark:text-green-700"  leftIcon="starFill">Button</ps-icon-button>
            <ps-icon-button border="border border-2 border-primary-300 " colors="bg-white dark:bg-green-400 text-secondary-800 dark:text-green-600" leftIcon="starFill">Button</ps-icon-button>
            <ps-icon-button border="border border-secondary-200 " shadow="shadow-sm" :disabled="true" colors="bg-white dark:bg-green-400 text-secondary-800 dark:text-green-600" leftIcon="starFill" >Button</ps-icon-button>
        </div>

        <!-- Start icon buttons -->
        <ps-label-title class="mt-4">
           Icon Buttons Using PsIconButton
        </ps-label-title>
        <div class="flex flex-wrap  rtl:space-x-reverse space-x-24 mt-4" >
            <ps-icon-button colors="bg-primary-500 dark:bg-green-100 text-white justify-center w-10 h-10 dark:text-green-900" focus=""  leftIcon="setting" padding="px-2"> </ps-icon-button>
            <ps-icon-button colors="bg-primary-600 dark:bg-green-200 text-white w-10 h-10 dark:text-green-800"  leftIcon="setting" padding="px-2"/>
            <ps-icon-button colors="bg-primary-700 dark:bg-green-300 text-white w-10 h-10 dark:text-green-700"   leftIcon="setting" padding="px-2"/>
            <ps-icon-button colors="bg-primary-500 dark:bg-green-400 text-white w-10 h-10 dark:text-green-600" focus=""  leftIcon="setting" padding="px-2"/>
            <ps-icon-button colors="bg-secondary-300 dark:bg-green-100 text-secondary-800 w-10 h-10 dark:text-green-300" :disabled="true" hover=""  leftIcon="setting" padding="px-2"/>
        </div>

        <ps-label-title class="mt-4">
            Button group
        </ps-label-title>

        <div class="mt-4 flex flex-row  rtl:space-x-reverse space-x-10">
            <ps-button-group bgradius="rounded-lg" padding="py-2 px-4">
                <template #leftButton >
                    <div @click="clicked('btn1')" class="me-8 "> Button </div>
                </template >
                <template #middleButton>
                    <div @click="clicked('btn2')" class="me-8 "> Button </div>
                </template>
                <template #rightButton>
                    <div @click="clicked('btn3')"> Button </div>
                </template>
            </ps-button-group>

            <ps-button-group bgradius="rounded-lg" padding="py-2.5 px-4" colors="bg-primary-600 dark:bg-green-400 text-white dark:text-green-600" focus="">
                <template #leftButton >
                    <div @click="clicked('btn1')" class="flex flex-row me-8 "><ps-icon name="setting" class="me-2.5" /> Button </div>
                </template >
                <template #middleButton>
                    <div @click="clicked('btn2')" class="flex flex-row me-8 "> <ps-icon name="setting" class="me-2.5" /> Button </div>
                </template>
                <template #rightButton>
                    <div @click="clicked('btn3')" class="flex flex-row"><ps-icon name="setting" class="me-2.5" /> Button </div>
                </template>
            </ps-button-group>

            <ps-button-group bgradius="rounded-lg" padding="py-2.5 px-4">
                <template #leftButton >
                    <div @click="clicked('btn1')" class="flex flex-row me-8 "> Button <ps-icon name="setting" class="ms-2.5" /> </div>
                </template >
                <template #middleButton>
                    <div @click="clicked('btn2')" class="flex flex-row me-8 ">  Button <ps-icon name="setting" class="ms-2.5" /> </div>
                </template>
                <template #rightButton>
                    <div @click="clicked('btn3')" class="flex flex-row"> Button <ps-icon name="setting" class="ms-2.5" /> </div>
                </template>
            </ps-button-group>
        </div>
        <!-- End Buttons -->

        <!-- Start Banner Design -->
        <ps-label-header-7 class="my-4">Banner</ps-label-header-7>
        <ps-banner :uicomponent="true" :visible="true" theme="bg-red-500" class="text-white flex items-center">Paragraph for banner above the nav.</ps-banner>

        <ps-banner :uicomponent="true" :visible="true" theme="bg-blue-500" class="mt-4 text-white flex items-center">
            Paragraph for banner above the nav. <a href="#" class="underline">Link to Redirect</a>
        </ps-banner>

        <ps-banner-icon :uicomponent="true" :visible="true" iconName="cross-circle-fill" theme="bg-yellow-500" class="mt-4 text-white" iconColor="white">
            Paragraph for banner above the nav. <a href="#" class="underline">Link to Redirect</a>
        </ps-banner-icon>
        <!-- End Banner Design -->

        <!-- Start Breadcrumbs Design -->
        <ps-label-header-7 class="my-4">Breadcrumbs</ps-label-header-7>
        <div class="grid grid-cols gap-4">
            <ps-breadcrumb-2 :items="breadcrumb" />

            <ps-breadcrumb-2 class="bg-secondary-200 w-10 h-7 p-1" :items="breadcrumb" />

            <ps-breadcrumb-2 :items="breadcrumb1" iconName="bookopen" class="my-auto" iconColor="#6366F1" />

            <ps-breadcrumb-2 :items="breadcrumb" iconName="book" class="w-20 h-8 rounded border border-2 border-primary-300 py-1 px-2" />

            <ps-breadcrumb-2 class="my-auto" iconName="bookopen" iconColor="#D1D5DB" :items="breadcrumb2" />

            <ps-breadcrumb-2 class="my-auto" iconName="book-blank" iconColor="#9CA3AF" :items="breadcrumb3" />

        </div>

        <div class="flex flex-auto  w-1/2 mt-4">


        </div>
        <!-- End Breadcrumbs Design -->

        <!-- Start Search Design -->
        <ps-label-header-7 class="my-4">Search</ps-label-header-7>
        <ps-input-with-left-icon theme="bg-white" class="w-96 h-10 flex shadow-md" rounded="rounded-lg" placeholder="Search" >
            <template #icon  >
                <ps-icon name="search" class='cursor-pointer mt-1.5' />
            </template>
        </ps-input-with-left-icon>

        <ps-search theme="bg-white" class="w-96 mt-9" placeholder="Search"></ps-search>

        <ps-input-with-right-icon theme="bg-white" class="w-96 mt-9 rounded-lg flex" rounded="rounded-lg"  placeholder="Search" >
            <template #icon >
                <ps-icon name="search" class='cursor-pointer mt-1.5'/>
            </template>
        </ps-input-with-right-icon>

        <ps-search-icon class="w-96 h-10 mt-9 shadow-md" rounded="rounded-lg">
            <template #icon >
                <ps-icon-1 theme="#1F2937" w="21" h="18" name="filterSearch" class='cursor-pointer'/>
            </template>
        </ps-search-icon>

        <ps-search-button class="mt-9 w-96 shadow-md" inputWidth="w-96">
            <template #icon >
                <ps-icon-1 theme="#1F2937" w="21" h="18" name="filterSearch" class='cursor-pointer'/>
            </template>
            <template #button>
                <ps-button class="text-white" theme="bg-purple-500" rounded="rounded-r-lg">
                    <ps-icon name="search" class='cursor-pointer'/>
                </ps-button>
            </template>
        </ps-search-button>
        <!-- End Search Design -->

        <!-- Start Dropdown Menu -->
        <ps-label-header-7 class="my-4">DropDown</ps-label-header-7>

        <div class="flex  rtl:space-x-reverse space-x-4">

            <ps-dropdown horizontalAlign="left" :disabled="true" verticalAlign="bottom-12" > <!-- heigh of ps-dropdown-select is 12-->
                <template #select>
                    <ps-dropdown-select cursor="cursor-not-allowed" text="text-sm font-normal text-secondary-500" :disabled="true" :selectedValue=" 'Input Field ' " w="w-72"  />
                </template>
                <template #list>
                    <div
                        class="rounded-md bg-white shadow-xs w-90"
                        role="menu"
                        aria-orientation="vertical"
                        aria-labelledby="options-menu"
                    >
                        <div class="pt-2">
                            <div
                                v-for="i in 5"
                                :key="i"
                                class="flex py-4 px-2 hover:bg-red-100 cursor-pointer items-center"
                                @click="onItemClick(i)"
                            >
                                <img width="300px" height="200px" alt="Placeholder" src="https://s.svgbox.net/hero-outline.svg?ic=currency-rupee" class="h-6 w-6"/>
                                <span class="ms-2" :class="i == selectedIndex ? 'text-red-500' : 'text-primary-500'">
                                    Default
                                </span>
                            </div>
                        </div>
                        <div class="border-t border-gray-100"></div>
                    </div>
                </template>
            </ps-dropdown>

            <ps-dropdown  >
                <template #select>
                    <ps-dropdown-select border="border border-2 border-primary-500" rounded="rounded" opacity="opacity-50" text="text-sm font-normal text-secondary-500" :selectedValue=" 'Input name ' " w="w-72"  />
                </template>
                <template #list>
                    <div
                        class="rounded-md bg-white shadow-xs w-90"
                        role="menu"
                        aria-orientation="vertical"
                        aria-labelledby="options-menu"
                    >
                        <div class="pt-2">
                            <div
                                v-for="i in 5"
                                :key="i"
                                class="flex py-4 px-2 hover:bg-red-100 cursor-pointer items-center"
                                @click="onItemClick(i)"
                            >
                                <img width="300px" height="200px" alt="Placeholder" src="https://s.svgbox.net/hero-outline.svg?ic=currency-rupee" class="h-6 w-6"/>
                                <span class="ms-2" :class="i == selectedIndex ? 'text-red-500' : 'text-primary-500'">
                                    {{ $t("ui_collection__transaction_history") }} {{ i }}
                                </span>
                            </div>
                        </div>
                        <div class="border-t border-gray-100"></div>
                    </div>
                </template>
            </ps-dropdown>

            <ps-dropdown  >
                <template #select>
                    <ps-dropdown-select border="border border-2 border-red-500" rounded="rounded" opacity="opacity-50" text="text-sm font-normal text-secondary-500" :selectedValue=" 'Input name ' " w="w-72"  />
                </template>
                <template #list>
                    <div
                        class="rounded-md bg-white shadow-xs w-90"
                        role="menu"
                        aria-orientation="vertical"
                        aria-labelledby="options-menu"
                    >
                        <div class="pt-2">
                            <div
                                v-for="i in 5"
                                :key="i"
                                class="flex py-4 px-2 hover:bg-red-100 cursor-pointer items-center"
                                @click="onItemClick(i)"
                            >
                                <img width="300px" height="200px" alt="Placeholder" src="https://s.svgbox.net/hero-outline.svg?ic=currency-rupee" class="h-6 w-6"/>
                                <span class="ms-2" :class="i == selectedIndex ? 'text-red-500' : 'text-primary-500'">
                                    {{ $t("ui_collection__transaction_history") }} {{ i }}
                                </span>
                            </div>
                        </div>
                        <div class="border-t border-gray-100"></div>
                    </div>
                </template>
            </ps-dropdown>

        </div>

         <div class="flex  rtl:space-x-reverse space-x-4 mt-4">

            <ps-dropdown horizontalAlign="left"  > <!-- heigh of ps-dropdown-select is 12-->
                <template #select>
                    <ps-dropdown-select leftIcon="setting" padding="py-2.5 px-4" text="text-sm" :selectedValue=" ' Dropdown ' " w="w-40"  />
                </template>
                <template #list>
                    <div
                        class="rounded-md bg-white shadow-xs w-90"
                        role="menu"
                        aria-orientation="vertical"
                        aria-labelledby="options-menu"
                    >
                        <div class="pt-2">
                            <div
                                v-for="i in 5"
                                :key="i"
                                class="flex py-4 px-2 hover:bg-red-100 cursor-pointer items-center"
                                @click="onItemClick(i)"
                            >
                                <img width="300px" height="200px" alt="Placeholder" src="https://s.svgbox.net/hero-outline.svg?ic=currency-rupee" class="h-6 w-6"/>
                                <span class="ms-2" :class="i == selectedIndex ? 'text-red-500' : 'text-primary-500'">
                                    {{ $t("ui_collection__transaction_history") }} {{ i }}
                                </span>
                            </div>
                        </div>
                        <div class="border-t border-gray-100"></div>
                    </div>
                </template>
            </ps-dropdown>

            <ps-dropdown horizontalAlign="left" > <!-- heigh of ps-dropdown-select is 12-->
                <template #select>
                    <ps-dropdown-select leftIcon="setting" border="border border-1 border-primary-500" padding="py-2.5 px-4" text="text-sm" :selectedValue=" ' Dropdown ' " w="w-40"  />
                </template>
                <template #list>
                    <div
                        class="rounded-md bg-white shadow-xs w-90"
                        role="menu"
                        aria-orientation="vertical"
                        aria-labelledby="options-menu"
                    >
                        <div class="pt-2">
                            <div
                                v-for="i in 5"
                                :key="i"
                                class="flex py-4 px-2 hover:bg-red-100 cursor-pointer items-center"
                                @click="onItemClick(i)"
                            >
                                <img width="300px" height="200px" alt="Placeholder" src="https://s.svgbox.net/hero-outline.svg?ic=currency-rupee" class="h-6 w-6"/>
                                <span class="ms-2" :class="i == selectedIndex ? 'text-red-500' : 'text-primary-500'">
                                    {{ $t("ui_collection__transaction_history") }} {{ i }}
                                </span>
                            </div>
                        </div>
                        <div class="border-t border-gray-100"></div>
                    </div>
                </template>
            </ps-dropdown>

            <ps-dropdown horizontalAlign="left" > <!-- heigh of ps-dropdown-select is 12-->
                <template #select>
                    <ps-dropdown-select leftIcon="setting" border="border border-1 border-red-500" padding="py-2.5 px-4" text="text-sm" :selectedValue=" ' Dropdown ' " w="w-40"  />
                </template>
                <template #list>
                    <div
                        class="rounded-md bg-white shadow-xs w-90"
                        role="menu"
                        aria-orientation="vertical"
                        aria-labelledby="options-menu"
                    >
                        <div class="pt-2">
                            <div
                                v-for="i in 5"
                                :key="i"
                                class="flex py-4 px-2 hover:bg-red-100 cursor-pointer items-center"
                                @click="onItemClick(i)"
                            >
                                <img width="300px" height="200px" alt="Placeholder" src="https://s.svgbox.net/hero-outline.svg?ic=currency-rupee" class="h-6 w-6"/>
                                <span class="ms-2" :class="i == selectedIndex ? 'text-red-500' : 'text-primary-500'">
                                    {{ $t("ui_collection__transaction_history") }} {{ i }}
                                </span>
                            </div>
                        </div>
                        <div class="border-t border-gray-100"></div>
                    </div>
                </template>
            </ps-dropdown>

            <ps-dropdown horizontalAlign="left" > <!-- heigh of ps-dropdown-select is 12-->
                <template #select>
                    <ps-dropdown-select leftIcon="setting" padding="py-2.5 px-4" text="text-sm" placeholder=" " w="w-20"  />
                </template>
                <template #list>
                    <div
                        class="rounded-md bg-white shadow-xs w-90"
                        role="menu"
                        aria-orientation="vertical"
                        aria-labelledby="options-menu"
                    >
                        <div class="pt-2">
                            <div
                                v-for="i in 5"
                                :key="i"
                                class="flex py-4 px-2 hover:bg-red-100 cursor-pointer items-center"
                                @click="onItemClick(i)"
                            >
                                <img width="300px" height="200px" alt="Placeholder" src="https://s.svgbox.net/hero-outline.svg?ic=currency-rupee" class="h-6 w-6"/>
                                <span class="ms-2" :class="i == selectedIndex ? 'text-red-500' : 'text-primary-500'">
                                    {{ $t("ui_collection__transaction_history") }} {{ i }}
                                </span>
                            </div>
                        </div>
                        <div class="border-t border-gray-100"></div>
                    </div>
                </template>
            </ps-dropdown>

        </div>

        <ps-label-title class="my-4"> DropDown Menu </ps-label-title>
        <div class="flex  rtl:space-x-reverse space-x-4">

            <ps-dropdown horizontalAlign="left" verticalAlign="bottom-12" > <!-- heigh of ps-dropdown-select is 12-->
                <template #select>
                    <ps-drop-down-select-menu  colors="bg-primary-500 dark:bg-primary-200 text-white dark:text-green-800" padding="py-1.5 px-4" rightIcon="rightChervon" class="w-48" >  Default  </ps-drop-down-select-menu>
                </template>
                <template #list>
                    <div
                        class="rounded-md bg-white shadow-xs w-90"
                        role="menu"
                        aria-orientation="vertical"
                        aria-labelledby="options-menu"
                    >
                        <div class="border-t border-gray-100"></div>
                    </div>
                </template>
            </ps-dropdown>

            <ps-dropdown horizontalAlign="left" verticalAlign="bottom-12" > <!-- heigh of ps-dropdown-select is 12-->
                <template #select>
                    <ps-drop-down-select-menu  colors="bg-primary-500 dark:bg-primary-200 text-white dark:text-green-800" padding="py-1.5 px-4"  leftIcon="sign-out" rightIcon="rightChervon" class="w-48" >  With ico  </ps-drop-down-select-menu>
                </template>
                <template #list>
                    <div
                        class="rounded-md bg-white shadow-xs w-90"
                        role="menu"
                        aria-orientation="vertical"
                        aria-labelledby="options-menu"
                    >
                        <div class="border-t border-gray-100"></div>
                    </div>
                </template>
            </ps-dropdown>

            <ps-dropdown >
                <template #select>
                    <ps-drop-down-select-menu  colors="bg-primary-500 text-white" border="border-none" rightIcon="rightChervon" class="w-40 h-14" >
                        <div class="flex flex-col ps-2">
                            <ps-label class="text-start" textColor="text-white"> Default </ps-label>
                            <ps-label class="text-xs" textColor="text-white"> Short Description </ps-label>
                        </div>
                    </ps-drop-down-select-menu>
                </template>
                <template #list>
                    <div
                        class="rounded-md bg-white shadow-xs w-90"
                        role="menu"
                        aria-orientation="vertical"
                        aria-labelledby="options-menu"
                    >
                        <div class="border-t border-gray-100"></div>
                    </div>
                </template>
            </ps-dropdown>

        </div>
        <!-- End Dropdown Menu -->

        <!-- Start Input and Input Group -->
        <ps-label-header-7 class="my-4">Input</ps-label-header-7>

        <div class="flex  rtl:space-x-reverse space-x-4">

            <ps-input
                class="w-60 h-10 "
                v-model:value="input"
                type="text"
                placeholder="Input Name" />

            <ps-input
                class="w-60 h-10 "
                defaultBorder="border border-primary-500"
                v-model:value="input"
                type="text"
                placeholder="Input Name"
                />


            <ps-input-with-left-icon theme="w-60 h-10 bg-white shadow-none" class="flex" placeholder="Input Name" >
                <template #icon  >
                    <ps-icon name="user-square" class='cursor-pointer mt-1.5 me-2.5 text-secondary-500' />
                </template>
            </ps-input-with-left-icon>

            <ps-input-with-left-icon theme="w-60 h-10 bg-white shadow-none" defaultBorder="border border-red-500" class="flex" placeholder="Input Name" >
                <template #icon  >
                    <ps-icon name="user-square" class='cursor-pointer mt-1.5 me-2.5 text-secondary-500' />
                </template>
            </ps-input-with-left-icon>

        </div>

        <ps-label-title class="my-4"> Input Group </ps-label-title>
        <div class="flex  rtl:space-x-reverse space-x-4">

            <ps-input-group class="w-80 h-9 rounded"
                value=""
                type="text"
                placeholder="Input Name ..."
                background="border border-gray-400">
                <template #leftButton >
                    <ps-label class="px-3 py-1 text-base text-secondary-500 font-normal border border-e-secondary-400"> https:// </ps-label>
                </template >

                <template #rightButton>
                    <ps-label class="px-3 py-1 text-base text-secondary-500 font-normal border border-s-secondary-400"> .com </ps-label>
                </template>

            </ps-input-group>

            <ps-input-group class="w-80 h-9"
                    value=""
                    type="text"
                    placeholder="Placeholder ..."
                    theme="rounded " line="hidden"
                    background="border border-secondary-200 rounded"
                >

                 <template #rightButton>
                    <ps-dropdown >
                        <template #select>
                            <ps-dropdown-select border="border border-s-secondary-200" rounded="rounded-r-md" :selectedValue=" 'Label ' + selectedIndex" w="w-26 h-9"  />
                        </template>
                        <template #list>
                            <div
                                class="rounded-md bg-white shadow-xs w-32"
                                role="menu"
                                aria-orientation="vertical"
                                aria-labelledby="options-menu"
                            >
                                <div class="pt-2">
                                    <div
                                        v-for="i in 5"
                                        :key="i"
                                        class="flex py-4 px-2 hover:bg-red-100 cursor-pointer items-center"
                                        @click="onItemClick(i)"
                                    >
                                        <span class="ms-2" :class="i == selectedIndex ? 'text-red-500' : 'text-primary-500'">
                                            Label {{ i }}
                                        </span>
                                    </div>
                                </div>
                                <div class="border-t border-gray-100"></div>
                            </div>
                        </template>
                    </ps-dropdown>
                 </template>

            </ps-input-group>

        </div>
        <!-- End Input and Input Group -->

        <!-- Start Modals -->
        <ps-label-header-7 class="my-4"> Action Modals </ps-label-header-7>
        <div class="flex flex-wrap  rtl:space-x-reverse space-x-2 mt-4" >
            <ps-button @click="confirmClicked">Confirm</ps-button>
            <ps-button @click="dangerClicked" colors="bg-red-500 text-white"  hover="hover:outline-none hover:ring hover:ring-red-100" focus="focus:outline-none focus:ring focus:ring-red-300">Danger</ps-button>
            <ps-button @click="warningClicked"  colors="bg-yellow-500 text-white">Warning</ps-button>
            <ps-button @click="actionClicked" >Only Confirm</ps-button>
            <ps-button @click="csvUploadClicked" >CSV Upload Modal</ps-button>
            <ps-button @click="imageUploadClicked" >Image Upload Modal</ps-button>
        </div>

        <ps-label-header-7 class="my-4"> Message Modals </ps-label-header-7>
        <div class="flex flex-wrap  rtl:space-x-reverse space-x-2 mt-4" >
            <ps-button @click="successClicked" colors="bg-green-500 text-white"  hover="hover:outline-none hover:ring hover:ring-green-100" focus="focus:outline-none focus:ring focus:ring-green-300">Success</ps-button>
            <ps-button @click="errorClicked" colors="bg-red-500 text-white"  hover="hover:outline-none hover:ring hover:ring-red-100" focus="focus:outline-none focus:ring focus:ring-red-300">Error</ps-button>
            <ps-button @click="messageClicked">Message</ps-button>
            <ps-button @click="loadingClicked">Loading</ps-button>
        </div>
        <!-- End Modals -->

        <!-- Start Pagination Design -->
        <ps-label-header-7 class="mb-4 mt-4">Pagination</ps-label-header-7>

        <ps-pagination-1 class="mt-2" borders="border border-1 border-secondary-300"></ps-pagination-1>

        <ps-pagination v-show="3 > 2"  value="1" totalRecords="20" perPageOptions="10" />

        <!-- End Pagination Design -->

        <!-- Start Indicator Design -->
        <ps-label-header-7 class="mb-4 mt-4">Steps Indicator</ps-label-header-7>
        <div class="flex  rtl:space-x-reverse space-x-2">
            <ps-step-status class="bg-white border-blue-500 w-8 h-8 p-1">
                <template #icon>
                <ps-icon class="text-white text-center cursor-pointer" w="16" h="16" name="check" />
                </template>
            </ps-step-status>

            <ps-step-status class="bg-purple-500 border-purple-500 w-8 h-8 p-1">
                <template #icon class="m-2.5">
                <ps-icon w="10" h="10" viewBox="0 0 10 10" class="text-white m-1.5" name="bullet" />
                </template>
            </ps-step-status>

             <ps-step-status class="bg-white border border-primary-500 w-8 h-8 p-1">
                <template #icon>
                    <div class="flex items-center text-primary-500 cursor-pointer" >
                        <ps-icon name="check"  />
                    </div>
                </template>
            </ps-step-status>

            <ps-step-status class="bg-red-500 flex items-center w-8 h-8">
                <template #icon>
                <div class="flex items-center text-white cursor-pointer" >
                        <ps-icon name="cross"   />
                    </div>
                </template>
            </ps-step-status>

            <ps-step-status class="bg-white border-purple-500 w-8 h-8 p-1">
                <template #icon>
                <ps-label class="text-white text-center cursor-pointer" textColor="text-purple-500">1</ps-label>
                </template>
            </ps-step-status>

            <ps-step-status class="bg-white border-blue-500 w-8 h-8 p-1">
                <template #icon>
                <ps-label class="text-white text-center cursor-pointer" textColor="text-blue-500">5</ps-label>
                </template>
            </ps-step-status>
        </div>

        <div class="flex flex-rows  rtl:space-x-reverse space-x-2 mt-3">
            <div class="flex cols-span-6 items-center">
                <ps-icon w="22" h="22" name="bullet" theme="#D1D5DB" />
                <ps-icon w="22" h="22" name="bullet" theme="#6366F1" />
                <ps-icon w="22" h="22" name="bullet" theme="#EF4444" />
            </div>
            <div class="w-full">
                <ps-line class="w-1/4 mt-2 " theme="bg-blue-500"/>
                <ps-line class="w-1/4 mt-2 " theme="bg-blue-100"/>
                <ps-line class="w-1/4 mt-2 " theme="bg-red-500"/>
                <ps-line class="w-1/4 mt-2 " />
            </div>
        </div>


        <div class="grid grid-cols-3 gap-4">
            <div>
                <ps-step class="w-72" theme="border border-grey-500">
                    <template #icon>
                        <ps-icon class=" text-center cursor-pointer" w="16" h="16" name="close" />
                    </template>
                </ps-step>

                <ps-step class="w-72" theme="border border-grey-500">
                    <template #icon>
                        <ps-icon class=" text-center cursor-pointer" w="16" h="16" name="close" />
                    </template>
                    <template #text>
                        <ps-label class=" text-center cursor-pointer"> Title </ps-label>
                    </template>
                </ps-step>

                <ps-step class="w-72" theme="border border-grey-500">
                    <template #icon>
                        <ps-label class="text-white text-center cursor-pointer text-sm mx-1.5" >1</ps-label>
                    </template>
                    <template #text>
                        <ps-label class=" text-center cursor-pointer"> Title </ps-label>
                    </template>
                </ps-step>
            </div>

            <div>
                <ps-step class="w-72" theme="border border-primary-500">
                    <template #icon>
                        <ps-icon class=" text-center cursor-pointer text-primary-500" w="16" h="16" name="close" theme="#6366F1"  />
                    </template>
                </ps-step>

                <ps-step class="w-72" theme="border border-primary-500">
                    <template #icon>
                        <div class="flex items-center text-primary-500 cursor-pointer">
                            <ps-icon name="check" w="16" h="16" />
                        </div>
                    </template>
                    <template #text>
                        <ps-label class=" text-center cursor-pointer"> Title </ps-label>
                    </template>
                </ps-step>

                <ps-step class="w-72" theme="border border-primary-500">
                    <template #icon>
                        <ps-label class="text-center cursor-pointer text-sm mx-1.5" textColor="text-purple-500">1</ps-label>
                    </template>
                    <template #text>
                        <ps-label class=" text-center cursor-pointer"> Title </ps-label>
                    </template>
                </ps-step>
            </div>
            <div class="flex">

            </div>
        </div>
        <!-- End Step indicator Design -->

        <!-- Start Range Slider Design -->
        <ps-label-title class="mb-4 mt-4">Range Slider</ps-label-title>
        <div class="w-1/2">
            <ps-input-range />
            <ps-label class="flex mt-2 text-center">
            <ps-range />100
            </ps-label>
            <ps-range disabled />
            <ps-range-1 class="mt-2" />
        </div>
        <!-- End Range Slider Design -->

        <!-- Start Radio Design -->
        <ps-label-title class="mb-4 mt-4">Radion List Title</ps-label-title>
        <div class="flex mt-4" id="r1">
            <div>
                <ps-radio-value
                    v-model:value="radioFruits"
                    title="Option1"
                />
                <ps-radio-value
                    v-model:value="radioFruits"
                    title="Option2"
                />
                <ps-radio-value
                    v-model:value="radioFruits"
                    title="Option3"
                />
                <ps-radio-value
                    v-model:value="radioFruits"
                    title="Option4"
                />
            </div>

            <div>
                <ps-radio-value
                    color="text-red-600 border-red-300"
                    v-model:value="radioFruits"
                    title="Red"
                />
                <ps-radio-value
                    color="text-purple-600 border-purple-300"
                    v-model:value="radioFruits"
                    title="Purple"
                />
                <ps-radio-value
                    color="text-yellow-400 border-yellow-300"
                    v-model:value="radioFruits"
                    title="Yellow"
                />
                <ps-radio-value
                    color="text-orange-500 border-orange-300"
                    v-model:value="radioFruits"
                    title="Orange"
                />
            </div>

            <div id="r1">
                <ps-radio v-for=" selectData  in radioDataList"
                :key="selectData.id"
                :value="selectData"
                v-model:selectedValue="radioSelectedList"
                :title="selectData.name"
                class="focus:bg-primary-500 dark:bg-primaryDark-accent me-4"></ps-radio>
                <ps-label class="mt-4">{{radioSelectedList}}</ps-label>
            </div>

        </div>
        <!-- End Radio Design -->

        <!-- Start Tag Design -->
        <ps-label-header-7 class="mb-4 mt-4">Tags</ps-label-header-7>
        <div class="flex  rtl:space-x-reverse space-x-24">
            <ps-tag class="w-12 h-7 items-center" textColor="text-secondary-800">Tag</ps-tag>
            <ps-tag class="w-12 h-7 items-center" padding="py-1 px-3" textColor="bg-gray-200 text-secondary-800">Tag</ps-tag>
            <ps-tag class="w-12 h-8 items-center" padding="py-1.5 px-3" textColor=" bg-indigo-500 text-white">Tag</ps-tag>
        </div>
        <div class="flex  rtl:space-x-reverse space-x-24 mt-8 w-1/3">
            <ps-tag-icon class="w-16 h-7 items-center" padding="px-3.5 py-2" textColor=" bg-secondary-200 text-secondary-800">
                <template #iconText>
                    <div class="flex items-center cursor-pointer">
                        <ps-icon name="check" w="16" h="16" />
                    </div>
                    Tage
                </template>
            </ps-tag-icon>
            <ps-tag-icon class="w-16 h-8 items-center" textColor=" bg-indigo-500 text-white" padding="px-3.5 py-2.5">
                <template #iconText>
                    <div class="flex items-center cursor-pointer">
                        <ps-icon name="check" w="16" h="16" />
                    </div>
                    Tage
                </template>
            </ps-tag-icon>
            <ps-tag-icon class="w-24 h-7 items-center" padding="py-1" textColor=" bg-secondary-200 text-secondary-800">
                <template #iconText>
                    <div class="flex items-center ms-3  cursor-pointer">
                        <ps-icon name="check"  w="16" h="16" />
                    </div>
                    Tage
                    <div class="flex items-center me-3  cursor-pointer">
                        <ps-icon name="crossCircle"  w="16" h="16" />
                    </div>
                </template>
            </ps-tag-icon>
        </div>
        <div class="flex  rtl:space-x-reverse space-x-24 mt-8 w-1/3">
            <ps-tag-icon class="w-24 h-7 items-center" padding="py-1" textColor=" bg-primary-500 text-white">
                <template #iconText>
                    <ps-icon name="right" class="ms-3 mt-1.5" theme="white" />
                    Tage
                    <ps-icon-1  name="close-circle" w="16" h="16" class="ms-1 me-3" theme="white"  />
                </template>
            </ps-tag-icon>
            <ps-tag-icon class="w-16 h-8 items-center" padding="py-3 px-3" textColor=" bg-gray-200">
                <template #iconText>
                    <ps-icon name="bullet" class="mt-2 me-1"  w="14" h="14" viewBox="0 0 14 14" theme="text-indigo-500" />
                    Tage
                </template>
            </ps-tag-icon>
            <ps-tag-icon class="w-16 h-8 items-center" padding="p-3" textColor=" bg-indigo-500 text-white" >
                <template #iconText>
                    <ps-icon name="bullet" class="mt-2 me-1" w="14" h="14" viewBox="0 0 14 14" theme="white" />
                    Tage
                </template>
            </ps-tag-icon>
        </div>
        <div class="mt-8 flex  rtl:space-x-reverse space-x-2">
            <ps-tag-icon rounded="rounded-full" padding="py-1.5 px-2">
                <template #iconText>
                    <img class="inline-block h-8 w-8 rounded-full ring-2 ring-white" src="https://images.unsplash.com/photo-1491528323818-fdd1faba62cc?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt=""/>
                    <ps-label class="ms-1 text-sm">Boonie  Gilbert</ps-label>
                </template>
            </ps-tag-icon>

            <ps-tag-icon rounded="rounded-full" padding="py-1.5 px-2" textColor=" bg-gray-200" >
                <template #iconText>
                    <img class="inline-block h-8 w-8 rounded-full ring-2 ring-white" src="https://images.unsplash.com/photo-1491528323818-fdd1faba62cc?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt=""/>
                    <ps-label class="ms-1 text-sm">Boonie  Gilbert</ps-label>
                </template>
            </ps-tag-icon>

            <ps-tag-icon rounded="rounded-full" padding="py-1.5 px-2" textColor=" bg-indigo-500 text-white" >
                <template #iconText>
                    <img class="inline-block h-8 w-8 rounded-full ring-2 ring-white" src="https://images.unsplash.com/photo-1491528323818-fdd1faba62cc?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt=""/>
                    <ps-label class="ms-1 text-sm me-2" textColor="text-white">Boonie  Gilbert</ps-label>
                </template>
            </ps-tag-icon>

            <ps-tag-icon rounded="rounded-full" padding="py-1.5 px-2">
                <template #iconText>
                    <div class="bg-indigo-500 rounded-full">
                        <ps-icon-1 name="person" class=' m-1.5 text-white'   theme="white"/>
                    </div>
                    <ps-label class="mx-2">Boonie  Gilbert</ps-label>
                </template>
            </ps-tag-icon>

            <ps-tag-icon rounded="rounded-full" padding="py-1.5 px-2" textColor=" bg-indigo-500 text-white">
                <template #iconText>
                    <div class="bg-indigo-500 rounded-none">
                        <ps-icon-1 name="person" class='m-1.5 text-white' theme="white"/>
                    </div>
                    <ps-label textColor="text-white" >Boonie  Gilbert</ps-label>
                    <ps-icon-1  name="close-circle" w="16" h="16" class="ms-1.5" theme="white"  />
                </template>
            </ps-tag-icon>
        </div>
        <!-- End Tag Design -->

        <!-- Start Tabs Design -->
        <ps-label-title class="mb-4 mt-4">Tabs horizontal and Tabs Large</ps-label-title>
        <div class="flex  rtl:space-x-reverse space-x-4">
            <div class="flex items-center">
                <ps-tabs  @click="currentTab(1)">
                    <ps-icon-1 name="message" class="pt-1" />
                </ps-tabs>

                <ps-tabs @click="currentTab(2)">
                    <ps-icon-1 name="message" class="pt-1" theme="#6366F1" />
                    <ps-label>First</ps-label>
                    <div class="ms-2 rounded-full my-auto justify-center bg-indigo-500 w-5 h-5 border border-gray-500">
                        <ps-label class="text-center text-sm" textColor="text-white">4</ps-label>
                    </div>
                </ps-tabs>

                <ps-tabs @click="currentTab(3)">
                    <ps-icon-1 name="message" class="pt-1" theme="#6366F1" />
                    <ps-label>First</ps-label>
                </ps-tabs>
            </div>
            <div class="flex w-full  rtl:space-x-reverse space-x-4">
                <ps-tabs-large buttonTheme="bg-primary-500" class="text-white">
                    First
                    <template #icon>
                        <div class="ms-2 rounded-full my-auto justify-center w-9 h-6 bg-primary-700" >
                            <ps-label textColor="text-white">4</ps-label>
                        </div>
                    </template>
                </ps-tabs-large>

                <ps-tabs-large buttonTheme="bg-white">
                    First
                    <template #icon theme="">
                        <div class="ms-2 rounded-full my-auto justify-center w-9 h-6 bg-secondary-100" >
                            <ps-label>4</ps-label>
                        </div>
                    </template>
                </ps-tabs-large>

                <ps-tabs-large buttonTheme="bg-primary-50"  theme="bg-indigo-200 w-8">
                    <div class="flex items-center">
                        <ps-icon-1 name="message" class="pt-1" theme="#6366F1" />
                        <ps-label textColor="text-primary-500">First</ps-label>
                    </div>
                    <template #icon>
                        <div class="ms-2 rounded-full my-auto justify-center w-9 h-6 bg-primary-500" >
                            <ps-label textColor="text-white">4</ps-label>
                        </div>
                    </template>
                </ps-tabs-large>
            </div>
        </div>
        <div class="p-3 mt-6 bg-white border">
            <div v-if="tab === 1">
            Tab 1 Content show Lorem ipsum dolor sit amet consectetur adipisicing
            elit. Quas sunt ducimus numquam! Quam, perspiciatis!
            </div>
            <div v-if="tab === 2">
            Tab 2 Content show Lorem ipsum dolor sit amet consectetur, adipisicing
            elit. aliquam rem. Exercitationem corporis eius voluptatibus.
            </div>
            <div v-if="tab === 3">
            Tab 3 Content show Lorem ipsum dolor sit amet consectetur adipisicing
            elit.
            </div>
        </div>
        <!-- End Tabs Design -->

        <!-- Start Textarea Design -->
        <ps-label-header-7 class="mb-4 mt-4">Textarea</ps-label-header-7>
        <div class="flex  rtl:space-x-reverse space-x-3">
            <ps-textarea rows="3" v-model:value="textString" />
            <ps-textarea rows="3" theme="border border-red-500" v-model:value="textString" />
            <ps-textarea rows="3" theme="border border-indigo-500" v-model:value="textString" />
            <ps-textarea disabled="true" rows="3" maxlength = "100" v-model:value="textString" />
        </div>

        <div class="flex  rtl:space-x-reverse space-x-3 mt-4">
            <ps-textarea rows="4" maxlength = "100" placeholder="Start Typin..." />
            <ps-textarea-max rows="4" theme="border border-indigo-500" maxlength = "100" placeholder="Start Typin..." />
        </div>
        <!-- End Textarea Design -->

        <!-- Start Toggle Design -->
        <ps-label-header-7 class="mb-4 mt-4">Toggle</ps-label-header-7>
        <div class=" flex flex-row  rtl:space-x-reverse space-x-4 mb-4">
            <ps-toggle :disabled="true" />
            <ps-toggle :selectedValue="true" :disabled="true" />
            <ps-toggle  />
            <ps-toggle toggleOnTheme="bg-green-500 border-green-500 justify-start text-white" onLabel="On" offLabel="Off" />
        </div>

        <div class="flex  rtl:space-x-reverse space-x-4">
            <div class="flex items-center">
                <ps-label class="me-2">Option</ps-label>
                <ps-toggle theme="bg-indigo-500"></ps-toggle>
            </div>
            <div class="flex items-center">
                <ps-label class="me-2">Option</ps-label>
                <ps-toggle :selectedValue="true" theme="bg-indigo-500"></ps-toggle>
            </div>
            <ps-toggle v-model:selectedValue="toggleValue" /> <ps-label> {{ toggleValue }} </ps-label>
            <Toggle v-model="toggleValue" on-label="On" off-label="Off" />
            <ps-toggle v-model="toggleValue1" theme="bg-indigo-500">
                <template v-slot:label="{ checked, classList }">
                    <span :class="classList.label">{{ checked ? 'On' : 'Off' }}</span>
                </template>
            </ps-toggle>
        </div>
        <div class="flex mt-2">
            <div>
                <ps-label class="me-2">Option</ps-label>
                <p class="text-secondary-700 text-sm font-normal">Partnership user experience iPad ramen monetization stock.</p>
            </div>
            <ps-toggle theme="bg-indigo-500"></ps-toggle>
        </div>
        <div class="flex mt-2">
            <div>
                <ps-label class="me-2">Option</ps-label>
                <p class="text-secondary-700 text-sm font-normal">Partnership user experience iPad ramen monetization stock.</p>
            </div>
            <ps-toggle :selectedValue="true" theme="bg-indigo-500"></ps-toggle>
        </div>
        <!-- End Toggle Design -->

        <!-- Start TextContent Design -->
        <ps-label-title class="mb-4 mt-4">TextContent</ps-label-title>
        <div class="grid grid-cols-12 gap-4">
            <div class="col-span-4">
                <ps-label-header-4>Header 4</ps-label-header-4>
                <ps-label class="text-justify leading-6">
                Social proof niche market ownership early adopters hypotheses gamification sales gen-z long tail. User experience startup social proof infrastructure channels advisor low hanging fruit buzz marketing innovator learning curve series A financing churn rate supply chain.
                </ps-label>
            </div>
            <div class="col-span-4">
                <ps-label-header-5 class="text-center">Header 5</ps-label-header-5>
                <ps-label class="text-center leading-6">
                    Try a quick search to explore hundreds of affordable options.
                </ps-label>
            </div>
            <div class="col-span-4">
                <ps-label-title>Title</ps-label-title>
                <ul class="list-disc text-justify leading-6 ">
                    <li>Try a quick search to explore hundreds of affordable options.</li>
                </ul>
            </div>
        </div>
        <!-- End TextContent Design -->

        <!-- Start Tooltips Design -->
        <ps-label-header-7 class="mb-4 mt-4">Tooltips</ps-label-header-7>
        <div class="flex  rtl:space-x-reverse space-x-4">
            <div class="w-1/3">
                <ps-tooltips>
                    Social proof niche market ownership early adopters hypotheses.
                    <svg class="absolute text-black h-2 left-0 ms-3 top-full" x="0px" y="0px" viewBox="0 0 255 255" xml:space="preserve"><polygon class="fill-current" points="0,0 127.5,127.5 255,0"/></svg>
                </ps-tooltips>
            </div>
            <div class="w-1/3">
                <ps-tooltips>
                    Social proof niche market ownership early adopters hypotheses.
                    <svg class="absolute text-black h-2 w-full left-0 top-full" x="0px" y="0px" viewBox="0 0 255 255" xml:space="preserve"><polygon class="fill-current" points="0,0 127.5,127.5 255,0"/></svg>
                </ps-tooltips>
            </div>
            <div class="w-1/3">
                <ps-tooltips>
                    Social proof niche market ownership early adopters hypotheses.
                    <svg class="absolute text-black h-2 right-0 me-3 top-full" x="0px" y="0px" viewBox="0 0 255 255" xml:space="preserve"><polygon class="fill-current" points="0,0 127.5,127.5 255,0"/></svg>
                </ps-tooltips>
            </div>
        </div>

        <div class="flex mt-4  rtl:space-x-reverse space-x-4">
            <div class="w-1/3">
                <ps-tooltips>
                    Social proof niche market ownership early adopters hypotheses.
                    <svg class="absolute text-black h-2 right-0 me-3  bottom-full" width="320" height="9" viewBox="0 0 320 9" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M320 9H169.92L162.5 0L154.708 9H0H320Z" fill="#1F2937"/>
                    </svg>
                </ps-tooltips>
            </div>

            <div class="w-1/3">
                <ps-tooltips>
                    Social proof niche market ownership early adopters hypotheses.
                   <svg class="absolute text-black h-2 left-0 ms-3 bottom-full" width="201" height="9" viewBox="0 0 201 9" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M201 9H50.9197L43.5 0L35.7083 9H0H201Z" fill="#1F2937"/>
                    </svg>
                </ps-tooltips>
            </div>

            <div class="w-1/3">
                <ps-tooltips>
                    Social proof niche market ownership early adopters hypotheses.
                    <svg class="absolute text-black h-2 right-0 me-3 bottom-full" width="201" height="9" viewBox="0 0 201 9" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M0 9H150.08L157.5 0L165.292 9H201H0Z" fill="#1F2937"/>
                    </svg>
                </ps-tooltips>
            </div>

        </div>

        <div class="flex mt-4  rtl:space-x-reverse space-x-6">
            <div class="w-1/3">
                <ps-tooltips>
                    Social proof niche market ownership early adopters hypotheses.
                    <svg class="absolute text-black top-0 mt-3 left-full" width="10" height="28" viewBox="0 0 10 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M0 -4.37114e-07L-1.78208e-07 4.07692L10 13.2735L-1.03044e-06 23.5738L-1.22392e-06 28L0 -4.37114e-07Z" fill="#1F2937"/>
                    </svg>
                </ps-tooltips>
            </div>

            <div class="w-1/3">
                <ps-tooltips>
                    Social proof niche market ownership early adopters hypotheses.
                    <svg class="absolute text-black top-0 mt-3 right-full" width="10" height="28" viewBox="0 0 10 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M10 -4.37114e-07L10 4.07692L5.80205e-07 13.2735L10 23.5738L10 28L10 -4.37114e-07Z" fill="#1F2937"/>
                    </svg>
                </ps-tooltips>
            </div>

            <div class="w-1/3">
                <ps-tooltips theme="bg-white text-default rounded-lg shadow-md" >
                    Tooltips shouldn’t really exceed 1 line, but sometimes it’s unavoidable
                </ps-tooltips>
            </div>
        </div>
        <!-- End Tooltips Design -->

        <!-- Start Notification Design -->
        <ps-label-header-7 class="mb-4 mt-4">Notification</ps-label-header-7>
        <div class="flex  rtl:space-x-reverse space-x-2 mb-6">
            <ps-icon-1 name="order" w="24" h="24" />
            <ps-icon-1 name="elements" w="24" h="24" />
            <ps-icon-1 name="chart-line" w="24" h="24" />
        </div>
        <div class="flex  rtl:space-x-reverse space-x-2">
            <ps-icon name="bell"  theme="#1F2937"/>
            <div class="relative">
                <div class="absolute w-1.5 h-1.5 ms-3 bg-red-500 rounded-full"></div>
                <ps-icon name="bell"  theme="#1F2937"/>
            </div>
            <div class="relative">
                <div class="absolute w-3 h-3 ms-3 -top-1.5 bg-red-500 rounded-full text-xxs px-0.5 text-white"><span>3</span></div>
                <ps-icon name="bell"  theme="#1F2937"/>
            </div>
            <ps-icon name="email"  theme="#1F2937"/>
            <div class="relative">
                <div class="absolute w-1.5 h-1.5 -top-1 ms-3 bg-red-500 rounded-full"></div>
                <ps-icon name="email"  theme="#1F2937"/>
            </div>
            <div class="relative">
                <div class="absolute w-3 h-3 ms-3 -top-1.5 bg-red-500 rounded-full text-xxs px-0.5 text-white"><span>3</span></div>
                <ps-icon name="email"  theme="#1F2937"/>
            </div>
        </div>
        <ps-notification textTheme="text-red-500" class="mt-2 border-red-500 text-gray-200">
            <ps-icon-1 theme="#EF4444" name="alert-triangle"  w='24' h='24' class="text-sm"  />
            <div class="mx-4">
                <ps-label class="text-bold">Title</ps-label>
                <ps-label class="font-light">Subtitle</ps-label>
            </div>
        </ps-notification>

        <ps-notification textTheme="text-blue-500" class="border-blue-500 text-gray-200">
            <ps-icon-1 theme="#3B82F6" name="warning-icon"  w='24' h='24' class="text-sm"  />
            <div class="mx-4">
                <ps-label class="text-bold">Title</ps-label>
                <ps-label class="font-light">Subtitle</ps-label>
            </div>
        </ps-notification>

        <ps-notification textTheme="text-green-500" class="border-green-500 text-gray-200">
            <ps-icon-1 theme="#10B981" name="rightalert"  w='24' h='24' class="text-sm"  />
            <div class="mx-4">
                <ps-label class="text-bold">Title</ps-label>
                <ps-label class="font-light">Subtitle</ps-label>
            </div>
        </ps-notification>

        <ps-notification textTheme="text-yellow-500" class="border-yellow-500 text-gray-200">
            <ps-icon-1 theme="#F59E0B" name="alert-triangle"  w='24' h='24' class="text-sm"  />
            <div class="mx-4">
                <ps-label class="text-bold">Title</ps-label>
                <ps-label class="font-light">Paragraph for banner above the nav</ps-label>
            </div>
        </ps-notification>

        <ps-notification textTheme="text-dark-500" class="border-grey-500 text-gray-200">
            <ps-icon-1 theme="#6B7280" fill-rule="evenodd" clip-rule="evenodd" name="flag"  w='24' h='24' class="text-sm"  />
            <div class="mx-4">
                <ps-label class="text-bold">Title</ps-label>
                <ps-label class="font-light">Paragraph for banner above the nav</ps-label>
            </div>
        </ps-notification>

        <ps-notification textTheme="text-red-500" class="mt-2 border-red-500 text-gray-200">
            <ps-icon-1 theme="#EF4444" name="alert-triangle"  w='24' h='24' class="text-sm"  />
            <div class="mx-4">
                <ps-label class="text-bold">Title</ps-label>
            </div>
        </ps-notification>
        <!-- End Notification Design -->

        <!-- Start Activity Design -->
        <ps-label-header-7 class="mb-4 mt-4">Activity</ps-label-header-7>
        <div class="flex  rtl:space-x-reverse space-x-2">
            <ps-activity theme="bg-indigo-100">
                <template #content>
                    <img class="mx-2 inline-block h-8 w-8 rounded-full ring-2 ring-white" src="https://images.unsplash.com/photo-1491528323818-fdd1faba62cc?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt=""/>
                    <div><ps-label class="text-sm">Alisa has placed a New Order.</ps-label>
                    <ps-label-caption>Just now</ps-label-caption></div>
                </template>
            </ps-activity>


            <ps-activity>
                <template #content>
                    <img class="mx-2 inline-block h-8 w-8 rounded-full ring-2 ring-white" src="https://images.unsplash.com/photo-1491528323818-fdd1faba62cc?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt=""/>
                    <div><ps-label class="text-sm">Alisa has placed a New Order.</ps-label>
                    <ps-label-caption>Just now</ps-label-caption></div>
                </template>
            </ps-activity>
        </div>

        <div class="flex  rtl:space-x-reverse space-x-2">
            <ps-activity>
                <template #content>
                    <div class="m-1 me-2 w-10 h-10 relative flex justify-center items-center rounded-full bg-green-500 text-xl text-white uppercase">zxl</div>
                    <div>
                    <ps-label class="text-sm">John Doe commended</ps-label>
                    <ps-label class="text-sm">Hello! i’m taking care of this product of Design. Check @Arijona for the update.</ps-label>
                    <ps-label-caption>Just now</ps-label-caption>
                    </div>
                </template>
            </ps-activity>

            <ps-activity theme="bg-indigo-100">
                <template #content>
                    <div class="m-1 me-2 w-8 h-8 relative flex justify-center items-center rounded-full bg-indigo-500">
                        <ps-icon name="user" class="text-primary-200 ms-1 items-center" theme="#C7D2FE" />
                    </div>
                    <div>
                    <ps-label class="text-sm font-medium">John Doe commended</ps-label>
                    <ps-label class="text-sm">Alisa has placed a New Order.</ps-label>
                    <ps-label-caption>Just now</ps-label-caption>
                    </div>
                </template>
            </ps-activity>

        </div>
        <!-- End Activity Design -->
        <!-- Start Number Count Design -->
        <ps-label-header-7 class="mt-4 mb-3">Input Number Count</ps-label-header-7>
        <div class="flex  rtl:space-x-reverse space-x-3">
            <ps-number-count :onInput="onInput" :num="0" />
            <ps-number-count :onInput="onInput" :num="2" theme=" border border-secondary-400" borderLeft="border-s-secondary-400" borderRight="border-e-secondary-400"/>
            <ps-number-count :onInput="onInput" :num="2" theme=" border border-primary-300"  />
            <ps-number-count :onInput="onInput" :num="2" theme=" border border-red-500" borderLeft="border-s-red-500" borderRight="border-e-red-500"  />
        </div>
        <!-- End Number Count Design -->
        <!-- Start Avatar Design -->
        <ps-label-header-7 class="mt-4 mb-3">Avatar</ps-label-header-7>
        <div class="flex  rtl:space-x-reverse space-x-3">
            <ps-avatar w="w-8" h="h-8">
                <ps-icon name="user" theme="#1F2937" />
            </ps-avatar>
            <ps-avatar w="w-10" h="h-10">
                <ps-icon name="user" theme="#1F2937" />
            </ps-avatar>
            <ps-avatar>
                <ps-icon name="user" theme="#1F2937" />
            </ps-avatar>
            <ps-avatar w="w-14" h="h-14">
                <ps-icon name="user" theme="#1F2937" />
            </ps-avatar>
            <ps-avatar w="w-16" h="h-16">
                <ps-icon name="user" theme="#1F2937" />
            </ps-avatar>

            <ps-avatar w="w-8" h="h-8" theme="bg-indigo-100">
                <ps-label class=" text-md text-white uppercase">jw</ps-label>
            </ps-avatar>
            <ps-avatar w="w-10" h="h-10" theme="bg-indigo-100">
                <ps-label class=" text-md text-white uppercase">jw</ps-label>
            </ps-avatar>
            <ps-avatar theme="bg-indigo-100">
                <ps-label class=" text-md text-white uppercase">jw</ps-label>
            </ps-avatar>
            <ps-avatar w="w-14" h="h-14" theme="bg-indigo-100">
                <ps-label class=" text-md text-white uppercase">jw</ps-label>
            </ps-avatar>
            <ps-avatar w="w-16" h="h-16" theme="bg-indigo-100">
                <ps-label class=" text-xl text-white uppercase">jw</ps-label>
            </ps-avatar>

            <ps-avatar rounded="rounded-md" w="w-8" h="h-8">
                <ps-label class=" text-md text-white uppercase">jw</ps-label>
            </ps-avatar>
            <ps-avatar rounded="rounded-md" w="w-10" h="h-10">
                <ps-label class=" text-md text-white uppercase">jw</ps-label>
            </ps-avatar>
            <ps-avatar rounded="rounded-md">
                <ps-label class=" text-md text-white uppercase">jw</ps-label>
            </ps-avatar>
            <ps-avatar rounded="rounded-md" w="w-14" h="h-14">
                <ps-label class=" text-md text-white uppercase">jw</ps-label>
            </ps-avatar>
            <ps-avatar rounded="rounded-md" w="w-16" h="h-16">
                <ps-label class=" text-xl text-white uppercase">jw</ps-label>
            </ps-avatar>
        </div>
        <div class="flex  rtl:space-x-reverse space-x-4">
            <ps-avatar class="relative" w="w-16" h="h-16" theme="bg-indigo-100">
                <div class="absolute w-1.5 h-1.5 right-3.5 -top-0.5 bg-green-500 rounded-full"></div>
                <ps-icon name="user" theme="#1F2937" />
            </ps-avatar>

            <ps-avatar class="relative" w="w-16" h="h-16" theme="bg-indigo-100">
                <div class="absolute w-1.5 h-1.5 right-3.5 -top-0.5 bg-green-500 rounded-full"></div>
                <ps-label class=" text-xl text-white uppercase">jw</ps-label>
            </ps-avatar>

            <ps-avatar class="relative" w="w-12" h="h-12" theme="bg-indigo-100">
                <div class="absolute w-1.5 h-1.5 right-2 -bottom-0.5 bg-green-500 rounded-full"></div>
                <ps-icon name="user" theme="#1F2937" />
            </ps-avatar>

            <ps-avatar class="relative" w="w-12" h="h-12" theme="bg-indigo-100">
                <div class="absolute w-1.5 h-1.5 right-2 -bottom-0.5 bg-green-500 rounded-full"></div>
                <ps-label class=" text-xl text-white uppercase">jw</ps-label>
            </ps-avatar>

            <ps-avatar class="relative" rounded="rounded-md" w="w-16" h="h-16">
                <div class="absolute w-1.5 h-1.5 right-0 -top-0.5 bg-green-500 rounded-full"></div>
                <ps-icon name="user" theme="#1F2937" />
            </ps-avatar>

            <ps-avatar class="relative" rounded="rounded-md" w="w-16" h="h-16">
                <div class="absolute w-1.5 h-1.5 right-0 -top-0.5 bg-green-500 rounded-full"></div>
                <ps-label class=" text-xl text-white uppercase">jw</ps-label>
            </ps-avatar>

            <ps-avatar class="relative" rounded="rounded-md" w="w-12" h="h-12">
                <div class="absolute w-1.5 h-1.5 right-0 -bottom-0.5 bg-green-500 rounded-full"></div>
                <ps-icon name="user" theme="#1F2937" />
            </ps-avatar>

            <ps-avatar class="relative" rounded="rounded-md" w="w-12" h="h-12">
                <div class="absolute w-1.5 h-1.5 right-0 -bottom-0.5 bg-green-500 rounded-full"></div>
                <ps-label class=" text-xl text-white uppercase">jw</ps-label>
            </ps-avatar>
        </div>

        <div class="- rtl:space-x-reverse space-x-4">
            <img class="relative z-30 inline object-cover w-12 h-12 border-2 border-white rounded-full" src="https://images.pexels.com/photos/2589653/pexels-photo-2589653.jpeg?auto=compress&cs=tinysrgb&h=650&w=940" alt="Profile image"/>
            <img class="relative z-20 inline object-cover w-12 h-12 border-2 border-white rounded-full" src="https://images.pexels.com/photos/2955305/pexels-photo-2955305.jpeg?auto=compress&cs=tinysrgb&h=650&w=940" alt="Profile image"/>
            <img class="relative z-10 inline object-cover w-12 h-12 border-2 border-white rounded-full" src="https://images.pexels.com/photos/2589653/pexels-photo-2589653.jpeg?auto=compress&cs=tinysrgb&h=650&w=940" alt="Profile image"/>
        </div>
        <!-- Start Avatar Design -->
        <!-- Start Chart  -->
        <ps-label-title class="mt-3"> Group Bar Chart </ps-label-title>
        <bar-chart
            :chartOptions="chartOptions"
            :chartData="chartData"
            width="300"
            height="100"
        />
        <ps-label-title class="mt-3"> Bar Chart Stacked(Vertical) </ps-label-title>
        <bar-chart
            :chartOptions="stackedBarOptions"
            :chartData="stackedBarData"
            width="300"
            height="100"
        />

        <div class="w-full grid grid-cols-12">
            <div class="col-span-5">
                <ps-label-title class="mt-3"> Line Chart </ps-label-title>
                <line-chart
                    :chartOptions="lineChartOptions"
                    :chartData="lineChartData"
                />
            </div>
            <div class="col-span-5">
                <ps-label-title class="mt-3"> Bar Chart </ps-label-title>
                <bar-chart
                :chartOptions="chartBarOptions"
                :chartData="chartBarData"
                />
            </div>
        </div>
        <div class="w-full grid grid-cols-12 mt-4">
            <div class="col-span-5">
                <ps-label-title class="mt-3"> HorizontalBar Chart </ps-label-title>
                <bar-chart
                    :chartOptions="horizontalChartOptions"
                    :chartData="horizontalChartData"
                />
            </div>

            <div class="col-span-5">
                <ps-label-title class="mt-3"> Vertical Bar Chart </ps-label-title>
                <bar-chart
                    :chartOptions="verticalChartOptions"
                    :chartData="verticalChartData"
                />
            </div>

        </div>

        <div class="w-full grid grid-cols-12 mt-4">
            <div class="col-span-5">
                <ps-label-title class="mt-3"> Doughnut Chart </ps-label-title>
                <doughnut-chart
                    :chartOptions="doughnutChartOptions"
                    :chartData="doughnutChartData"
                />
            </div>

            <div class="col-span-5">
                <ps-label-title class="mt-3"> Doughnut Chart </ps-label-title>
                <doughnut-chart
                    :chartOptions="dnChartOptions"
                    :chartData="dnChartData"
                />
            </div>

        </div>

        <div class="w-full grid grid-cols-12 mt-4">
            <div class="col-span-5">
                <ps-label-title class="mt-3"> Pie Chart </ps-label-title>
                <pie-chart
                    :chartOptions="pieChartOptions"
                    :chartData="pieChartData"
                />
            </div>

            <div class="col-span-5">
                <ps-label-title class="mt-3"> Radar Chart </ps-label-title>
               <!-- <radar-chart
                    :chartOptions="radarChartOptions"
                    :chartData="radarChartData"
                />-->
            </div>
        </div>
        <ps-label-title class="mt-3"> Bubble Chart </ps-label-title>
        <bubble-chart
            :chartOptions="bubbleChartOptions"
            :chartData="bubbleChartData"
        />

        <div class="w-full grid grid-cols-12 mt-4">
            <div class="col-span-5">
                <ps-label-title class="mt-3"> Doughnut Chart </ps-label-title>
                <doughnut-chart
                    :chartOptions="textChartOptions"
                    :chartData="textChartData"
                    :plugins="[chartPlugin]"
                />
            </div>


        </div>
        <!-- End Chart  -->

        <!-- Start Relevant Dashboard  -->
        <div class="py-10 bg-gray-200 flex flex-row">
            <div class="rounded-lg px-4 py-2 mx-4 w-72 bg-white">
                <ps-label class="text-sm font-semibold" > Behaviour Report</ps-label>
                <div class="flex flex-row justify-between items-center border-b border-b-secondary-400 py-2">
                    <div >
                        <ps-label class="text-xl font-semibold" > 50,000</ps-label>
                        <ps-label class="text-sm" textColor="text-secondary-400" > Total Product Detail Views</ps-label>
                    </div>
                    <div class="">
                        <ps-label class="text-sm my-auto flex opacity-50" textColor="text-green-500" > <ps-icon name="chartHeight" viewBox="0 0 76 53" w="76" h="53" /></ps-label>
                    </div>
                </div>
                <div class="flex flex-row justify-between items-center border-b border-b-secondary-400 py-2">
                    <div >
                        <ps-label class="text-xl font-semibold" > 20,321</ps-label>
                        <ps-label class="text-sm" textColor="text-secondary-400" > Total Add to Carts</ps-label>
                    </div>
                    <div class="">
                        <ps-label class="text-sm my-auto flex  opacity-50" textColor="text-red-500" > <ps-icon name="chartLow" viewBox="0 0 78 32" w="78" h="32" /></ps-label>
                    </div>
                </div>
                <div class="flex flex-row justify-between items-center border-b border-b-secondary-400 py-2">
                    <div >
                        <ps-label class="text-xl font-semibold" > 18,890</ps-label>
                        <ps-label class="text-sm" textColor="text-secondary-400" > Total Checkouts</ps-label>
                    </div>
                    <div class="">
                        <ps-label class="text-sm my-auto flex  opacity-50" textColor="text-red-500" > <ps-icon name="chartLow" viewBox="0 0 78 32" w="78" h="32" /></ps-label>
                    </div>
                </div>
                <div class="flex flex-row justify-between items-center border-b border-b-secondary-400 py-2">
                    <div >
                        <ps-label class="text-xl font-semibold" >50,000</ps-label>
                        <ps-label class="text-sm" textColor="text-secondary-400" > Total Abandonment </ps-label>
                    </div>
                    <div class="">
                        <ps-label class="text-sm my-auto flex opacity-50" textColor="text-green-500" > <ps-icon name="chartHeight" viewBox="0 0 76 53" w="76" h="53" /></ps-label>
                    </div>
                </div>
                <div class="flex flex-row justify-between items-center border-b border-b-secondary-400 py-2">
                    <div >
                        <ps-label class="text-xl font-semibold" > 29,123</ps-label>
                        <ps-label class="text-sm" textColor="text-secondary-400" > Total Purchases</ps-label>
                    </div>
                    <div class="">
                        <ps-label class="text-sm my-auto flex opacity-50" textColor="text-green-500" > <ps-icon name="chartHeight" viewBox="0 0 76 53" w="76" h="53" /></ps-label>
                    </div>
                </div>
                <div class="flex justify-center  py-2">
                    <ps-label class="text-sm my-auto flex font-medium items-center" textColor="text-primary-500" > {{ $t('core__be_see_more_label') }} <ps-icon name="rightChervon" class="ms-2"  /></ps-label>
                </div>

            </div>
            <div>
                <div class="flex flex-col rounded-lg px-4 py-2 mx-4 w-72 bg-white">
                    <div class="flex fles-row">
                        <div class="flex rounded-full p-2 bg-green-500">
                            <ps-icon name="cash"  />
                        </div>
                    </div>
                    <div class="flex flex-row justify-between items-center border-b border-b-secondary-400 py-2">
                        <div >
                            <ps-label class="text-xl font-semibold" > 50,000</ps-label>
                            <ps-label class="text-sm" textColor="text-secondary-400" > Total Product Detail Views</ps-label>
                        </div>
                        <div class="">
                            <ps-label class="text-sm my-auto flex" textColor="text-green-500" > <ps-icon name="arrowTopRight" class="me-1.5 mt-1.5" />1.9%</ps-label>
                        </div>
                    </div>
                    <div class="flex justify-center  py-2">
                        <ps-label class="text-sm my-auto flex font-medium items-center" textColor="text-primary-500" > {{ $t('core__be_see_more_label') }} <ps-icon name="rightChervon" class="ms-2"  /></ps-label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Start Google and OpenStreet map  -->
        <div class="flex flex-row  rtl:space-x-reverse space-x-8 mt-8">
            <div class="flex flex-col space-y-2">
                <ps-label> Google Map </ps-label>
                <google-map-pin-picker :mapKey="$page.props.mapKey"  :lat="lat" :lng="lng" class="h-68 w-64" :onChange="updateCoordinates" />
                <ps-label> lat : {{ lat }} </ps-label>
                <ps-label> lng : {{ lng }}</ps-label>
            </div>
             <div class="flex flex-col space-y-2">
                <ps-label> Open Street Map </ps-label>
                <open-street-map-pin-picker :dir="$page.props.dir" :lat="latitude" :lng="longitude" mapSize="h-64 w-64" :onChange="updateLeafletCoordinates" />
                 <ps-label> lat : {{ latitude }} </ps-label>
                <ps-label> lng : {{ longitude }}</ps-label>
            </div>
        </div>

        <div class="py-8 px-4 bg-secondary-300 flex flex-wrap">
            <div>
                <ps-label class="my-4"> Date Picker </ps-label>
                <date-picker v-model:value="date" />
                <ps-label class="mt-4"> {{ date }} </ps-label>
            </div>
            <div class="ms-4 flex flex-col">
                <ps-label class="mt-4"> Date Picker Not AutoApply</ps-label>
                <div class="mt-4 flex flex-row">
                <div class="bg-white dark:bg-secondary-900 rounded-lg">
                    <date-picker v-model:value="tempDate" />
                    <div class="flex flex-row justify-end pb-4 pe-4">
                        <ps-text-button @click="cancelDate" colors="text-secondary-800 dark:text-white" class="me-2" > Cancel </ps-text-button>
                        <ps-button @click="applyDate" > Apply </ps-button>
                    </div>
                </div>
                </div>
                <ps-label class="mt-4"> {{ appliedDate }} </ps-label>
            </div>
        </div>
        <div class="py-8 px-4 bg-secondary-300 flex flex-wrap">
            <div>
                <ps-label class="my-4"> Date Range Picker </ps-label>
                <date-picker v-model:value="date" :range="true" />
                <ps-label class="mt-4"> {{ date }} </ps-label>
            </div>
            <div class="ms-4 flex flex-col">
                <ps-label class="my-4"> Date Time Picker </ps-label>
                <date-picker v-model:value="date" :enableTimePicker="true" />
                <ps-label class="mt-4"> {{ date }} </ps-label>
            </div>
        </div>
        <div class="py-8 px-4 bg-secondary-300 flex flex-wrap">
            <div>
                <ps-label class="my-4"> Date Picker Inline(False) </ps-label>
                <date-picker v-model:value="date" :inline="false" :autoApply="false" />
                <ps-label class="mt-4"> {{ date }} </ps-label>
            </div>
            <div class="ms-4 flex flex-col">
                <ps-label class="my-4"> Date Time Picker </ps-label>
                <date-picker v-model:value="date" :enableTimePicker="true"  :inline="false" :autoApply="false" />
                <ps-label class="mt-4"> {{ date }} </ps-label>
            </div>
        </div>

        <div class="py-8 px-4 bg-secondary-300 flex flex-wrap">
            <div class="w-68 p-5 rounded-lg bg-white">
                <ps-label class="font-bold mb-8"> Column Filter Option </ps-label>
                <div v-if="showColumList.length > 0" >
                    <div class="flex flex-row justify-between items-center" >
                        <ps-label textColor="text-secondary-500 dark:text-white" class="font-semibold" > Shown </ps-label>
                        <ps-text-button @click="hideAll" class="font-semibold"  > Hide All </ps-text-button>
                    </div>
                    <div class="flex flex-row justify-between items-center mt-4" v-for="colum in showColumList" :key="colum.id" >
                        <ps-label textColor="text-secondary-800 dark:text-white" class="" > {{ colum.name }} </ps-label>
                        <ps-text-button  colors="text-secondary-800 dark:text-white" @click="hideColum(colum.id)"  > <ps-icon name="eye-on"  /> </ps-text-button>
                    </div>
                </div>
                <div v-if="hideColumList.length > 0" class="mt-8">
                    <div class="flex flex-row justify-between items-center" >
                        <ps-label textColor="text-secondary-500 dark:text-white" class="font-semibold" > Hide </ps-label>
                        <ps-text-button @click="showAll" class="font-semibold"  > Show All </ps-text-button>
                    </div>
                    <div class="flex flex-row justify-between items-center mt-4" v-for="colum in hideColumList" :key="colum.id">
                        <ps-label textColor="text-secondary-800 dark:text-white" class="" > {{ colum.name }}</ps-label>
                        <ps-text-button  colors="text-secondary-800 dark:text-white" @click="showColum(colum.id)"  > <ps-icon name="eyeOff" viewBox="0 0 22 20" w="22" h="20" /> </ps-text-button>
                    </div>
                </div>
                <ps-button class="mt-8 w-full" > Confirm </ps-button>
                <ps-text-button class="mt-4 w-full" colors="text-secondary-800 dark:text-white" >  Cancel </ps-text-button>
            </div>

            <div class="class flex flex-col space-y-4">
                <ps-button class="ms-8" @click="replaceVideoClicked" > Replace Video </ps-button>
                <ps-button class="ms-8" @click="replaceImageClicked" > Replace Image </ps-button>
            </div>

        </div>

        <div class="flex gap-2 bg-gray-200 mt-5 p-10">
            <ps-image-upload class="w-112">
                <template #title>
                    <ps-label-header-4 class="text-center font-normal text-2xl mt-6 mb-4"> {{ $t("ps_video_upload__title") }} </ps-label-header-4>
                </template>
            </ps-image-upload>

            <ps-image-upload class="w-112">
                <template #title>
                    <ps-label-header-4 class="text-center font-normal text-2xl mt-6 mb-4"> {{ $t("ps_icon_upload__title") }} </ps-label-header-4>
                </template>
            </ps-image-upload>
        </div>

        <ps-data-table :columns="columns" :rows="rows" />

        <ps-confirm-dialog ref="ps_confirm_dialog" />
        <ps-danger-dialog ref="ps_danger_dialog" />
        <ps-action-dialog ref="ps_action_dialog" />
        <ps-warning-dialog ref="ps_warning_dialog" />

        <ps-success-dialog ref="ps_success_dialog" />
        <ps-error-dialog ref="ps_error_dialog" />
        <ps-message-dialog ref="ps_message_dialog" />
        <ps-loading-dialog ref="ps_loading_dialog" />

        <ps-csv-modal ref="ps_csv_modal" />
        <ps-image-icon-modal ref="ps_image_icon_modal" />
        <ps-action-modal ref="ps_action_modal" />
    </div>
    </ps-layout>
</template>

<script>
import { defineAsyncComponent, onUnmounted, onMounted, reactive, ref } from "vue";
import PsAlert from "@/Components/Core/Alert/PsAlert.vue";
import PsIcon from "@/Components/Core/Icons/PsIcon.vue";
import PsIcon1 from "@/Components/Core/Icons/PsIcon1.vue";
import PsLabel from "@/Components/Core/Label/PsLabel.vue";
import PsLabelTitle2 from "@/Components/Core/Label/PsLabelTitle2.vue";
import PsLabelTitle3 from "@/Components/Core/Label/PsLabelTitle3.vue";
import PsLabelTitle4 from "@/Components/Core/Label/PsLabelTitle4.vue";
import PsLabelHeader7 from "@/Components/Core/Label/PsLabelHeader7.vue";
import PsLabelHeader6 from "@/Components/Core/Label/PsLabelHeader6.vue";
import PsLabelHeader5 from "@/Components/Core/Label/PsLabelHeader5.vue";
import PsLabelHeader4 from "@/Components/Core/Label/PsLabelHeader4.vue";
import PsLabelHeader3 from "@/Components/Core/Label/PsLabelHeader3.vue";
import PsLabelHeader2 from "@/Components/Core/Label/PsLabelHeader2.vue";
import PsLabelHeader1 from "@/Components/Core/Label/PsLabelHeader1.vue";
import PsLabelCaption from "@/Components/Core/Label/PsLabelCaption.vue";
import PsLayout from "@/Components/PsLayout.vue";
import PsLabelTitle from "@/Components/Core/Label/PsLabelTitle.vue";
import PsCard from "@/Components/Core/Card/PsCard.vue";
import PsCard1 from "@/Components/Core/Card/PsCard1.vue";
import PsNavBar from "@/Components/Layouts/Navbar/PsNavBar.vue";
import PsNavBar2 from "@/Components/Layouts/Navbar/PsNavBar2.vue";
import PsNavBar3 from "@/Components/Layouts/Navbar/PsNavBar3.vue";
import PsNavBar4 from "@/Components/Layouts/Navbar/PsNavBar4.vue";
import PsNavBar5 from "@/Components/Layouts/Navbar/PsNavBar5.vue";
import PsBadge from "@/Components/Core/Badge/PsBadge.vue";
import PsBadgeIcon from "@/Components/Core/Badge/PsBadgeIcon.vue";
import PsBadgeIcon1 from "@/Components/Core/Badge/PsBadgeIcon1.vue";
import PsBanner from "@/Components/Core/Banners/PsBanner.vue";
import PsBannerIcon from "@/Components/Core/Banners/PsBannerIcon.vue";
import PsBreadcrumb from "@/Components/Core/Breadcrumbs/PsBreadcrumb.vue";
import PsBreadcrumb2 from "@/Components/Core/Breadcrumbs/PsBreadcrumb2.vue";
import PsInputWithLeftIcon from '@/Components/Core/Input/PsInputWithLeftIcon.vue';
import PsInputWithRightIcon from '@/Components/Core/Input/PsInputWithRightIcon.vue';
import PsSearch from '@/Components/Core/Search/PsSearch.vue';
import PsSearchIcon from '@/Components/Core/Search/PsSearchIcon.vue';
import PsSearchButton from '@/Components/Core/Search/PsSearchButton.vue';
import PsPagination from '@/Components/Core/Pagination/PsPagination.vue';
import PsPagination1 from '@/Components/Core/Pagination/PsPagination1.vue';
import Pagination from '@/Components/Core/Pagination/Pagination.vue';
import PsStepStatus from '@/Components/Core/Steps/PsStepStatus.vue';
import PsStep from '@/Components/Core/Steps/PsStep.vue';
import PsLine from '@/Components/Core/Line/PsLine.vue';
import PsLink1 from '@/Components/Core/Link/PsLink1.vue';
import PsInputRange from '@/Components/Core/Range/PsInputRange.vue';
import PsRange from '@/Components/Core/Range/PsRange.vue';
import PsRange1 from '@/Components/Core/Range/PsRange1.vue';
import PsRadioValue from "@/Components/Core/Radio/PsRadioValue.vue";
import PsRadio from '@/Components/Core/Radio/PsRadio.vue';
import PsTag from '@/Components/Core/Tag/PsTag.vue';
import PsTagIcon from '@/Components/Core/Tag/PsTagIcon.vue';
import PsTextarea from '@/Components/Core/Textarea/PsTextarea.vue';
import PsTextareaMax from '@/Components/Core/Textarea/PsTextareaMax.vue';
import PsToggle from '@/Components/Core/Toggle/PsToggle.vue';
import Toggle from '@vueform/toggle'
import PsTabs from '@/Components/Core/Tabs/PsTabs.vue';
import PsTabsLarge from '@/Components/Core/Tabs/PsTabsLarge.vue';
import PsTooltips from '@/Components/Core/Tooltips/PsTooltips.vue';
import PsNotification from '@/Components/Core/Notification/PsNotification.vue';
import PsActivity from '@/Components/Core/Activity/PsActivity.vue';
import PsNumberCount from '@/Components/Core/InputNumber/PsNumberCount.vue';
import PsAvatar from '@/Components/Core/Avatar/PsAvatar.vue';
import BarChart from '@/Components/Core/Charts/BarChart.vue';
import PieChart from "@/Components/Core/Charts/PieChart.vue";
import BubbleChart from "@/Components/Core/Charts/BubbleChart.vue";
import DoughnutChart from "@/Components/Core/Charts/DoughnutChart.vue";
import LineChart from "@/Components/Core/Charts/LineChart.vue";
import RadarChart from "@/Components/Core/Charts/RadarChart.vue";
import PsLoading from "@/Components/Core/Loading/PsLoading.vue";
import PsButton from "@/Components/Core/Buttons/PsButton.vue";
import PsTextButton from "@/Components/Core/Buttons/PsTextButton.vue";
import PsButtonGroup from "@/Components/Core/Buttons/PsButtonGroup.vue";
import PsIconButton from "@/Components/Core/Buttons/PsIconButton.vue";
import PsDropdown from "@/Components/Core/Dropdown/PsDropdown.vue";
import PsDropdownSelect from "@/Components/Core/Dropdown/PsDropdownSelect.vue";
import PsDropDownSelectMenu from "@/Components/Core/Dropdown/PsDropDownSelectMenu.vue";
import PsInput from "@/Components/Core/Input/PsInput.vue";
import PsInputGroup from "@/Components/Core/Input/PsInputGroup.vue";
import PsAccordion from '@/Components/Core/Accordion/PsAccordion.vue';
import PsCsvModal from '@/Components/Core/Modals/PsCsvModal.vue';
import PsImageIconModal from '@/Components/Core/Modals/PsImageIconModal.vue';
import PsActionModal from '@/Components/Core/Modals/PsActionModal.vue';
//dialog
import PsConfirmDialog from "@/Components/Core/Dialog/PsConfirmDialog.vue";
import PsDangerDialog from "@/Components/Core/Dialog/PsDangerDialog.vue";
import PsLoadingDialog from "@/Components/Core/Dialog/PsLoadingDialog.vue";
import PsActionDialog from "@/Components/Core/Dialog/PsActionDialog.vue";
import PsWarningDialog from "@/Components/Core/Dialog/PsWarningDialog.vue";
import PsSuccessDialog from "@/Components/Core/Dialog/PsSuccessDialog.vue";
import PsErrorDialog from "@/Components/Core/Dialog/PsErrorDialog.vue";
import PsMessageDialog from "@/Components/Core/Dialog/PsMessageDialog.vue";
import DatePicker from "@/Components/Core/DateTime/DatePicker.vue";
import PsDataTable from "@/Components/Core/Table/PsDataTable.vue";
import PsImageUpload from "@/Components/Core/Upload/PsImageUpload.vue";

const GoogleMapPinPicker = defineAsyncComponent(() => import('@/Components/Core/Map/GoogleMapPinPicker.vue'));
const OpenStreetMapPinPicker = defineAsyncComponent(() => import('@/Components/Core/Map/OpenStreetMapPinPicker.vue'))

export default {
    components: {
        GoogleMapPinPicker,
        OpenStreetMapPinPicker,
        PsAlert,
        PsIcon,
        PsIcon1,
        PsLabel,
        PsLabelTitle,
        PsLabelTitle2,
        PsLabelTitle3,
        PsLabelTitle4,
        PsLabelHeader7,
        PsLabelHeader6,
        PsLabelHeader5,
        PsLabelHeader4,
        PsLabelHeader3,
        PsLabelHeader2,
        PsLabelHeader1,
        PsLabelCaption,
        PsCard,
        PsCard1,
        PsNavBar,
        PsNavBar2,
        PsNavBar3,
        PsNavBar4,
        PsNavBar5,
        PsBadge,
        PsBadgeIcon,
        PsBadgeIcon1,
        PsBanner,
        PsBannerIcon,
        PsBreadcrumb,
        PsBreadcrumb2,
        PsInputWithLeftIcon,
        PsInputWithRightIcon,
        PsSearch,
        PsSearchIcon,
        PsSearchButton,
        PsPagination,
        PsPagination1,
        Pagination,
        PsStepStatus,
        PsStep,
        PsLine,
        PsLink1,
        PsInputRange,
        PsRange,
        PsRange1,
        PsRadioValue,
        PsRadio,
        PsTag,
        PsTagIcon,
        PsTextarea,
        PsTextareaMax,
        PsToggle,
        Toggle,
        PsTabs,
        PsTabsLarge,
        PsTooltips,
        PsNotification,
        PsActivity,
        PsNumberCount,
        PsAvatar,
        BarChart,
        PieChart,
        BubbleChart,
        DoughnutChart,
        LineChart,
        RadarChart,
        PsLoading,
        PsButton,
        PsButtonGroup,
        PsTextButton,
        PsIconButton,
        PsDropdown,
        PsDropdownSelect,
        PsDropDownSelectMenu,
        PsInput,
        PsInputGroup,
        PsConfirmDialog,
        PsDangerDialog,
        PsLoadingDialog,
        PsActionDialog,
        PsWarningDialog,
        PsSuccessDialog,
        PsErrorDialog,
        PsMessageDialog,
        PsAccordion,
        PsCsvModal,
        PsImageIconModal,
        DatePicker,
        PsActionModal,
        PsDataTable,
        PsImageUpload
    },
    layout: PsLayout,
    setup() {
        const radioFruits = ref();

        const latitude = ref(16.8409);
        const longitude = ref(96.1735);
        const lat = ref(16.8409);
        const lng = ref(96.1735);

        const date = ref();
        const tempDate = ref();
        const appliedDate = ref();

        const columns = [
            {
                label: "User Name",
                field: "user_name",
                type: 'String',
                action: 'Action'
            },
            {
                label: "Amount",
                field: "amount",
                type: 'String',
                action: 'Action'
            },
            {
                label: "Item Count",
                field: "item_count",
                type: 'String',
                action: 'Action'
            },
            {
                label: "Date",
                field: "date",
                type: 'String',
                action: 'Action'
            },
            {
                label: "Payment",
                field: "payment",
                type: 'String',
                action: 'Action'
            },
            {
                label: "Order Status",
                field: "order_status",
                type: 'String',
                action: 'Action'
            },
            {
                label: "Delivery",
                field: "delivery",
                type: 'String',
                action: 'Action'
            },
            {
                label: "Detail",
                field: "detail",
                type: 'String',
                action: 'Action'
            }
        ];

        const rows = [
            {
                id: "1",
                user_name: "Mg  Mg",
                amount: "$ 1,000",
                item_count: '5',
                date: "2022/07/06 03:08 PM",
                payment: "COD",
                order_status: 'Completed',
                delivery: "",
                detail: "Detail",

            },
            {
                id: "2",
                user_name: "Mg  Mg",
                amount: "$ 1,000",
                item_count: '5',
                date: "2022/07/06 03:08 PM",
                payment: "COD",
                order_status: 'Completed',
                delivery: "",
                detail: "Detail",
            },
            {
                id: "3",
                user_name: "Mg  Mg",
                amount: "$ 1,000",
                item_count: '5',
                date: "2022/07/06 03:08 PM",
                payment: "COD",
                order_status: 'Completed',
                delivery: "",
                detail: "Detail",
            },
            {
                id: "4",
                user_name: "Mg  Mg",
                amount: "$ 1,000",
                item_count: '5',
                date: "2022/07/06 03:08 PM",
                payment: "COD",
                order_status: 'Completed',
                delivery: "",
                detail: "Detail",
            },
            {
                id: "5",
                user_name: "Mg  Mg",
                amount: "$ 1,000",
                item_count: '5',
                date: "2022/07/06 03:08 PM",
                payment: "COD",
                order_status: 'Completed',
                delivery: "",
                detail: "Detail",
            },
            {
                id: "6",
                user_name: "Mg  Mg",
                amount: "$ 1,000",
                item_count: '5',
                date: "2022/07/06 03:08 PM",
                payment: "COD",
                order_status: 'Completed',
                delivery: "",
                detail: "Detail",
            },
            {
                id: "7",
                user_name: "Mg  Mg",
                amount: "$ 1,000",
                item_count: '5',
                date: "2022/07/06 03:08 PM",
                payment: "COD",
                order_status: 'Completed',
                delivery: "",
                detail: "Detail",
            },
            {
                id: "8",
                user_name: "Mg  Mg",
                amount: "$ 1,000",
                item_count: '5',
                date: "2022/07/06 03:08 PM",
                payment: "COD",
                order_status: 'Completed',
                delivery: "",
                detail: "Detail",
            },
            {
                id: "9",
                user_name: "Mg  Mg",
                amount: "$ 1,000",
                item_count: '5',
                date: "2022/07/06 03:08 PM",
                payment: "COD",
                order_status: 'Completed',
                delivery: "",
                detail: "Detail",
            },
            {
                id: "10",
                user_name: "Mg  Mg",
                amount: "$ 1,000",
                item_count: '5',
                date: "2022/07/06 03:08 PM",
                payment: "COD",
                order_status: 'Completed',
                delivery: "",
                detail: "Detail",
            },
        ];

        const radioDataList = reactive([
            {
                id:1, name:"Apple"
            },
            {
                id:2, name:"Banana"
            },
            {
                id:3, name:"Grape"
            }
        ]);

        const showColumList = reactive([
            {
                id:1, name:"Name"
            },
            {
                id:2, name:"Category"
            }
        ]);
        const hideColumList = reactive([
            {
                id:3, name:"Subcategory"
            },
            {
                id:3, name:"User Name"
            }
        ]);

        const radioSelectedList = reactive({id:2, name:"Apple"});

        const textString = ref('Rich Text Editors allow you to format text in a variety of ways');

        const toggleValue = ref(false);
        const toggleValue1 = ref(true);
        const toggleValue2 = ref(false);
        const tab = ref('');

        const ps_confirm_dialog = ref();
        const ps_danger_dialog = ref();
        const ps_loading_dialog = ref();
        const ps_action_dialog = ref();
        const ps_warning_dialog = ref();
        const ps_success_dialog = ref();
        const ps_error_dialog = ref();
        const ps_message_dialog = ref();
        const ps_csv_modal = ref();
        const ps_image_icon_modal = ref();
        const ps_action_modal = ref();

        const selectedIndex = ref(3);
        const input = ref('');
        function onItemClick(i) {
            selectedIndex.value = i;
        }
        function clicked(v){
            alert(v);
        }

        function confirmClicked(){
            ps_confirm_dialog.value.openModal('Confirmation modal','Non-disclosure agreement branding beta equity churn rate channels long tail paradigm shift validation strategy value proposition.Send message','Cofirm','cancel',
            ()=>{
                console.log('ok')
            },
            ()=>{
                console.log('cancel')
            });
        }
        function dangerClicked(){
            ps_danger_dialog.value.openModal('Danger modal','Non-disclosure agreement branding beta equity churn rate channels long tail paradigm shift validation strategy value proposition.','Confirm','Cancel',
            ()=>{
                console.log('ok')
            },
            ()=>{
                console.log('cancel')
            });
        }
        function loadingClicked(){
            ps_loading_dialog.value.closeModal();

            setTimeout(()=> ps_loading_dialog.value.closeModal(),3000);

        }
        function actionClicked(){
            ps_action_dialog.value.openModal('Title','message text','Confirm',
            ()=>{
                console.log('ok')
            });
        }function successClicked(){
            ps_success_dialog.value.openModal('Awesome!','You have complete task successfully.','Complete Another task',
            ()=>{
                console.log('ok')
            });
        }
        function errorClicked(){
            ps_error_dialog.value.openModal('Something Went Wrong','We’re having technical issues at the moment. Please have some patients.','Retry',
            ()=>{
                console.log('ok')
            });
        }
        function messageClicked(){
            ps_message_dialog.value.openModal('Welcome to the team','You’ve completed the task successfully','Start Creating','View Guidelines',
            ()=>{
                console.log('ok')
            },
            ()=>{
                console.log('cancel')
            });
        }

        function warningClicked(){
            ps_warning_dialog.value.openModal('Warning modal','Non-disclosure agreement branding beta equity churn rate channels long tail paradigm shift validation strategy value proposition.','Button','Cancel',
            ()=>{
                console.log('ok')
            },
            ()=>{
                console.log('cancel')
            });
        }

        function csvUploadClicked(){
            ps_csv_modal.value.openModal();
        }

        function imageUploadClicked(){
            ps_image_icon_modal.value.openModal();
        }

        // For bar chart
        const chartData = {

            labels: ["Photoshop", "Illustrator", "Figma", "Sketch", "Invision"],
            datasets: [
                {
                label: "Red",
                backgroundColor: "#EF4444",
                data: [75,15,6,62,85],
                barThickness: 20,
                maxBarThickness: 20,
                barPercentage: 0.5,
                },
                {
                label: "Blue",
                backgroundColor: "#3B82F6",
                data: [57,60,58,95,52],
                barThickness: 20,
                }
            ]
        };
        const chartOptions = {
            responsive: true,
            title: {
                display: true,
                text: 'Projected Cumulative Savings',
                fontSize: 18
            },
            scales: {
                y: {
                    max: 100,
                    min: 0,
                    ticks: {
                        stepSize: 25
                    }
                },
            }
        };

        const verticalChartData = {

            labels: ["1", "2", "3", "4", "5","6", "7", "8", "9", "10","11","12"],
            datasets: [
                {
                label: "Hide label",
                backgroundColor: "#F59E0B",
                data: [12,10,8,11,10,8,11,10,8,11,5,12],
                barThickness: 5,
                maxBarThickness: 20,
                barPercentage: 0.5,
                },
            ]
        };
        const verticalChartOptions = {
            responsive: true,
            title: {
                display: true,
                text: 'Projected Cumulative Savings',
                fontSize: 18
            },
            scales: {
                yAxes: {
                    display: false,
                },
                x: {
                    grid: {
                        display: false,//hide x grid line
                    }
                }
            },
            plugins: {
                legend: {
                    display: false,
                },
            },
        };

        const chartBarOptions = {
            responsive: true,
            scales: {
                y: {
                    max: 100,
                    min: 0,
                    ticks: {
                        stepSize: 25
                    }
                },
            },
            plugins: {
                legend: {
                    display: false,
                },
            },
        };
        const chartBarData = {
            labels: ["Android", "Ios", "Other"],
            datasets: [
                {
                    backgroundColor: ["#0263FF","#FF7723","#8E30FF"],
                    data: [40,90,65],
                    barThickness: 20,
                    maxBarThickness: 20,
                }
            ]
        };

        //Stacked Bar
        const stackedBarOptions = {
            responsive: true,
            scales: {
                yAxes: {
                    display: false,
                    stacked: true
                },
                xAxes: {
                    stacked: true,
                    gridLines: { display: true }
                },
                y: {
                    max: 1000,
                    min: 0,
                    ticks: {
                        stepSize: 250
                    },

                }
            },
            plugins: {
                legend: {
                    display: false,
                },
            },
        };

        const stackedBarData = {
            labels: ["0", "1", "2", "3", "4","5", "6", "7", "8", "9","10","11","12"],
            datasets: [{
                label: 'Title1',
                data: [260, 100, 260, 150, 160, 200, 230, 500, 500, 300, 260, 250, 300 ],
                backgroundColor: "#6366F1",
                barThickness: 25,
            },
            {
                label: 'Title2',
                data: [240, 200, 260, 220, 300, 200, 250, 0, 0, 50, 100, 200, 170 ],
                backgroundColor: "#818CF8",
                barThickness: 25,
            },
            {
                label: 'Title3',
                data: [300, 100, 260, 150, 200, 100, 100, 0, 0, 100, 150, 150, 100 ],
                backgroundColor: "#A5B4FC",
                barThickness: 25,
            },
            {
                label: 'Title4',
                data: [100, 150, 260, 100, 100, 150, 200, 0, 0, 200, 170, 100, 200 ],
                backgroundColor: "#E0E7FF",
                barThickness: 25,
            }
            ]
        }

        //horizontal bar chart
        const horizontalChartOptions = {
            responsive: true,
            scales: {
                xAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            },
            indexAxis: 'y', //appear horizontal bar
            scales: {
                yAxes: {
                    display: false
                },
                x: {
                    max: 6000,
                    min: 0,
                    ticks: {
                        stepSize: 1000
                    }
                }
            },
            plugins: {
                legend: {
                    display: false,
                },
            },
        };

        const horizontalChartData = {
            labels: ["Photoshop", "Illustrator", "Figma", "Sketch", "Invision"],
            datasets: [{
                axis: 'y',
                label: 'My First Dataset',
                data: [1200, 3500, 500, 4500, 5000, 55, 40],
                fill: false,
                backgroundColor: [
                '#C7D2FE',
                '#A5B4FC',
                '#E0E7FF',
                '#818CF8',
                '#6366F1',
                ],
                borderWidth: 1
            }]
        };

        //For Pie Chart
        const pieChartData = {
            labels: ["Title1", "Title1", "Title1", "Title1"],
            datasets: [
                {
                    backgroundColor: [
                        "#6366F1",
                        "#818CF8",
                        "#A5B4FC",
                        "#E0E7FF",
                    ],
                    data: [80, 40, 30, 10],
                },
            ],
        };
        const pieChartOptions = {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    },
                    gridLines: {
                        display: true
                    }
                }],
                xAxes: [ {
                    gridLines: {
                        display: false
                    }
                }]
            },
            responsive: true,
            maintainAspectRatio: false,
        };

        // For bubble chart
        const bubbleChartData = {
            datasets: [
                {
                    label: "Title1",
                    backgroundColor: "#F59E0B",
                    data: [
                        { x: 0.5, y: 480, r: 3 },
                        { x: 2, y: 250, r: 5 },
                        { x: 3, y: 210, r: 3 },
                        { x: 4, y: 400, r: 5 },
                        { x: 5, y: 400, r: 5 },
                        { x: 8, y: 600, r: 5 },
                        { x: 9, y: 750, r: 7 },
                        { x: 12, y: 600, r: 5 },
                        { x: 13, y: 710, r: 7 },
                        { x: 14, y: 730, r: 7 },
                        { x: 15, y: 740, r: 7 },
                        { x: 14, y: 500, r: 5 },
                        { x: 15, y: 400, r: 5 },
                    ],
                },
                {
                    label: "Title1",
                    backgroundColor: "#8B5CF6",
                    data: [
                        { x: 0.5, y: 700, r: 5 },
                        { x: 1, y: 800, r: 5 },
                        { x: 3, y: 400, r: 5 },
                        { x: 5, y: 230, r: 5 },
                        { x: 6, y: 210, r: 5 },
                        { x: 7, y: 59, r: 5 },
                        { x: 8, y: 250, r: 5 },
                        { x: 10, y: 250, r: 5 },
                        { x: 11, y: 600, r: 5 },
                        { x: 12, y: 700, r: 3 },
                        { x: 11, y: 800, r: 5 },
                        { x: 15, y: 600, r: 5 },
                    ],
                },
            ],
        };

        const bubbleChartOptions = {
            scales: {
                y: {
                    max: 1000,
                    min: 0,
                    ticks: {
                        stepSize: 250
                    }
                },
                x: {
                    max: 16,
                    min: 0,
                    ticks: {
                        stepSize: 4
                    }
                }
            },
            responsive: true,

        };

        // For doughnut chart
        const doughnutChartData = {
            labels: ["Title1", "Title2"],
            datasets: [
                {
                    backgroundColor: [
                        "#6366F1",
                        "#EEF2FF",
                    ],
                    data: [35, 75],
                    borderWidth: 3
                },
            ]
        };

        const dnChartData = {
            labels: ["Android", "IOS", "Other"],
            datasets: [
                {
                    label: "Android",
                    data: [80,20],
                    backgroundColor: ["#6366F1","#F3F4F6"]
                }, {
                    label: "IOS",
                    data: [70,30],
                    backgroundColor: ["#EF4444","#F3F4F6"]
                },
                {
                    label: "Other",
                    data: [60,40],
                    backgroundColor: ["#10B981","#F3F4F6"]
                }
            ],
        };

        const doughnutChartOptions = {
            responsive: true,
            cutout: 120,
            rotation: -90,
            circumference: 180,
            legend: {
                display: false
            },
            tooltip: {
                enabled: false
            },
            plugins: {
                legend: {
                    display: false,
                },
            },
            maintainAspectRatio: false,
        };

        const dnChartOptions = {
            responsive: true,
            cutout: 100,
            plugins: {
                legend: {
                    display: true,
                    align: 'center',
                    position: 'bottom'
                },
            },
            maintainAspectRatio: false,
        };

        // For line chart
        const lineChartData = {
            labels: ["Mon","Tue","Wed","Thur","Fri","Sat","Sun"],
            datasets: [
                {
                    label: "Hide Label",
                    data: [80, 60, 50, 70, 20, 40, 60],
                    backgroundColor: "rgba(99,102,241,0.2)",
                    borderColor: "#6366F1",
                    pointBackgroundColor: "#fff",
                    fill: true
                },
            ],
        };

        const lineChartOptions = {
            responsive: true,
            scales: {
                y: {
                    max: 100,
                    min: 0,
                    ticks: {
                        stepSize: 25
                    }
                }
            },
            plugins: {
                legend: {
                    display: false,// Hides the datasets labels
                }
            },
            maintainAspectRatio: false,
        };
        const textChartData = {
            labels: ["Title1", "Title1", "Title1","Title1"],
            datasets: [
                {
                    data: [60,15,15,30],
                    backgroundColor: ["#10B981","#EF4444","#F59E0B","#8B5CF6"],
                }
            ],
        };
        const textChartOptions = {
            responsive: true,
            cutout: 100,
            plugins: {
                legend: {
                    display: true,
                    align: 'center',
                    position: 'bottom'
                },
            },
            maintainAspectRatio: false,
        };
        const chartPlugin = {
            id: "idOfPlugin-doughnut-chart",
            beforeDraw: function (chart) {
                var width = chart.width,
                height = chart.height,
                ctx = chart.ctx;
                ctx.restore();
                var fontSize = (height / 150).toFixed(2);
                // ctx.font = `${fontSize}em`
                ctx.font = fontSize + "em Inter";
                ctx.fillStyle = "#4771FA";
                ctx.textBaseline = "middle";

                var text = "80%",
                textX = Math.round((width - ctx.measureText(text).width) / 1.9),
                textY = height / 2.4;

                ctx.fillText(text, textX, textY);
                ctx.save();
            },
        };

        // For radar chart
        const radarChartData = {
            labels: [
                "Photoshop",
                "Illustrator",
                "Xd",
                "Indesign",
                "Premiere"
            ],
            datasets: [
                {
                    label: "Title1",
                    backgroundColor: "rgba(254, 202, 202, 0.1)",
                    borderColor: "#EF4444",
                    borderWidth: 1,
                    pointBackgroundColor: "#fff",
                    pointBorderColor: "#B91C1C",
                    data: [5,5,5,8,7]
                },
                {
                    label: "Title2",
                    backgroundColor: "rgba(191, 219, 254, 0.1)",
                    borderColor: "#3B82F6",
                    borderWidth: 1,
                    pointBackgroundColor: "#fff",
                    pointBorderColor: "#0263FF",
                    data: [2,2,7,6,7]
                },
                {
                    label: "Title3",
                    backgroundColor: "rgba(253, 230, 136, 0.1)",
                    borderColor: "#F59E0B",
                    borderWidth: 1,
                    pointBackgroundColor: "#fff",
                    pointBorderColor: "#B45309",
                    data: [5,2,2,7,6]
                },
                {
                    label: "Title4",
                    backgroundColor: "rgba(16, 185, 129, 0.1)",
                    borderColor: "#10B981",
                    borderWidth: 1,
                    pointBackgroundColor: "#fff",
                    pointBorderColor: "#047857",
                    data: [10,9.5,6,8,4]
                },

            ]
        };

        const radarChartOptions = {
            responsive: true,
            scales: {
                r: {
                    ticks: {
                        display: false, // Hides the labels in the middel (numbers)
                        maxTicksLimit: 5,
                    }
                }
            },
            plugins: {
                legend: {
                    display: false,// Hides the datasets labels
                }
            },
            maintainAspectRatio: false,
        };

        function currentTab(value) {
            tab.value = value;
        }
        let currentPage = ref("1");
        function onPageChange(page) {
            console.log(page)
            currentPage.value = page;
        }

        async function pageChangeHandle(value) {
            switch (value) {
                case 'next':
                currentPage.value += 1
                break
                case 'previous':
                currentPage.value -= 1
                break
                default:
                currentPage.value = value
            }

        }

        function updateCoordinates(location) {
            lat.value = location.latLng.lat();
            lng.value = location.latLng.lng();

        }

        function updateLeafletCoordinates(location) {

            latitude.value = location.lat;
            longitude.value = location.lng;

        }
        function cancelDate(){
            appliedDate.value = null;
        }
        function applyDate(){
            appliedDate.value = tempDate.value;
        }
        function hideColum(id){
            let index = -1;
            for(let i=0; i < showColumList.length; i++){
                if(showColumList[i].id == id ){
                    index = i;
                }
            }
            if(index != -1){
                hideColumList.push(showColumList[index]);
                showColumList.splice(index,1);
            }
        }
        function showColum(id){
            let index = -1;
            for(let i=0; i < hideColumList.length; i++){
                if(hideColumList[i].id == id ){
                    index = i;
                }
            }
            if(index != -1){
                showColumList.push(hideColumList[index]);
                hideColumList.splice(index,1);
            }
        }
        function showAll(){
            for(let i=0; i < hideColumList.length; i++){
                showColumList.push(hideColumList[i]);
            }
            hideColumList.splice(0,hideColumList.length);
        }
        function hideAll(){
            for(let i=0; i < showColumList.length; i++){
                hideColumList.push(showColumList[i]);
            }
            showColumList.splice(0,showColumList.length);
        }
        function replaceVideoClicked(){
            ps_action_modal.value.openModal('Confirmation modal',
            'Replace Video',
            'Remove Video',
            'videoCamera',
            'trash',
            '24',
            '24',
            ()=>{
                console.log('replace')
            },
            ()=>{
                console.log('remove')
            }
            );
        }
        function replaceImageClicked(){
            ps_action_modal.value.openModal('Confirmation modal',
            'Replace Image',
            'Remove Image',
            'image',
            'trash',
            '24',
            '24',
            ()=>{
                console.log('replace')
            },
            ()=>{
                console.log('remove')
            }
            );
        }

        return {
            updateCoordinates,
            updateLeafletCoordinates,
            latitude,
            longitude,
            lat,
            lng,

            date,
            tempDate,
            appliedDate,
            cancelDate,
            applyDate,

            showColumList,
            hideColumList,
            hideColum,
            showColum,
            showAll,
            hideAll,

            radioFruits,
            radioDataList,
            radioSelectedList,
            textString,
            toggleValue,
            toggleValue1,
            toggleValue2,
            currentTab,
            tab,
            onPageChange,
            currentPage,
            pageChangeHandle,
            clicked,
            onItemClick,
            input,
            selectedIndex,
            ps_confirm_dialog,
            ps_danger_dialog,
            ps_loading_dialog,
            ps_action_dialog,
            ps_warning_dialog,
            ps_success_dialog,
            ps_error_dialog,
            ps_message_dialog,

            confirmClicked,
            actionClicked,
            dangerClicked,
            loadingClicked,
            warningClicked,
            successClicked,
            errorClicked,
            messageClicked,

            chartData,
            chartOptions,

            pieChartData,
            pieChartOptions,

            bubbleChartData,
            bubbleChartOptions,

            doughnutChartData,
            doughnutChartOptions,

            lineChartData,
            lineChartOptions,

            radarChartData,
            radarChartOptions,

            stackedBarOptions,
            stackedBarData,

            horizontalChartOptions,
            horizontalChartData,

            chartBarOptions,
            chartBarData,

            dnChartData,
            dnChartOptions,

            verticalChartData,
            verticalChartOptions,

            textChartOptions,
            textChartData,
            chartPlugin,
            ps_csv_modal,
            ps_image_icon_modal,
            ps_action_modal,
            csvUploadClicked,
            imageUploadClicked,
            replaceVideoClicked,
            replaceImageClicked,
            columns,
            rows
        }
    },
    computed: {
        breadcrumb() {
            return [
                {
                    label: "Bare",
                }
            ]
        },

        breadcrumb1() {
            return [
                {
                    label: "Bare",
                    color: "text-primary-500"
                }
            ]
        },

        breadcrumb2() {
            return [
                {
                    label: "Bare",
                    color: "text-secondary-300"
                }
            ]
        },

        breadcrumb3() {
            return [
                {
                    label: "Bare",
                    color: "text-secondary-400"
                }
            ]
        }
    },
}
</script>
