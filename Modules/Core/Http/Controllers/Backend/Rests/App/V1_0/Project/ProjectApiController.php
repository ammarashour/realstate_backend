<?php

namespace Modules\Core\Http\Controllers\Backend\Rests\App\V1_0\Project;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Laravel\Sanctum\PersonalAccessToken;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Project;
use Modules\Core\Http\Services\ProjectService;

class ProjectApiController extends Controller
{
    protected $projectService, $okStatusCode, $sucessStatus, $errorStatus, $internalServerErrorStatusCode;

    public function __construct(ProjectService $projectService)
    {
        $this->projectService = $projectService;
        $this->okStatusCode = Constants::okStatusCode;
        $this->sucessStatus = Constants::successStatus;
        $this->errorStatus = Constants::errorStatus;
        $this->internalServerErrorStatusCode = Constants::internalServerErrorStatusCode;
    }

    public function index(Request $request)
    {

    }
    public function updateProjectApiKey(Request $request)
    {
        try {

            $project = $this->projectService->getProject(null);


            if (!empty($project)) {
                $project->api_key = $request->api_key;
                $project->update();

                // Update Builder App Info Cache
                $builderAppInfoCache = $this->projectService->getBuilderAppInfoCache();
                if(isset($builderAppInfoCache)){
                    $builderAppInfoCache = new \stdClass();
                    $builderAppInfoCache->isConnected = 1;
                    updateBuilderAppInfoCache($builderAppInfoCache);
                }

                return responseMsgApi("Updating Api Key success", $this->okStatusCode, $this->sucessStatus);
                // $token = PersonalAccessToken::findToken($request->token);

                // if ($token->exists) {
                //     foreach ($project->toArray() as $key => $value) {
                //         if($key != 'ps_license_code' || $key != "id"){
                //             $project[$key] = $request->toArray()[$key];
                //         }
                //     }

                //     $project->update();

                //     return responseMsgApi("Updating Api Key success", $this->okStatusCode, $this->sucessStatus);
                // } else {
                //     return responseMsgApi("Error Updating Api Key", $this->internalServerErrorStatusCode, $this->errorStatus);
                // }

            } else {
                return responseMsgApi("Error", $this->internalServerErrorStatusCode, $this->errorStatus);
                // $project = Project::first();

                // $token = PersonalAccessToken::findToken($request->token);

                // if ($token->exists) {
                //     foreach ($project->toArray() as $key => $value) {
                //         if ($key != "ps_license_code" || $key != "id") {
                //             $project[$key] = $request->toArray()[$key];
                //         }
                //     }

                //     $project->update();

                //     return responseMsgApi("Base project does not same.You need to override at your project", $this->okStatusCode, $this->sucessStatus);
                // } else {
                //     return responseMsgApi("Error", $this->internalServerErrorStatusCode, $this->errorStatus);
                // }

            }
        } catch (\Throwable $e) {
            return responseMsgApi($e->getMessage(), $this->internalServerErrorStatusCode, $this->errorStatus);
        }
    }
}
