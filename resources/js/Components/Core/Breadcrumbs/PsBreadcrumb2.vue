<template>
    <nav class="w-full rounded-md ">
        <ol class="flex list-reset">
            <li  class="text-secondary-800"><ps-icon-1 v-if="iconName" :name="iconName" class="me-1" :theme="iconColor" /></li>
            
            <li v-for="(item,index) in items" :key="index" class="flex flex-row text-secondary-800 first-line:items-center">

                <ps-label v-if="item.url" :textColor="item.color ? item.color : 'text-secondary-800 dark:text-white'" 
                    class="flex flex-row items-center">
                    <Link :href="item.url" class="text-s">{{item.label}}</Link>
                    <span v-if="index < (items.length-1)" class="mx-0.5 lg:mx-1 flex items-center justify-center">
                        <span class="block rtl:hidden">
                            <ps-icon w="24" h="24" name="rightChervon" class="hidden text-gray-500 lg:flex" />
                            <ps-icon w="20" h="20" name="rightChervon" class="flex text-gray-500 lg:hidden" />
                        </span>
                        <span class="hidden rtl:block">
                            <ps-icon w="24" h="24" name="leftChervon" class="hidden text-gray-500 lg:flex" />
                            <ps-icon w="20" h="20" name="leftChervon" class="flex text-gray-500 lg:hidden" />
                        </span>
                       
                    </span>
                </ps-label>
                <ps-label v-else class="flex flex-row">
                    <span :class="item.color ? item.color : 'text-secondary-800 dark:text-white'"  class="text-sm lg:text-base">{{item.label}}</span>
                    <span v-if="index < (items.length-1)" class="text-secondary-800 mx-0.5 sm:mx-1  flex items-center justify-center">
                        <span class="block rtl:hidden">
                            <ps-icon w="24" h="24" name="rightChervon" class="hidden lg:flex" />
                            <ps-icon w="20" h="20" name="rightChervon" class="flex lg:hidden" />
                        </span>
                        <span class="hidden rtl:block">
                            <ps-icon w="24" h="24" name="leftChervon" class="hidden lg:flex" />
                            <ps-icon w="20" h="20" name="leftChervon" class="flex lg:hidden" />
                        </span>
                    </span>
                </ps-label>

            </li>
        </ol>
        </nav>
</template>
<script>
    import PsLabel from "@/Components/Core/Label/PsLabel.vue";
    import PsIcon1 from "@/Components/Core/Icons/PsIcon1.vue";
    import PsIcon from "@/Components/Core/Icons/PsIcon.vue";

    export default {
        name: "PsBreadcrumb2",
        components: {
            PsLabel,
            PsIcon1,
            PsIcon
        },
        props: {
            iconName: {
                type: String,
                default: ""
            },
            iconColor: {
                type: String,
                default: "#4B5563"
            },
            items: {
                type: Array,
                required: true
            }
        }
    }
</script>
