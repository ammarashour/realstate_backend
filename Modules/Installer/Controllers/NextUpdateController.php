<?php

namespace Modules\Installer\Controllers;

use App\Config\ps_constant;
use App\Config\ps_url;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Inertia\Inertia;
use Modules\Core\Entities\UpdaterData;
use Modules\Installer\Helpers\DatabaseManager;
use Modules\Installer\Helpers\InstalledFileManager;
use Modules\Installer\Services\NextUpdateService;
use Throwable;

class NextUpdateController extends Controller
{
    use \Modules\Installer\Helpers\MigrationsHelper;

    const parentPath = "updateWizard/";
    const welcomePath = self::parentPath."Welcome";
    const sourceCodePath = self::parentPath."SourceCode";
    const finishedPath = self::parentPath."Finished";
    const addNewLangStringPath = self::parentPath."AddNewLangString";
    const addNewFeLangStringPath = self::parentPath."AddNewFeLangString";
    const addNewMobileLangStringPath = self::parentPath."AddNewMobileLangString";
    const addNewVendorLangStringPath = self::parentPath."AddNewVendorLangString";
    const BuilderTableFieldPath = self::parentPath."BuilderTableField";

    protected $nextUpdateService;

    public function __construct(NextUpdateService $nextUpdateService)
    {
        $this->nextUpdateService = $nextUpdateService;
    }

    /**
     * Display the updater welcome page.
     *
     * @return \Illuminate\View\View
     */
    public function welcome()
    {
        $dataArr = $this->nextUpdateService->welcome();
        return renderView(self::welcomePath, $dataArr);
    }

    public function sourceCode()
    {
        return renderView(self::sourceCodePath);
    }

    public function sourceCodeSync()
    {
        $dataArr = $this->nextUpdateService->sourceCodeSync("NextLaravelUpdater::sourceCode");
        return $dataArr;
    }


    public function addNewLangString()
    {
        // dd("view")
        session()->forget('logMessages');
        // Session::forget('logMessages');
        return renderView(self::addNewLangStringPath);
    }


    public function addNewLangStringStore(Request $request)
    {

        $dataArr = $this->nextUpdateService->addNewLangStore($request,self::parentPath.'NextLaravelUpdater::addNewFeLangString');
        return redirect()->route('NextLaravelUpdater::addNewLangString')->with(['logMessages' => 'be_lang_sync_success']);


        // return redirect()->route('NextLaravelUpdater::addNewLangString')
        //     ->with(['message' => trans("lang_import_have_been_finished_successfully")]);
    }

    public function addNewFeLangString()
    {
        return renderView(self::addNewFeLangStringPath);
    }


    public function addNewFeLangStringStore(Request $request)
    {
        $dataArr = $this->nextUpdateService->addNewFeLangStore($request);
        return redirect()->route('NextLaravelUpdater::addNewFeLangString')->with(['logMessages' => 'fe_lang_sync_success']);
        // return redirect()->route('NextLaravelUpdater::addNewFeLangString')
        //     ->with(['message' => trans("fe_lang_import_have_been_finished_successfully")]);
    }

    public function addNewMobileLangString()
    {
        return renderView(self::addNewMobileLangStringPath);
    }


    public function addNewMobileLangStringStore(Request $request)
    {
        $dataArr = $this->nextUpdateService->addNewMobileLangStore($request);
        return redirect()->route('NextLaravelUpdater::addNewMobileLangString')->with(['logMessages' => 'mb_lang_sync_success']);
    }

    public function addNewVendorLangString()
    {
        return renderView(self::addNewVendorLangStringPath);
    }


    public function addNewVendorLangStringStore(Request $request)
    {
        $dataArr = $this->nextUpdateService->addNewVendorLangStore($request);
        // return $dataArr;
        return redirect()->route('NextLaravelUpdater::addNewVendorLangString')->with(['logMessages' => 'vendor_lang_sync_success']);
    }

    public function builderTableField()
    {
        return renderView(self::BuilderTableFieldPath);
    }

    public function builderTableFieldSync(Request $request)
    {
        $dataArr = $this->nextUpdateService->builderTableFieldSync("NextLaravelUpdater::builderTableField");
        return $dataArr;
    }

}
