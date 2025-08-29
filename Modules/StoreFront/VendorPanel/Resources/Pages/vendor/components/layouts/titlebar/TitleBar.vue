<template>
  <div
    :class="{ 'ps-0 xl:ps-76' : sideMenuStore.isFullSideMenu, 'ps-0 xl:ps-20' : !sideMenuStore.isFullSideMenu}"
    class="fixed z-50 flex items-center justify-between w-full py-2 transition-all bg-white shadow duration-600 dark:bg-secondary-800 dark:text-white pe-6"
  >
    <div class="flex flex-row font-extrabold text-secondary-800 dark:text-secondary-100 ps-6">
      <!-- Sidebar Toggle -->
      <button
        @click="sideMenuStore.setShowMenuFlag((!sideMenuStore.isFullSideMenu) ? false : sideMenuStore.showMenu);sideMenuStore.setFullSideMenuFlag(!sideMenuStore.isFullSideMenu);"
        class="hidden xl:block"
      >
        <!-- Menu Icons -->
        <ps-icon name="hamburger"/>
      </button>

      <button
      @click="
        sideMenuStore.setSideMenuOpenFlag(!sideMenuStore.isSideMenuOpen);
        sideMenuStore.setShowMenuFlag(!sideMenuStore.isFullSideMenu ? false : sideMenuStore.showMenu);
        "
        class="block xl:hidden"
      >
        <!-- Menu Icons -->
        <ps-icon name="hamburger"/>
      </button>
    </div>

    <div class="flex items-center text-secondary-800 ">
        <div class="ms-3 sm:ms-4 lg:ms-6 xxl:ms-8">
            <ps-dropdown align="left">
                <template #select>
                    <ps-dropdown-select padding="px-4 py-0.5"
                        :selectedValue="($page.props.languages).filter(language => language.symbol==activeLanguage)[0].name"
                        />
                </template>
                <template #list>
                    <div class="w-32 rounded-md shadow-xs">
                        <div class="z-30 pt-2 ">
                            <div v-for="language in ($page.props.languages)" :key="language.id"
                                class="flex items-center w-56 px-2 py-4 cursor-pointer hover:bg-primary-000 dark:hover:bg-primary-900"
                                @click="handleLanguage(language)">
                                <ps-label class="ms-2"
                                    :class="language.symbol==activeLanguage ? ' font-bold' : ''">
                                    {{ language.name }} </ps-label>
                            </div>
                        </div>
                    </div>
                </template>
            </ps-dropdown>
        </div>

        <!-- dark/light mode -->
        <ps-icon-toggle class="ms-3 sm:ms-4 lg:ms-6 xxl:ms-8" :selectedValue="themeStore.isDarkMode" @onChange="themeStore.toggleDarkMode" /> <!--toggleDarkMode-->

        <!-- for notification dropdown -->
        <!-- <ps-dropdown horizontalAlign="right" class='w-full' h="h-auto">
            <template #select>
                <div class="relative cursor-pointer">
                <div class="absolute w-3 h-3 ms-3 -top-1.5 bg-red-500 rounded-full text-xxs px-0.5 text-white"><span>1</span></div>
                <ps-icon name="bell" w="22" h="22" theme="#1F2937" class="ms-2"/>
            </div>
            </template>
            <template #list>
                <div class="rounded-md shadow-xs w-112">
                    <div class="z-30 ">
                        <div class="items-center w-full p-4 py-2">

                            <div class="flex justify-between">
                                <ps-label-header-6 textColor="text-secondary-800 dark:text-secondary-100">Notifications</ps-label-header-6>
                                <ps-label textColor="text-primary-500">Mark as all read</ps-label>
                            </div>
                            <ps-label textColor="text-primary-500 mt-2">Today</ps-label>

                        </div>

                        <div v-for="index in 3" :key="index">
                            <ps-activity theme="bg-indigo-100">
                                <template #content>
                                    <img class="inline-block w-8 h-8 mx-2 rounded-full ring-2 ring-white" src="https://images.unsplash.com/photo-1491528323818-fdd1faba62cc?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt=""/>
                                    <span class="w-full">
                                        <span class="flex justify-between text-sm ">
                                            <ps-label>Alisa</ps-label>
                                            <ps-label-caption-3>by:admin@gmial.com</ps-label-caption-3>
                                        </span>
                                        <ps-label-caption-3>Hello</ps-label-caption-3>
                                    </span>
                                </template>
                            </ps-activity>
                        </div>

                        <ps-text-button class="justify-center w-full m-3" textColor="text-primary-500" @click="toContact()">View All Notification</ps-text-button>
                    </div>
                </div>
            </template>
        </ps-dropdown> -->
        <!-- end notification -->

        <!-- for message dropdown -->

        <!-- end message -->

        <!-- for profile dropdown -->
        <ps-dropdown horizontalAlign="right" class='w-full ms-3 sm:ms-4 lg:ms-6 xxl:ms-8' h="h-auto">
            <template #select>
                <div class="w-8 h-8 rounded-full">
                <img v-if="$page.props.authUser?.user_cover_photo" class="object-cover w-8 h-8 rounded-full cursor-pointer"
                v-lazy=" { src: $page.props.uploadUrl + '/' + $page.props.authUser?.user_cover_photo, loading: $page.props.sysImageUrl+'/loading_gif.gif', error: $page.props.sysImageUrl+'/default_profile.png' }"
                 :alt="$t('core__be_profile')">
                <img v-else class="object-cover w-8 h-8 rounded-full cursor-pointer" :src="$page.props.uploadUrl + '/default_profile.png' "
                :alt="$t('core__be_profile')">
                </div>
            </template>
            <template #list>
                <div class="w-56 rounded-md shadow-xs ">
                    <div class="z-30 ">
                        <Link :href="route('fe_profile')"
                            class="flex items-center w-56 p-4 cursor-pointer hover:bg-primary-000 dark:hover:bg-primary-900">
                            <ps-icon name="user-line"  />
                            <ps-label class="ms-2">{{$t('core__be_profile')}}</ps-label>
                        </Link>
                        <Link :href="route('dashboard')"
                            class="flex items-center w-56 p-4 cursor-pointer hover:bg-primary-000 dark:hover:bg-primary-900">
                            <ps-icon name="refresh"  />
                            <ps-label class="ms-2">{{$t('core__be_switch_to_fe')}}</ps-label>
                        </Link>
                        <Link  v-if="$page.props.canAccessAdminPanel" :href="route('admin.index')"
                            class="flex items-center w-56 p-4 cursor-pointer hover:bg-primary-000 dark:hover:bg-primary-900">
                            <ps-icon name="refresh"  />
                            <ps-label class="ms-2">{{$t('core__be_switch_to_admin')}}</ps-label>
                        </Link>
                        <form @submit.prevent="logout">
                            <button type="submit" class="flex items-center w-56 p-4 cursor-pointer hover:bg-primary-000 dark:hover:bg-primary-900">
                                <ps-icon name="signOut" />
                                <ps-label class="ms-2">{{$t('core__be_logout')}}</ps-label>
                            </button>
                        </form>
                    </div>
                </div>
            </template>
        </ps-dropdown>
        <!-- end profile -->

    </div>
    <ps-danger-dialog ref="ps_danger_dialog" />
    <ps-warning-dialog ref="ps_warning_dialog" />
  </div>
