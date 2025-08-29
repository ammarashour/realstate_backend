<?php

// namespace Modules\Core\Http\Services\Localization;

// use App\Helpers\PsLanguageJsonHelper;
// use App\Http\Services\PsService;
// use App\Models\User;
// use Illuminate\Support\Facades\DB;
// use Modules\Core\Constants\Constants;
// use Modules\Core\Entities\Localization\Language;
// use Illuminate\Support\Facades\Auth;
// use App\Http\Contracts\Localization\FeLanguageServiceInterface;
// use Modules\Core\Entities\Localization\FeLanguageString;
// use Modules\Core\Entities\Localization\LanguageString;
// use App\Http\Contracts\Localization\BeLanguageStringServiceInterface;
// use App\Http\Contracts\Localization\FeLanguageStringServiceInterface;
// use Illuminate\Support\Facades\File;

// class FeLanguageService extends PsService implements FeLanguageServiceInterface
// {
//     protected $feLanguageStringService, $languageStringService;
//     public function __construct()
//     {
//         $this->languageStringService = function(){
//             return app()->make(BeLanguageStringServiceInterface::class);
//         };
//         $this->feLanguageStringService = function () {
//             return app()->make(FeLanguageStringServiceInterface::class);
//         };
//     }

//     public function save($languageData)
//     {

//         DB::beginTransaction();
//         try {
//             $language = $this->saveLanguage($languageData);
//             $activeLanguage = $this->get(null, [Language::status => 1]);

//             $this->saveLanguageStrings($language, $activeLanguage);
//             $this->saveFeLanguageStrings($language, $activeLanguage);

//             DB::commit();
//         } catch (\Throwable $e) {
//             DB::rollBack();
//             throw $e;
//         }
//     }

//     public function update($id, $languageData)
//     {
//         DB::beginTransaction();
//         try {
//             $this->updateLanguage($id, $languageData);
//             DB::commit();
//         } catch (\Throwable $e) {
//             DB::rollBack();
//             throw $e;
//         }
//     }

//     public function getAll($relations = null, $pagPerPage = null, $conds = null)
//     {
//         $languages = Language::when(isset($conds['order_by']) && $conds['order_by'], function ($q) use ($conds) {
//             if ($conds['order_by'] == 'added_user_id' || $conds['order_by'] == 'updated_user_id') {
//                 $q->leftJoin(User::tableName, User::name . '.' . User::id, '=', Language::tableName . '.' . $conds['order_by']);
//                 $q->select(User::tableName . '.' . User::name . ' as owner', Language::tableName . '.*');
//             }
//         })->when($conds, function ($query, $conds) {
//             $query = $this->searching($query, $conds);
//         })->when($relations, function ($q, $relations) {
//             $q->with($relations);
//         })->latest();

//         if ($pagPerPage) {
//             $languages = $languages->paginate($pagPerPage)->onEachSide(1)->withQueryString();
//         } else {
//             $languages = $languages->get();
//         }
//         return $languages;
//     }

//     public function get($id = null,  $conds = null)
//     {
//         $language = Language::when($id, function ($query, $id) {
//             $query->where(Language::id, $id);
//         })
//             ->when($conds, function ($query, $conds) {
//                 $query->where($conds);
//             })
//             ->first();
//         return $language;
//     }

//     public function setStatus($id, $status)
//     {
//         try {
//             $status = $this->prepareUpdateStausData($status);

//             $this->unPublishAllLanguages();

//             $language = $this->get($id);
//             $language->status = $status['status'];
//             $language->update();
//         } catch (\Throwable $e) {
//             throw $e;
//         }
//     }

//     public function delete($id)
//     {
//         try {
//             $name = $this->deleteLanguage($id);

//             return [
//                 'msg' => __('core__be_delete_success', ['attribute' => $name]),
//                 'flag' => Constants::success,
//             ];
//         } catch (\Throwable $e) {
//             throw $e;
//         }
//     }



//     ////////////////////////////////////////////////////////////////////
//     /// Private Functions
//     ////////////////////////////////////////////////////////////////////

//     //-------------------------------------------------------------------
//     // Data Preparations
//     //-------------------------------------------------------------------

