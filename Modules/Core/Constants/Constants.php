<?php

namespace Modules\Core\Constants;

class Constants
{
    const dataReset = 47;
    const dataTableDefaultRow = 10;

    const dashboardDataTableDefaultRow = 5;

    // Image Path
    const folderPath = 'PSX_MPC';

    const storageOriginalPath = '/storage/'.Constants::folderPath.'/uploads/';

    const storageThumb1xPath = '/storage/'.Constants::folderPath.'/thumbnail/';

    const storageThumb2xPath = '/storage/'.Constants::folderPath.'/thumbnail2x/';

    const storageThumb3xPath = '/storage/'.Constants::folderPath.'/thumbnail3x/';

    // For module (not existed model)
    const slowMovingItemModule = 4;

    const pendingModule = 15;

    const blueMarkUserModule = 16;

    const disableModule = 17;

    const rejectModule = 18;

    const buyerReportModule = 22;

    const sellerReportModule = 23;

    const dailyActiveUserReportModule = 25;

    const successfulDealCountReportModule = 26;

    const soldOutItemReportModule = 27;

    const itemReportModule = 28;

    const userReportModule = 24;

    const slowMovingItemReportModule = 69;

    const landingPageModule = 71;

    const bannedUserModule = 35;

    const paymentSettingModule = 56;

    const offlinePaymentSettingModule = 57;

    const promotionInAppPurchaseModule = 58;

    const packageInAppPurchaseModule = 59;

    const packageReportModule = 32;

    const privacyPolicyModule = 51;

    const dataDeletionPolicyModule = 52;

    // For module (existed model)
    const itemModule = 1;

    const currencyModule = 3;

    const categoryModule = 5;

    const categoryReportModule = 30;

    const tableModule = 62;

    const tableFieldModule = 64;  //64

    const customizeUiDetailModule = 65;

    const subCategoryModule = 6;

    const coreKeyTypeModule = 13;

    const paymentModule = 14;

    const userModule = 34;

    const userRoleModule = 36;

    const languageModule = 48;

    const languageStringModule = 67; //67

    const mobileSettingModule = 39;

    const complaintItemReportModule = 31;

    const offlinePaidItemModule = 19;

    const offlinePackageBoughtModule = 20;

    const apiTokenModule = 53;

    const promotionReportModule = 29;

    // core key
    const availableCurrency = 'ava-cur';

    const availableCurrencyModule = 63;

    const blogModule = 60;

    const aboutModule = 50;

    const backendSettingModule = 38;

    const phoneCountryCodeModule = 66;

    const coreMenuModule = 42;

    const coreSubMenuModule = 43;

    const systemConfigModule = 41;

    const CoreModule = 44;

    const DeepLinkGenerateModule = 45;

    const moduleModule = 61;

    const locationCityModule = 7;

    const locationTownshipModule = 8;

    const contactModule = 33;

    const pushNotificationModule = 46;

    const mobileLanguageModule = 49;

    const mobileLanguageStringModule = 68; //68

    const feLanguageModule = 72;

    const feLanguageStringModule = 73; // 73

    const vendorLanguageStringModule = 76; // 76

    const vendorMenuGroupModule = 77;

    const vendorSubMenuModule = 78;

    const vendorMenuModule = 79;

    const vendorModuleModule = 80;

    const vendorRoleModule = 81;

    const vendorModule = 82;

    const pendingVendorModule = 83;

    const rejectVendorModule = 85;

    const vendorSubscriptionPlanModule = 86;

    const vendorSubscriptionReportModule = 87;

    const downloadDbModule = 88;

    //core menu
    const vendorLanguageMenu = 70;

    const vendorStoreModule = 'ps-0000000001';

    const vendorItemModule = 'ps-0000000002';

    const vendorSubscriptionHistoryModule = 'ps-0000000003';

    const vendorSubscriptionUpdateModule = 'ps-0000000004';

    const vendorPaymentListModule = 'ps-0000000005';

    const vendorPaymentSettingModule = 'ps-0000000006';

    const vendorPaymentStatusModule = 'ps-0000000007';

