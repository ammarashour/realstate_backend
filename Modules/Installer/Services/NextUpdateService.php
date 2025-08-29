<?php

namespace Modules\Installer\Services;

use App\Config\Cache\CacheBuilderAppInfoCache;
use App\Config\Cache\CheckVersionUpdateCache;
use App\Config\ps_config;
use App\Config\ps_constant;
use App\Config\ps_url;
use App\Enums\Language\InsertionSource;
use App\Enums\Language\JsonGenerationOption;
use App\Helpers\PsArtisanHelper;
use App\Helpers\PsPHPHelper;
use App\Http\Contracts\Localization\LanguageServiceInterface;
use App\Http\Contracts\Localization\BeLanguageStringServiceInterface;
use App\Http\Contracts\Localization\FeLanguageStringServiceInterface;
use App\Http\Contracts\Localization\MobileLanguageServiceInterface;
use App\Http\Contracts\Localization\MobileLanguageStringServiceInterface;
use App\Http\Contracts\Localization\VendorLanguageStringServiceInterface;
use App\Http\Services\PsService;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Modules\Core\Entities\Utilities\CheckVersionUpdate;
use Modules\Core\Entities\Utilities\CoreField;
use Modules\Core\Entities\Utilities\CustomField;
use Modules\Core\Entities\Localization\FeLanguageString;
use Modules\Core\Entities\Localization\LanguageString;
use Modules\Core\Entities\Localization\MobileLanguage;
use Modules\Core\Entities\Localization\MobileLanguageString;
use Modules\Core\Entities\Project;
use Modules\Core\Entities\Utilities\DynamicColumnVisibility;
use Modules\Core\Entities\Table;
use Modules\Core\Entities\UpdaterData;
use Modules\Core\Imports\FeLanguageStringImport;
use Modules\Core\Imports\BeLanguageStringImport;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Concerns\ToArray;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\CoreKeyCounter;
use Modules\Core\Entities\CoreKeyType;
use Modules\Core\Entities\LogChange;
use Modules\Core\Entities\Localization\VendorLanguageString;
use Modules\Core\Http\Facades\LanguageFacade;
use Modules\Core\Http\Facades\MobileLanguageFacade;
use Modules\Core\Http\Facades\PsCache;
use Modules\Core\Imports\MobileLanguageStringImport;
use Modules\Core\Imports\MobileLanguageStringImportJson;
use Modules\Core\Imports\VendorLanguageStringImport;
use Throwable;

class NextUpdateService extends PsService
{

    public function __construct(
        protected MobileLanguageServiceInterface $mobileLanguageService,
        protected MobileLanguageStringServiceInterface $mobileLanguageStringService,
        protected BeLanguageStringServiceInterface $beLanguageStringService,
        protected FeLanguageStringServiceInterface $feLanguageStringService,
        protected VendorLanguageStringServiceInterface $vendorLanguageStringService)
    {}

    public function addNewLangStore()
    {
        $domain = $_SERVER['HTTP_HOST'] ?? '-';

        $project = Project::first();
        $checkBuilderConnection = getHttpWithApiKey(ps_constant::base_url, getApiKey(), ps_url::checkBuilderConnection);
        if ($checkBuilderConnection?->status !== "success" || empty($checkBuilderConnection)) {

            $msg = $checkBuilderConnection?->message ? $checkBuilderConnection?->message : "Builder Connection Fail";
            return redirectBackWithError(resultMessage($msg, "error"));
        } else {
            $checkVersionUpdate = CheckVersionUpdate::first();
            $para = 'base_project_id=' . $project->base_project_id . '&is_publish=' . ps_constant::isPublish . '&domain='.$domain;
            $getLatestVersion = getHttpWithApiKey(ps_constant::base_url, getApiKey(), ps_url::getLatestVersion, $para);

            // checking that you have updated latest version or not start
            if (!empty($checkVersionUpdate)) {
                $currentVersionCode = intval($checkVersionUpdate->backend_language_version_code);
                $latestVersionCode = $getLatestVersion->version_code;
                if ($currentVersionCode == $latestVersionCode) {
                    return ['logMessages' => 'be_lang_sync_success'];
                }
                $currentVersionCode = $currentVersionCode + 1;
            } else {
                $currentVersionCode = 0;
            }

            $dataArr = [
                'current_version_code' => $currentVersionCode,
                'base_project_id' => $project->base_project_id,
                'is_publish' => ps_constant::isPublish
            ];

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' .  getApiKey(),
                // 'Content-Type' => 'multipart/form-data'
            ])->post(ps_constant::base_url . ps_url::getBeLangZip . "?&project_id=" . getProjectId() . '&domain='.$domain, $dataArr);

