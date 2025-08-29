<?php

namespace App\Config;

use Illuminate\Support\Facades\File;

class ps_constant
{
    const minPhpVersion = "8.1.0";

    const createPermission = 1;
    const readPermission = 2;
    const updatePermission = 3;
    const deletePermission = 4;
    const loginUserIdParaFromApi = "login_user_id";
    const deviceTokenKeyFromApi = "header-token";
    const supportedApiVersionCount = 9;
    const latestApiVersion = 1.0;

    const envatoApiUri = "https://api.envato.com/";
    const envatoApiVersion = "v3";
    const freeTrialTotalDay = 60;


    const flutterNotificationClick = "FLUTTER_NOTIFICATION_CLICK";
    const broadcast = "broadcast";
    const feBroadcast = "fe_broadcast";

    // for one-click-auto update
    const builderDomain = "https://www.psx-builder.panacea-soft.com/";

    // const builderDomain = "https://www.psx-builder.panacea-soft.com/";

    const builderApiVersion = "v1.0";
    const base_url = ps_constant::builderDomain . "api/" . ps_constant::builderApiVersion;
    const searchDomain = "https://www.products.panacea-soft.co/";
    const searchSubFolder = "psx-mpc-demo";
    const searchSubFolderWithSlash1 = "/psx-mpc-demo";
    const searchSubFolderWithSlash2 = "psx-mpc-demo/";

    // file paths to replace domain
    const appJSFilePath = "build/assets/app.*.js";
    const PsApiServiceJSFilePath = "build/assets/PsApiService.*.js";
    const psApiServiceJSFilePath = "build/assets/psApiService.*.js";


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

    // for variable
    const showCoreField = "showCoreField";
    const handlingColumn = "handlingColumn";
    const handlingFilter = "handlingFilter";

    // for condition
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
    const ascending = "asc";
    const descending = "desc";
    const isSoldOut = 1;
    const yes = 1;
    const no = 0;
    // for beta (0 is beta. 1 is not beta)
    const isPublish = 1;
    const notSaveForNextTime = 0;
    const saveForNextTime = 1;

    // core key
    const customFieldAttribute = 'cus-ui-dtl';

    // For Module
    const customFieldAttributeModule = 65;

    // vendor order status
    const pendingStatusOfVendorOrder = 1;
    const acceptedStatusOfVendorOrder = 2;
    const deliveringStatusOfVendorOrder = 3;
    const devliveredStatusOfVendorOrder = 4;
    const completedStatusOfVendorOrder = 5;
    const cancelledStatusOfVendorOrder = 6;

    // vendor payment status
    const pendingStatusOfVendorPayment = 1;
    const paidStatusOfVendorPayment = 2;
    const rejectedStatusOfVendorPayment = 3;
    const refundedStatusOfVendorPayment = 4;

    // img folder
    const imgFolderPath = "/storage/PSX_MPC/";

    // baseProjectIds
    const mpcBaseProjectId = 11;
    const cgcBaseProjectId = 28;

    // base project ids to hide vendor
    const baseProjectIdsToHideVendor = [ps_constant::cgcBaseProjectId];

    // vendor menu ids
    const vendorMenuIdsInAdminPanel = [70, 71, 72, 73, 74, 76, 77, 78, 79, 80, 81];
    const vendorSubMenuIdsInAdminPanel = [17, 18, 19, 20];
    const vendorMenuGroupIdsInAdminPanel = [5];

    const localDomains = ["http://127.0.0.1:8000/", "http://localhost:8000/"];
    const checkDoaminCacheKey = "CHECKING_DOMAIN";
    const cache4HrsDuration = 14400; // by second format

    const privateKeyFileNameForFCM = "firebase-private-key.json";
    const adsTxtFileNameForAdsense = "ads.txt";

    // language string prefixs
    const beLangStringPrefix = "";
    const feLangStringPrefix = "";
    const vendorLangStringPrefix = "";

    // platform ids
    const webPlatformId = 1;
    const mobilePlatformId = 2;

    // screen ids
    const dashboardScreenIds = ["ps-sweb00001", "ps-sweb00002", "ps-sweb00003", "ps-sweb00004"];

    // dashboard screen of component ids
    const searchAndPopularCategoryComponentIds = ["ps-cweb00001", "ps-cweb00014", "ps-cweb00024", "ps-cweb00037"];
    const categoryHorizontalListComponentIds = ["ps-cweb00002", "ps-cweb00015", "ps-cweb00025", "ps-cweb00038"];
    const howItsWorkComponentIds = ["ps-cweb00003", "ps-cweb00026", "ps-cweb00039"];
    const vendorHorizontalListComponentIds = ["ps-cweb00004", "ps-cweb00027", "ps-cweb00040"];
    const featureItemHorizontalListComponentIds = ["ps-cweb00005", "ps-cweb00016", "ps-cweb00028", "ps-cweb00041"];
    const recentItemHorizontalListComponentIds = ["ps-cweb00006", "ps-cweb00017", "ps-cweb00029", "ps-cweb00042"];
    const popularItemHorizontalListComponentIds = ["ps-cweb00007", "ps-cweb00018", "ps-cweb00030", "ps-cweb00043"];
    const vendorCardComponentIds = ["ps-cweb00008", "ps-cweb00031", "ps-cweb00044"];
    const discountItemHorizontalListComponentIds = ["ps-cweb00009", "ps-cweb00019", "ps-cweb00032", "ps-cweb00045"];
    const packageHorizontalListComponentIds = ["ps-cweb00010", "ps-cweb00020", "ps-cweb00033", "ps-cweb00046"];
    const topSellerHorizontalListComponentIds = ["ps-cweb00011", "ps-cweb00021", "ps-cweb00034", "ps-cweb00047"];
    const blogHorizontalListComponentIds = ["ps-cweb00012", 'ps-cweb00022', "ps-cweb00035", "ps-cweb00048"];
    const mobileShowCaseComponentIds = ["ps-cweb00013", "ps-cweb00023", "ps-cweb00036", "ps-cweb00049"];

    // dynamic link
    const DYNAMIC_LINK_CONFIG = 'dynamic_link_config';
    const FIREBASE = 'FIREBASE';
    const PSX_DYNAMIC_LINK = 'PSX_DYNAMIC_LINK';
    const dynamicLinkItemType = "ITEM";

    // Types
    const DYNAMIC_LINK_ITEM = 'ITEM';

    // Type => Route Name
    const DYNAMIC_LINKS = [self::DYNAMIC_LINK_ITEM => 'fe_item_detail'];

    // Chunk Limit
    const CHUNK_LIMIT = 150;

    // Customize Theme
    const MOBILE = 'Mobile';
    const WEB = 'Web';
}