    const vendorOrderStatusModule = 'ps-0000000008';

    const vendorOrderListModule = 'ps-0000000009';

    const orderTransactionReportModule = 'ps-0000000010';

    const vendorCurrencyModule = 'ps-0000000011';

    const vendorDeliverySettingModule = 'ps-0000000012';

    // core key
    const item = 'itm';

    const user = 'usr';

    const payment = 'pmt';

    const psPayment = 'ps-pmt';

    const category = 'ctg';

    const categoryLanguage = 'ctg-lang';

    const subCategoryLanguage = 'sub-ctg-lang';

    const tableField = 'tbl-field';

    const subcategory = 'sub-cat';

    const mobileLanguageString = 'mb-lang-stg';

    const mobileLanguage = 'mb-lang';

    const locationTownship = 'loc-tsp';

    const locationCity = 'loc';

    const blog = 'blog';

    const tag = 'tag';

    const backendSetting = 'be-stg';

    const contact = 'contact';

    const currency = 'itm-cur';

    const coreModule = 'core-mde';

    const module = 'mdl';

    const coreMenuGroup = 'menu-gp';

    const coreSubMenuGroup = 'sub-menu-gp';

    const customizeUiDetail = 'cus-ui-dtl';

    const coreKeyType = 'core-key-type';

    const dataDeletion = 'core-data-del';

    const frontendSetting = 'fe-stg';

    const itemReport = 'itm-rpt';

    const language = 'lang';

    const languageString = 'lang-str';

    const mobileSetting = 'mb-stg';

    const privacyPolicy = 'prv-pcy';

    const landingPage = 'land-pg';

    const pushNotificationMessage = 'push-noti-msg';

    const paidItem = 'paid-itm';

    const role = 'role';

    const about = 'abt';

    const phoneCountryCode = 'phone';

    const rating = 'rate';

    const systemConfig = 'sys-config';

    const userPermission = 'usr-psm';

    const uiType = 'uit';

    const apiToken = 'api-tkn';

    const color = 'color';

    const customPage = 'cpg';

    const vendor = 'ven';

    const vendorBranches = 'ven-branch';

    const feLanguageString = 'ps-fe-lang-str';

    const vendorLanguageString = 'ven-lang-str';

    const vendorMenu = 'ven-menu';

    const vendorMenuGroup = 'ven-menu-group';

    const vendorSubMenuGroup = 'ven-sub-menu';

    const vendorRole = 'ven-role';

    const themeScreen = 'theme-screen';

    const vendorModuleKey = 'ven-module';

    // for payment code (used for id genetation)
    const paymentTableCode = 'payment';

    const middleCoreKeyCode = '0000';

    // for base dir for view folder path
    const parentPath = 'core/product/';

    // flag
    const success = 'success';

    const danger = 'danger';

    const warning = 'warning';

    // for permission
    const viewAnyAbility = 'viewAny';

    const createAbility = 'create';

    const editAbility = 'update';

    const deleteAbility = 'delete';

    // For image path in storage
    const imgPath = 'storage/';

    const originPath = 'storage/uploads/';

    const thumbnail1xPath = 'storage/thumbnail/';

    const thumbnail2xPath = 'storage/thumbnail2x/';

    const thumbnail3xPath = 'storage/thumbnail3x/';

    //    // For image path in public
    //    const uploadPathForDel = 'public/uploads/';
    //    const thumb1xPathForDel = 'public/thumbnail/';
    //    const thumb2xPathForDel = 'public/thumbnail2x/';
    //    const thumb3xPathForDel = 'public/thumbnail3x/';

    // For image path under root public
    const uploadPathForDel = 'storage/uploads/';

    const thumb1xPathForDel = 'storage/thumbnail/';

    const thumb2xPathForDel = 'storage/thumbnail2x/';

    const thumb3xPathForDel = 'storage/thumbnail3x/';

    // for csv file
    const csvFile = 'csvFile';

    const show = 1;

    const hide = 0;

    const delete = 1;

    const unDelete = 0;

    const enable = 1;