//     private function prepareUpdateStausData($status)
//     {
//         return ['status' => $status];
//     }

//     //
//     //-------------------------------------------------------------------
//     // Database
//     //-------------------------------------------------------------------

//     private function saveLanguage($languageData)
//     {
//         $language = new Language();
//         $language->symbol = $languageData['symbol'];
//         $language->name = $languageData['name'];
//         $language->added_user_id = Auth::user()->id;
//         $language->save();
//         return $language;
//     }

//     private function saveLanguageStrings($language, $activeLanguage)
//     {
//         $languageStrings = $this->getLanguageStringService()->getAll($activeLanguage->id);
//         foreach ($languageStrings as $languageString) {
//             $languageStringData[] = [
//                 'key' => $languageString->key,
//                 'value' => $languageString->value,
//                 'language_id' => $language->id,
//                 'added_user_id' => Auth::id(),
//             ];
//         }
//         LanguageString::insert($languageStringData);

//         // update json file
//         PsLanguageJsonHelper::generateJsonFile($language->symbol, $languageStrings);
//         // $fileName = $language->symbol . '.json';
//         // generateLangStrJson($fileName, $languageStrings);
//     }
//     private function saveFeLanguageStrings($language, $activeLanguage)
//     {
//         $languageStrings = $this->getFeLanguageStringService()->getAll($activeLanguage->id);
//         foreach ($languageStrings as $languageString) {
//             $languageStringData[] = [
//                 'key' => $languageString->key,
//                 'value' => $languageString->value,
//                 'language_id' => $language->id,
//                 'added_user_id' => Auth::id(),
//             ];
//         }
//         FeLanguageString::insert($languageStringData);
//         // update json file
//         $fileName = $language->symbol . '.json';
//         generateFEangStrJson($fileName, $languageStrings);
//     }

//     private function updateLanguage($id, $languageData)
//     {
//         $language = $this->get($id);
//         $language->symbol = $languageData['symbol'];
//         $language->name = $languageData['name'];
//         $language->added_user_id = Auth::user()->id;
//         $language->update();

//         // update json file
//         $fileName = $language->symbol . '.json';
//         //direct use of language string model to avoid dependency injection
//         $lang_str = FeLanguageString::where(FeLanguageString::languageId, $language->id);
//         generateFEangStrJson($fileName, $lang_str);
//     }

//     private function deleteLanguage($id)
//     {
//         $language = $this->get($id);
//         $name = $language->name;
//         $language->delete();

//         $fileName = $language->symbol . '.json';
//         $filePath = base_path('lang/' . $fileName);

//         if (File::exists($filePath)) {
//             File::delete($filePath);
//         }

//         return $name;
//     }

//     private function searching($query, $conds)
//     {
//         // search term
//         if (isset($conds['searchterm']) && $conds['searchterm']) {
//             $search = $conds['searchterm'];
//             $query->where(function ($query) use ($search) {
//                 $query->where(Language::name, 'like', '%' . $search . '%');
//             });
//         }
//         // Filter with id
//         if (isset($conds['id']) && $conds['id']) {
//             $search = $conds['id'];
//             $query->where(function ($query) use ($search) {
//                 $query->where(Language::id, '=', $search);
//             });
//         }
        
//         // order by
//         if (isset($conds['order_by']) && isset($conds['order_type']) && $conds['order_by'] && $conds['order_type']) {

//             if ($conds['order_by'] == 'add_user_id' || $conds['order_by'] == 'updated_user_id') {
//                 $query->orderBy('owner', $conds['order_type']);
//             } else {

//                 $query->orderBy($conds['order_by'], $conds['order_type']);
//             }
//         } else {
//             $query->orderBy(Language::status, 'desc')->orderBy('name', 'asc');
//         }
//         return $query;
//     }

//     private function unPublishAllLanguages()
//     {
//         $languages = $this->getAll();
//         foreach ($languages as $language) {
//             $language->update(['status' => Constants::unPublish]);
//         }
//     }

//     private function getFeLanguageStringService()
//     {
//         return ($this->feLanguageStringService)();
//     }
//     private function getLanguageStringService()
//     {
//         return ($this->languageStringService)();
//     }
// }
