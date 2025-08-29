<template>
    <!-- search -->
    <div class="flex justify-between">
        <div class="flex ">
            <!-- for per page options dropdown -->
            <ps-dropdown align="left" class="text-sm me-2">
                <template #select>
                    <div class="flex flex-row">
                        <ps-label class="my-auto">{{$t('core__be_show')}}:&nbsp;</ps-label>
                        <ps-dropdown-select :placeholder="$t('core__be_show')"
                            :selectedValue="perPage + ' ' + $t('core__be_rows')"
                            class="w-32"/>
                    </div>
                </template>
                <template #list>
                    <div class="rounded-md shadow-xs w-24 ">
                        <div class="z-30 ">
                                <div v-for="(option, index) in perPageOptions" :key="index"
                                    class="w-32 flex py-4 px-2 hover:bg-primary-000 dark:hover:bg-primary-900 cursor-pointer items-center"
                                    @click="[perPage = option, setPerPage(option)]"
                                    >
                                    <ps-label class="ms-2"
                                        :class="perPage == option ? ' font-bold' : ''">
                                        {{ option }} {{ $t('core__be_rows') }} </ps-label>
                                </div>
                            </div>
                    </div>
                </template>
            </ps-dropdown>
            <!-- end per page options -->

            <slot name="import"/>
        </div>
        <div class="">
            <slot name="search"/>
        </div>
    </div>

    <!-- search option -->
    <div class="mb-4">
        <slot name="searchOption"/>
    </div>

    <!-- table -->
    <div class="flex flex-col overflow-x-auto overflow-y-hidden w-full">
        <slot name="table"/>
    </div>

    <!-- pagination -->
    <div class="
      mt-8
      flex
      w-full
      justify-between
    "
    >
        <div class="flex-1 flex justify-between sm:hidden">
            <button class="
          inline-flex
          items-center
          px-4
          py-2
          border border-gray-300
          text-sm
          font-medium
          rounded-md
          text-gray-700
          bg-white
          hover:bg-gray-50
            hover:text-gray-800
            disabled:cursor-default
            disabled:text-gray-400
        " :disabled="page == 1" @click="changePage('prev')">
                Previous
            </button>
            <button class="
          ms-3
          inline-flex
          items-center
          px-4
          py-2
          border border-gray-300
          text-sm
          font-medium
          rounded-md
          text-gray-700
          bg-white
          hover:bg-gray-50
              hover:text-gray-800
              disabled:cursor-default
              disabled:text-gray-400
        " :disabled="page == pages" @click="changePage('next')">
                Next
            </button>
        </div>

        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between
              ">
            <div class="flex text-sm text-gray-700">
                <p class="text-sm text-gray-700">
                    Showing
                    {{ " " }}
                    <span class="font-medium">{{ totalRecords >0 ? (page - 1) * perPage + 1 : 0 }}</span>
                    {{ " " }}
                    to
                    {{ " " }}
                    <span class="font-medium">{{
                            page * perPage > totalRecords ? totalRecords : page * perPage
                    }}</span>
                    {{ " " }}
                    of
                    {{ " " }}
                    <span class="font-medium">{{ totalRecords }}</span>
                    {{ " " }}
                    entries
                </p>
            </div>
            <div class="flex  rtl:space-x-reverse space-x-1" v-if="totalRecords>perPage">
                <!-- Previous -->
                <button class="
                    inline-flex
                    items-center
                    border
                    py-2
                    px-4
                    text-xs
                    font-medium
                    text-gray-600
                    rounded
                    bg-white
                    hover:text-gray-500 hover:bg-gray-200
                    disabled:cursor-default
                    disabled:text-gray-400
                    " :disabled="page == 1" @click="changePage('prev')">
                    <span class="sr-only">Previous</span>
                    <ps-icon name="doubleArrowLeft" />
                </button>

                <!-- First Page -->
                <a v-show="totalRecords>0" href="#" class="
                    rounded
                    bg-white
                    text-gray-500
                    inline-flex
                    items-center
                    border
                    py-2
                    px-4
                    text-sm
                    font-medium
                    " :class="page == 1 ? 'bg-indigo-500 text-white' : 'hover:text-gray-500 hover:bg-gray-200'"
                            @click="changePage(1)">
                    1
                </a>

                <!-- pages between first and last -->
                <template v-for="n in pages" :key="n">
                    <!-- paginate number > prev ... -->
                    <span class="
                        inline-flex
                        items-center
                        py-2
                        px-4
                        bg-white
                        text-sm
                        font-medium
                        text-gray-700
                    " v-if="n > 1 && n == page - 3">
                        ...
                    </span>

                    <!-- paginate number -->
                    <a href="#" class="
                            rounded
                            bg-white
                            text-gray-500
                            inline-flex
                            items-center
                            border
                            py-2
                            px-4
                            text-sm
                            font-medium
                        " :class="n == page ? 'bg-indigo-500 text-white' : 'hover:text-gray-500 hover:bg-gray-200'"
                        @click="changePage(n)" v-if="n != 1 && n != pages && n >= page - 2 && n <= page + 2">
                        {{ n }}
                    </a>

                    <!-- paginate number > next ... -->
                    <span class="
                            inline-flex
                            items-center
                            py-2
                            px-4
                            bg-white
                            text-sm
                            font-medium
                            text-gray-700
                        " v-if="n < pages && n == page + 3 && n < pages">
                        ...
                    </span>
                </template>

                <!-- Last Page -->
                <a v-show="totalRecords>0" href="#" class="
                    rounded
                    bg-white
                    text-gray-500
                    inline-flex
                    items-center
                    border
                    py-2
                    px-4
                    text-sm
                    font-medium
                    " :class="page == pages ? 'bg-indigo-500 text-white' : 'hover:text-gray-500 hover:bg-gray-200'"
                    @click="changePage(pages)" v-if="pages != 1">
                    {{ pages }}
                </a>

                <!-- Next -->
                <button class="
                    inline-flex
                    items-center
                    border
                    py-2
                    px-4
                    my-0.5
                    text-xs
                    font-medium
                    text-gray-600
                    rounded
                    bg-white
                    hover:text-gray-500 hover:bg-gray-200
                    disabled:cursor-default
                    disabled:text-gray-400
                    " :disabled="page == pages" @click="changePage('next')"
                    >
                    <span class="sr-only">Next</span>
                    <ps-icon name="doubleArrowRight" />
                </button>
            </div>
        </div>
    </div>
</template>

<script>
import PsDropdown from "@/Components/Core/Dropdown/PsDropdown.vue";
import PsDropdownSelect from "@/Components/Core/Dropdown/PsDropdownSelect.vue";

export default {
    name: "PsPaginator",
    components: {
        PsDropdown,
        PsDropdownSelect
    },
    props: ["totalRecords", "perPageOptions"],
    data() {
        return {
            page: 1,
            perPage: this.perPageOptions[0],
        };
    },
    computed: {
        pages() {
            const remainder = this.totalRecords % this.perPage;

            if (remainder > 0) {
                return Math.floor(this.totalRecords / this.perPage) + 1;
            } else {
                return this.totalRecords / this.perPage;
            }
        },
    },
    methods: {
        changePage(val) {

            if (val == 'prev') {
                this.page = this.page > 1 ? this.page - 1 : this.page;

            } else if (val == 'next') {
                this.page = this.page < this.pages ? this.page + 1 : this.page;

            } else {
                this.page = val;
            }

            this.$emit("update:value", { page: this.page, perPage: this.perPage });
        },

        setPerPage(val) {
            this.perPage = val;
            this.page = 1
            this.$emit("update:value", { page: this.page, perPage: this.perPage });
        },
    },
};
</script>