    const disable = 0;

    const publish = 1;

    const unPublish = 0;

    const default = 1;

    const unDefault = 0;

    const Ban = 1;

    const unBan = 0;

    const available = 1;

    const unAvailable = 0;

    const ascending = 'asc';

    const descending = 'desc';

    const isSoldOut = 1;

    const yes = 1;

    const no = 0;

    // for item status
    const disableItem = 2;

    const pendingItem = 0;

    const rejectItem = 3;

    const publishItem = 1;

    const unpublishItem = 4;

    // for user role
    const superAdminRoleId = 1;

    const normalUserRoleId = 2;

    // for user status
    const noLoginUser = 'nologinuser';

    const activeUser = 'active';

    const banned = 'banned';

    const deleted = 'deleted';

    const unpublished = 'unpublished';

    // for user account status
    const publishUser = 1;

    const pendingUser = 2;

    // for dashboard filter (in dropdown)
    const today = 'Today';

    const yesterday = 'Yesterday';

    const last7Days = 'Last 7 days';

    const last14Days = 'Last 14 days';

    const last30Days = 'Last 30 days';

    const last60Days = 'Last 60 days';

    const custom = 'Custom';

    // for api status code
    const internalServerErrorStatusCode = 500;

    const okStatusCode = 200;

    const createdStatusCode = 201;

    const noContentStatusCode = 204;

    const notFoundStatusCode = 404;

    const badRequestStatusCode = 400;

    const forbiddenStatusCode = 403;

    const goneStatusCode = 410;

    // for api status
    const successStatus = 'success';

    const errorStatus = 'error';

    // for ui core_keys_id
    const dropDownUi = 'uit00001';

    const textUi = 'uit00002';

    const radioUi = 'uit00003';

    const checkBoxUi = 'uit00004';

    const dateTimeUi = 'uit00005';

    const textAreaUi = 'uit00006';

    const numberUi = 'uit00007';

    const multiSelectUi = 'uit00008';

    const imageUi = 'uit00009';

    const timeOnlyUi = 'uit00010';

    const dateOnlyUi = 'uit00011';

    // for payment core_keys_id
    const paypalPaymentId = 'payment00001';

    const stripePaymentId = 'payment00002';

    const razorPaymentId = 'payment00003';

    const paystackPaymentId = 'payment00004';

    const offlinePaymentId = 'payment00005';

    const promotionInAppPurchasePaymentId = 'payment00006';

    const packageInAppPurchasePaymentId = 'payment00007';

    const vendorSubscriptionPlanPaymentId = 'payment00008';

    const codPaymentId = 'payment00009';

    const flutterwavePaymentId = 'payment00010';

    // for payment custom field core_keys_id
    const paypalMerchantId = 'pmt00001';

    const paypalPublicKey = 'pmt00002';

    const paypalPrivateKey = 'pmt00003';

    const paypalClientId = 'pmt00004';

    const paypalSecretKey = 'pmt00005';

    const paypalEnvironment = 'pmt00006';

    const stripePublishableKey = 'pmt00012';

    const stripeSecretKey = 'pmt00013';

    const razorKey = 'pmt00014';

    const paystackKey = 'pmt00015';

    const flutterwavePublicKey = 'ps-pmt00044';

    const flutterwaveSecretKey = 'ps-pmt00045';

    const flutterwaveEncryptionKey = 'ps-pmt00046';

    // for user custom field core_keys_id
    const usrCity = 'ps-usr00001';

    const usrIsVerifyBlueMark = 'ps-usr00002';

    const usrBlueMarkNote = 'ps-usr00003';

    const usrRemainingPost = 'ps-usr00004';

    const usrFollowerCount = 'ps-usr00005';

    const usrFollowingCount = 'ps-usr00006';

    // for item custom field core_keys_id type
    const itmItemType = 'ps-itm00002';

    const itmPurchasedOption = 'ps-itm00003'; // Item price

    const itmConditionOfItem = 'ps-itm00004';

    const itmDealOption = 'ps-itm00007';

    const itemQty = 'ps-itm00046';

