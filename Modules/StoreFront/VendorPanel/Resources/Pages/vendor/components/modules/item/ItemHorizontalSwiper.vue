<template>
    <div>
        <splide :options="options" :has-track="false">
            <splide-track>
                <splide-slide v-if="isLoading" >
                    <item-horizontal-skeletor-item />
                </splide-slide>
                <splide-slide v-if="isLoading" >
                    <item-horizontal-skeletor-item />
                </splide-slide>
                <splide-slide v-if="isLoading" >
                    <item-horizontal-skeletor-item />
                </splide-slide>
                <splide-slide v-if="isLoading" >
                    <item-horizontal-skeletor-item />
                </splide-slide>
                <splide-slide v-for="item in itemList" :key="item.id">
                    <item-horizontal-item v-if="item.title == ''" :item="item" />
                    <!-- <ps-route-link v-else :to="{ name: 'fe_item_detail', query: { item_id: item.id   }}"> -->
                        <item-horizontal-item v-else :item="item" :notShowTitle="notShowTitle" :storeName="storeName"/>
                    <!-- </ps-route-link> -->
                </splide-slide>
            </splide-track>
            <div :class="'splide__arrows splide__arrows--'+ languageStore.getLanguageDir() ">
                <button
                    class="w-10 h-10 p-2 rounded shadow-sm bg-feSecondary-50 dark:bg-feSecondary-800 arrow splide__arrow--prev"
                    type="button"
                    aria-label="Previous slide"
                    aria-controls="splide01-track"
                >
                    <ps-icon textColor="dark:text-feSecondary-200" name="arrowNarrowRight" h="23" w="23" viewBox="0 -3 9 20"/>
                </button>
                <button
                    class="w-10 h-10 p-2 rounded shadow-sm bg-feSecondary-50 dark:bg-feSecondary-800 arrow splide__arrow--next"
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
import '@splidejs/splide/dist/css/splide-core.min.css';
import ItemHorizontalItem from '@template1/vendor/components/modules/item/ItemHorizontalItem.vue';
import PsRouteLink from '@template1/vendor/components/core/link/PsRouteLink.vue';
import PsIcon from '@template1/vendor/components/core/icons/PsIcon.vue';
import ItemHorizontalSkeletorItem from "@template1/vendor/components/modules/item/ItemHorizontalSkeletorItem.vue";
import { useLanguageStore } from '../../../../../../../../../resources/js/store/Localization/LanguageStore';

    export default {
        name: 'ItemHorizontalSwiper',
        components: {
            Splide,
            SplideSlide,
            SplideTrack,
            ItemHorizontalItem,
            ItemHorizontalSkeletorItem,
            PsRouteLink,
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
            },
            isLoading: {
                type: Boolean,
                default: false
            },
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
                    },
                    1280:{
                        perPage:3,
                    },
                    1024:{
                        perPage:3,
                    },
                    768:{
                        perPage:2,
                    },
                    640:{
                        perPage:1
                    },
                    200:{
                        perPage:1
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


.splide{z-index: 0;}
.splide__arrow{
    -ms-flex-align:center;
    align-items:center;
    background:#ffffff;
    border:0;
    border-radius: 4px;
    cursor:pointer;
    display:-ms-flexbox;
    display:flex;
    height:2.5rem;
    -ms-flex-pack:center;
    justify-content:center;
    opacity:1;
    padding:.5rem;
    position:absolute;
    top:50%;
    transform:translateY(-50%);
    width:2.5rem;
    z-index:1;
    border: 0.5px solid #E5E7EB;
    box-shadow: 0px 1px 2px rgba(6, 25, 56, 0.05);
}

/* right to left */
.splide__arrows--rtl .splide__arrow--prev{left:auto;right:-1em}
.splide__arrows--rtl .splide__arrow--next{right:auto;left:-1em}

/* left to right */
.splide__arrows--ltr .splide__arrow--prev{right:auto;left:-1em}
.splide__arrows--ltr .splide__arrow--next{left:auto;right:-1em}

/* .splide__arrow svg{fill:#000;height:1.2em;width:1.2em}
.splide__arrow:hover:not(:disabled){opacity:1}
.splide__arrow:disabled{opacity:.3}*/

/* .splide__arrow--prev{left:-.5rem}
.splide__arrow--next{right:-.5rem} */

/* .splide__arrow--prev{
    position:absolute;
    top:50%;
    transform:translateY(-50%);
}
.splide__arrow--next{
    position:absolute;
    top:50%;
    transform:translateY(-50%);
} */
@media (max-width: 640px) {
    .arrow{
        top: 100%;
        transform: translateY(100%);
    }
    .splide__arrows--rtl .splide__arrow--prev{
        right: calc(50% - 32px);
        transform: translateX(50%);
    }
    .splide__arrows--rtl .splide__arrow--next{
        left: calc(50% - 32px);
        transform: translateX(-50%);
    }
    .splide__arrows--ltr .splide__arrow--prev{
        left: calc(50% - 32px);
        transform: translateX(-50%);
    }
    .splide__arrows--ltr .splide__arrow--next{
        right: calc(50% - 32px);
        transform: translateX(50%);
    }
}

/* @media (min-width: 786px) {
    .splide__arrow{
        top: 50%;
        transform: translateY(-50%);
    }
    .splide__arrow--prev{
        left: -1rem;
    }
    .splide__arrow--next{
        right: -1rem;
    }
} */

/* @media (min-width: 1024px) {
    .splide__arrow--prev{
        left: -1.5rem;
    }
    .splide__arrow--next{
        right: -1.5rem;
    }
} */
</style>
