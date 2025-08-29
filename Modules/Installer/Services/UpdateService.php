<?php

namespace Modules\Installer\Services;

use App\Http\Contracts\Localization\LanguageServiceInterface;
use App\Http\Contracts\Localization\MobileLanguageServiceInterface;
use App\Http\Contracts\Localization\MobileLanguageStringServiceInterface;
use App\Http\Services\PsService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Localization\FeLanguageString;
use Modules\Core\Entities\Localization\LanguageString;
use Modules\Core\Entities\Localization\MobileLanguage;
use Modules\Core\Entities\Localization\MobileLanguageString;
use Modules\Core\Entities\UpdaterData;
use Modules\Core\Imports\FeLanguageStringImport;
use Modules\Core\Imports\BeLanguageStringImport;

/**
 * @deprecated
 */
class UpdateService extends PsService
{

    protected $csvFileZip, $langStringLanguageIdCol;

    public function __construct(
        protected LanguageServiceInterface $languageService,
        protected MobileLanguageServiceInterface $mobileLanguageService,
        protected MobileLanguageStringServiceInterface $mobileLanguageStringService)
    {
        $this->csvFileZip = "csvFileZip";
        $this->langStringLanguageIdCol = LanguageString::languageId;
        $this->mobileLangStringIdCol = MobileLanguageString::id;
        $this->mobileLangStringKeyCol = MobileLanguageString::key;
        $this->mobileLangStringTableName = MobileLanguageString::tableName;
        $this->mobileLangStringValueCol = MobileLanguageString::value;
        $this->mobileLangStringMobileLanguageIdCol = MobileLanguageString::mobileLanguageId;
    }

    public function addNewLangStore($request)
    {
        // extract lang zip file start
        $zipFile = $request->file($this->csvFileZip);
        $zip = new \ZipArchive();
        $zip->open($zipFile);
        $langSymbols = [];
        $langStringCSVFiles = [];
        for ($i = 0; $i < $zip->numFiles; $i++) {
            $fileName = $zip->getNameIndex($i);
            $langSymbol = str_replace(".csv", "", $fileName);
            array_push($langSymbols, $langSymbol);
            array_push($langStringCSVFiles, $fileName);

        }
        $zip->extractTo("./");
        $zip->close();
        // extract lang zip file end

        // add new lang string for our supported lang start
        $ourSupportedLangSymbols = [];
        $languages = $this->languageService->getAll();
        foreach ($languages as $language){
            array_push($ourSupportedLangSymbols, $language->symbol);
            foreach ($langSymbols as $langSymbol){
                if ($language->symbol == $langSymbol){
                    $languageId = $language->id;
                    $file = $langSymbol.".csv";

                    $import = new BeLanguageStringImport($language);
                    $import->import($file);

                    // update json file
                    $fileName = $language->symbol . '.json';
                    $lang_str = LanguageString::where($this->langStringLanguageIdCol, $language->id)->get();
                    generateLangStrJson($fileName, $lang_str);
                }
            }
        }
        // add new lang string for our supported lang end


        // for not our support lang, we will add english lang string for our new lang string start
        $notOurSupportedLangSymbols = array_diff($ourSupportedLangSymbols, $langSymbols);
        foreach ($languages as $language){
            foreach ($notOurSupportedLangSymbols as $notOurSupportedLangSymbol){
                if ($language->symbol == $notOurSupportedLangSymbol){
                    $languageId = $language->id;
                    $file = "en.csv";

                    $import = new BeLanguageStringImport($language);
                    $import->import($file);

                    // update json file
                    $fileName = $language->symbol . '.json';
                    $lang_str = LanguageString::where($this->langStringLanguageIdCol, $language->id)->get();
                    generateLangStrJson($fileName, $lang_str);
                }
            }
        }
        // for not our support lang, we will add english lang string for our new lang string end

        // clean imported files start
        Storage::delete($langStringCSVFiles);
        // clean imported files start

    }

    public function addNewFeLangStore($request)
    {
        // extract lang zip file start
        $zipFile = $request->file($this->csvFileZip);
        $zip = new \ZipArchive();
        $zip->open($zipFile);
        $langSymbols = [];
        $langStringCSVFiles = [];
        for ($i = 0; $i < $zip->numFiles; $i++) {
            $fileName = $zip->getNameIndex($i);
            $langSymbol = str_replace(".csv", "", $fileName);
            array_push($langSymbols, $langSymbol);
            array_push($langStringCSVFiles, $fileName);

        }
        $zip->extractTo("./");
        $zip->close();
        // extract lang zip file end

        // add new fe lang string for our supported lang start
        $ourSupportedLangSymbols = [];
        $languages = $this->languageService->getAll();
        foreach ($languages as $language){
            array_push($ourSupportedLangSymbols, $language->symbol);
            foreach ($langSymbols as $langSymbol){
                if ($language->symbol == $langSymbol){
                    $languageId = $language->id;
                    $file = $langSymbol.".csv";

                    $import = new FeLanguageStringImport($language);
                    $import->import($file);

                    // update json file
                    $fileName = $language->symbol . '.json';
                    $lang_str = FeLanguageString::where($this->langStringLanguageIdCol, $language->id)->get();
                    generateFeLangStrJson($fileName, $lang_str);
                }
            }
        }
        // add new fe lang string for our supported lang end


        // for not our support lang, we will add english lang string for our new lang string start
        $notOurSupportedLangSymbols = array_diff($ourSupportedLangSymbols, $langSymbols);
        foreach ($languages as $language){
            foreach ($notOurSupportedLangSymbols as $notOurSupportedLangSymbol){
                if ($language->symbol == $notOurSupportedLangSymbol){
                    $languageId = $language->id;
                    $file = "en.csv";

                    $import = new FeLanguageStringImport($language);
                    $import->import($file);

                    // update json file
                    $fileName = $language->symbol . '.json';
                    $lang_str = FeLanguageString::where($this->langStringLanguageIdCol, $language->id)->get();
                    generateFeLangStrJson($fileName, $lang_str);
                }
            }
        }
        // for not our support lang, we will add english lang string for our new lang string end

        // clean imported files start
        Storage::delete($langStringCSVFiles);
        // clean imported files start

    }