    // for iap types
    const iapTypeAndroid = 'Android';

    const iapTypeIOS = 'IOS';

    // vendor sp expired date notic day
    const vendorExpiredNoticDateInDays = 7;

    // for vendor sp durations
    const vendorSpOneYear = 'One Year';

    const vendorSpSixMonths = 'Six Months';

    const vendorSpOneMonth = 'One Month';

    // for payment attribute col name for promotion
    const pmtAttrPromoteIapDayCol = 'days';

    const pmtAttrPromoteIapTypeCol = 'type'; // Android or IOS

    const pmtAttrPromoteIapStatusCol = 'status'; // 1 or 0

    // for payment attribute col name for package
    const pmtAttrPackageIapTypeCol = 'type'; // Android or IOS

    const pmtAttrPackageIapPriceCol = 'price';

    const pmtAttrPackageIapCountCol = 'count';

    const pmtAttrPackageIapStatusCol = 'status'; // 1 or 0

    const pmtAttrPackageIapCurrencyCol = 'currency_id'; // id from available_currencies table

    // for payment attribute col name for vendor subscription plan
    const pmtAttrVendorSpDurationCol = 'duration';

    const pmtAttrVendorSpSalePriceCol = 'sale_price';

    const pmtAttrVendorSpDiscountPriceCol = 'discount_price';

    const pmtAttrVendorSpStatusCol = 'status'; // 1 or 0

    const pmtAttrVendorSpIsMostPopularPlanCol = 'is_most_popular_plan'; // 1 or 0

    const pmtAttrVendorSpCurrencyCol = 'currency_id'; // id from available_currencies table

    // for payment attribute col name for offline payment
    const pmtAttrOfflinePaymentStatusCol = 'status'; // 1 or 0

    // for payment method
    const offlinePaymentMethod = 'offline';

    const paystackPaymentMethod = 'paystack';

    const razorPaymentMethod = 'razor';

    const paypalPaymentMethod = 'paypal';

    const stripePaymentMethod = 'stripe';

    const iapPaymentMethod = 'In_App_Purchase';

    const codPaymentMethod = 'Cash_On_Delivery';

    const flutterwavePaymentMethod = 'flutterwave';

    // for paid item status
    const paidItemProgressStatus = 'Progress'; //1

    const paidItemCompletedStatus = 'Finished'; //2

    const paidItemNotYetStartStatus = 'Not Yet Start'; //3

    const paidItemWaitingForApproval = 'Waiting For Approval';

    const paidItemRejected = 'Rejected';

    const paidItemNotAvailable = 'Not Available';

    // for chat type
    const chatToSeller = 'to_seller';

    const chatToBuyer = 'to_buyer';

    const chatBuyerReturnType = 'buyer';

    const chatSellerReturnType = 'seller';

    const chatFromBuyer = 'CHAT_FROM_BUYER';

    const chatFromSeller = 'CHAT_FROM_SELLER';

    // for follower/following return type
    const followerReturnType = 'follower';

    const followingReturnType = 'following';

    // for rating type
    const ratingUserType = 'user';

    const enLanguageId = 1;

    const arLanguageId = 2;

    const frLanguageId = 3;

    const esLanguageId = 4;

    const ptLanguageId = 5;

    const hiLanguageId = 6;

    const idLanguageId = 7;

    const jaLanguageId = 8;

    const msLanguageId = 9;

    const ruLanguageId = 10;

    const trLanguageId = 11;

    const deLanguageId = 12;

    const itLanguageId = 13;

    const koLanguageId = 14;

    const thLanguageId = 15;

    const zhLanguageId = 16;

    // for user account verify type
    const emailVerify = 1;

    const googleVerify = 2;

    const facebookVerify = 3;

    const phoneVerify = 4;

    const appleVerify = 5;

    // for noti type
    const chatMessageType = 'CHAT_MESSAGE';

    const pushNotiType = 'PUSH_NOTI';

    const offerAcceptedType = 'OFFER_ACCEPTED';

    const offerRejectedType = 'OFFER_REJECTED';

