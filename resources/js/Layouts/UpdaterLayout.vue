<template>
    <div class="master">
        <div class="box">
            <div class="header">
                <h1 class="header__title">
                    {{ $t(title) }}
                </h1>
            </div>
            <ul class="step">
                <li class="step__divider"></li>
                <li class="step__item"  :class="isActive($page.props.currentRouteName, 'NextLaravelUpdater::builderTableField')">
                    <div class="step__icon">
                        <ps-icon h="15" w="15" name="data_table" class="font-semibold" />
                    </div>
                </li>

                <li class="step__divider"></li>
                <li class="step__item"  :class="isActive($page.props.currentRouteName, 'NextLaravelUpdater::addNewVendorLangString')">
                    <div class="step__icon">
                        <ps-icon h="15" w="15" name="translate" class="font-semibold" />
                    </div>
                </li>

                <li class="step__divider"></li>
                <li class="step__item"  :class="isActive($page.props.currentRouteName, 'NextLaravelUpdater::addNewMobileLangString')">
                    <div class="step__icon">
                        <ps-icon h="15" w="15" name="translate" class="font-semibold" />
                    </div>
                </li>

                <li class="step__divider"></li>
                <li class="step__item"  :class="isActive($page.props.currentRouteName, 'NextLaravelUpdater::addNewFeLangString')">
                    <div class="step__icon">
                        <ps-icon h="15" w="15" name="translate" class="font-semibold" />
                    </div>
                </li>

                <!-- <li class="step__divider"></li>
                <li class="step__item"  :class="isActive($page.props.currentRouteName, 'NextLaravelUpdater::addNewLangString')">
                    <div class="step__icon">
                        <ps-icon h="15" w="15" name="translate" class="font-semibold" />
                    </div>
                </li> -->

                <li class="step__divider"></li>
                <li class="step__item"  :class="isActive($page.props.currentRouteName, 'NextLaravelUpdater::addNewLangString')">
                    <div class="step__icon">
                        <ps-icon h="15" w="15" name="translate" class="font-semibold" />
                    </div>
                </li>

                <li class="step__divider"></li>
                <li class="step__item"  :class="isActive($page.props.currentRouteName, 'NextLaravelUpdater::sourceCode')" >
                    <div class="step__icon">
                        <ps-icon h="15" w="15" name="code-blocks" class="font-semibold" />
                    </div>
                </li>

                <li class="step__divider"></li>
                <li class="step__item" :class="isActive($page.props.currentRouteName, 'NextLaravelUpdater::welcome')">
                    <!-- @if(!Request::is('update'))
                        <a href="{{ route('NextLaravelUpdater::welcome') }}">
                            <i class="step__icon fa fa-refresh" aria-hidden="true"></i>
                        </a>
                    @else -->
                    <div class="step__icon">
                        <ps-icon h="15" w="15" name="satified" class="font-semibold" />
                    </div>
                    <!-- @endif -->
                </li>
                <li class="step__divider"></li>
            </ul>
            <div class="main">
                <p v-if="$page.props.message" class="alert alert-success text-center">
                    <strong>
                            {{ $t($page.props.message['message']) }}
                    </strong>
                    <!-- <strong>
                            {{ $page.props.message }}
                    </strong> -->
                </p>
                <slot name="container"></slot>
            </div>
        </div>
    </div>
</template>


<script>
    import { defineComponent, computed, onMounted, onUnmounted, ref } from "vue";
    import PsButton from "@/Components/Core/Buttons/PsButton.vue";
    import PsLoadingCircleDialog from '@/Components/Core/Dialog/PsLoadingCircleDialog.vue';
    import PsIcon from "@/Components/Core/Icons/PsIcon.vue";
    import { trans } from 'laravel-vue-i18n';
    import { router } from '@inertiajs/vue3';

    export default defineComponent({
        components: {
            PsIcon,
            PsButton,
            PsLoadingCircleDialog,
        },
        props: ['title', 'message'],
        setup(props) {

            function isActive(currentRouteName, routeName){
                if(currentRouteName == routeName){
                    let className = "active";
                    return className;
                }
            }

            onMounted(() => {
                var loading = document. getElementById("home_loading__container");
                loading.style.display = "none";
            });

            return {
                isActive
            }
        }
    });
</script>

<style>
    @import '@public/installer/css/next-style.css';
</style>
