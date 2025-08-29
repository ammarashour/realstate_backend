import { defineStore } from 'pinia';
import firebase from "firebase/app";
import firebaseApp from 'firebase/app';
import { ref } from 'vue';
import axios from 'axios';
import 'firebase/messaging';
import 'firebase/database';
import NotiRegisterHolder from '@templateCore/object/holder/NotiRegisterHolder';
import NotiUnRegisterHolder from '@templateCore/object/holder/NotiUnRegisterHolder';
import PsConst from '@templateCore/object/constant/ps_constants';

export const useNotificationStore = defineStore('CoreNotificationStore', () => {

    let isSupportMessaging = ref(false);
    const regiterHolder = new NotiRegisterHolder();
    const unRegiterHolder = new NotiUnRegisterHolder();

    function initFirebase(firebaseConfig) {
        const firebaseConfiguration = JSON.parse(firebaseConfig);

        if (firebase.apps.length < 1) {
            firebase.initializeApp(firebaseConfiguration);
        }
    }

    function requestPermission() {
        this.isSupportMessaging = firebase.messaging.isSupported() ? firebase.messaging() : null;
        if (this.isSupportMessaging) {
            Notification.requestPermission().then((permission) => {
                if (permission === 'granted') {
                    console.log('****Notification permission granted.');
                } else {
                    console.log('****Unable to get permission to notify.');
                }
            });
        }
        return this.isSupportMessaging;
    }

    async function subscribeTokenToTopic(route, token, topic) {
        if (this.isSupportMessaging) {
            await axios.post(route, {
                token: token,
                topic: topic
            })
                .then(function (response) {
                    console.log("Subscribed to " + topic + ".Status Code is " + response.status);
                })
                .catch(function (error) {
                    console.log(error);
                });
        }
    }

    function isUserPresence(currentRoute) {
        // Checking User Presence
        if (localStorage.loginUserId && localStorage.loginUserId != '' && localStorage.loginUserId != null && localStorage.loginUserId != undefined && localStorage.loginUserId != PsConst.NO_LOGIN_USER) {
            if (firebase.apps.length >= 1) {
                const userRef = firebaseApp.database().ref('User_Presence');
                if (currentRoute == 'fe_chat') {
                    const chat_user_presence = {
                        userId: localStorage.loginUserId,
                        userName: 'Tester'
                    };
                    userRef.child(localStorage.loginUserId).set(chat_user_presence);
                    // console.log('online');
                } else {
                    userRef.child(localStorage.loginUserId).remove();
                    // console.log('offline');
                }
            }

        }
    }

    function initMessageServieWorker(appUrl, webPushKey, psValueStore, route, appInfoStore, notiStore, loginUserId) {
        if ("serviceWorker" in navigator) {

            if (this.isSupportMessaging) {

                let url = appUrl + "/firebase-messaging-sw.js";
                // console.log(appUrl);
                // console.log(appUrl.endsWith("/"));

                if (appUrl != null
                    && String(appUrl).endsWith("/")) {
                    url = appUrl + "firebase-messaging-sw.js";
                }
                navigator.serviceWorker.getRegistrations().then((registrations) => {
                    //remove previously cached registration
                    registrations.forEach((registration) => {
                        registration.unregister();
                    });

                    navigator.serviceWorker.register(url)
                        .then((registration) => {
                            this.isSupportMessaging.getToken({ vapidKey: webPushKey, serviceWorkerRegistration: registration })
                                .then(async (currentToken) => {
                                    if (currentToken) {
                                        // console.log('current token for client: ', currentToken);
                                        localStorage.deviceToken = currentToken;
                                        psValueStore.replacedeviceToken(localStorage.deviceToken);
                                        this.subscribeTokenToTopic(route, currentToken, 'fe_broadcast');

                                        // await appInfoStore.loadAppInfo();
                                        psValueStore.loadData();

                                        if (localStorage.getItem("showProfile") == null || localStorage.showProfile == '') {
                                            if (appInfoStore.appInfo.data.mobileSetting.is_show_owner_info == '1') {
                                                localStorage.showProfile = 'show';

                                            } else {
                                                localStorage.showProfile = 'hide';
                                            }

                                        }
                                        if (localStorage.getItem("notiSetting") == null || localStorage.notiSetting == '') {
                                            resetNotiSetting(appInfoStore, psValueStore, notiStore, loginUserId);
                                        } else if (localStorage.getItem("notiSetting") == "true") {
                                            regiterHolder.platformName = PsConst.PLATFORM;
                                            regiterHolder.deviceId = psValueStore.deviceToken;
                                            regiterHolder.loginUserId = loginUserId.value;
                                            notiStore.registerNotiToken(regiterHolder);
                                        } else {
                                            unRegiterHolder.platformName = PsConst.PLATFORM;
                                            unRegiterHolder.deviceId = psValueStore.deviceToken;
                                            unRegiterHolder.userId = loginUserId.value;
                                            notiStore.unRegisterNotiToken(unRegiterHolder);
                                        }
                                        psValueStore.replaceshowProfile(localStorage.showProfile);
                                        psValueStore.replaceNotiSetting(localStorage.notiSetting);

                                        

                                    }
                                }).catch((err) => {
                                    console.log('An error occurred while retrieving token. ', err);
                                    checkDeviceToken();
                                    // catch error while creating client token
                                });
                        }).catch(function (err) {
                            console.log("Service worker registration failed, error:", err);
                            checkDeviceToken();
                        });
                })
            }
        } else {
            console.log('no serviceWorker in navigator');
            checkDeviceToken();
        }
    }

    function checkDeviceToken() {
        if(localStorage.deviceToken == null || localStorage.deviceToken == '') {
            localStorage.deviceToken = "errorToken";
        }

    }

    function resetNotiSetting(appInfoStore, psValueStore, notiStore, loginUserId) {
        if (appInfoStore.appInfo.data.frontendConfigSetting.enableNotification == '1') {
            localStorage.notiSetting = 'true';
            regiterHolder.platformName = PsConst.PLATFORM;
            regiterHolder.deviceId = psValueStore.deviceToken;
            regiterHolder.loginUserId = loginUserId.value;
            notiStore.registerNotiToken(regiterHolder);
        } else {
            localStorage.notiSetting = 'hide';
            unRegiterHolder.platformName = PsConst.PLATFORM;
            unRegiterHolder.deviceId = psValueStore.deviceToken;
            unRegiterHolder.userId = loginUserId.value;
            notiStore.unRegisterNotiToken(unRegiterHolder);
        }
    }

    return {
        initFirebase,
        requestPermission,
        subscribeTokenToTopic,
        isUserPresence,
        initMessageServieWorker
    };
});