    const offerReceivedType = 'OFFER_RECEIVED';

    // for search history type
    const searchHistoryItemType = 'item';

    const searchHistoryUserType = 'user';

    const searchHistoryCategoryType = 'category';

    const searchHistoryAllType = 'all';

    // for ad_post_type key
    const onlyPaidItemAdType = 'only_paid_item';

    const normalAdsOnlyAdType = 'normal_ads_only';

    const paidItemFirstAdType = 'paid_item_first';

    const googleAdsBetweenAdType = 'google_ads_between';

    const bumpsUpsBetweenAdType = 'bumps_ups_between';

    const bumbsAndGoogleAdsBetweenAdType = 'bumps_and_google_ads_between';

    const paidItemFirstWithGoogleAdType = 'paid_item_first_with_google';

    // for item ad_type
    const normalAd = 'normal_ad';

    const googleAd = 'google_ad';

    const paidAd = 'paid_ad';

    // for home page search type
    const categoryType = 'category';

    const itemType = 'item';

    const userType = 'user';

    const allType = 'all';

    const subCategoryType = 'sub-category';

    // for noti flag
    const approvalNotiFlag = 'approval';

    const chatNotiFlag = 'chat';

    const verifyBlueMarkNotiFlag = 'verify_blue_mark';

    const followNotiFlag = 'follow';

    const reviewNotiFlag = 'review';

    const subscribeNotiFlag = 'subscribe';

    const feSubscribeNotiFlag = '_FE';

    const mbSubscribeNotiFlag = '_MB';

    // rating type
    const itemRatingType = 'item';

    const userRatingType = 'user';

    const shopRatingType = 'shop';

    const notFromHomePageSearch = 0;

    const fromHomePageSearch = 1;

    // Constants for Price Setting
    const NO_PRICE = 'NO_PRICE';

    const PRICE_RANGE = 'PRICE_RANGE';

    const NORMAL_PRICE = 'NORMAL_PRICE';

    // System Setting
    const SYSTEM_CONFIG = 'system_config';

    const VENDOR_SUBSCRIPTION_CONFIG = 'vendor_subscription_config';

    // Vendor Status
    const vendorPendingStatus = 0;

    const vendorRejectStatus = 1;

    const vendorAcceptStatus = 2;

    const vendorSpamStatus = 4;

    // img type
    const itemCoverImgType = 'item';

    const itemVideoIconImgType = 'item-video-icon';

    const itemVideoImgType = 'item-video';

    const blogCoverImgType = 'blog';

    const chatImgType = "chat";

    const pushNotificationMessageCoverImgType = 'push_notification_message';

    const vendorLogoImgType = 'vendor-logo';

    const vendorBanner1ImgType = 'vendor-banner-1';

    const vendorBanner2ImgType = 'vendor-banner-2';

    const categoryCoverImgType = 'category-cover';

    const categoryIconImgType = 'category-icon';

    const subcategoryCoverImgType = 'subCategory-cover';

    const subcategoryIconImgType = 'subCategory-icon';

    const backendLogo = 'backend-logo';

    const backendFavIcon = 'fav-icon';

    const beWaterMarkImage = 'backend-water-mask-image';

    const waterMarkBackground = 'water-mask-background';

    const waterMarkBackgroundOrg = 'water-mask-background-original';

    const frontendLogo = 'frontend-logo';

    const frontendIcon = 'frontend-icon';

    const frontendBanner = 'frontend-banner';

    const appBrandingImage = 'app-branding-image';

    const metaImage = 'backend-meta-image';

    const vendorOwnerRole = '1';

    // order status
    const orderPendingStatus = 1;

    const orderApprovedStatus = 2;

    const orderDeliveringStatus = 3;

    const orderDeliveredStatus = 4;

    // Offline Payment Setting
    const offlinePaymentFileKey = 'icon';

    const offlinePaymentIconImgType = 'offline-payment-icon';

    // BlueMark Status
    const blueMarkApproveStatus = 1;

    const blueMarkPendingStatus = 2;

    const blueMarkRejectStatus = 3;
}