    public function addNewMobileLangStore($request)
    {
        // extract lang zip file start
        $zipFile = $request->file($this->csvFileZip);
        $zip = new \ZipArchive();
        $zip->open($zipFile);
        $langSymbols = [];
        $langStringCSVFiles = [];
        for ($i = 0; $i < $zip->numFiles; $i++) {
            $fileName = $zip->getNameIndex($i);
            $langSymbol = str_replace(".json", "", $fileName);
            array_push($langSymbols, $langSymbol);
            array_push($langStringCSVFiles, $fileName);

        }
        $zip->extractTo("./");
        $zip->close();
        // extract lang zip file end

        // add new lang string for our supported lang start
        $ourSupportedLangSymbols = [];
        $mobileLanguages = $this->mobileLanguageService->getAll(noPagination: Constants::yes);
        foreach ($mobileLanguages as $mobileLanguage){
            array_push($ourSupportedLangSymbols, $mobileLanguage->symbol);
            foreach ($langSymbols as $langSymbol){
                if ($mobileLanguage->symbol == $langSymbol){
                    // update code for mobile language start
                    $this->mobileLanguageStringService->updateCode($mobileLanguage->id);
                    // update code for mobile language end

                    $file = $langSymbol.".json";
                    $languageStrings = json_decode(file_get_contents($file));

                    $fileKeys = [];
                    $existKeys = [];

                    foreach ($languageStrings as $key=>$value){
                        array_push($fileKeys,$key);
                    }

                    //filter exist keys
                    $langStrs = MobileLanguageString::where($this->mobileLangStringMobileLanguageIdCol, $mobileLanguage->id)->whereIn($this->mobileLangStringKeyCol, $fileKeys)->get();

                    //for update
                    foreach ($langStrs as $langStr){
                        array_push($existKeys,$langStr->key);

                        foreach ($languageStrings as $k=>$v){
                            if($k == $langStr->key){
                                $langStr->value = $v;

                                $langStr->update();
                            }
                        }
                    }

                    //for new keys
                    foreach ($languageStrings as $newkey=>$newvalue){
                        //if not in exist keys
                        if (!in_array($newkey, $existKeys)) {
                            $language_string = new MobileLanguageString();
                            $language_string->key = $newkey;
                            $language_string->value = $newvalue;
                            $language_string->mobile_language_id = $mobileLanguage->id;
                            $language_string->added_user_id = Auth::user()->id;
                            $language_string->save();
                        }
                    }
                }
            }
        }
        // add new lang string for our supported lang end


        // for not our support lang, we will add english lang string for our new lang string start
        $notOurSupportedLangSymbols = array_diff($ourSupportedLangSymbols, $langSymbols);
        foreach ($mobileLanguages as $mobileLanguage){
            foreach ($notOurSupportedLangSymbols as $notOurSupportedLangSymbol){
                if ($mobileLanguage->symbol == $notOurSupportedLangSymbol){

                    // update code for mobile language start
                    $this->mobileLanguageStringService->updateCode($mobileLanguage->id);
                    // update code for mobile language end

                    $file = "en.json";
                    $languageStrings = json_decode(file_get_contents($file));

                    $fileKeys = [];
                    $existKeys = [];

                    foreach ($languageStrings as $key=>$value){
                        array_push($fileKeys,$key);
                    }

                    //filter exist keys
                    $langStrs = MobileLanguageString::where($this->mobileLangStringMobileLanguageIdCol, $mobileLanguage->id)->whereIn($this->mobileLangStringKeyCol, $fileKeys)->get();

                    //for update
                    foreach ($langStrs as $langStr){
                        array_push($existKeys,$langStr->key);

                        foreach ($languageStrings as $k=>$v){
                            if($k == $langStr->key){
                                $langStr->value = $v;

                                $langStr->update();
                            }
                        }
                    }

                    //for new keys
                    foreach ($languageStrings as $newkey=>$newvalue){
                        //if not in exist keys
                        if (!in_array($newkey, $existKeys)) {
                            $language_string = new MobileLanguageString();
                            $language_string->key = $newkey;
                            $language_string->value = $newvalue;
                            $language_string->mobile_language_id = $mobileLanguage->id;
                            $language_string->added_user_id = Auth::user()->id;
                            $language_string->save();
                        }
                    }
                }
            }
        }
        // for not our support lang, we will add english lang string for our new lang string end


        // clean imported files start
        Storage::delete($langStringCSVFiles);
        // clean imported files start

    }

    public function builderZipFileStore($request)
    {
        $zipFile = $request->file("zipFile");
        $zip = new \ZipArchive();
        $zip->open($zipFile);
        $fileName = $zip->getNameIndex(0);
        $zip->extractTo("./");
        $zip->close();

        $updaterData = UpdaterData::first();

        if (empty($updaterData)){
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

}