            if ($response->successful()) {
                $zipContent = $response->body();
                $fileName = 'beLanguage.zip';
                $folderName = "beLanguageZip" . time();

                if (!File::isDirectory(public_path($folderName))) {
                    File::makeDirectory(public_path($folderName), 0777, true, true);
                }

                File::put(public_path($folderName . '/' . $fileName), $zipContent);

                $filePath = public_path($folderName);

                // extract lang zip file start
                $zip = new \ZipArchive();
                $res = $zip->open($filePath . '/' . $fileName);
                if ($res === true) {
                    $langSymbols = [];
                    $langStringCSVFiles = [];
                    for ($i = 0; $i < $zip->numFiles; $i++) {
                        $fileName = $zip->getNameIndex($i);
                        $langSymbol = str_replace(".csv", "", $fileName);
                        array_push($langSymbols, $langSymbol);
                        array_push($langStringCSVFiles, $filePath . '/' . $fileName);
                    }
                    $zip->extractTo($filePath . "/");
                    $zip->close();
                } else {
                    return ['logMessages' => 'be_lang_sync_fail'];
                }
                // extract lang zip file end

                // add new lang string for our supported lang start
                $ourSupportedLangSymbols = [];
                $languages = LanguageFacade::getAll();
                foreach ($languages as $language) {
                    array_push($ourSupportedLangSymbols, $language->symbol);
                    foreach ($langSymbols as $langSymbol) {
                        if ($language->symbol == $langSymbol) {
                            $file = $filePath . '/' . $langSymbol . ".csv";

                            $import = new BeLanguageStringImport(
                                            $this->beLanguageStringService,
                                            $language,
                                            InsertionSource::DEFAULT,
                                            JsonGenerationOption::TARGET_FILE_ONLY
                                        );

                            $import->import($file);
                            break;
                        }
                    }
                }
                // add new lang string for our supported lang end

                // for not our support lang, we will add english lang string for our new lang string start
                $notOurSupportedLangSymbols = array_diff($ourSupportedLangSymbols, $langSymbols);
                foreach ($languages as $language) {
                    foreach ($notOurSupportedLangSymbols as $notOurSupportedLangSymbol) {
                        if ($language->symbol == $notOurSupportedLangSymbol) {
                            $file = $filePath . '/' . "en.csv";

                            $import = new BeLanguageStringImport(
                                $this->beLanguageStringService,
                                $language,
                                InsertionSource::DEFAULT,
                                JsonGenerationOption::TARGET_FILE_ONLY
                            );
                            $import->import($file);

                            break;
                        }
                    }
                }
                // for not our support lang, we will add english lang string for our new lang string end
                // clean imported files start
                Storage::delete($fileName);
                Storage::delete($langStringCSVFiles);
                Storage::deleteDirectory($folderName, true);
                // clean imported files start

                // updated at psx_check_version_updates
                if (empty($checkVersionUpdate)) {
                    $checkVersionUpdate = new CheckVersionUpdate();
                    $checkVersionUpdate->backend_language_version_number = $getLatestVersion->version_number;
                    $checkVersionUpdate->backend_language_version_code = $getLatestVersion->version_code;
                    $checkVersionUpdate->save();

                    PsCache::clear(CheckVersionUpdateCache::BASE);
                } else {
                    $checkVersionUpdate->backend_language_version_number = $getLatestVersion->version_number;
                    $checkVersionUpdate->backend_language_version_code = $getLatestVersion->version_code;
                    $checkVersionUpdate->update();

                    PsCache::clear(CheckVersionUpdateCache::BASE);
                }
            } else {
                return redirectBackWithError(resultMessage("There have issue at getting BE language Zip", "error"));
            }
        }
        return ['logMessages' => 'be_lang_sync_success'];
    }

    public function addNewFeLangStore()
    {
        $domain = $_SERVER['HTTP_HOST'] ?? '-';

        $project = Project::first();
        $checkBuilderConnection = getHttpWithApiKey(ps_constant::base_url, getApiKey(), ps_url::checkBuilderConnection);
        if ($checkBuilderConnection?->status !== "success" || empty($checkBuilderConnection)) {

            $msg = $checkBuilderConnection?->message ? $checkBuilderConnection?->message : "Builder Connection Fail";
            return redirectBackWithError(resultMessage($msg, "error"));
        } else {
            $checkVersionUpdate = CheckVersionUpdate::first();
            $para = 'base_project_id=' . $project->base_project_id . '&is_publish=' . ps_constant::isPublish . '&domain='.$domain;
            $getLatestVersion = getHttpWithApiKey(ps_constant::base_url, getApiKey(), ps_url::getLatestVersion, $para);

            // checking that you have updated latest version or not start
            if (!empty($checkVersionUpdate)) {
                $currentVersionCode = intval($checkVersionUpdate->frontend_language_version_code);
                $latestVersionCode = $getLatestVersion->version_code;
                if ($currentVersionCode == $latestVersionCode) {
                    return ['logMessages' => 'fe_lang_sync_success'];
                }
                $currentVersionCode = $currentVersionCode + 1;
            } else {
                $currentVersionCode = 0;
            }

            $dataArr = [
                'current_version_code' => $currentVersionCode,
                'base_project_id' => $project->base_project_id,
                'is_publish' => ps_constant::isPublish
            ];

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' .  getApiKey(),
                // 'Content-Type' => 'multipart/form-data'
            ])->post(ps_constant::base_url . ps_url::getFeLangZip . "?&project_id=" . getProjectId() . '&domain='.$domain, $dataArr);

            if ($response->successful()) {
                $zipContent = $response->body();

                $fileName = 'feLanguage.zip';
                $folderName = "feLanguageZip" . time();

                if (!File::isDirectory(public_path($folderName))) {
                    File::makeDirectory(public_path($folderName), 0777, true, true);
                }

                File::put(public_path($folderName . '/' . $fileName), $zipContent);

                $filePath = public_path($folderName);

                // extract lang zip file start
                $zip = new \ZipArchive();
                $res = $zip->open($filePath . '/' . $fileName);
                if ($res === true) {
                    $langSymbols = [];
                    $langStringCSVFiles = [];
                    for ($i = 0; $i < $zip->numFiles; $i++) {
                        $fileName = $zip->getNameIndex($i);
                        $langSymbol = str_replace(".csv", "", $fileName);
                        array_push($langSymbols, $langSymbol);
                        array_push($langStringCSVFiles, $filePath . '/' . $fileName);
                    }
                    $zip->extractTo($filePath . "/");
                    $zip->close();
                } else {
                    return ['logMessages' => 'fe_lang_sync_fail'];
                }
                // extract lang zip file end

                // add new lang string for our supported lang start
                $ourSupportedLangSymbols = [];
                $languages = LanguageFacade::getAll();
                foreach ($languages as $language) {
                    array_push($ourSupportedLangSymbols, $language->symbol);
                    foreach ($langSymbols as $langSymbol) {
                        if ($language->symbol == $langSymbol) {
                            $file = $filePath . '/' . $langSymbol . ".csv";

                            $import = new FeLanguageStringImport(
                                            $this->feLanguageStringService,
                                            $language,
                                            JsonGenerationOption::TARGET_FILE_ONLY
                                        );

                            $import->import($file);
                            break;
                        }
                    }
                }
                // add new lang string for our supported lang end


                // for not our support lang, we will add english lang string for our new lang string start
                $notOurSupportedLangSymbols = array_diff($ourSupportedLangSymbols, $langSymbols);
                foreach ($languages as $language) {
                    foreach ($notOurSupportedLangSymbols as $notOurSupportedLangSymbol) {
                        if ($language->symbol == $notOurSupportedLangSymbol) {
                            $file = $filePath . '/' . "en.csv";

                            $import = new FeLanguageStringImport(
                                $this->feLanguageStringService,
                                $language,
                                JsonGenerationOption::TARGET_FILE_ONLY
                            );
                            $import->import($file);

                            break;
                        }
                    }
                }
                // for not our support lang, we will add english lang string for our new lang string end

                // clean imported files start
                Storage::delete($fileName);
                Storage::delete($langStringCSVFiles);
                Storage::deleteDirectory($folderName, true);
                // clean imported files end

                // updated at psx_check_version_updates
                if (empty($checkVersionUpdate)) {
                    $checkVersionUpdate = new CheckVersionUpdate();
                    $checkVersionUpdate->frontend_language_version_number = $getLatestVersion->version_number;
                    $checkVersionUpdate->frontend_language_version_code = $getLatestVersion->version_code;
                    $checkVersionUpdate->save();

                    PsCache::clear(CheckVersionUpdateCache::BASE);
                } else {
                    $checkVersionUpdate->frontend_language_version_number = $getLatestVersion->version_number;
                    $checkVersionUpdate->frontend_language_version_code = $getLatestVersion->version_code;
                    $checkVersionUpdate->update();

                    PsCache::clear(CheckVersionUpdateCache::BASE);
                }
            } else {
                return redirectBackWithError(resultMessage("There have issue at getting FE language Zip", "error"));
            }
        }

        return ['logMessages' => 'fe_lang_sync_success'];
    }

    public function addNewMobileLangStore($request)
    {
        $domain = $_SERVER['HTTP_HOST'] ?? '-';

        $project = Project::first();
        $checkBuilderConnection = getHttpWithApiKey(ps_constant::base_url, getApiKey(), ps_url::checkBuilderConnection);
        if ($checkBuilderConnection?->status !== "success" || empty($checkBuilderConnection)) {

            $msg = $checkBuilderConnection?->message ? $checkBuilderConnection?->message : "Builder Connection Fail";
            return redirectBackWithError(resultMessage($msg, "error"));
        } else {

            $checkVersionUpdate = CheckVersionUpdate::first();
            $para = 'base_project_id=' . $project->base_project_id . '&is_publish=' . ps_constant::isPublish . '&domain='.$domain;
            $getLatestVersion = getHttpWithApiKey(ps_constant::base_url, getApiKey(), ps_url::getLatestVersion, $para);

            // checking that you have updated latest version or not start
            if (!empty($checkVersionUpdate)) {
                $currentVersionCode = intval($checkVersionUpdate->mobile_language_version_code);
                $latestVersionCode = $getLatestVersion->version_code;
                if ($currentVersionCode == $latestVersionCode) {
                    return ['logMessages' => 'mb_lang_sync_success'];
                }
                $currentVersionCode = $currentVersionCode + 1;
            } else {
                $currentVersionCode = 0;
            }

            $dataArr = [
                'current_version_code' => $currentVersionCode,
                'base_project_id' => $project->base_project_id,
                'is_publish' => ps_constant::isPublish
            ];

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' .  getApiKey(),
                // 'Content-Type' => 'multipart/form-data'
            ])->post(ps_constant::base_url . ps_url::getMbLangZip . "?&project_id=" . getProjectId() . '&domain='.$domain, $dataArr);

            if ($response->successful()) {
                // extract lang zip file start
                $zipContent = $response->body();

                $fileName = 'mbLanguage.zip';
                $folderName = "mbLanguageZip" . time();

                if (!File::isDirectory(public_path($folderName))) {
                    File::makeDirectory(public_path($folderName), 0777, true, true);
                }

                File::put(public_path($folderName . '/' . $fileName), $zipContent);

                $filePath = public_path($folderName);

                // extract lang zip file start
                $zip = new \ZipArchive();
                $res = $zip->open($filePath . '/' . $fileName);

                if ($res === true) {
                    $langSymbols = [];
                    $langStringCSVFiles = [];
                    for ($i = 0; $i < $zip->numFiles; $i++) {
                        $fileName = $zip->getNameIndex($i);
                        $langSymbol = str_replace(".json", "", $fileName);
                        array_push($langSymbols, $langSymbol);
                        array_push($langStringCSVFiles, $filePath . '/' . $fileName);
                    }
                    $zip->extractTo($filePath . "/");
                    $zip->close();
                } else {
                    return ['logMessages' => 'mb_lang_sync_fail'];
                }
                // extract lang zip file end

                // add new lang string for our supported lang start
                $ourSupportedLangSymbols = [];
                $languages = MobileLanguageFacade::getAll();
                foreach ($languages as $language) {
                    array_push($ourSupportedLangSymbols, $language->symbol);
                    foreach ($langSymbols as $langSymbol) {
                        if ($language->symbol == $langSymbol) {
                            $file = $filePath . '/' . $langSymbol . ".json";
                            $languageStrings = json_decode(file_get_contents($file), true);
                            $formattedData = array_map(fn($key, $value) => [
                                'key' => $key,
                                'value' => $value
                            ], array_keys($languageStrings), array_values($languageStrings));

                            $import = new MobileLanguageStringImport(
                                            $this->mobileLanguageStringService,
                                            $language
                                        );
                            $import->collection(collect($formattedData));
                            break;
                        }
                    }
                }
                // add new lang string for our supported lang end


                // for not our support lang, we will add english lang string for our new lang string start
                $notOurSupportedLangSymbols = array_diff($ourSupportedLangSymbols, $langSymbols);
                foreach ($languages as $language) {
                    foreach ($notOurSupportedLangSymbols as $notOurSupportedLangSymbol) {
                        if ($language->symbol == $notOurSupportedLangSymbol) {
                            $file = $filePath . '/' . "en.json";

                            $languageStrings = json_decode(file_get_contents($file), true);
                            $formattedData = array_map(fn($key, $value) => [
                                'key' => $key,
                                'value' => $value
                            ], array_keys($languageStrings), array_values($languageStrings));

                            $import = new MobileLanguageStringImport(
                                $this->mobileLanguageStringService,
                                $language
                            );
                            $import->collection(collect($formattedData));

                            break;
                        }
                    }
                }
                // for not our support lang, we will add english lang string for our new lang string end

                // update code for mobile language
                // after imported.
                foreach ($languages as $language) {
                    $this->mobileLanguageStringService->updateCode($language->id);
                }

                // clean imported files start
                Storage::delete($fileName);
                Storage::delete($langStringCSVFiles);
                Storage::deleteDirectory($folderName, true);
                // clean imported files start

                if (empty($checkVersionUpdate)) {
                    $checkVersionUpdate = new CheckVersionUpdate();
                    $checkVersionUpdate->mobile_language_version_number = $getLatestVersion->version_number;
                    $checkVersionUpdate->mobile_language_version_code = $getLatestVersion->version_code;
                    $checkVersionUpdate->save();

                    PsCache::clear(CheckVersionUpdateCache::BASE);
                } else {
                    $checkVersionUpdate->mobile_language_version_number = $getLatestVersion->version_number;
                    $checkVersionUpdate->mobile_language_version_code = $getLatestVersion->version_code;
                    $checkVersionUpdate->update();

                    PsCache::clear(CheckVersionUpdateCache::BASE);
                }
            } else {
                return redirectBackWithError(resultMessage("There have issue at getting MB language Zip", "error"));
            }
        }
        return ['logMessages' => 'mb_lang_sync_success'];
    }

    public function addNewVendorLangStore($request)
    {
        $domain = $_SERVER['HTTP_HOST'] ?? '-';

        $project = Project::first();
        $checkBuilderConnection = getHttpWithApiKey(ps_constant::base_url, getApiKey(), ps_url::checkBuilderConnection);
        if ($checkBuilderConnection?->status !== "success" || empty($checkBuilderConnection)) {

            $msg = $checkBuilderConnection?->message ? $checkBuilderConnection?->message : "Builder Connection Fail";
            return redirectBackWithError(resultMessage($msg, "error"));
        } else {
            $checkVersionUpdate = CheckVersionUpdate::first();
            $para = 'base_project_id=' . $project->base_project_id . '&is_publish=' . ps_constant::isPublish . '&domain='.$domain;
            $getLatestVersion = getHttpWithApiKey(ps_constant::base_url, getApiKey(), ps_url::getLatestVersion, $para);

            // checking that you have updated latest version or not start
            if (!empty($checkVersionUpdate)) {
                $currentVersionCode = intval($checkVersionUpdate->vendor_language_version_code);
                $latestVersionCode = $getLatestVersion->version_code;
                if ($currentVersionCode == $latestVersionCode) {
                    return ['logMessages' => 'vendor_lang_sync_success'];
                }
                $currentVersionCode = $currentVersionCode + 1;
            } else {
                $currentVersionCode = 0;
            }

            $dataArr = [
                'current_version_code' => $currentVersionCode,
                'base_project_id' => $project->base_project_id,
                'is_publish' => ps_constant::isPublish
            ];

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' .  getApiKey(),
                // 'Content-Type' => 'multipart/form-data'
            ])->post(ps_constant::base_url . ps_url::getVendorLangZip . "?&project_id=" . getProjectId() . '&domain='.$domain, $dataArr);

            if ($response->successful()) {
                $zipContent = $response->body();

                $fileName = 'vendorLanguage.zip';
                $folderName = "vendorLanguageZip" . time();

                if (!File::isDirectory(public_path($folderName))) {
                    File::makeDirectory(public_path($folderName), 0777, true, true);
                }

                File::put(public_path($folderName . '/' . $fileName), $zipContent);

                $filePath = public_path($folderName);

                // extract lang zip file start
                $zip = new \ZipArchive();
                $res = $zip->open($filePath . '/' . $fileName);

                if ($res === true) {
                    $langSymbols = [];
                    $langStringCSVFiles = [];
                    for ($i = 0; $i < $zip->numFiles; $i++) {
                        $fileName = $zip->getNameIndex($i);
                        $langSymbol = str_replace(".csv", "", $fileName);
                        array_push($langSymbols, $langSymbol);
                        array_push($langStringCSVFiles, $filePath . '/' . $fileName);
                    }
                    $zip->extractTo($filePath . "/");
                    $zip->close();
                } else {
                    return ['logMessages' => 'vendor_lang_sync_fail'];
                }
                // extract lang zip file end

                // add new lang string for our supported lang start
                $ourSupportedLangSymbols = [];
                $languages = LanguageFacade::getAll();
                foreach ($languages as $language) {
                    array_push($ourSupportedLangSymbols, $language->symbol);
                    foreach ($langSymbols as $langSymbol) {
                        if ($language->symbol == $langSymbol) {
                            $file = $filePath . '/' . $langSymbol . ".csv";

                            $import = new VendorLanguageStringImport(
                                            $this->vendorLanguageStringService,
                                            $language,
                                            JsonGenerationOption::TARGET_FILE_ONLY
                                        );

                            $import->import($file);
                            break;
                        }
                    }
                }
                // add new fe lang string for our supported lang end


                // for not our support lang, we will add english lang string for our new lang string start
                $notOurSupportedLangSymbols = array_diff($ourSupportedLangSymbols, $langSymbols);
                foreach ($languages as $language) {
                    foreach ($notOurSupportedLangSymbols as $notOurSupportedLangSymbol) {
                        if ($language->symbol == $notOurSupportedLangSymbol) {
                            $file = $filePath . '/' . "en.csv";

                            $import = new VendorLanguageStringImport(
                                $this->vendorLanguageStringService,
                                $language,
                                JsonGenerationOption::TARGET_FILE_ONLY
                            );
                            $import->import($file);

                            break;
                        }
                    }
                }
                // for not our support lang, we will add english lang string for our new lang string end

                // clean imported files start
                Storage::delete($fileName);
                Storage::delete($langStringCSVFiles);
                Storage::deleteDirectory($folderName, true);
                // clean imported files end

                // updated at psx_check_version_updates
                if (empty($checkVersionUpdate)) {
                    $checkVersionUpdate = new CheckVersionUpdate();
                    $checkVersionUpdate->vendor_language_version_number = $getLatestVersion->version_number;
                    $checkVersionUpdate->vendor_language_version_code = $getLatestVersion->version_code;
                    $checkVersionUpdate->save();

                    PsCache::clear(CheckVersionUpdateCache::BASE);
                } else {
                    $checkVersionUpdate->vendor_language_version_number = $getLatestVersion->version_number;
                    $checkVersionUpdate->vendor_language_version_code = $getLatestVersion->version_code;
                    $checkVersionUpdate->update();

                    PsCache::clear(CheckVersionUpdateCache::BASE);
                }
            } else {
                return redirectBackWithError(resultMessage("There have issue at getting Vendor language Zip", "error"));
            }
        }

        return ['logMessages' => 'vendor_lang_sync_success'];
    }

    /**
     * @deprecated
     */
    public function builderZipFileStore($request)
    {
        $zipFile = $request->file("zipFile");
        $zip = new \ZipArchive();
        $zip->open($zipFile);
        $fileName = $zip->getNameIndex(0);
        $zip->extractTo("./");
        $zip->close();

        $updaterData = UpdaterData::first();

        if (empty($updaterData)) {
            $updaterData = new UpdaterData();
            $updaterData->file_name = $fileName;
            $updaterData->is_imported = 0;
            $updaterData->save();
        } else {
            $updaterData->file_name = $fileName;
            $updaterData->is_imported = 0;
            $updaterData->update();
        }
    }

    // For One click update Start

    public function sourceCodeSync($redirectRouteName)
    {
        $domain = $_SERVER['HTTP_HOST'] ?? '-';

        putenv('COMPOSER_HOME=' . __DIR__ . '/../../../vendor/bin/composer');
        $project = Project::first();
        $checkBuilderConnection = getHttpWithApiKey(ps_constant::base_url, getApiKey(), ps_url::checkBuilderConnection);
        if ($checkBuilderConnection?->status !== "success" || empty($checkBuilderConnection)) {

            $msg = $checkBuilderConnection?->message ? $checkBuilderConnection?->message : "Builder Connection Fail";
            return redirectBackWithError(resultMessage($msg, "error"));
        } else {
            $para = 'base_project_id=' . $project->base_project_id . '&is_publish=' . ps_constant::isPublish . '&domain='.$domain;

            $checkVersionUpdate = CheckVersionUpdate::first();
            $getLatestVersion = getHttpWithApiKey(ps_constant::base_url, getApiKey(), ps_url::getLatestVersion, $para);
            $haveSourceCodeZipFile = getHttpWithApiKey(ps_constant::base_url, getApiKey(), ps_url::haveSourceCodeZipFile, $para);

            // checking zip file have or not start
            if ($haveSourceCodeZipFile->isEmptyZip) {
                return redirectBackWithError(resultMessage($haveSourceCodeZipFile->msg, "error"));
            }
            // checking zip file have or not end

            $para = 'base_project_id=' . $project->base_project_id . '&is_publish=' . ps_constant::isPublish . '&domain='.$domain;
            $getSourceCode = Http::withHeaders([
                'Authorization' => 'Bearer ' . getApiKey(),
            ])->get(ps_constant::base_url . ps_url::getSourceCode . "?" . $para);

            if ($getSourceCode->successful()) {

                //make backup
                try {
                    if (function_exists('proc_open')) {
                        PsArtisanHelper::runArtisanCommandWithPhpVersion(config('app.php_path'), 'artisan backup:run --disable-notifications');
                    } else {
                        Artisan::call('backup:run --disable-notifications');
                    }
                    // shell_exec("cd .. && ".CheckPhpVersion()." artisan backup:run --disable-notifications");
                } catch (\Throwable $e) {
                    if (function_exists('proc_open')) {
                        PsArtisanHelper::runArtisanCommandWithPhpVersion(config('app.php_path'), 'artisan optimize:clear');
                    } else {
                        Artisan::call('optimize:clear');
                    }
                    return redirectBackWithError(resultMessage("Source Code Sync Fail. Reload and Try Againg", "error"));
                }

                // delete old build folder under public dir
                File::deleteDirectory(public_path('build'));

                $zipContent = $getSourceCode->body();

                // Save the zip file content to a local file
                $fileName = 'received_files.zip';

                $filePath = public_path("code/");
                if (! File::exists($filePath)) {
                    File::makeDirectory($filePath);
                }
                file_put_contents(public_path("code/{$fileName}"), $zipContent);
                $folderName = "code";

                // extract the zip file
                $zip = new \ZipArchive();
                $zip->open($filePath . $fileName);
                // $fileName = $zip->getNameIndex(0);
                $zip->extractTo(base_path("./"));
                $zip->close();

                $path_to_file = null;
                $path_to_file2 = null;
                $path_to_file3 = null;
                // replace content at builded file
                $pathArr = findFindWithHashKey(public_path(ps_constant::appJSFilePath));
                if (count($pathArr) !== 0) {
                    $path_to_file = $pathArr[0];
                }

                $pathArr2 = findFindWithHashKey(public_path(ps_constant::PsApiServiceJSFilePath));
                if (count($pathArr2) !== 0) {
                    $path_to_file2 = $pathArr2[0];
                }

                $pathArr3 = findFindWithHashKey(public_path(ps_constant::psApiServiceJSFilePath));
                if (count($pathArr3) !== 0) {
                    $path_to_file3 = $pathArr3[0];
                }

                if (empty(config("app.dir"))) {
                    $domainSubFolderBuild = ps_constant::searchDomain . ps_constant::searchSubFolder . '/' . 'build' . '/';
                    $domainSubFolder = ps_constant::searchDomain . ps_constant::searchSubFolder;
                    $envDomainBuild = config("app.base_domain") . 'build' . '/';

                    if ($path_to_file !== null) {
                        findAndReplaceForBuildFolder($path_to_file, $domainSubFolderBuild, $envDomainBuild);
                        findAndReplaceForBuildFolder($path_to_file, $domainSubFolder, config("app.base_domain"));
                        findAndReplaceForBuildFolder($path_to_file, ps_constant::searchDomain, config("app.base_domain"));
                        findAndReplaceForBuildFolder($path_to_file, ps_constant::searchSubFolderWithSlash1, "");
                        findAndReplaceForBuildFolder($path_to_file, ps_constant::searchSubFolderWithSlash2, "");
                        findAndReplaceForBuildFolder($path_to_file, ps_constant::searchSubFolder, "");
                    }
                    if ($path_to_file2 !== null) {
                        findAndReplaceForBuildFolder($path_to_file2, $domainSubFolderBuild, $envDomainBuild);
                        findAndReplaceForBuildFolder($path_to_file2, $domainSubFolder, config("app.base_domain"));
                        findAndReplaceForBuildFolder($path_to_file2, ps_constant::searchDomain, config("app.base_domain"));
                        findAndReplaceForBuildFolder($path_to_file2, ps_constant::searchSubFolderWithSlash1, "");
                        findAndReplaceForBuildFolder($path_to_file2, ps_constant::searchSubFolderWithSlash2, "");
                        findAndReplaceForBuildFolder($path_to_file2, ps_constant::searchSubFolder, "");
                    }
                    if ($path_to_file3 !== null) {
                        findAndReplaceForBuildFolder($path_to_file3, $domainSubFolderBuild, $envDomainBuild);
                        findAndReplaceForBuildFolder($path_to_file3, $domainSubFolder, config("app.base_domain"));
                        findAndReplaceForBuildFolder($path_to_file3, ps_constant::searchDomain, config("app.base_domain"));
                        findAndReplaceForBuildFolder($path_to_file3, ps_constant::searchSubFolderWithSlash1, "");
                        findAndReplaceForBuildFolder($path_to_file3, ps_constant::searchSubFolderWithSlash2, "");
                        findAndReplaceForBuildFolder($path_to_file3, ps_constant::searchSubFolder, "");
                    }
                } else {
                    if ($path_to_file !== null) {
                        findAndReplaceForBuildFolder($path_to_file, ps_constant::searchDomain, config("app.base_domain"));
                        findAndReplaceForBuildFolder($path_to_file, ps_constant::searchSubFolder, config("app.dir"));
                    }
                    if ($path_to_file2 !== null) {
                        findAndReplaceForBuildFolder($path_to_file2, ps_constant::searchDomain, config("app.base_domain"));
                        findAndReplaceForBuildFolder($path_to_file2, ps_constant::searchSubFolder, config("app.dir"));
                    }
                    if ($path_to_file3 !== null) {
                        findAndReplaceForBuildFolder($path_to_file3, ps_constant::searchDomain, config("app.base_domain"));
                        findAndReplaceForBuildFolder($path_to_file3, ps_constant::searchSubFolder, config("app.dir"));
                    }
                }


                // run migration command
                $composerPath = base_path('./composer.phar');
                if (function_exists('proc_open')) {
                    PsArtisanHelper::runArtisanCommandWithPhpVersion(config('app.php_path'), "$composerPath update");
                    PsArtisanHelper::runArtisanCommandWithPhpVersion(config('app.php_path'), 'artisan migrate --force');
                } else {
                    Artisan::call('migrate', ['--force' => true]);
                }
                // $command = "cd .. && php $composerPath update 2>&1";
                // $command = shell_exec("cd .. && ". CheckPhpVersion() ." composer.phar update");

                // extracted zip file delete
                Storage::delete($folderName . "/" . $fileName);

                // updated at psx_check_version_updates
                if (empty($checkVersionUpdate)) {
                    $checkVersionUpdate = new CheckVersionUpdate();
                    $checkVersionUpdate->source_code_version_number = $getLatestVersion->version_number;
                    $checkVersionUpdate->source_code_version_code = $getLatestVersion->version_code;
                    $checkVersionUpdate->save();

                    PsCache::clear(CheckVersionUpdateCache::BASE);
                } else {
                    $checkVersionUpdate->source_code_version_number = $getLatestVersion->version_number;
                    $checkVersionUpdate->source_code_version_code = $getLatestVersion->version_code;
                    $checkVersionUpdate->update();

                    PsCache::clear(CheckVersionUpdateCache::BASE);
                }
            } else {
                return redirectBackWithError(resultMessage("There have issue at getting source code", "error"));
            }

            return redirectView($redirectRouteName, "Source Code Sync have been finished successfully", "success", ['next' => 1]);
        }
    }

    public function handleBuilderTableFieldSync($redirectRouteName)
    {
        $domain = $_SERVER['HTTP_HOST'] ?? '-';

        $project = Project::first();
        $para = 'base_project_id=' . $project->base_project_id .
                    '&is_publish=' . ps_constant::isPublish .
                    '&domain='.$domain;
        $builderAppInfoPara = "&project_id=" . getProjectId()
                                    . "&project_url=" . $project->project_url
                                    . "&project_code=" . $project->project_code
                                    . "&api_key=" . $project->api_key
                                    . "&token=" . $project->token
                                    . "&user_id=1&is_publish=" . ps_constant::isPublish
                                    . '&log_code=' . getLogCode()
                                    . '&domain='.$domain;
        $builderAppInfo = getHttpWithApiKey(ps_constant::base_url, getApiKey(), ps_url::builderAppInfo, $builderAppInfoPara);
        if (!$builderAppInfo->isProjectChanged) {

            $checkVersionUpdate = CheckVersionUpdate::first();
            $getLatestVersion = getHttpWithApiKey(ps_constant::base_url, getApiKey(), ps_url::getLatestVersion, $para);

            if ($builderAppInfo->syncAble) {
                if (!empty($builderAppInfo)) {
                    $data = $builderAppInfo;
                    $handleVersionUpdate = postHttpWithApiKey(ps_constant::base_url, getApiKey(), ps_url::handleDefaultFieldTable, "&project_id=" . getProjectId(), $data);
                    // dd($handleVersionUpdate, ps_constant::base_url, getApiKey(), ps_url::handleDefaultFieldTable, getProjectId(), $data);
                    $projectObj = $handleVersionUpdate->projectObj;
                    $tableObjArr = collect($handleVersionUpdate->tableObjArr);
                    $customFieldObjArr = collect($handleVersionUpdate->customFieldObjArr);
                    $coreFieldObjArr = collect($handleVersionUpdate->coreFieldObjArr);
                    $languageObjArr = collect($handleVersionUpdate->languageObjArr);
                    $coreKeyTypeObjArr = collect($handleVersionUpdate->coreKeyTypeObjArr);
                    if (!empty($tableObjArr)) {
                        foreach ($tableObjArr as $tableObj) {
                            // save or update in tables table
                            Table::unguard();
                            $table = Table::updateOrCreate(
                                ['id' => $tableObj->id],
                                [
                                    "name" => $tableObj->name,
                                    "description" => $tableObj->description,
                                    "core_key_type_id" => $tableObj->core_key_type_id,
                                    "is_only_for_core_field" => $tableObj->is_only_for_core_field,
                                    "table_used_type_id" => $tableObj->table_used_type_id,
                                    "added_user_id" => 1,
                                ]
                            );
                        }
                    }

                    if ($coreFieldObjArr->isNotEmpty()) {
                        foreach ($coreFieldObjArr as $fieldObj) {
                            if ($fieldObj->is_core_field) {
                                $coreField = $fieldObj;
                                $hasNewCoreField = CoreField::where("table_id", $coreField->table_id)->where("field_name", $coreField->field_name)->first();

                                CoreField::unguard();
                                $coreFieldFilterSetting = CoreField::updateOrCreate(
                                    [
                                        "table_id" => $coreField->table_id,
                                        "field_name" => $coreField->field_name
                                    ],
                                    [
                                        "table_id" => $coreField->table_id,
                                        "project_name" => $projectObj->project_name,
                                        "project_id" => $projectObj->id,
                                        "label_name" => $coreField->name_key,
                                        "module_name" => $coreField->module_name,
                                        "base_module_name" => $coreField->base_module_name,
                                        "field_name" => $coreField->field_name,
                                        "placeholder" => $coreField->placeholder_key,
                                        "data_type" => $coreField->data_type,
                                        "is_delete" => $coreField->is_delete,
                                        "enable" => $coreField->enable,
                                        "mandatory" => $coreField->mandatory,
                                        "is_show_sorting" => $coreField->is_show_sorting,
                                        "ordering" => $coreField->ordering,
                                        "is_show_in_filter" => $coreField->is_show_in_filter,
                                        "is_include_in_hideshow" => $coreField->is_include_in_hideshow,
                                        "is_show" => $coreField->is_show,
                                        "is_core_field" => 1,
                                        "permission_for_enable_disable" => $coreField->permission_for_enable_disable,
                                        "permission_for_delete" => $coreField->permission_for_delete,
                                        "permission_for_mandatory" => $coreField->permission_for_mandatory,
                                        "added_user_id" => 1
                                    ]
                                );

                                if (empty($hasNewCoreField)) {
                                    if ($coreField->is_include_in_hideshow == 1) {
                                        // save in screen_display_ui_settings
                                        $oldScreenDisplayUiSetting = DynamicColumnVisibility::where("module_name", $coreField->module_name)->where("key", $coreField->field_name)->first();
                                        if (empty($oldScreenDisplayUiSetting)) {
                                            $screenDisplayUiSetting = new DynamicColumnVisibility();
                                            $screenDisplayUiSetting->module_name = $coreField->module_name;
                                            $screenDisplayUiSetting->key = $coreField->field_name;
                                            $screenDisplayUiSetting->is_show = $coreField->is_show;
                                            $screenDisplayUiSetting->added_user_id = 1;
                                            $screenDisplayUiSetting->save();
                                        }
                                    }
                                }
                            }
                        }
                    }

                    if ($customFieldObjArr->isNotEmpty()) {
                        foreach ($customFieldObjArr as $fieldObj) {
                            if (!$fieldObj->is_core_field) {
                                $customField = $fieldObj;
                                $hasNewCustomField = CustomField::where("table_id", $customField->table_id)->where("core_keys_id", $customField->core_keys_id)->first();

                                CustomField::unguard();
                                $customizeUi = CustomField::updateOrCreate(
                                    [
                                        "table_id" => $customField->table_id,
                                        "core_keys_id" => $customField->core_keys_id
                                    ],
                                    [
                                        "table_id" => $customField->table_id,
                                        "project_name" => $projectObj->project_name,
                                        "project_id" => $projectObj->id,
                                        "name" => $customField->name_key,
                                        "placeholder" => $customField->placeholder_key,
                                        "ui_type_id" => $customField->ui_type_id,
                                        "core_keys_id" => $customField->core_keys_id,
                                        "is_delete" => $customField->is_delete,
                                        "data_type" => $customField->data_type,
                                        "module_name" => $customField->module_name,
                                        "base_module_name" => $customField->base_module_name,
                                        "enable" => $customField->enable,
                                        "mandatory" => $customField->mandatory,
                                        "is_show_sorting" => $customField->is_show_sorting,
                                        "ordering" => $customField->ordering,
                                        "is_show_in_filter" => $customField->is_show_in_filter,
                                        "is_include_in_hideshow" => $customField->is_include_in_hideshow,
                                        "is_show" => $customField->is_show,
                                        "is_core_field" => 0,
                                        "permission_for_enable_disable" => $customField->permission_for_enable_disable,
                                        "permission_for_delete" => $customField->permission_for_delete,
                                        "permission_for_mandatory" => $customField->permission_for_mandatory,
                                        "added_user_id" => 1
                                    ]
                                );

                                if (empty($hasNewCustomField)) {
                                    if ($customField->is_include_in_hideshow == 1) {
                                        $oldScreenDisplayUiSetting = DynamicColumnVisibility::where("module_name", $customField->module_name)->where("key", $customField->core_keys_id)->first();
                                        if (empty($oldScreenDisplayUiSetting)) {
                                            // save in screen_display_ui_settings
                                            $screenDisplayUiSetting = new DynamicColumnVisibility();
                                            $screenDisplayUiSetting->module_name = $customField->module_name;
                                            $screenDisplayUiSetting->key = $customField->core_keys_id;
                                            $screenDisplayUiSetting->is_show = $customField->is_show;
                                            $screenDisplayUiSetting->added_user_id = 1;
                                            $screenDisplayUiSetting->save();
                                        }
                                    }
                                }
                            }
                        }
                    }

                    // if new field, will add lang start
                    if ($languageObjArr->isNotEmpty()) {

                        // Refactored only this language string import
                        // 30 Jan 2025
                        // latest code : 1707285025 ( dev )
                        // test code : 1714470561 ( dev )

                        // Here we have to do 2 things
                        // 1) convert to array
                        // 2) remove the id field
                        $langStringsArray = array_map(fn($obj) => array_diff_key((array) $obj, ['id' => '']), $languageObjArr->toArray());

                        $import = new BeLanguageStringImport(
                            $this->beLanguageStringService,
                            null,
                            InsertionSource::FROM_BUILDER,
                            JsonGenerationOption::ALL_LANGUAGE_FILES
                        );
                        $import->collection(collect($langStringsArray));

                    }
                    // if new field, will add lang end

                    // if new table, will add from begin start
                    if ($coreKeyTypeObjArr->isNotEmpty()) {
                        CoreKeyType::truncate();
                        foreach ($coreKeyTypeObjArr as $coreKeyTypeObj) {
                            $coreKeyType = new CoreKeyType();
                            $coreKeyType->id = $coreKeyTypeObj->id;
                            $coreKeyType->code = $coreKeyTypeObj->code;
                            $coreKeyType->client_code = $coreKeyTypeObj->client_code;
                            $coreKeyType->name = $coreKeyTypeObj->name;
                            $coreKeyType->description = $coreKeyTypeObj->description;
                            $coreKeyType->save();
                        }

                        $coreKeyCounterCodes = CoreKeyCounter::all()->pluck('code');
                        $getNewCoreKeyTypes = CoreKeyType::whereNotIn('client_code', $coreKeyCounterCodes)->get();

                        foreach ($getNewCoreKeyTypes as $getNewCoreKeyType) {
                            $newcoreKeysCounter = new CoreKeyCounter();
                            $newcoreKeysCounter->code = $getNewCoreKeyType->client_code;
                            $newcoreKeysCounter->counter = 1;
                            $newcoreKeysCounter->added_user_id = '1';
                            $newcoreKeysCounter->save();
                        }
                    }
                    // if new table, will add from begin end

                    $latestLogCode = $builderAppInfo->latestLogChangeObj->code;
                    $logChange = LogChange::first();
                    if (!empty($logChange)) {
                        $logChange->code = $latestLogCode;
                        $logChange->updated_user_id = Auth::user()->id;
                        $logChange->update();
                    } else {
                        $logChange = new LogChange();
                        $logChange->code = $latestLogCode;
                        $logChange->added_user_id = Auth::user()->id;
                        $logChange->save();
                    }
                }
            }

            // updated at psx_check_version_updates
            if (empty($checkVersionUpdate)) {
                $checkVersionUpdate = new CheckVersionUpdate();
                $checkVersionUpdate->field_table_version_number = $getLatestVersion->version_number;
                $checkVersionUpdate->field_table_version_code = $getLatestVersion->version_code;
                $checkVersionUpdate->save();

                PsCache::clear(CheckVersionUpdateCache::BASE);

                // Update Builder App Info Cache
                $buildAppInfoCache = new \stdClass();
                $buildAppInfoCache->syncAble = 0;
                $buildAppInfoCache->versionCode = $getLatestVersion->version_code;
                updateBuilderAppInfoCache($buildAppInfoCache);
            } else {
                $checkVersionUpdate->field_table_version_number = $getLatestVersion->version_number;
                $checkVersionUpdate->field_table_version_code = $getLatestVersion->version_code;
                $checkVersionUpdate->update();

                PsCache::clear(CheckVersionUpdateCache::BASE);

                // Update Builder App Info Cache
                $buildAppInfoCache = new \stdClass();
                $buildAppInfoCache->syncAble = 0;
                $buildAppInfoCache->versionCode = $getLatestVersion->version_code;
                updateBuilderAppInfoCache($buildAppInfoCache);
            }


            // Update Builder App Info Cache
            PsCache::clear(CacheBuilderAppInfoCache::BASE);

            // dd($coreFieldObjArr);
            return redirectView($redirectRouteName, "Congratulations! You have completed the updating process successfully.");
        } else {
            return redirectBackWithError(resultMessage("Project is not same.", "error"));
        }
    }

    public function builderTableFieldSync($redirectRouteName)
    {
        $checkBuilderConnection = getHttpWithApiKey(ps_constant::base_url, getApiKey(), ps_url::checkBuilderConnection);
        if ($checkBuilderConnection?->status !== "success" || empty($checkBuilderConnection)) {

            $msg = $checkBuilderConnection?->message ? $checkBuilderConnection?->message : "Builder Connection Fail";
            return redirectBackWithError(resultMessage($msg, "error"));
        } else {

            // call function twice
            // $builderAppInfo = $this->handleBuilderTableFieldSync($redirectRouteName);
            $builderAppInfo = $this->handleBuilderTableFieldSync($redirectRouteName);
            return $builderAppInfo;
        }
    }

    public function welcome()
    {
        $minPhpVersion = ps_constant::minPhpVersion;
        $phpPathFromEnv = config("app.php_path");

        // return "phpPathFromEnv ".shell_exec("whereis php");


        if (!empty($phpPathFromEnv) && function_exists('proc_open')) {
            // checking php path right or wrong start
            $phpVersion = PsPHPHelper::getPhpVersion($phpPathFromEnv);

            if (empty($phpVersion)) {
                $dataArr = [
                    "errMsg" => "This php path ($phpPathFromEnv) is wrong. You can find detailed instructions in our guide at",
                    "errType" => ps_config::phpPathError,
                    "docLink" => ps_config::howToChangePhpPathDocLink,
                ];
                return $dataArr;
            }
            // checking php path right or wrong end

            // checking minPhp start
            $msg = "This application needs PHP version $minPhpVersion or higher to function properly. However, your current PHP version is $phpVersion.
            Detected PHP Path: $phpPathFromEnv
            If this path isn't accurate, please update the PHP path in your .env file. You can find detailed instructions in our guide at";

            if (version_compare($phpVersion, $minPhpVersion) < 0) {
                $dataArr = [
                    // "errMsg" => "This path ($phpPath) of php version is $phpVersion. Our minimum php version is $minPhpVersion. Therefore, please add path at env if this path($phpPath) is not your using path."
                    "errMsg" => $msg,
                    "errType" => ps_config::phpPathError,
                    "docLink" => ps_config::howToChangePhpPathDocLink,
                ];
                return $dataArr;
            }
            // checking minPhp end

        } else if (!function_exists('proc_open')) {
            $msg = "proc_open function is not found on your server.
            Please enable proc_open to ensure the version update process works correctly.
            You can continue without enabling proc_open but application might not work properly and could crash.
            Please read the documentation.";

            $dataArr = [
                "errMsg" => $msg,
                "errType" => ps_config::procOpenError,
                "docLink" => ps_config::howToUpdateComposerDocLink,
            ];
            return $dataArr;
        } else {

            // checking minPhp start
            $phpVersion = phpversion();

            $msg = "This application needs PHP version $minPhpVersion or higher to function properly. However, your current PHP version is $phpVersion.
            Please update the PHP path in your .env file. You can find detailed instructions in our guide at";

            if (version_compare($phpVersion, $minPhpVersion) < 0) {
                $dataArr = [
                    "errMsg" => $msg,
                    "errType" => ps_config::phpPathError,
                    "docLink" => ps_config::howToChangePhpPathDocLink,
                ];
                return $dataArr;
            }
            // checking minPhp end

        }
    }

    private function getKeyFromCsvFile($langStringCSVFiles, $prefixKey)
    {
        $file = 'en.csv';
        $keys = [];

        if(collect($langStringCSVFiles)->isNotEmpty()){
            // Open the CSV file for reading
            if (($handle = fopen($file, 'r')) !== false) {
                $keys = [];

                // Skip the header row
                $header = fgetcsv($handle);

                // Loop through each row in the CSV
                while (($row = fgetcsv($handle)) !== false) {
                    // Get only the "Key" column value
                    $keys[] = handleKey($row[0], $prefixKey);
                }

                // Close the file
                fclose($handle);

            }
        }

        return $keys;
    }
}
