import { defineStore } from 'pinia';
import { ref } from 'vue';

export const useSideMenuStore = defineStore('CoreSideMenuStore', () => {

    // isFullSideMenu : boolean
    // It will indicate side menu is full mode or partial mode
    const isFullSideMenu = ref(true);

    // isSideMenuOpen : boolean
    // It will indicate side menu is open or collapse
    const isSideMenuOpen = ref(false);

    // showMenu : boolean
    // It will indicate the hamberger menu need to be visible or not
    const showMenu = ref(false);

    // sidebarActive : string
    // It will store the current active menu name ( not translated one )
    const sidebarActive = ref('dashboard');

    // isSidebarActiveMenuFound : boolean
    // It will indicated that newly updated sidebar menu is selected in UI
    // This will stop all the looping to find the menu to select.
    const isSidebarActiveMenuFound = ref(false);

    function setFullSideMenuFlag(value) {
        this.isFullSideMenu = value;
    }
    function setSideMenuOpenFlag(value) {
        this.isSideMenuOpen = value;
    }
    function setShowMenuFlag(value) {
        this.showMenu = value;
    }
    function setSidebarActive(value) {
        this.sidebarActive = value;
        this.isSidebarActiveMenuFound = false;
    }
    function selectActiveMenu(currentRoute, menugroup) {

        if (currentRoute == 'admin') {
            const dashboardName = 'core__be_dashboard_label';
            localStorage.sidebarScroll = 0;
            this.sidebarActive = dashboardName;

        } else if(currentRoute == 'vendor_info') {
            const dashboardName = 'core__vendor_my_store_module';
            localStorage.sidebarScroll = 0;
            this.sidebarActive = dashboardName;
        }else {
            outerLoop: for (let i = 0; i < menugroup.length; i++) {
                for (let j = 0; j < menugroup[i].sub_menu_group.length; j++) {
                    if (menugroup[i].sub_menu_group[j].module.length > 0) {
                        for (let k = 0; k < menugroup[i].sub_menu_group[j].module.length; k++) {
                            if (menugroup[i].sub_menu_group[j].module[k].module_name == currentRoute) {
                                const moduleLangName = menugroup[i].sub_menu_group[j].module[k].module_lang_key;
                                this.sidebarActive = moduleLangName;
                                break outerLoop;
                            }
                        }
                    } else {
                        if (menugroup[i].sub_menu_group[j].sub_menu_name == currentRoute) {
                            const moduleLangName = menugroup[i].sub_menu_group[j].sub_menu_lang_key;
                            this.sidebarActive = moduleLangName;
                        }
                    }

                }
            }
        }
    }

    function findActiveMenu(group) {
        let subMenuLangKey = "";
        if(!this.isSidebarActiveMenuFound) {
            if (group && group.module && group.module != '') {
                for (let i = 0; i < group.module.length; i++) {

                    if (group.module[i].module_lang_key == this.sidebarActive) {
                        subMenuLangKey = group.sub_menu_lang_key;
                        this.isSidebarActiveMenuFound = true;
                        break;
                    }
                }
            }
        }

        return subMenuLangKey;
    }

    return {
        isFullSideMenu,
        isSideMenuOpen,
        showMenu,
        sidebarActive,
        isSidebarActiveMenuFound,
        setSideMenuOpenFlag,
        setFullSideMenuFlag,
        setShowMenuFlag,
        setSidebarActive,
        selectActiveMenu,
        findActiveMenu
    };
});
