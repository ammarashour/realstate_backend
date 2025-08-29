<?php

namespace App\Config;

class ps_url
{   
    // const saveClientCustomizeUiChanges = "/clientCustomizeUi";
    // const getProjectJson = "/project/get_project_json";
    // const syncTaleField = "/table_field/sync_fields";
    // const syncToBuilder = "/project/sync_project_json";
    // const checkConnection = "/version_number/check_builder_connection";

    const builderAppInfo = "/builder_app_info/get_next_builder_app_info"; // next
    const handleDefaultFieldTable = "/table_field/handle_next_default_field_table"; // next    
    const getNextProjectJson = "/project/get_next_project_json";
    const getProject = "/project/get_project";
    const getLatestVersion = "/version_number/get_next_latest_version"; // next
    const checkBuilderConnection = "/version_number/check_builder_connection";
    const getSourceCode = "/source_code/get_next_source_code"; // next    
    const getBeLangZip = "/be_language_sync/get_next_latest_zip"; // next
    const getFeLangZip = "/fe_language_sync/get_next_latest_zip"; // next
    const getMbLangZip = "/mb_language_sync/get_next_latest_zip"; // next
    const getVendorLangZip = "/vendor_language_sync/get_next_latest_zip"; // next
    const haveSourceCodeZipFile = "/source_code/have_next_source_code_zip_file"; // next    
    const generateCoreKeysId = "/clientCustomizeUi/generate_core_keys_id";

}

