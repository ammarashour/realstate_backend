<?php

namespace Modules\Installer\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Core\Entities\UpdaterData;
use Modules\Installer\Helpers\DatabaseManager;
use Modules\Installer\Helpers\InstalledFileManager;
use Modules\Installer\Services\UpdateService;


/**
 * @deprecated
 */
class UpdateController extends Controller
{
    use \Modules\Installer\Helpers\MigrationsHelper;

    protected $updateService;

    public function __construct(UpdateService $updateService)
    {
        $this->updateService = $updateService;
    }

    /**
     * Display the updater welcome page.
     *
     * @return \Illuminate\View\View
     */
    public function welcome()
    {
        return view('vendor.installer.update.welcome');
    }

    public function addNewLangString()
    {
        return view('vendor.installer.update.add-new-lang-string');
    }


    public function addNewLangStringStore(Request $request)
    {
        $dataArr = $this->updateService->addNewLangStore($request);
        return redirect()->route('LaravelUpdater::addNewLangString')
            ->with(['message' => trans("lang_import_have_been_finished_successfully")]);
    }

    public function addNewFeLangString()
    {
        return view('vendor.installer.update.add-new-fe-lang-string');
    }


    public function addNewFeLangStringStore(Request $request)
    {
        $dataArr = $this->updateService->addNewFeLangStore($request);
        return redirect()->route('LaravelUpdater::addNewFeLangString')
            ->with(['message' => trans("fe_lang_import_have_been_finished_successfully")]);
    }

    public function addNewMobileLangString()
    {
        return view('vendor.installer.update.add-new-mobile-lang-string');
    }


    public function addNewMobileLangStringStore(Request $request)
    {
        $dataArr = $this->updateService->addNewMobileLangStore($request);
        return redirect()->route('LaravelUpdater::addNewMobileLangString')
            ->with(['message' => trans("mobile_lang_import_have_been_finished_successfully")]);
    }

    public function builderZipFile()
    {
        $updaterData = UpdaterData::first();

        return view('vendor.installer.update.builder-zip-file', ['updaterData' => $updaterData]);
    }

    public function builderZipFileStore(Request $request)
    {
        $dataArr = $this->updateService->builderZipFileStore($request);
        return redirect()->route('LaravelUpdater::builderZipFile')
            ->with(['message' => trans("builder_zip_have_been_finished_successfully")]);
    }

    /**
     * Display the updater overview page.
     *
     * @return \Illuminate\View\View
     */
    public function overview()
    {
        $migrations = $this->getMigrations();
        $dbMigrations = $this->getExecutedMigrations();

        return view('vendor.installer.update.overview', ['numberOfUpdatesPending' => count($migrations) - count($dbMigrations)]);
    }

    /**
     * Migrate and seed the database.
     *
     * @return \Illuminate\View\View
     */
    public function database()
    {
        $databaseManager = new DatabaseManager;
        $response = $databaseManager->migrateAndSeed();

        return redirect()->route('LaravelUpdater::final')
                         ->with(['message' => $response]);
    }

    /**
     * Update installed file and display finished view.
     *
     * @param InstalledFileManager $fileManager
     * @return \Illuminate\View\View
     */
    public function finish(InstalledFileManager $fileManager)
    {
        $fileManager->update();

        return view('vendor.installer.update.finished');
    }
}
