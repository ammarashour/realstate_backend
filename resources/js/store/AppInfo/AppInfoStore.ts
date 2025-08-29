import { defineStore } from 'pinia';
import { ref } from 'vue';

export const useAppInfoStore = defineStore('CoreAppInfoStore', () => {
    const isProjectChanged = ref(false);
    const isNewVersionAvailable = ref(false);
    const isConnectedWithBuilder = ref(false);

    function checkNewVersionAvailable(appInfo, currentVersionInfos) {
        if(appInfo != null && appInfo != ''){

            const builderVersioncode = appInfo?.latestVersion?.version_code;
            const currentVersionUpdate = currentVersionInfos;
        
            if(currentVersionUpdate != null){
                const sourceCode = currentVersionUpdate.source_code_version_code == builderVersioncode ? true : false;
                const backendLanguage = currentVersionUpdate.backend_language_version_code == builderVersioncode ? true : false;
                const frontendLanguage = currentVersionUpdate.frontend_language_version_code == builderVersioncode ? true : false;
                const mobileLanguage = currentVersionUpdate.mobile_language_version_code == builderVersioncode ? true : false;
                const syncAble = appInfo.syncAble == 1 ? true : false;
                if(sourceCode && backendLanguage && frontendLanguage && mobileLanguage && !syncAble){
                    this.isNewVersionAvailable = false;
                }else{
                    this.isNewVersionAvailable = true;
                }
            }else{
                this.isNewVersionAvailable = true;
            }
        
        }
    }

    function checkProjectChanged(appInfo) {
        this.isProjectChanged = (appInfo != null && appInfo.isProjectChanged == 1) ? true : false;
    }

    function checkConnectionWithBuilder(appInfo) {
        this.isConnectedWithBuilder = (appInfo != null && appInfo.isConnectedWithBuilder == 0) ? true : false;
    }

    return {
        isProjectChanged,
        isNewVersionAvailable,
        isConnectedWithBuilder,
        checkNewVersionAvailable,
        checkProjectChanged,
        checkConnectionWithBuilder
    };
});


