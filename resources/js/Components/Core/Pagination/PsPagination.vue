<template>
    <div class="flex justify-between mt-10">
        <ps-label class="text-secondary-600 text-base font-normal">
            {{$t('ps_pagination__showing')}}
            {{ " " }}
            <span class="font-medium">{{ totalRecords >0 ? (page - 1) * perPage + 1 : 0 }}</span>
            {{ " " }}
            {{$t('ps_pagination__to')}}
            {{ " " }}
            <span class="font-medium">{{
                    page * perPage > totalRecords ? totalRecords : page * perPage
            }}</span>
            {{ " " }}
            {{$t('ps_pagination__of')}}
            {{ " " }}
            <span class="font-medium">{{ totalRecords }}</span>
            {{ " " }}
            {{$t('ps_pagination__entries')}}
        </ps-label>

        <div class="flex items-center  rtl:space-x-reverse space-x-1" v-if="totalRecords>perPage">
            <!-- Previous icon -->
            <ps-button class="h-8" rounded="rounded" colors="bg-white dark:bg-secondaryDark-black" border="border border-gray-200 rounded" 
                        :disabled="page == 1" @click="changePage('prev')">
                <ps-icon name="doubleArrowLeft" w="16" h="16"   />
            </ps-button>
            <!-- paginate number -->
            <div v-for="n in pages" :key="n" >
                <ps-button rounded="rounded" colors="bg-white" border="border border-gray-200 rounded"
                    :class="n == page ? 'bg-indigo-500 text-white' : 'hover:text-gray-500 hover:bg-gray-200'"
                    @click="changePage(n)" v-if="n <= 5 ">
                    {{n}}
                </ps-button>
                <!-- paginate number > next ... -->
                <span class="
                    inline-flex
                    items-center
                    py-2
                    px-4
                    bg-white
                    text-sm
                    font-medium
                    text-gray-700" v-if="n < pages && n == page + 5 && n < pages">
                    ...
                </span>
            </div>
            <!-- Next icon -->
            <ps-button class="h-8" rounded="rounded" colors="bg-white" border="border border-gray-200 rounded"
                        :disabled="page == pages" @click="changePage('next')">
                <ps-icon name="doubleArrowRight" w="16" h="16" />
            </ps-button>
        </div>
    </div>
</template>
<script>
import PsLabel from "@/Components/Core/Label/PsLabel.vue";
import PsIcon from "@/Components/Core/Icons/PsIcon.vue";
import PsButton from "@/Components/Core/Buttons/PsButton.vue";
export default {
    name: "PsPagination",
    components: {
        PsLabel,
        PsIcon,
        PsButton
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
    }
}
</script>