</template>
<script>
import { reactive, ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import PsIcon from "@/Components/Core/Icons/PsIcon.vue";
import PsDropdown from "@/Components/Core/Dropdown/PsDropdown.vue";
import PsDropdownSelect from "@/Components/Core/Dropdown/PsDropdownSelect.vue";
import PsLabel from "@/Components/Core/Label/PsLabel.vue";
import PsLabelCaption3 from "@/Components/Core/Label/PsLabelCaption3.vue";
import PsLabelHeader6 from "@/Components/Core/Label/PsLabelHeader6.vue";
import PsToggle from '@/Components/Core/Toggle/PsToggle.vue';
import PsButton from "@/Components/Core/Buttons/PsButton.vue";
import PsIconToggle from '@/Components/Core/Toggle/PsIconToggle.vue';
import PsTextButton from '@/Components/Core/Buttons/PsTextButton.vue';
import { trans, loadLanguageAsync } from 'laravel-vue-i18n';
import PsDangerDialog from "@/Components/Core/Dialog/PsDangerDialog.vue";
import PsWarningDialog from "@/Components/Core/Dialog/PsWarningDialog.vue";
import { router } from '@inertiajs/vue3';
import firebaseApp from 'firebase/app';
import "firebase/auth";
import { useThemeStore } from '../../../../../../../../../resources/js/store/Utilities/ThemeStore';
import { useLanguageStore } from '../../../../../../../../../resources/js/store/Localization/LanguageStore';
import { useSideMenuStore } from '../../../../../../../../../resources/js/store/Menu/SideMenuStore';

export default {
    components: {
        PsIcon,
        PsDropdown,
        PsDropdownSelect,
        PsLabel,
        Link,
        PsToggle,
        PsButton,
        PsIconToggle,
        PsLabelHeader6,
        PsTextButton,
        PsLabelCaption3,
        PsDangerDialog,
        PsWarningDialog
    },
    props: ['can','defaultProfileImg'],
  data() {
    return {
      show: false,
      selectedLanguage: ''
    };
  },
  setup (){
    // console.log(JSON.stringify(firebaseApp.auth()))
    const contacts = reactive({data : {}});

    const loading = ref(false);
    const ps_danger_dialog = ref();
    const count = ref();
    const ps_warning_dialog = ref();
    const sideMenuStore = useSideMenuStore();
    const themeStore = useThemeStore();
    const languageStore = useLanguageStore();
    const activeLanguage = ref(localStorage.activeLanguage);

    async function clickMessageButton(){
        if(contacts.data != null && contacts.data.length > 0){
            await loadContact();
        }else{
            loading.value = true;
            await loadContact();
            loading.value = false;
        }
    }

    async function loadContact(){
        await axios.get(route('contact.getContactFormTitle'))
            .then(res => {
                contacts.data = res.data.contacts;
                count.value = res.data.unseenCount;
            })
            .catch(error => {
                    // psmodal.value.toggle(true);
                });
    }

    function clickedDeleteContact(id){
        ps_danger_dialog.value.openModal(
                trans('core__delete'),
                trans('core__comfirm_to_delete_contact'),
                trans('core__be_btn_confirm'),
                trans('core__be_btn_cancel'),
                () => {
                    router.delete(route("contact.destroy", id),{
                        onSuccess: () => {
                            loadContact();
                        },
                        onError: () => {

                        },
                        });
                },
                () => { }
            );
    }

    function makeAllRead(){
        ps_warning_dialog.value.openModal(
                trans('core__warning'),
                trans('core__comfirm_to_mark_as_read'),
                trans('core__be_btn_confirm'),
                trans('core__be_btn_cancel'),
                () => {
                    router.put(route("contact.allasread"),{
                        onSuccess: () => {
                            loadContact();
                        },
                        onError: () => {

                        },
                        });
                },
                () => { }
            );
    }

    function goToDetail(id){
         router.get(route("contact.edit",id));
    }

    function handleLanguage(row){
        router.put(route('language.changeLanguage',row.symbol), '', {
            onSuccess: () => {
                loadLanguageAsync(row.symbol);
                languageStore.setActiveLanguage(row.symbol);
                document.cookie = `activeLanguage=${row.symbol}; max-age=31536000; path=/`;

            }
        });
    }

    return {
        contacts,
        clickMessageButton,
        clickedDeleteContact,
        loading,
        ps_danger_dialog,
        makeAllRead,
        goToDetail,
        count,
        ps_warning_dialog,
        handleLanguage,
        activeLanguage,
        themeStore,
        sideMenuStore
    }
  },
  computed: {
    logout() {
        firebaseApp.auth().signOut();
        this.$inertia.post(route('logout'));
    },
    toContact() {
        this.$inertia.get(route('contact.index'));
    },

  },
};
</script>
