<?php

namespace App\Config;

class ps_config
{
    const coreKeyFormat = '00';
    const coreFieldFilterForRelation = '@@';
    const pagPerPage = '10';
    const sessionExpiredTime = 12; //with hours

    const howToChangePhpPathDocLink = "https://www.docs.panacea-soft.com/psx-mpc/faqs/web/how-to-change-php-path";
    const howToUpdateComposerDocLink = "https://www.docs.panacea-soft.com/psx-mpc/faqs/web/how-to-manually-update-composer";
    const siteMapFolder = "sitemaps";
    const redirectPathForNoPermission = "admin.index";

    const procOpenError = 'proc_open';
    const phpPathError = 'php_path';
}

