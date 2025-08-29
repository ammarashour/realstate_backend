<template>
    <!-- start search row -->
    <div class="flex justify-between mt-10">
        <!-- for per page options dropdown -->
        <div class="flex">
            <ps-dropdown align="left" class="me-4" h="h-46">
                <template #select>
                    <div class="flex flex-row">
                        <ps-label class="my-auto font-bold me-2" >{{ $t('core__be_show') }}:&nbsp;</ps-label>
                        <ps-dropdown-select :placeholder="$t('core__be_show')"
                            :selectedValue="perPage + ' ' + $t('core__be_rows')"
                            class="text-red-600 light:font-bold w-29 h-10"/>
                    </div>
                </template>
                <template #list>
                    <div class="rounded-md shadow-xs w-24">
                        <div class="z-30 ">
                            <div v-for="(option, index) in perPageOptions" :key="index"
                                :class="perPage != option ? 'w-32 flex py-4 px-2 hover:bg-primary-000 dark:hover:bg-primary-900 cursor-pointer items-center' : ''"
                                @click="[perPage = option, setPerPage(option)]"
                                >
                                <ps-label class="ms-2" v-if="perPage != option">
                                    {{ option }} {{ $t('core__be_rows') }} </ps-label>
                            </div>
                        </div>
                    </div>
                </template>
            </ps-dropdown>
            <slot name="searchLeft" />
        </div>
        <!-- end per page options -->
        <div class="flex gap-2">
            <!-- For global search start -->
            <slot name="searchRight" />
            <!-- For global search end -->
            <dropdown align="left" v-model:colFilterOptions="psColFilterOptions">
                <template #select>
                    <div class="me-2 relative flex justify-center items-center rounded w-10 h-10 bg-primary-500">
                        <ps-icon name="eye-on" class='cursor-pointer text-white dark:text-secondaryDark-black' />
                    </div>
                </template>
            </dropdown>
        </div>
    </div>
    <!-- search option -->
    <slot class="mt-4" name="searchOption"/>
    <!-- end search row -->
</template>

<script>
import PsDropdown from "@/Components/Core/Dropdown/PsDropdown.vue";
import PsDropdownSelect from "@/Components/Core/Dropdown/PsDropdownSelect.vue";
import PsIcon from "@/Components/Core/Icons/PsIcon.vue";
import Dropdown from "@/Components/Core/DropdownModal/Dropdown.vue";
import PsLabel from "@/Components/Core/Label/PsLabel.vue";

export default {
    name: "PsTableSearch",
    components: {
        PsDropdown,
        PsDropdownSelect,
        PsIcon,
        Dropdown,
        PsLabel
    },
    props: ["perPageOptions","colFilterOptions"],
    data(props) {
        return {
            page: 1,
            perPage: this.perPageOptions[0],
            psColFilterOptions: props.colFilterOptions ? props.colFilterOptions : '',
        };
    }
}
</script>