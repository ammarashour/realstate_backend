<template>
    <div class="">
        <splide :options="options" :has-track="false">
            <splide-track>
                <splide-slide v-for="item in itemList" :key="item.id">
                    <item-horizontal-item v-if="item.title == ''" :item="item" />
                    <!-- <ps-route-link v-else :to="{ name: 'fe_item_detail', query: { item_id: item.id   }}"> -->
                        <item-horizontal-item :item="item" :notShowTitle="notShowTitle" :storeName="storeName"/>
                    <!-- </ps-route-link> -->
                </splide-slide>
            </splide-track>

            <div :class="'splide__arrows splide__arrows--'+ languageStore.getLanguageDir()">
                <button
                    class="w-10 h-10 p-2 rounded shadow-sm bg-feSecondary-50 dark:bg-feSecondary-800 splide__arrow--prev"
                    type="button"
                    aria-label="Previous slide"
                    aria-controls="splide01-track"
                >
                    <ps-icon textColor="dark:text-feSecondary-200" name="arrowNarrowRight" h="23" w="23" viewBox="0 -3 9 20"/>
                </button>
                <button
                    class="w-10 h-10 p-2 rounded shadow-sm bg-feSecondary-50 dark:bg-feSecondary-800 splide__arrow--next"
                    type="button"
                    aria-label="Next slide"
                    aria-controls="splide01-track"
                >
                    <ps-icon textColor="dark:text-feSecondary-200" name="arrowNarrowRight" h="23" w="23" viewBox="0 -3 9 20"/>
                </button>
            </div>
        </splide>
    </div>
</template>

<script>
import { Splide, SplideSlide, SplideTrack } from '@splidejs/vue-splide';
import '@splidejs/vue-splide/css';
import ItemHorizontalItem from '@template1/vendor/components/modules/item/ItemHorizontalItem.vue';
import PsRouteLink from '@template1/vendor/components/core/link/PsRouteLink.vue';
import PsButton from '@template1/vendor/components/core/buttons/PsButton.vue';
import PsIcon from '@template1/vendor/components/core/icons/PsIcon.vue';
import { useLanguageStore } from '../../../../../../../../../resources/js/store/Localization/LanguageStore';
    export default {
        name: 'FeatureItemHorizontalSwiper',
        components: {
            Splide,
            SplideSlide,
            SplideTrack,
            ItemHorizontalItem,
            PsRouteLink,
            PsButton,
            PsIcon
        },
        props: {
            itemList: Object,
            notShowTitle: {
                type: Boolean,
                default: false
            },
            storeName: {
                type: String,
                default: ""
            }
        },
        setup(props) {
            const languageStore = useLanguageStore();
            
            const options = {
                rewind: true,
                gap   : '1.5rem',
                perPage: 4,
                focus  : 0,
                omitEnd: true,
                pagination: false,
                direction: languageStore.getLanguageDir(),
                breakpoints:{
                    1536:{
                        perPage: 4,
                        gap   : '1.5rem',
                    },
                    1280:{
                        perPage:4,
                        gap   : '.75rem',
                    },
                    1024:{
                        perPage:3,
                        gap   : '.75rem',
                    },
                    768:{
                        perPage:2,
                        gap   : '.75rem',
                    },
                    640:{
                        perPage:2,
                        gap   : '.5rem',
                    },
                    200:{
                        perPage:2
                    }
                }
            };
            const options2 = {
                rewind: true,
                gap   : '1.5rem',
                perPage: 4,
                focus  : 0,
                omitEnd: true,
            };
            const options3 = {
                rewind: true,
                gap   : '1.5rem',
                perPage: 4,
                focus  : 0,
                omitEnd: true,
            };
            const options4 = {
                rewind: true,
                gap   : '1.5rem',
                perPage: 4,
                focus  : 0,
                omitEnd: true,
            };
            
            return { options, languageStore };
        }
    }
</script>

<style scoped>
.splide__arrow--prev{
    position:absolute;
    top:50%;
    transform:translateY(-50%);
}
.splide__arrow--next{
    position:absolute;
    top:50%;
    transform:translateY(-50%);
}
/* right to left */
.splide__arrows--rtl .splide__arrow--prev{left:auto;right:-1em}
.splide__arrows--rtl .splide__arrow--next{right:auto;left:-1em}

/* left to right */
.splide__arrows--ltr .splide__arrow--prev{right:auto;left:-1em}
.splide__arrows--ltr .splide__arrow--next{left:auto;right:-1em}

    /* .splide{z-index: 0; padding: 0;}
@media (max-width: 640px) {

    .splide__arrow--next{
        left: calc(100% - 1.5rem);
    }
}
@media (max-width: 786px) {
    .splide__arrow--prev{
        left: -1rem;
    }
    .splide__arrow--next{
        right: -1rem;
    }
} */
</style>
