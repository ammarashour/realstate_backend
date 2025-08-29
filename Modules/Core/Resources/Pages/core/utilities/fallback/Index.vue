<template>
    <div>
        Redirecting...
    </div>
</template>

<script setup>
import { onMounted } from 'vue';

const props = defineProps({
    appRedirect: String,
    iosPackageId: String,
    appPackageId: String,
    webRedirect: String
})

function redirectToApp() {
    const userAgent = navigator.userAgent || navigator.vendor;
    const isAndroid = /android/i.test(userAgent);
    const isIOS = /iPhone|iPad|iPod/i.test(userAgent);

    if (isAndroid || isIOS) {
        const startTime = Date.now();
        let hidden = false;

        document.addEventListener("visibilitychange", () => {
            if (document.hidden) hidden = true;
        });

        window.location.href = props.appRedirect;

        setTimeout(() => {
            const elapsed = Date.now() - startTime;
            if (!hidden && elapsed > 2000) {
                if (isAndroid) {
                    window.location.href = `https://play.google.com/store/apps/details?id=${props.appPackageId}`;
                } else {
                    window.location.href = `itms-apps://apps.apple.com/us/app/id${props.iosPackageId}`;
                }
            }
        }, 2000);
    } else {
        window.location.href = props.webRedirect;
    }
}

onMounted(() => {
    redirectToApp();
});
</script>
