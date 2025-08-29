<!-- <template>
    <div class="flex items-center px-5 py-3 border-b">

        <ps-nav-menu 
            name="dashboard" 
            title="Dashboard"
              />

        <ps-label-caption class='ms-4'> Profile </ps-label-caption>

        <ps-label-caption class='ms-4'> Messages </ps-label-caption>

        <ps-label-caption class='ms-4'> Blog </ps-label-caption>

        <ps-label-caption class='ms-4'> Notification </ps-label-caption>

        <ps-label-caption class='ms-4'> Contact Us </ps-label-caption>

        <ps-label class='ms-4'> Setting </ps-label>

    </div>
</template>

<script lang="ts">
import PsLabel from '@template1/vendor/components/core/label/PsLabel.vue';
import PsNavMenu from '@template1/vendor/components/core/navmenu/PsNavMenu.vue';
// import $ from "cash-dom";
import { ref } from 'vue';
import PsLabelCaption from '@template1/vendor/components/core/label/PsLabelCaption.vue';

export default {
    components : {
        PsLabel,        
        PsNavMenu,
        PsLabelCaption
    },
    setup() {

        function aboutUsParmas() {
            return { userId : 2222};
        }

        const isDarkMode = ref(true);
        loadIsDarkMode();
        function loadIsDarkMode() {
            if(localStorage.isDarkMode != null && localStorage.isDarkMode == 'true') {
                $("html").addClass("dark");
                isDarkMode.value = true;
            }else {
                $("html").removeClass("dark");
                isDarkMode.value = false;
            }
        }

        function toggleDarkMode() {
            
            if(localStorage.isDarkMode != null && localStorage.isDarkMode == 'true') {
                localStorage.isDarkMode = 'false';
                isDarkMode.value = false;
            }else {
                localStorage.isDarkMode = 'true';
                isDarkMode.value = true;
            }
            loadIsDarkMode();
        }

        return {
            aboutUsParmas,
            isDarkMode,
            toggleDarkMode
        }

    }
}
</script> -->