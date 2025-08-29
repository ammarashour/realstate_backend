<?php

namespace Modules\Installer\Controllers;

use App\Config\ps_constant;
use App\Rules\DomainCheck;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Http;
use Modules\Core\Entities\Project;
use Modules\Core\Http\Requests\UpdateLicenseRequest;


class PurchasedCodeController
{
    public function purchasedCode(){


        $project = Project::first();
        return view('vendor.installer.purchased-code')->with('project', $project);
    }

    public function purchasedCodeStore(UpdateLicenseRequest $request){



//         if (config('app.development')) {
//             $request->validate([
//                 'backend_url' => ['required', 'url', new DomainCheck()],
//                 'purchased_code' => 'required'
//             ]);
//         } else {
//             $request->validate([
//                 'backend_url' => ['required','url','active_url', new DomainCheck()],
//                 'purchased_code' => 'required'
//             ]);
//
//             if (App::environment("production")){
//                 // for production
//                 $purchaseCode = $request->purchased_code;
//                 $response = json_decode(Http::withHeaders([
//                     'authorization' => config('app.envato_token_type')." ".config('app.envato_token'),
//                 ])->get(ps_constant::envatoApiUri.ps_constant::envatoApiVersion.'/market/author/sale?code='.$purchaseCode));
//             } else {
//                 // for development start
//                 $purchaseCode = $request->purchased_code;
//                 if ($purchaseCode == "2a"){
//                     $response = json_decode(file_get_contents(public_path('json/buyser-purchase-response.json'), true));
//                 } else {
//                     $response = "";
//                 }
//                 // for development end
//             }
//             $message = checkPurchasedCode($response, 'LaravelInstaller::purchasedCode');
//             if (!empty($message)){
//                 return $message;
//             }
//         }

        $project = Project::first();
        if (empty($project)){
            $project = new Project();
            $project->project_name = "default";
            $project->project_code = $request->purchased_code;
            $project->project_url = $request->backend_url;
            $project->added_user_id = 1;
            $project->save();
        } else {
            $project->project_code = $request->purchased_code;
            $project->project_url = $request->backend_url;
            $project->update();
        }

        return redirect()->route('LaravelInstaller::userConfiguration');

    }
}
