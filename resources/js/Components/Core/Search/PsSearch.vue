<template>
    <div class="flex">
        <button id="dropdown-button" data-dropdown-toggle="dropdown" class="flex-shrink-0 z-10 inline-flex items-center py-2.5 px-4 text-sm font-medium text-center text-gray-900 bg-white border border-gray-300 rounded-l-lg dark:text-white dark:border-gray-600" type="button">
            {{$t('core__be_all')}} 
            <ps-icon name="downChervon" class="child1 ms-14" :class="iconTheme"  />
        </button>
        <div id="dropdown" class="hidden z-10 w-44 bg-white rounded divide-y divide-gray-100 shadow dark:bg-gray-700" data-popper-reference-hidden="" data-popper-escaped="" data-popper-placement="top" style="position: absolute; inset: auto auto 0px 0px; margin: 0px; transform: translate3d(897px, 5637px, 0px);">
            <ul class="py-1 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdown-button">
            <li>
                <button type="button" class="inline-flex py-2 px-4 w-full hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Mockups</button>
            </li>
            <li>
                <button type="button" class="inline-flex py-2 px-4 w-full hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Templates</button>
            </li>
            <li>
                <button type="button" class="inline-flex py-2 px-4 w-full hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Design</button>
            </li>
            <li>
                <button type="button" class="inline-flex py-2 px-4 w-full hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Logos</button>
            </li>
            </ul>
        </div>
        <div class="relative w-full">
            <input type="search" id="search-dropdown" class="block p-2.5 w-full z-20 text-sm text-gray-900 bg-white rounded-r-lg border-s-gray-50 border-s-2 border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-s-gray-700  dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:border-blue-500" :placeholder="placeholder" required>
            <button type="submit" class="absolute top-0 right-0 p-2.5 text-sm font-medium rounded-r-lg focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg></button>
        </div>
    </div>
</template>

<script>
import PsIcon from '../Icons/PsIcon.vue';

export default {
    name: "PsSearch",
    components: {
        PsIcon
    },
    props: {
        "value" : { type: String, default: "" },
        "theme" : { type: String, default : "input-primary" },
        "roundedLeft": { type: String, default: "rounded" },
        "roundedRight": { type: String, default: "rounded" },
        "maxlength" : { type : Number, default : 99999999 },
        "placeholder": { type: String, default: "" },
        "onInput" : Function
    },
    setup(_, {emit}) {
        // const psValueHolder = PsValueProvider.psValueHolder;
        function handleInput(v) {
            emit('update:value', v)
        }

        return {
            handleInput,
            // psValueHolder
        }
    }
}
</script>
<style scoped>
    .child2{
        display: none;
    }
    .parent:focus > .child > .child1{
        display: none;
    }
    .parent:focus  > .child > .child2{
        display: block;
    }


</style>























