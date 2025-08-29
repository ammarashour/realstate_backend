<?php

namespace Modules\Core\Http\Services;

use App\Http\Services\PsService;
use Illuminate\Support\Facades\DB;
use Modules\Core\Entities\Project;

class LicenseService extends PsService
{
    protected $projectService;
    public function __construct(ProjectService $projectService)
    {
        $this->projectService = $projectService;
    }

    public function index(){
        $project = $this->projectService->getProject();
        $dataArr = [
          'project' => $project
        ];
        return $dataArr;
    }

    public function create($request){
        try {
            $project = new Project();
            $project->project_name = "default";
            $project->project_code = $request->purchased_code;
            $project->project_url = $request->backend_url;
            $project->added_user_id = 1;
            $project->save();
        } catch (\Throwable $e) {
            return $e;
        }
    }

    public function update($id, $request){
        try {
            $project = $this->projectService->getProject($id);
            $project->project_url = $request->project_url;
            $project->project_code = $request->project_code;
            $project->update();
        } catch (\Throwable $e) {
            return $e;
        }
    }

}
